<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use PhpOffice\PhpSpreadsheet\IOFactory;

class AbsensiAiController extends Controller
{
    public function showUpload()
    {
        if (!auth()->user()->hasRole('hr')) {
            abort(403, 'Akses ditolak.');
        }

        return view('hr.absensi.ai-upload');
    }

    public function analyze(Request $request)
    {
        if (!auth()->user()->hasRole('hr')) {
            abort(403, 'Akses ditolak.');
        }

        $request->validate([
            'file' => 'required|mimes:xls,xlsx|max:5120',
        ], [
            'file.required' => 'File harus diunggah',
            'file.mimes'    => 'Format file harus .xls atau .xlsx',
            'file.max'      => 'Ukuran maksimal 5MB',
        ]);

        try {
            $file = $request->file('file');
            $fullPath = $file->getRealPath();
            $ext = $file->getClientOriginalExtension();
            
            $excelData = $this->parseExcel($fullPath, $ext);
            
            $dataTeks = "Periode: " . $excelData['periode'] . "\nData absensi karyawan:\n";
            foreach ($excelData['rows'] as $row) {
                $dataTeks .= "- Nama: {$row['nama']} | Hadir: {$row['hadir_str']} | Terlambat: {$row['terlambat_count']}x {$row['terlambat_menit']}mnt | Lembur: {$row['lembur_menit']}mnt | Tidak Hadir: {$row['tidak_hadir']}\n";
            }

            $apiKey = env('GROQ_API_KEY');
            if (!$apiKey) {
                throw new \Exception('API Key Groq tidak ditemukan di .env');
            }

            $response = Http::timeout(120)->withHeaders([
                'Authorization' => 'Bearer ' . $apiKey,
                'Content-Type'  => 'application/json',
            ])->post('https://api.groq.com/openai/v1/chat/completions', [
                'model'    => 'llama-3.3-70b-versatile',
                'messages' => [
                    [
                        'role'    => 'system',
                        'content' => 'Kamu adalah analis HR. Kembalikan HANYA JSON murni tanpa markdown tanpa backtick. Format: {"periode":"string","data":[{"nama":"string","hari_dibutuhkan":0,"hari_hadir":0,"hari_tidak_hadir":0,"terlambat_count":0,"terlambat_menit":0,"lembur_menit":0}]}'
                    ],
                    [
                        'role'    => 'user',
                        'content' => $dataTeks
                    ]
                ],
                'temperature' => 0,
            ]);

            if (!$response->successful()) {
                throw new \Exception('Gagal menghubungi Groq API: ' . $response->body());
            }

            $responseData = $response->json();
            $generatedText = $responseData['choices'][0]['message']['content'] ?? '';

            // Clean up markdown if any
            $generatedText = preg_replace('/^```json\s*/i', '', $generatedText);
            $generatedText = preg_replace('/^```\s*/i', '', $generatedText);
            $generatedText = preg_replace('/```$/', '', trim($generatedText));

            $rekapData = json_decode($generatedText, true);

            if (!$rekapData || !isset($rekapData['data'])) {
                throw new \Exception('Format respons AI tidak valid. Harap coba lagi.');
            }

            session(['ai_rekap' => $rekapData]);

            return redirect()->route('hr/absensi/ai/preview');

        } catch (\Exception $e) {
            flash()->error('Kesalahan AI: ' . $e->getMessage());
            return redirect()->back();
        }
    }

    public function preview()
    {
        if (!auth()->user()->hasRole('hr')) {
            abort(403, 'Akses ditolak.');
        }

        $rekapData = session('ai_rekap');

        if (!$rekapData) {
            return redirect()->route('hr/absensi/ai');
        }

        return view('hr.absensi.ai-preview', compact('rekapData'));
    }

    public function save(Request $request)
    {
        if (!auth()->user()->hasRole('hr')) {
            abort(403, 'Akses ditolak.');
        }

        $rekapData = session('ai_rekap');

        if (!$rekapData || !isset($rekapData['data'])) {
            flash()->error('Data rekap tidak ditemukan. Silakan ulangi proses upload.');
            return redirect()->route('hr/absensi/ai');
        }

        $periode = $rekapData['periode'] ?? '-';
        $tanggalInput = date('Y-m') . '-01'; 

        $imported = 0;
        $skippedNames = [];

        foreach ($rekapData['data'] as $row) {
            $nama = trim((string)($row['nama'] ?? ''));

            if (empty($nama) || strtolower($nama) === 'nan' || strtolower($nama) === 'nama') {
                continue;
            }

            $user = User::whereHas('profile', function($q) use ($nama) {
                        $q->where('nama_fingerprint', $nama);
                    })->orWhere('name', $nama)->first();

            if (!$user) {
                if (!in_array($nama, $skippedNames)) {
                    $skippedNames[] = $nama;
                }
                continue;
            }

            $hariHadir = (int)($row['hari_hadir'] ?? 0);
            $status = $hariHadir > 0 ? 'hadir' : 'alpha';

            $keterangan = json_encode([
                'periode' => $periode,
                'hari_dibutuhkan' => (int)($row['hari_dibutuhkan'] ?? 0),
                'hari_hadir' => $hariHadir,
                'hari_tidak_hadir' => (int)($row['hari_tidak_hadir'] ?? 0),
                'terlambat_count' => (int)($row['terlambat_count'] ?? 0),
                'terlambat_menit' => (int)($row['terlambat_menit'] ?? 0),
                'lembur_menit' => (int)($row['lembur_menit'] ?? 0),
            ]);

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

        // Clear session
        session()->forget('ai_rekap');

        if (count($skippedNames) > 0) {
            session()->flash('warning_skipped_names', $skippedNames);
        }

        flash()->success("Berhasil menyimpan $imported data karyawan dari analisis AI.");
        return redirect()->route('hr/absensi/page');
    }

    private function parseExcel(string $fullPath, string $ext): array
    {
        $ext = strtolower($ext);
        if ($ext === 'xls') {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xls();
        } else {
            $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
        }

        $reader->setReadDataOnly(true);
        $reader->setReadEmptyCells(false);
        $spreadsheet = $reader->load($fullPath);
        $sheet = $spreadsheet->getActiveSheet();
        $rows = $sheet->toArray();

        $periodeStr = trim((string)($rows[1][1] ?? '-'));

        $dataRows = [];

        foreach ($rows as $index => $row) {
            if ($index < 4) continue;

            $no = trim((string)($row[0] ?? ''));
            $nama = trim((string)($row[1] ?? ''));

            if (empty($nama) || strtolower($nama) === 'nan' || strtolower($nama) === 'nama' || $this->isRomanNumeral($no)) {
                continue;
            }

            $kehadiran = trim((string)($row[11] ?? '0/0'));
            
            $terlambatCount = (int)($row[5] ?? 0);
            $terlambatMenit = (int)($row[6] ?? 0);

            $lemburReguler = $this->convertLemburToMinutes($row[9] ?? '0');
            $lemburSpesial = $this->convertLemburToMinutes($row[10] ?? '0');
            $lemburTotalMenit = $lemburReguler + $lemburSpesial;

            $tidakHadir = (int)($row[13] ?? 0);

            $dataRows[] = [
                'nama' => $nama,
                'hadir_str' => $kehadiran,
                'terlambat_count' => $terlambatCount,
                'terlambat_menit' => $terlambatMenit,
                'lembur_menit' => $lemburTotalMenit,
                'tidak_hadir' => $tidakHadir,
            ];
        }

        return [
            'periode' => $periodeStr,
            'rows' => $dataRows
        ];
    }

    private function convertLemburToMinutes($val): int
    {
        $str = trim((string)$val);
        if (empty($str) || strtolower($str) === 'nan' || strtolower($str) === 'null') return 0;
        
        $str = str_replace(',', '.', $str);
        $parts = explode('.', $str);
        $hours = (int)($parts[0] ?? 0);
        $minutes = (int)($parts[1] ?? 0);
        return ($hours * 60) + $minutes;
    }

    private function parseKehadiran($val): array
    {
        $val = trim((string)$val);
        if (empty($val)) return [0, 0];
        $parts = explode('/', $val);
        return [
            (int)($parts[0] ?? 0),
            (int)($parts[1] ?? 0)
        ];
    }

    private function isRomanNumeral($str): bool
    {
        $pattern = '/^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/';
        return preg_match($pattern, strtoupper(trim($str))) === 1;
    }
}
