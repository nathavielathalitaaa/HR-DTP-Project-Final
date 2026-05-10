<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Session;
use Validator;
use App\Models\User;
use App\Models\Leave;
use App\Models\Holiday;
use App\Models\Absensi;
use Illuminate\Http\Request;

use Carbon\Carbon;

class HRController extends Controller
{
    /** employee list */
    public function employeeList()
    {
        // retrieve all employees
        $employeeList = User::with('profile')->orderBy('created_at', 'desc')->get();
        
        // get the latest user id and generate the next employee id
        $latestUser = User::orderBy('id', 'DESC')->first();
        $userId     = $latestUser ? (int) substr($latestUser->user_id, strrpos($latestUser->user_id, '-') + 1) + 1 : 1;
        $employeeId = 'SNR-' . str_pad($userId, 4, '0', STR_PAD_LEFT); // prefix sinergi

        // retrieve necessary data for the view
        $roleName = DB::table('role_type_users')->get();
        $position = DB::table('position_types')->get();
        $statusUser = DB::table('user_types')->get();

        return view('hr.employee', compact('employeeList', 'employeeId', 'roleName', 'position', 'statusUser'));
    }

    /** save record employee */
    public function employeeSaveRecord(Request $request)
    {
        $request->validate([
            'profile_image' => 'nullable|image|max:2048',
            'name'         => 'required|string',
            'email'        => 'required|string|email|max:255|unique:users',
            'position'     => 'nullable|string',
            'department'   => 'nullable|string',
            'role_name'    => 'nullable|string',
            'status'       => 'nullable|string',
            'phone_number' => 'nullable|numeric',
            'location'     => 'nullable|string',
            'join_date'    => 'nullable|string',
            'experience'   => 'nullable|string',
            'designation'  => 'nullable|string',
        ]);

        try {
            // generate the photo file name
            $photo = null;
            if ($request->hasFile('profile_image')) {
                $photo = $request->name . '_' . time() . '.' . $request->file('profile_image')->extension();
                $request->file('profile_image')->move(public_path('assets/images/user'), $photo);
            }

            // create a new user instance and populate fields
            $register = new User();
            $register->fill([
                'name'         => $request->name,
                'email'        => $request->email,
                'position'     => $request->position,
                'department'   => $request->department,
                'role_name'    => $request->role_name ?? 'staff',
                'status'       => $request->status ?? 'aktif',
                'phone_number' => $request->phone_number,
                'location'     => $request->location,
                'join_date'    => $request->tgl_bergabung ?? $request->join_date ?? null,
                'experience'   => $request->experience,
                'designation'  => $request->designation,
                'avatar'       => $photo ?? 'profile.png',
                'password'     => Hash::make($request->password ?? 'Sinergi@' . date('Y')),
            ]);
            $register->save();

            // 1. generate user_id otomatis jika belum ada
            if (empty($register->user_id)) {
                $rolePrefix = match(strtolower($request->role_name ?? 'staff')) {
                    'hr', 'admin'       => 'HR',
                    'supervisor'        => 'SUP',
                    'direktur'          => 'DIR',
                    default             => 'STF',
                };
                $count = User::where('user_id', 'LIKE', $rolePrefix . '-%')->count() + 1;
                $register->user_id = $rolePrefix . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
                $register->saveQuietly();
            }

            // assign role
            $register->assignRole($request->role_name ?? 'staff');

            // create/update employee profile
            $register->profile()->updateOrCreate(
                ['user_id' => $register->id],
                [
                    'nik'                    => $request->nik ?? null,
                    'no_kk'                  => $request->no_kk ?? null,
                    'npwp'                   => $request->npwp ?? null,
                    'bpjs_kesehatan'         => $request->bpjs_kesehatan ?? null,
                    'bpjs_ketenagakerjaan'   => $request->bpjs_ketenagakerjaan ?? null,
                    'jabatan'                => $request->jabatan ?? null,
                    'pendidikan_terakhir'    => $request->pendidikan_terakhir ?? null,
                    'tgl_bergabung'          => $request->tgl_bergabung ?? $request->join_date ?? null,
                    'tgl_kontrak_akhir'      => $request->tgl_kontrak_akhir ?? null,
                    'status_pernikahan'      => $request->status_pernikahan ?? 'belum_menikah',
                    'jumlah_anak'            => $request->jumlah_anak ?? 0,
                    'alamat'                 => $request->alamat ?? null,
                    'kota'                   => $request->kota ?? null,
                    'provinsi'               => $request->provinsi ?? null,
                    'kode_pos'               => $request->kode_pos ?? null,
                ]
            );

            $defaultPassword = 'Sinergi@' . date('Y');
            flash()->success('Data karyawan berhasil disimpan. Password default: ' . $defaultPassword);
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Error saving employee: ' . $e->getMessage());
            flash()->error('Gagal menambahkan data karyawan: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    /** update record employee */
    public function employeeUpdateRecord(Request $request)
    {
        try {
            $user = User::findOrFail($request->id);

            // handle photo upload
            if ($request->hasFile('photo')) {
                $photo = $request->name . '-' . time() . '.' . $request->photo->extension();
                $request->photo->move(public_path('assets/images/user'), $photo);

                // delete old photo if exists
                if (!empty($user->avatar) && file_exists(public_path('assets/images/user/' . $user->avatar))) {
                    unlink(public_path('assets/images/user/' . $user->avatar));
                }

                // update user avatar with new photo
                $user->avatar = $photo;
            }

            // update other fields
            $user->name         = $request->name;
            $user->email        = $request->email;
            $user->position     = $request->position;
            $user->department   = $request->department;
            $user->role_name    = $request->role_name;
            $user->status       = $request->status;
            $user->phone_number = $request->phone_number;
            $user->location     = $request->location;
            $user->join_date    = $request->join_date;
            $user->experience   = $request->experience;
            $user->designation  = $request->designation;

            // generate user_id if missing during update
            if (empty($user->user_id)) {
                $rolePrefix = match(strtolower($user->role_name ?? 'staff')) {
                    'hr', 'admin'   => 'HR',
                    'supervisor'    => 'SUP',
                    'direktur'      => 'DIR',
                    default         => 'STF',
                };
                $count = User::where('user_id', 'LIKE', $rolePrefix . '-%')->count() + 1;
                $user->user_id = $rolePrefix . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }

            $user->save();

            // create/update employee profile
            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nik'                    => $request->nik ?? null,
                    'no_kk'                  => $request->no_kk ?? null,
                    'npwp'                   => $request->npwp ?? null,
                    'bpjs_kesehatan'         => $request->bpjs_kesehatan ?? null,
                    'bpjs_ketenagakerjaan'   => $request->bpjs_ketenagakerjaan ?? null,
                    'jabatan'                => $request->jabatan ?? null,
                    'pendidikan_terakhir'    => $request->pendidikan_terakhir ?? null,
                    'tgl_bergabung'          => $request->tgl_bergabung ?? $request->join_date ?? null,
                    'tgl_kontrak_akhir'      => $request->tgl_kontrak_akhir ?? null,
                    'status_pernikahan'      => $request->status_pernikahan ?? 'belum_menikah',
                    'jumlah_anak'            => $request->jumlah_anak ?? 0,
                    'alamat'                 => $request->alamat ?? null,
                    'kota'                   => $request->kota ?? null,
                    'provinsi'               => $request->provinsi ?? null,
                    'kode_pos'               => $request->kode_pos ?? null,
                ]
            );

            flash()->success('Data karyawan berhasil diperbarui.');
            return redirect()->back();

        } catch (\Exception $e) {
            \Log::info($e);
            DB::rollback();
            flash()->error('Gagal memperbarui data karyawan.');
            return redirect()->back();
        }
    }

    /** delete record employee */
    public function employeeDeleteRecord(Request $request)
    {
        try {
            $deleteRecord = User::findOrFail($request->id_delete);
            $deleteRecord->delete();
            if (!empty($request->del_photo)) {
                unlink(public_path('assets/images/user/'.$request->del_photo));
            }

            flash()->success('Data karyawan berhasil dihapus.');
            return redirect()->back();
        } catch(\Exception $e) {
            \Log::info($e);
            DB::rollback();
            flash()->error('Gagal menghapus data karyawan.');
            return redirect()->back();
        }
    }

    /** holiday page */
    public function holidayPage()
    {
        $holidayList = Holiday::all();
        return view('hr.holidays',compact('holidayList'));
    }

    /** save record holiday */
    public function holidaySaveRecord(Request $request)
    {
        $request->validate([
            'holiday_type' => 'required|string',
            'holiday_name' => 'required|string',
            'holiday_date' => 'required|string',
        ]);
    
        try {
            // use updateorcreate to handle both creation and update
            $holiday = Holiday::updateOrCreate(
                ['id' => $request->idUpdate],
                [
                    'holiday_type' => $request->holiday_type,
                    'holiday_name' => $request->holiday_name,
                    'holiday_date' => $request->holiday_date,
                ]
            );
    
            flash()->success('Data hari libur berhasil disimpan.');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e); // log the error
            flash()->error('Gagal menyimpan data hari libur.');
            return redirect()->back();
        }
    }

    /** delete record */
    public function holidayDeleteRecord(Request $request) 
    {
        try {
            // find the holiday record or fail if not found
            $holiday = Holiday::findOrFail($request->id_delete);
            $holiday->delete();

            flash()->success('Data hari libur berhasil dihapus.');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e); // log the error
            flash()->error('Gagal menghapus data hari libur.');
            return redirect()->back();
        }
    }




    /** attendance — redirect ke halaman absensi real */
    public function attendance()
    {
        // attendance.blade.php masih berisi data dummy, redirect ke absensi real
        flash()->info('Halaman absensi per karyawan dialihkan ke Rekap Absensi.');
        return redirect()->route('hr/absensi/page');
    }



    /** attendance main */
    public function attendanceMain()
    {
        try {
            // total karyawan aktif
            $totalEmployee = User::where('status', 'aktif')->count();
            
            // absensi hari ini (hadir)
            $today = Carbon::today();
            $hadirHariIni = Absensi::where('tanggal', $today)
                ->where('status', 'hadir')
                ->count();
            
            // absensi hari ini (total yang tidak hadir)
            $absenHariIni = $totalEmployee - $hadirHariIni;
            
            // hitung hari kerja (senin-jumat) di bulan berjalan sampai hari ini
            $hariKerja = 0;
            $startMonth = Carbon::now()->startOfMonth();
            $endDate = $today;
            
            $current = $startMonth->copy();
            while ($current <= $endDate) {
                // senin (1) sampai jumat (5)
                if ($current->dayOfWeek >= 1 && $current->dayOfWeek <= 5) {
                    $hariKerja++;
                }
                $current->addDay();
            }
            
            // data absensi hari ini dengan relasi user
            $absensiHariIni = Absensi::with('user')
                ->where('tanggal', $today)
                ->get();
            
            return view('hr.attendance.attendance-main', compact(
                'totalEmployee',
                'hadirHariIni',
                'absenHariIni',
                'hariKerja',
                'absensiHariIni'
            ));
        } catch (\Exception $e) {
            \Log::error('Error in attendanceMain: ' . $e->getMessage());
            flash()->error('Terjadi kesalahan saat memuat data absensi');
            return back();
        }
    }


    /** show employee detail profile */
    public function showEmployee($id)
    {
        $user    = User::findOrFail($id);
        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);
        return view('pages.account-profile', compact('user', 'profile'));
    }

    /** halaman edit profil karyawan (hr only) */
    public function editEmployee($id)
    {
        $user    = User::with('profile')->findOrFail($id);
        $position   = DB::table('position_types')->get();
        $roleName   = DB::table('role_type_users')->get();
        $statusUser = DB::table('user_types')->get();
        return view('hr.employee-edit', compact('user', 'position', 'roleName', 'statusUser'));
    }
}
