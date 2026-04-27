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
        // Generate last 12 months
        $months = [];
        for ($i = 0; $i < 12; $i++) {
            $date = Carbon::now()->subMonths($i);
            $months[$date->format('Y-m')] = $date->format('F Y');
        }

        return view('HR.absensi.import', compact('months'));
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls|max:5120',
            'bulan' => 'required|date_format:Y-m',
        ], [
            'file.required' => 'File harus diunggah',
            'file.mimes' => 'Format file harus .xlsx atau .xls',
            'file.max' => 'Ukuran file maksimal 5MB',
            'bulan.required' => 'Bulan harus dipilih',
            'bulan.date_format' => 'Format bulan tidak valid',
        ]);

        try {
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();
            $filename = uniqid('absensi_') . '.' . $ext;
            $path = $file->storeAs('imports', $filename, 'local');
            $fullPath = storage_path('app' . DIRECTORY_SEPARATOR . 'imports' . DIRECTORY_SEPARATOR . $filename);

            // Load spreadsheet
            $spreadsheet = IOFactory::load($fullPath);
            $sheet = $spreadsheet->getActiveSheet();
            $rows = $sheet->toArray();

            $imported = 0;
            $skipped = [];
            $bulan = $request->bulan;

            // Get last day of month
            $lastDay = Carbon::createFromFormat('Y-m', $bulan)->endOfMonth()->day;

            // Process each row (skip header row 0, data from row 1)
            foreach ($rows as $index => $row) {
                if ($index < 1) continue; // Skip header

                $no = trim((string)($row[0] ?? ''));
                $nama = trim((string)($row[1] ?? ''));
                $jabatan = trim((string)($row[2] ?? ''));

                // Skip if NO is empty or is Roman numeral (section header)
                if (empty($no) || $this->isRomanNumeral($no)) {
                    continue;
                }

                // Skip if NAMA is empty
                if (empty($nama) || strtolower($nama) === 'nan') {
                    continue;
                }

                // Extract counts
                $sakitDgnSrt = (int)($row[3] ?? 0);
                $sakitTanpaSrt = (int)($row[4] ?? 0);
                $ijin = (int)($row[5] ?? 0);
                $alfa = (int)($row[6] ?? 0);
                $hariKerja = (int)($row[7] ?? 0);
                // $off = (int)($row[8] ?? 0); // Not used for import

                $totalSakit = $sakitDgnSrt + $sakitTanpaSrt;

                // Find user by name
                $user = User::where('name', 'LIKE', '%' . $nama . '%')->first();

                if (!$user) {
                    $skipped[] = $nama;
                    continue;
                }

                // Delete existing absensi for this month
                Absensi::where('user_id', $user->id)
                    ->where('tanggal', 'like', $bulan . '%')
                    ->delete();

                // Create records for each day
                $dayCounter = 1;
                $hariKerjaCreated = 0;
                $sakitCreated = 0;
                $ijinCreated = 0;
                $alfaCreated = 0;

                for ($day = 1; $day <= $lastDay; $day++) {
                    $date = Carbon::createFromFormat('Y-m-d', $bulan . '-' . str_pad($day, 2, '0', STR_PAD_LEFT));

                    // Skip Sundays (assuming 0 = Sunday in Carbon)
                    if ($date->dayOfWeek == 0) {
                        continue;
                    }

                    $status = null;
                    $jamMasuk = null;
                    $jamKeluar = null;

                    // Fill hadir first
                    if ($hariKerjaCreated < $hariKerja) {
                        $status = 'hadir';
                        $jamMasuk = '08:00:00';
                        $jamKeluar = '17:00:00';
                        $hariKerjaCreated++;
                    }
                    // Then sakit
                    elseif ($sakitCreated < $totalSakit) {
                        $status = 'sakit';
                        $sakitCreated++;
                    }
                    // Then izin
                    elseif ($ijinCreated < $ijin) {
                        $status = 'izin';
                        $ijinCreated++;
                    }
                    // Then alfa
                    elseif ($alfaCreated < $alfa) {
                        $status = 'alfa';
                        $alfaCreated++;
                    }

                    if ($status) {
                        Absensi::updateOrCreate(
                            ['user_id' => $user->id, 'tanggal' => $date->format('Y-m-d')],
                            [
                                'status' => $status,
                                'jam_masuk' => $jamMasuk,
                                'jam_keluar' => $jamKeluar,
                                'keterangan' => 'Import dari Excel',
                            ]
                        );
                    }
                }

                $imported++;
            }

            // Build flash message
            $message = "Berhasil import {$imported} karyawan.";
            if (count($skipped) > 0) {
                $message .= " Gagal/tidak ditemukan: " . count($skipped) . " karyawan (" . implode(', ', $skipped) . ")";
            }

            flash()->success($message);
            return redirect()->route('hr/absensi/page');

        } catch (\Exception $e) {
            flash()->error('Gagal import: ' . $e->getMessage());
            return redirect()->back();
        } finally {
            if (isset($fullPath) && file_exists($fullPath)) {
                unlink($fullPath);
            }
        }
    }

    /**
     * Check if string is a Roman numeral
     */
    private function isRomanNumeral($str)
    {
        $pattern = '/^M{0,3}(CM|CD|D?C{0,3})(XC|XL|L?X{0,3})(IX|IV|V?I{0,3})$/';
        return preg_match($pattern, strtoupper(trim($str))) === 1;
    }
}