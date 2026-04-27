<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Session;
use Validator;
use App\Models\User;
use App\Models\Leave;
use App\Models\Holiday;
use App\Models\Department;
use App\Models\Absensi;
use Illuminate\Http\Request;
use App\Models\LeaveInformation;
use Carbon\Carbon;

class HRController extends Controller
{
    /** Employee list */
    public function employeeList()
    {
        // Retrieve all employees
        $employeeList = User::all();
        
        // Get the latest user ID and generate the next employee ID
        $latestUser = User::orderBy('id', 'DESC')->first();
        $userId     = $latestUser ? (int) substr($latestUser->user_id, 4) + 1 : 1;
        $employeeId = 'KH_' . str_pad($userId, 3, '0', STR_PAD_LEFT); // Zero pad to always show 3 digits

        // Retrieve necessary data for the view
        $roleName = DB::table('role_type_users')->get();
        $position = DB::table('position_types')->get();
        $department = DB::table('departments')->get();
        $statusUser = DB::table('user_types')->get();

        return view('HR.employee', compact('employeeList', 'employeeId', 'roleName', 'position', 'department', 'statusUser'));
    }

    /** save record employee */
    public function employeeSaveRecord(Request $request)
    {
        $request->validate([
            'photo'        => 'required|image',
            'name'         => 'required|string',
            'email'        => 'required|string|email|max:255|unique:users',
            'position'     => 'required|string',
            'department'   => 'required|string',
            'role_name'    => 'required|string',
            'status'       => 'required|string',
            'phone_number' => 'required|numeric',
            'location'     => 'required|string',
            'join_date'    => 'required|string',
            'experience'   => 'required|string',
            'designation'  => 'required|string',
        ]);

        try {
            // Generate the photo file name
            $photo = $request->name . '.' . $request->photo->extension();  
            $request->photo->move(public_path('assets/images/user'), $photo);

            // Create a new user instance and populate fields
            $register = new User();
            $register->fill([
                'name'         => $request->name,
                'email'        => $request->email,
                'position'     => $request->position,
                'department'   => $request->department,
                'role_name'    => $request->role_name,
                'status'       => $request->status,
                'phone_number' => $request->phone_number,
                'location'     => $request->location,
                'join_date'    => $request->join_date,
                'experience'   => $request->experience,
                'designation'  => $request->designation,
                'avatar'       => $photo,
                'password'     => Hash::make('Hello@123'),
            ]);
            $register->save();

            flash()->success('Record added successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e); // Log the error
            flash()->error('Failed to add record :)');
            return redirect()->back();
        }
    }

    /** Update Record Employee */
    public function employeeUpdateRecord(Request $request)
    {
        try {
            $user = User::find($request->id);

            // Handle photo upload
            if ($request->hasFile('photo')) {
                $photo = $request->name . '-' . time() . '.' . $request->photo->extension();
                $request->photo->move(public_path('assets/images/user'), $photo);

                // Delete old photo if exists
                if (!empty($user->avatar) && file_exists(public_path('assets/images/user/' . $user->avatar))) {
                    unlink(public_path('assets/images/user/' . $user->avatar));
                }

                // Update user avatar with new photo
                $user->avatar = $photo;
            }

            // Update other fields
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

            $user->save();

            flash()->success('Update record successfully :)');
            return redirect()->back();

        } catch (\Exception $e) {
            \Log::info($e);
            DB::rollback();
            flash()->error('Update record fail :)');
            return redirect()->back();
        }
    }

    /** Delete Record Employee */
    public function employeeDeleteRecord(Request $request)
    {
        try {
            $deleteRecord = User::findOrFail($request->id_delete);
            $deleteRecord->delete();
            if (!empty($request->del_photo)) {
                unlink(public_path('assets/images/user/'.$request->del_photo));
            }

            flash()->success('Delete record successfully :)');
            return redirect()->back();
        } catch(\Exception $e) {
            \Log::info($e);
            DB::rollback();
            flash()->error('Delete record fail :)');
            return redirect()->back();
        }
    }

    /** holiday Page */
    public function holidayPage()
    {
        $holidayList = Holiday::all();
        return view('HR.holidays',compact('holidayList'));
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
            // Use updateOrCreate to handle both creation and update
            $holiday = Holiday::updateOrCreate(
                ['id' => $request->idUpdate],
                [
                    'holiday_type' => $request->holiday_type,
                    'holiday_name' => $request->holiday_name,
                    'holiday_date' => $request->holiday_date,
                ]
            );
    
            flash()->success('Holiday created or updated successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e); // Log the error
            flash()->error('Failed to add holiday :)');
            return redirect()->back();
        }
    }

    /** delete record */
    public function holidayDeleteRecord(Request $request) 
    {
        try {
            // Find the holiday record or fail if not found
            $holiday = Holiday::findOrFail($request->id_delete);
            $holiday->delete();

            flash()->success('Holiday deleted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e); // Log the error
            flash()->error('Failed to delete holiday :)');
            return redirect()->back();
        }
    }

    /** get information leave */
    public function getInformationLeave(Request $request)
    {
        try {

            $numberOfDay = $request->number_of_day;
            $leaveType   = $request->leave_type;
            
            $leaveDay = LeaveInformation::where('leave_type', $leaveType)->first();
            
            if ($leaveDay) {
                $days = $leaveDay->leave_days - ($numberOfDay ?? 0);
            } else {
                $days = 0; // Handle case if leave type doesn't exist
            }
            
            $data = [
                'response_code' => 200,
                'status'        => 'success',
                'message'       => 'Get success',
                'leave_type'    => $days,
                'number_of_day' => $numberOfDay,
            ];
            
            return response()->json($data);

        } catch (\Exception $e) {
            // Log the exception and return an appropriate response
            \Log::error($e->getMessage());
            return response()->json(['error' => 'An error occurred.'], 500);
        }
    }

    /** leave Employee */
    public function leaveEmployee()
    {
        $annualLeave = LeaveInformation::where('leave_type','Annual Leave')->select('leave_days')->first();
       
        $leave = Leave::where('staff_id', Session::get('user_id'))->get();
        // $leaves = Leave::where('staff_id', Session::get('user_id'))->whereIn('leave_type')->get();
        return view('HR.LeavesManage.leave-employee',compact('leave'));
    }

    /** create Leave Employee */
    public function createLeaveEmployee()
    {
        $leaveInformation = LeaveInformation::all();
        return view('HR.LeavesManage.create-leave-employee',compact('leaveInformation'));
    }

    /** save record leave */
    public function saveRecordLeave(Request $request)
    {
        $request->validate([
            'leave_type' => 'required|string',
            'date_from'  => 'required',
            'date_to'    => 'required',
            'reason'     => 'required',
        ]);

        try {
           
            $save  = new Leave;
            $save->staff_id         = Session::get('user_id');
            $save->employee_name    = Session::get('name');
            $save->leave_type       = $request->leave_type;
            $save->remaining_leave  = $request->remaining_leave;
            $save->date_from        = $request->date_from;
            $save->date_to          = $request->date_to;
            $save->number_of_day    = $request->number_of_day;
            $save->leave_date       = json_encode($request->leave_date);
            $save->leave_day        = json_encode($request->select_leave_day);
            $save->status           = 'Pending';
            $save->reason           = $request->reason;
            $save->save();
    
            flash()->success('Apply Leave successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e); // Log the error
            flash()->error('Failed Apply Leave :)');
            return redirect()->back();
        }
    }

    /** view detail leave employee */
    public function viewDetailLeave($staff_id)
    {
        $leaveInformation = LeaveInformation::all();
        $leaveDetail = Leave::where('staff_id', $staff_id)->first();
        $leaveDate   = json_decode($leaveDetail->leave_date, true); // Decode JSON to array
        $leaveDay    = json_decode($leaveDetail->leave_day, true); // Decode JSON to array

        return view('HR.LeavesManage.view-detail-leave',compact('leaveInformation','leaveDetail','leaveDate','leaveDay'));
    }

    /** leave HR */
    // tampilkan semua pengajuan cuti untuk ditinjau hr
    public function leaveHR()
    {
        // ambil semua pengajuan cuti beserta data karyawannya
        $leaveList = Leave::with('user')->orderBy('created_at', 'desc')->get();
        return view('HR.LeavesManage.leave-hr', compact('leaveList'));
    }

    // hr menyetujui pengajuan cuti
    public function approveLeave(Request $request)
    {
        try {
            $leave = Leave::findOrFail($request->id);
            $leave->update([
                'status'      => 'Approved',
                'approved_by' => auth()->user()->name,
            ]);

            flash()->success('pengajuan cuti berhasil disetujui');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('gagal menyetujui pengajuan cuti');
            return redirect()->back();
        }
    }

    // hr menolak pengajuan cuti
    public function rejectLeave(Request $request)
    {
        try {
            $leave = Leave::findOrFail($request->id);
            $leave->update(['status' => 'Rejected']);

            flash()->success('pengajuan cuti berhasil ditolak');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('gagal menolak pengajuan cuti');
            return redirect()->back();
        }
    }

    /** attendance */
    public function attendance()
    {
        return view('HR.Attendance.attendance');
    }

    /** create Leave HR */
    public function createLeaveHR()
    {
        $users = User::all();
        $leaveInformation = LeaveInformation::all();
        return view('HR.LeavesManage.create-leave-hr',compact('users','leaveInformation'));
    }

    /** attendance Main */
    public function attendanceMain()
    {
        try {
            // Total karyawan aktif
            $totalEmployee = User::where('status', 'aktif')->count();
            
            // Absensi hari ini (hadir)
            $today = Carbon::today();
            $hadirHariIni = Absensi::where('tanggal', $today)
                ->where('status', 'hadir')
                ->count();
            
            // Absensi hari ini (total yang tidak hadir)
            $absenHariIni = $totalEmployee - $hadirHariIni;
            
            // Hitung hari kerja (Senin-Jumat) di bulan berjalan sampai hari ini
            $hariKerja = 0;
            $startMonth = Carbon::now()->startOfMonth();
            $endDate = $today;
            
            $current = $startMonth->copy();
            while ($current <= $endDate) {
                // Senin (1) sampai Jumat (5)
                if ($current->dayOfWeek >= 1 && $current->dayOfWeek <= 5) {
                    $hariKerja++;
                }
                $current->addDay();
            }
            
            // Data absensi hari ini dengan relasi user
            $absensiHariIni = Absensi::with('user')
                ->where('tanggal', $today)
                ->get();
            
            return view('HR.Attendance.attendance-main', compact(
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

    /** department */
    public function department()
    {
        $departmentList = Department::all();
        return view('HR.department',compact('departmentList'));
    }

    /** save record department */
    public function saveRecordDepartment(Request $request)
    {
        $request->validate([
            'department'      => 'required|string',
            'head_of'         => 'required|string',
            'phone_number'    => 'required|integer',
            'email'           => 'required|email',
            'total_employee'  => 'required|integer',
        ]);
    
        try {
            // Use updateOrCreate to handle both creation and update
            $department = Department::updateOrCreate(
                ['id' => $request->id_update],
                [
                    'department'     => $request->department,
                    'head_of'        => $request->head_of,
                    'phone_number'   => $request->phone_number,
                    'email'          => $request->email,
                    'total_employee' => $request->total_employee,
                ]
            );
    
            flash()->success('Department created or updated successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e);
            flash()->error('Failed to add or update department :)');
            return redirect()->back();
        }
    }

    /** delete record department */
    public function deleteRecordDepartment(Request $request)
    {
        try {
            // Find the department or fail if not found
            $department = Department::findOrFail($request->id_delete);
            $department->delete();
            
            flash()->success('Record deleted successfully :)');
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e); // Log the error
            flash()->error('Failed to delete record :)');
            return redirect()->back();
        }
    }

}
