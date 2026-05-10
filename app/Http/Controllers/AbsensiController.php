<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Response;
use Illuminate\Http\Request;

class AbsensiController extends Controller
{
    public function index(Request $request)
    {
        $bulan = $request->bulan ?? date('Y-m');
        $tahun = explode('-', $bulan)[0];

        $tanggalMulai = $bulan . '-01';

        $query = Absensi::select(['id', 'user_id', 'tanggal', 'status', 'keterangan'])
            ->with(['user.profile', 'user' => function ($q) {
                $q->select(['id', 'name']);
            }])
            ->where('tanggal', $tanggalMulai);

        // jika user bukan hr/admin/super-admin, tampilkan hanya absensi miliknya sendiri
        if (!auth()->user()->hasAnyRole(['hr', 'admin', 'super-admin'])) {
            $query->where('user_id', auth()->user()->id);
        }

        $absensiList = $query->orderBy('user_id')->get();

        $rekapList = $absensiList->map(function($item) {
            $data = json_decode($item->keterangan, true);
            if (!is_array($data)) {
                $data = [
                    'periode' => '-',
                    'hari_dibutuhkan' => 0,
                    'hari_hadir' => 0,
                    'hari_tidak_hadir' => 0,
                    'terlambat_count' => 0,
                    'terlambat_menit' => 0,
                    'lembur_menit' => 0,
                ];
            }
            $item->rekap = $data;
            return $item;
        });

        return view('hr.absensi.index', compact('rekapList', 'bulan', 'tahun'));
    }

    public function exportExcel(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['hr', 'admin', 'super-admin'])) {
            abort(403, 'Akses ditolak. Hanya HR/Admin yang dapat mengekspor data absensi.');
        }

        $bulan = $request->bulan ?? date('Y-m');
        $tanggalMulai = $bulan . '-01';

        $absensiList = Absensi::with(['user.profile', 'user' => fn($q) => $q->select('id', 'name')])
            ->where('tanggal', $tanggalMulai)
            ->get();

        $namaFile = 'rekap-absensi-' . $bulan . '.csv';

        $headers = [
            'Content-Type'        => 'text/csv',
            'Content-Disposition' => 'attachment; filename="' . $namaFile . '"',
        ];

        $callback = function () use ($absensiList) {
            $file = fopen('php://output', 'w');

            // bom untuk excel agar utf-8 terbaca benar
            fprintf($file, chr(0xEF) . chr(0xBB) . chr(0xBF));

            // header kolom
            fputcsv($file, ['No', 'Nama Karyawan', 'Departemen/Jabatan', 'Hari Dibutuhkan', 'Hari Hadir', 'Tidak Hadir', 'Terlambat (Kali)', 'Terlambat (Menit)', 'Lembur (Menit)', 'Lembur (Jam)']);

            foreach ($absensiList as $i => $absensi) {
                $data = json_decode($absensi->keterangan, true) ?? [];
                
                $lemburJam = isset($data['lembur_menit']) ? round($data['lembur_menit'] / 60, 2) : 0;
                
                fputcsv($file, [
                    $i + 1,
                    $absensi->user?->name ?? '-',
                    $absensi->user?->profile?->jabatan ?? '-',
                    $data['hari_dibutuhkan'] ?? 0,
                    $data['hari_hadir'] ?? 0,
                    $data['hari_tidak_hadir'] ?? 0,
                    $data['terlambat_count'] ?? 0,
                    $data['terlambat_menit'] ?? 0,
                    $data['lembur_menit'] ?? 0,
                    $lemburJam
                ]);
            }

            fclose($file);
        };

        return Response::stream($callback, 200, $headers);
    }

    public function exportPdf(Request $request)
    {
        if (!auth()->user()->hasAnyRole(['hr', 'admin', 'super-admin'])) {
            abort(403, 'Akses ditolak. Hanya HR/Admin yang dapat mengekspor data absensi.');
        }

        $bulan = $request->bulan ?? date('Y-m');
        $tanggalMulai = $bulan . '-01';

        $absensiList = Absensi::with(['user.profile', 'user' => fn($q) => $q->select('id', 'name')])
            ->where('tanggal', $tanggalMulai)
            ->get();
            
        $rekapList = $absensiList->map(function($item) {
            $data = json_decode($item->keterangan, true);
            if (!is_array($data)) {
                $data = [
                    'periode' => '-',
                    'hari_dibutuhkan' => 0,
                    'hari_hadir' => 0,
                    'hari_tidak_hadir' => 0,
                    'terlambat_count' => 0,
                    'terlambat_menit' => 0,
                    'lembur_menit' => 0,
                ];
            }
            $item->rekap = $data;
            return $item;
        });

        // hitung ringkasan
        $ringkasan = [
            'total_karyawan' => $rekapList->count(),
            'total_hadir'  => $rekapList->sum(fn($i) => $i->rekap['hari_hadir'] ?? 0),
            'total_alfa' => $rekapList->sum(fn($i) => $i->rekap['hari_tidak_hadir'] ?? 0),
            'total_terlambat_kali' => $rekapList->sum(fn($i) => $i->rekap['terlambat_count'] ?? 0),
            'total_lembur_jam' => round($rekapList->sum(fn($i) => $i->rekap['lembur_menit'] ?? 0) / 60, 2),
        ];

        $pdf = Pdf::loadView('hr.absensi.export-pdf', compact('rekapList', 'bulan', 'ringkasan'))
            ->setPaper('A4', 'landscape');

        return $pdf->download('rekap-absensi-' . $bulan . '.pdf');
    }

    public function destroy($id)
    {
        if (!auth()->user()->hasAnyRole(['hr', 'admin', 'super-admin'])) {
            abort(403, 'Akses ditolak.');
        }

        try {
            Absensi::findOrFail($id)->delete();
            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }
}