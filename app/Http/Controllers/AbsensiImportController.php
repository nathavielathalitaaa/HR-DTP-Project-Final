<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Carbon\Carbon;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AbsensiImportController extends Controller
{
    public function showImport()
    {
        if (!auth()->user()->hasRole('hr')) {
            abort(403, 'Akses ditolak. Hanya HR yang dapat mengakses fitur import.');
        }

        // generate last 12 months
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths($i);
            $months[$date->format('Y-m')] = $date->format('F Y');
        }

        return view('hr.absensi.import', compact('months'));
    }

    public function import(Request $request)
    {
        if (!auth()->user()->hasRole('hr')) {
            abort(403, 'Akses ditolak. Hanya HR yang dapat mengakses fitur import.');
        }

        $request->validate([
    'file'  => 'required|file|max:5120',
    'bulan' => 'required|date_format:Y-m',
], [
    'file.required' => 'File harus diunggah',
    'file.max'      => 'Ukuran file maksimal 5MB',
    'bulan.required'=> 'Bulan harus dipilih',
]);

try {
    $file     = $request->file('file');
    $fullPath = $file->getRealPath();

    $ext = strtolower($file->getClientOriginalExtension());
$reader = $ext === 'xls'
    ? IOFactory::createReader('Xls')
    : IOFactory::createReader('Xlsx');

$reader->setReadDataOnly(true);
$spreadsheet = $reader->load($fullPath);
$sheet       = $spreadsheet->getActiveSheet();
$rows        = $sheet->toArray(null, true, true, false);

// Setup periode dan variabel tracking
$bulan         = $request->bulan; // format: "2026-04"
$tanggalInput  = $bulan . '-01'; // simpan sebagai tanggal pertama bulan
$periodeStr    = \Carbon\Carbon::parse($tanggalInput)->format('F Y');
$imported      = 0;
$skippedNames  = [];

// process each row (data starts from row index 4 = baris 5)
foreach ($rows as $index => $row) {
                if ($index < 4) continue;

                $no = trim((string)($row[0] ?? ''));
                $nama = trim((string)($row[1] ?? ''));

                if (empty($nama) || strtolower($nama) === 'nan' || strtolower($nama) === 'nama' || $this->isRomanNumeral($no)) {
                    continue;
                }

                // parse
                $kehadiran = trim((string)($row[11] ?? '0/0'));
                $parts = explode('/', $kehadiran);
                $hariDibutuhkan = (int)($parts[0] ?? 0);
                $hariHadir = (int)($parts[1] ?? 0);

                $terlambatCount = (int)($row[5] ?? 0);
                $terlambatMenit = (int)($row[6] ?? 0);

                $lemburReguler = $this->convertLemburToMinutes($row[9] ?? '0');
                $lemburSpesial = $this->convertLemburToMinutes($row[10] ?? '0');
                $lemburTotalMenit = $lemburReguler + $lemburSpesial;

                $tidakHadir = (int)($row[13] ?? 0);

                // cari user berdasarkan nama_fingerprint atau name fallback
                $user = User::whereHas('profile', function($q) use ($nama) {
                            $q->where('nama_fingerprint', $nama);
                        })->orWhere('name', $nama)->first();

                if (!$user) {
                    if (!in_array($nama, $skippedNames)) {
                        $skippedNames[] = $nama;
                    }
                    continue;
                }

                $keterangan = json_encode([
                    'periode' => $periodeStr,
                    'hari_dibutuhkan' => $hariDibutuhkan,
                    'hari_hadir' => $hariHadir,
                    'hari_tidak_hadir' => $tidakHadir,
                    'terlambat_count' => $terlambatCount,
                    'terlambat_menit' => $terlambatMenit,
                    'lembur_menit' => $lemburTotalMenit,
                ]);

                $status = $hariHadir > 0 ? 'hadir' : 'alpha';

                Absensi::updateOrCreate(
                    ['user_id' => $user->id, 'tanggal' => $tanggalInput],
                    [
                        'status' => $status,
                        'keterangan' => $keterangan,
                        'jam_masuk' => null,
                        'jam_keluar' => null,
                    ]
                );

                $imported++;
            }

            if (count($skippedNames) > 0) {
                session()->flash('warning_skipped_names', $skippedNames);
            }
            
            flash()->success("Berhasil import {$imported} data karyawan.");
            return redirect()->route('hr/absensi/import');

        } catch (\Exception $e) {
            flash()->error('Gagal import: ' . $e->getMessage());
            return redirect()->back();
}
    }
    
    public function mapFingerprint(Request $request)
    {
        if (!auth()->user()->hasRole('hr')) {
            return response()->json(['success' => false, 'message' => 'Akses ditolak'], 403);
        }

        $request->validate([
            'user_id' => 'required|exists:users,id',
            'nama_fingerprint' => 'required|string',
        ]);

        try {
            $user = User::findOrFail($request->user_id);
            if ($user->profile) {
                $user->profile->update(['nama_fingerprint' => $request->nama_fingerprint]);
            } else {
                $user->profile()->create(['nama_fingerprint' => $request->nama_fingerprint]);
            }

            return response()->json(['success' => true, 'message' => 'Mapping berhasil disimpan']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    private function convertLemburToMinutes($str) {
        $str = trim((string)$str);
        if (empty($str) || strtolower($str) == 'nan' || strtolower($str) == 'null') return 0;
        
        $str = str_replace(',', '.', $str);
        $parts = explode('.', $str);
        $hours = (int)($parts[0] ?? 0);
        $minutes = (int)($parts[1] ?? 0);
        return ($hours * 60) + $minutes;
    }

    private function isRomanNumeral($str)
    {
        $pattern = '/^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/';
        return preg_match($pattern, strtoupper(trim($str))) === 1;
    }
}