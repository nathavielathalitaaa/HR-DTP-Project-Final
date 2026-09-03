<?php

namespace App\Http\Controllers;

use DB;
use Hash;
use Session;
use Validator;
use App\Models\User;

use App\Models\Absensi;
use App\Models\ActivityLog;
use App\Imports\KaryawanImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Cell\Coordinate;
use PhpOffice\PhpSpreadsheet\IOFactory;

class HRController extends Controller
{
    /**
     * Display the employee list page.
     * Generates a new employee ID and loads reference data for the form.
     *
     * @return \Illuminate\View\View
     */
    public function employeeList()
    {
        // ── Pengambilan Data Karyawan ───────────────────────────────────
        $employeeList = User::with('profile')->orderBy('created_at', 'desc')->get();
        
        // ── Generate ID Karyawan Baru ───────────────────────────────────
        $latestUser = User::orderBy('id', 'DESC')->first();
        $userId = $latestUser ? (int) substr($latestUser->user_id, strrpos($latestUser->user_id, '-') + 1) + 1 : 1;
        $employeeId = 'SIN-' . str_pad($userId, 4, '0', STR_PAD_LEFT);

        // ── Pengambilan Data Referensi ──────────────────────────────────
        $roleName = DB::table('role_type_users')->get();
        $position = DB::table('position_types')->get();
        $statusUser = DB::table('user_types')->get();

        return view('hr.employee', compact('employeeList', 'employeeId', 'roleName', 'position', 'statusUser'));
    }


    /**
     * Store a new employee record.
     * Handles file upload, user creation, role assignment, and profile creation.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function employeeSaveRecord(Request $request)
    {
        // ── Validasi Input ──────────────────────────────────────────────
        $request->validate([
            'profile_image' => 'nullable|image|max:2048',
            'name'          => 'required|string',
            'email'         => 'required|string|email|max:255|unique:users',
            'position'      => 'nullable|string',
            'department'    => 'nullable|string',
            'role_name'     => 'nullable|string',
            'status'        => 'nullable|string',
            'phone_number'  => 'nullable|numeric',
            'location'      => 'nullable|string',
            'join_date'     => 'nullable|string',
            'experience'    => 'nullable|string',
            'designation'   => 'nullable|string',
        ]);

        // ── Proses Penyimpanan Data ─────────────────────────────────────
        try {
            $photo = null;
            
            if ($request->hasFile('profile_image')) {
                $photo = $request->file('profile_image')->store('avatars');
            }

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

            if (empty($register->user_id)) {
                $rolePrefix = match(strtolower($request->role_name ?? 'staff')) {
                    'hr', 'admin', 'human resource' => 'HR',
                    'supervisor'                    => 'SUP',
                    'direktur'                      => 'DIR',
                    default                         => 'STF',
                };
                
                $count = User::where('user_id', 'LIKE', $rolePrefix . '-%')->count() + 1;
                $register->user_id = $rolePrefix . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
                $register->saveQuietly();
            }

            $roleToAssign = strtolower($request->role_name ?? 'staff');
            
            if ($roleToAssign === 'human resource') {
                $roleToAssign = 'hr';
            }
            
            $register->assignRole($roleToAssign);

            $register->profile()->updateOrCreate(
                ['user_id' => $register->id],
                [
                    'nik'                  => $request->nik ?? null,
                    'no_kk'                => $request->no_kk ?? null,
                    'npwp'                 => $request->npwp ?? null,
                    'bpjs_kesehatan'       => $request->bpjs_kesehatan ?? null,
                    'bpjs_ketenagakerjaan' => $request->bpjs_ketenagakerjaan ?? null,
                    'jabatan'              => $request->jabatan ?? null,
                    'pendidikan_terakhir'  => $request->pendidikan_terakhir ?? null,
                    'tgl_bergabung'        => $request->tgl_bergabung ?? $request->join_date ?? null,
                    'tgl_kontrak_akhir'    => $request->tgl_kontrak_akhir ?? null,
                    'status_pernikahan'    => $request->status_pernikahan ?? 'belum_menikah',
                    'jumlah_anak'          => $request->jumlah_anak ?? 0,
                    'alamat'               => $request->alamat ?? null,
                    'kota'                 => $request->kota ?? null,
                    'provinsi'             => $request->provinsi ?? null,
                    'kode_pos'             => $request->kode_pos ?? null,
                ]
            );

            $defaultPassword = 'Sinergi@' . date('Y');
            
            ActivityLog::log('create_employee', $register, "Menambahkan karyawan baru: {$register->name} ({$register->user_id})");

            flash()->success('Data karyawan berhasil disimpan. Password default: ' . $defaultPassword);
            
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error('Error saving employee: ' . $e->getMessage());
            flash()->error('Gagal menambahkan data karyawan: ' . $e->getMessage());
            
            return redirect()->back();
        }
    }


    /**
     * Update an existing employee record.
     * Handles file replacement and data synchronization.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function employeeUpdateRecord(Request $request)
    {
        // ── Proses Pembaruan Data ───────────────────────────────────────
        try {
            $user = User::findOrFail($request->id);

            if ($request->hasFile('photo')) {
                if (!empty($user->avatar) && $user->avatar !== 'profile.png') {
                    Storage::disk(config('filesystems.default'))->delete($user->avatar);
                    if (file_exists(public_path('assets/images/user/' . $user->avatar))) {
                        @unlink(public_path('assets/images/user/' . $user->avatar));
                    }
                }

                $user->avatar = $request->file('photo')->store('avatars');
            }

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

            if (empty($user->user_id)) {
                $rolePrefix = match(strtolower($user->role_name ?? 'staff')) {
                    'hr', 'admin', 'human resource' => 'HR',
                    'supervisor'                    => 'SUP',
                    'direktur'                      => 'DIR',
                    default                         => 'STF',
                };
                
                $count = User::where('user_id', 'LIKE', $rolePrefix . '-%')->count() + 1;
                $user->user_id = $rolePrefix . '-' . str_pad($count, 4, '0', STR_PAD_LEFT);
            }

            $user->save();

            $roleToSync = strtolower($user->role_name ?? 'staff');
            
            if ($roleToSync === 'human resource') {
                $roleToSync = 'hr';
            }
            
            $user->syncRoles($roleToSync);

            $user->profile()->updateOrCreate(
                ['user_id' => $user->id],
                [
                    'nik'                  => $request->nik ?? null,
                    'no_kk'                => $request->no_kk ?? null,
                    'npwp'                 => $request->npwp ?? null,
                    'bpjs_kesehatan'       => $request->bpjs_kesehatan ?? null,
                    'bpjs_ketenagakerjaan' => $request->bpjs_ketenagakerjaan ?? null,
                    'jabatan'              => $request->jabatan ?? null,
                    'pendidikan_terakhir'  => $request->pendidikan_terakhir ?? null,
                    'tgl_bergabung'        => $request->tgl_bergabung ?? $request->join_date ?? null,
                    'tgl_kontrak_akhir'    => $request->tgl_kontrak_akhir ?? null,
                    'status_pernikahan'    => $request->status_pernikahan ?? 'belum_menikah',
                    'jumlah_anak'          => $request->jumlah_anak ?? 0,
                    'alamat'               => $request->alamat ?? null,
                    'kota'                 => $request->kota ?? null,
                    'provinsi'             => $request->provinsi ?? null,
                    'kode_pos'             => $request->kode_pos ?? null,
                ]
            );

            ActivityLog::log('update_employee', $user, "Memperbarui data karyawan: {$user->name} ({$user->user_id})");

            flash()->success('Data karyawan berhasil diperbarui.');
            
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            DB::rollback();
            flash()->error('Gagal memperbarui data karyawan.');
            
            return redirect()->back();
        }
    }


    /**
     * Delete an employee record and their associated photo.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function employeeDeleteRecord(Request $request)
    {
        // ── Proses Penghapusan Data ─────────────────────────────────────
        try {
            $deleteRecord = User::findOrFail($request->id_delete);
            $deleteRecord->delete();
            
            if (!empty($request->del_photo)) {
                Storage::disk(config('filesystems.default'))->delete($request->del_photo);
                if (file_exists(public_path('assets/images/user/' . $request->del_photo))) {
                    @unlink(public_path('assets/images/user/' . $request->del_photo));
                }
            }

            ActivityLog::log('delete_employee', null, "Menghapus karyawan: {$deleteRecord->name} ({$deleteRecord->user_id})");

            flash()->success('Data karyawan berhasil dihapus.');
            
            return redirect()->back();
        } catch (\Exception $e) {
            \Log::error($e->getMessage());
            DB::rollback();
            flash()->error('Gagal menghapus data karyawan.');
            
            return redirect()->back();
        }
    }





    /**
     * Display an employee's detailed profile.
     *
     * @param int $id The user ID
     * @return \Illuminate\View\View
     */
    public function showEmployee($id)
    {
        $user = User::findOrFail($id);
        $profile = $user->profile()->firstOrCreate(['user_id' => $user->id]);
        
        return view('pages.account-profile', compact('user', 'profile'));
    }


    /**
     * Display the edit form for an employee's profile.
     *
     * @param int $id The user ID
     * @return \Illuminate\View\View
     */
    public function editEmployee($id)
    {
        $user = User::with('profile')->findOrFail($id);
        $position = DB::table('position_types')->get();
        $roleName = DB::table('role_type_users')->get();
        $statusUser = DB::table('user_types')->get();
        
        return view('hr.employee-edit', compact('user', 'position', 'roleName', 'statusUser'));
    }


    /**
     * Handle the bulk import of employees via Excel.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function importKaryawan(Request $request)
    {
        // ── Validasi File Import ────────────────────────────────────────
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120',
        ], [
            'file.required' => 'File Excel wajib dipilih.',
            'file.mimes'    => 'File harus berformat .xlsx atau .xls.',
            'file.max'      => 'Ukuran file maksimal 5MB.',
        ]);

        // ── Proses Eksekusi Import ──────────────────────────────────────
        try {
            $import = new KaryawanImport();
            $import->import($request->file('file')->getRealPath());

            $result = $import->getResult();

            $msg = $import->successCount . ' karyawan berhasil diimport.';
            if ($import->failedCount > 0) {
                $msg .= ' ' . $import->failedCount . ' baris gagal.';
            }

            flash()->success('Import selesai! ' . $msg);
            
            return redirect()->route('hr/employee/list')->with('import_result', $result);
        } catch (\Throwable $e) {
            \Log::error('Import Karyawan error: ' . $e->getMessage());
            flash()->error('Gagal memproses file: ' . $e->getMessage());
            
            return redirect()->back();
        }
    }


    /**
     * Download the Excel template for bulk employee import.
     * Generates the file dynamically if it doesn't exist.
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function downloadTemplate()
    {
        $path = public_path('assets/templates/template-import-karyawan.xlsx');

        if (!file_exists($path)) {
            $this->generateTemplate($path);
        }

        return response()->download($path, 'template-import-karyawan.xlsx');
    }


    /**
     * Generate the XLSX template file for bulk import.
     *
     * @param string $outputPath Path to save the generated template
     * @return void
     */
    private function generateTemplate(string $outputPath): void
    {
        $spreadsheet = new Spreadsheet();

        // ── Pembuatan Sheet 1: Data Karyawan ────────────────────────────
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('Data Karyawan');

        $headers = [
            'nama', 'email', 'role', 'departemen', 'posisi', 'jabatan_approval',
            'nik', 'no_kk', 'npwp', 'bpjs_kesehatan', 'bpjs_ketenagakerjaan',
            'tgl_bergabung', 'status_pernikahan', 'jumlah_anak',
            'pendidikan_terakhir', 'no_telepon', 'alamat', 'kota', 'provinsi',
        ];

        $headerStyle = [
            'font'      => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF'], 'size' => 11],
            'fill'      => ['fillType' => Fill::FILL_SOLID,
                            'startColor' => ['argb' => 'FF4F6560']],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_CENTER,
                            'vertical'   => Alignment::VERTICAL_CENTER],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN,
                                             'color' => ['argb' => 'FFADC5BB']]],
        ];

        $exampleStyle = [
            'font'      => ['italic' => true, 'color' => ['argb' => 'FF6B7280'], 'size' => 10],
            'alignment' => ['horizontal' => Alignment::HORIZONTAL_LEFT],
            'borders'   => ['allBorders' => ['borderStyle' => Border::BORDER_THIN,
                                             'color' => ['argb' => 'FFD1D5DB']]],
        ];

        foreach ($headers as $colIdx => $header) {
            $col = Coordinate::stringFromColumnIndex($colIdx + 1);
            $sheet->setCellValue($col . '1', $header);
            $sheet->getRowDimension(1)->setRowHeight(24);
        }
        
        $lastCol = Coordinate::stringFromColumnIndex(count($headers));
        $sheet->getStyle('A1:' . $lastCol . '1')->applyFromArray($headerStyle);

        $example = [
            'Budi Santoso', 'budi@sinergihotel.com', 'staff',
            'Front Office', 'Staff', '',
            '3201234567890001', '3201234567890001',
            '12.345.678.9-012.345', '0001234567890',
            '0001234567891', '2024-01-15', 'Single', '0',
            'S1', '08123456789', 'Jl. Contoh No.1', 'Malang', 'Jawa Timur',
        ];
        
        foreach ($example as $colIdx => $val) {
            $col = Coordinate::stringFromColumnIndex($colIdx + 1);
            $sheet->setCellValue($col . '2', $val);
        }
        
        $sheet->getStyle('A2:' . $lastCol . '2')->applyFromArray($exampleStyle);

        foreach (range(1, count($headers)) as $colIdx) {
            $col = Coordinate::stringFromColumnIndex($colIdx);
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        // ── Pembuatan Sheet 2: Panduan ──────────────────────────────────
        $panduan = $spreadsheet->createSheet();
        $panduan->setTitle('Panduan');

        $instructions = [
            ['PANDUAN PENGISIAN TEMPLATE IMPORT KARYAWAN'],
            [''],
            ['Kolom', 'Keterangan'],
            ['role', 'Wajib diisi: hr, supervisor, atau staff'],
            ['tgl_bergabung', 'Format: YYYY-MM-DD  (contoh: 2024-01-15)'],
            ['status_pernikahan', 'Single / Married / Divorced'],
            ['pendidikan_terakhir', 'SD / SMP / SMA-SMK / D3 / S1 / S2 / S3'],
            ['email', 'Harus unik, tidak boleh sama dengan yang sudah ada di sistem'],
            ['jabatan_approval', 'Isi jika user memiliki jabatan approval (hod, hr, purchasing, dst.)'],
            [''],
            ['Catatan:', 'Baris yang gagal diimpor akan ditampilkan di layar beserta alasannya.'],
            ['Password default:', 'Sinergi@2026 (minta karyawan ganti setelah login pertama)'],
        ];

        foreach ($instructions as $rowIdx => $cols) {
            foreach ($cols as $colIdx => $val) {
                $col = Coordinate::stringFromColumnIndex($colIdx + 1);
                $panduan->setCellValue($col . ($rowIdx + 1), $val);
            }
        }

        $panduan->getStyle('A1:B1')->getFont()->setBold(true)->setSize(12);
        $panduan->getStyle('A3:B3')->getFont()->setBold(true);
        $panduan->getColumnDimension('A')->setWidth(25);
        $panduan->getColumnDimension('B')->setWidth(70);

        // ── Simpan Template ─────────────────────────────────────────────
        @mkdir(dirname($outputPath), 0755, true);
        $writer = IOFactory::createWriter($spreadsheet, 'Xlsx');
        $writer->save($outputPath);
    }
}