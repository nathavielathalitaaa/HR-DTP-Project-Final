<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Department;
use App\Models\DocumentApproval;
use App\Models\Leave;
use App\Models\Penggajian;
use App\Models\Surat;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $user = auth()->user();

        // Data dasar — dikirim ke semua role
        $data = [
            'userRoleName'    => match(true) {
                $user->hasRole('hr')         => 'HR',
                $user->hasRole('supervisor') => 'Supervisor',
                $user->hasRole('staff')      => 'Staff',
                default                      => 'Karyawan'
            },
            'userDisplayName' => 'Selamat datang kembali',
        ];

        // ── HR: statistik penuh ───────────────────────────────
        if ($user->hasRole('hr')) {
            $data = array_merge($data, [
                'totalKaryawan'       => User::where('status', 'aktif')->count(),
                'hadirHariIni'        => Absensi::where('tanggal', now()->format('Y-m-d'))->where('status', 'hadir')->count(),
                'cutiMenungguCount'   => Leave::where('status', 'menunggu')->count(),
                'totalDepartemen'     => Department::count(),
                'totalGajiBayar'      => Penggajian::where('periode', now()->format('Y-m'))->where('status', 'dibayar')->sum('gaji_bersih'),
                'totalJamLembur'      => $this->getTotalJamLembur(),
                'chartAbsensi'        => $this->getChartAbsensi(),
                'cutiMenungguTerbaru' => Leave::where('status', 'menunggu')->orderBy('created_at', 'desc')->take(5)->get(),
                'suratMenungguCount'  => DocumentApproval::where('status', 'waiting')->where('document_type', 'LIKE', 'surat_%')->count(),
                'suratSelesaiHariIni' => Surat::where('status', 'approved_owner')->whereDate('updated_at', now()->format('Y-m-d'))->count(),
            ]);
        }

        // ── SUPERVISOR: monitoring + approval ────────────────
        // BUG FIX: tambah totalKaryawan & hadirHariIni (sebelumnya hardcode di view)
        // BUG FIX: tambah $suratMenungguList untuk list surat di dashboard
        elseif ($user->hasRole('supervisor')) {
            $data = array_merge($data, [
                'totalKaryawan'      => User::where('status', 'aktif')->count(),
                'hadirHariIni'       => Absensi::where('tanggal', now()->format('Y-m-d'))->where('status', 'hadir')->count(),
                'cutiMenungguCount'  => Leave::where('status', 'menunggu')->count(),
                'suratSubmitted'     => Surat::where('status', 'submitted')->count(),
                'suratMenungguList'  => Surat::where('status', 'submitted')
                                            ->with('user')
                                            ->orderBy('created_at', 'desc')
                                            ->take(5)
                                            ->get(),
                'chartAbsensi'       => $this->getChartAbsensi(),
            ]);
        }

        // ── STAFF: surat milik sendiri ────────────────────────
        elseif ($user->hasRole('staff')) {
            $suratStaff = Surat::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
            $data = array_merge($data, [
                'suratStaff'             => $suratStaff,
                'suratStaffPendingCount' => $suratStaff->whereIn('status', ['submitted', 'approved_supervisor'])->count(),
                'suratStaffRevisiCount'  => $suratStaff->where('status', 'revised')->count(),
            ]);
        }

        return view('dashboard.home', $data);
    }

    private function getTotalJamLembur(): float
    {
        $tanggalAwal  = now()->subDays(30)->format('Y-m-d');
        $totalLembur  = 0;

        Absensi::whereBetween('tanggal', [$tanggalAwal, now()->format('Y-m-d')])
            ->whereNotNull('jam_keluar')
            ->whereNotNull('jam_masuk')
            ->get()
            ->each(function ($a) use (&$totalLembur) {
                $selisih = (strtotime($a->jam_keluar) - strtotime($a->jam_masuk)) / 3600;
                if ($selisih > 8) $totalLembur += $selisih - 8;
            });

        return $totalLembur;
    }

    private function getChartAbsensi(): array
    {
        $labels = [];
        $hadir = $izin = $sakit = $alpha = [];

        for ($i = 6; $i >= 0; $i--) {
            $tgl      = now()->subDays($i)->format('Y-m-d');
            $labels[] = now()->subDays($i)->format('D, d M');

            $hadir[]  = Absensi::where('tanggal', $tgl)->where('status', 'hadir')->count();
            $izin[]   = Absensi::where('tanggal', $tgl)->where('status', 'izin')->count();
            $sakit[]  = Absensi::where('tanggal', $tgl)->where('status', 'sakit')->count();
            $alpha[]  = Absensi::where('tanggal', $tgl)->where('status', 'alpha')->count();
        }

        return [
            'labels'   => $labels,
            'datasets' => [
                ['label' => 'hadir',  'data' => $hadir,  'borderColor' => '#10b981', 'backgroundColor' => 'rgba(16,185,129,.1)'],
                ['label' => 'izin',   'data' => $izin,   'borderColor' => '#f59e0b', 'backgroundColor' => 'rgba(245,158,11,.1)'],
                ['label' => 'sakit',  'data' => $sakit,  'borderColor' => '#3b82f6', 'backgroundColor' => 'rgba(59,130,246,.1)'],
                ['label' => 'alpha',  'data' => $alpha,  'borderColor' => '#ef4444', 'backgroundColor' => 'rgba(239,68,68,.1)'],
            ],
        ];
    }
}