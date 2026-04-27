@extends('layouts.master')

@section('content')

{{-- Wrapper ikuti struktur template asli --}}
<div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
<div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
<div class="sng">

{{-- ── GREETING BANNER — SEMUA ROLE HIJAU ───────────── --}}
<div class="ds-banner">
    <div class="relative z-10 flex items-center justify-between flex-wrap gap-4">
        <div class="flex items-center gap-4 md:gap-6">
            <img src="{{ URL::to('assets/images/logo-sinergi.png') }}"
                 alt="Sinergi" class="h-11 opacity-90"
                 style="filter:brightness(0) invert(1);"
                 onerror="this.style.display='none'">
            <div>
                <p class="text-xs font-semibold tracking-wider uppercase opacity-80">Selamat Datang Kembali</p>
                <h2 class="text-xl md:text-2xl font-bold mt-1">{{ $userRoleName }} {{ auth()->user()->name }}</h2>
                <p class="text-xs opacity-70 mt-1">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
            </div>
        </div>

        <div class="ds-banner-info">
            <p class="text-xs font-semibold tracking-wider uppercase opacity-90">Sistem HRIS</p>
            <p class="text-sm font-bold mt-1">Sinergi Hotel & Villa</p>
            <p class="text-xs opacity-70 mt-0.5">Malang, Jawa Timur</p>
        </div>
    </div>
</div>

{{-- ── BREADCRUMB ──────────────────────────────────────────── --}}
<div class="flex items-center justify-between gap-4 mb-6 flex-wrap print:hidden mt-6">
    <div>
        @role('hr')
            <h5 class="text-xl font-bold text-slate-900">Dashboard HR</h5>
            <p class="text-sm mt-1 text-slate-500">Overview Karyawan, Absensi & Penggajian</p>
        @endrole
        @role('supervisor')
            <h5 class="text-xl font-bold text-slate-900">Dashboard Supervisor</h5>
            <p class="text-sm mt-1 text-slate-500">Monitoring Absensi & Approval Surat</p>
        @endrole
        @role('staff')
            <h5 class="text-xl font-bold text-slate-900">Dashboard Saya</h5>
            <p class="text-sm mt-1 text-slate-500">Surat Saya & Status Approval</p>
        @endrole
    </div>
    <div class="text-sm flex items-center gap-1 text-slate-500">
        <span>Dashboard</span><span class="opacity-35">/</span>
        @role('hr')<span class="font-bold text-custom-500">HR</span>@endrole
        @role('supervisor')<span class="font-bold text-custom-500">Supervisor</span>@endrole
        @role('staff')<span class="font-bold text-custom-500">Staff</span>@endrole
    </div>
</div>

{{-- ══════════════════════════════════════════════════════════
     ROLE: HR — TAMPILAN PENUH
═══════════════════════════════════════════════════════════ --}}
@role('hr')

{{-- ROW 1 — 4 STAT CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    {{-- Card 1: Total Karyawan --}}
    <div class="ds-card">
        <div class="flex justify-between items-start mb-4">
            <div class="ds-icon ic-blue"><i data-lucide="users" class="w-5 h-5"></i></div>
            <span class="ds-badge b-green">AKTIF</span>
        </div>
        <p class="text-xs font-bold tracking-widest uppercase mb-1 text-slate-500">Total Karyawan</p>
        <h3 class="text-4xl font-bold leading-none text-custom-500">{{ $totalKaryawan ?? 0 }}</h3>
        <p class="text-sm mt-1 text-slate-500">karyawan status aktif</p>
    </div>

    {{-- Card 2: Hadir Hari Ini --}}
    <div class="ds-card">
        <div class="flex justify-between items-start mb-4">
            <div class="ds-icon ic-green"><i data-lucide="check-circle" class="w-5 h-5"></i></div>
            <span class="ds-badge b-blue">HARI INI</span>
        </div>
        <p class="text-xs font-bold tracking-widest uppercase mb-1 text-slate-500">Hadir Hari Ini</p>
        <h3 class="text-4xl font-bold leading-none text-blue-600">{{ $hadirHariIni ?? 0 }}</h3>
        <p class="text-sm mt-1 text-slate-500">
            dari {{ $totalKaryawan ?? 0 }} karyawan
            <span class="font-bold text-custom-500">({{ ($totalKaryawan ?? 0) > 0 ? round((($hadirHariIni ?? 0)/($totalKaryawan))*100) : 0 }}%)</span>
        </p>
    </div>

    {{-- Card 3: Cuti Menunggu --}}
    <div class="ds-card ds-warn">
        <div class="flex justify-between items-start mb-4">
            <div class="ds-icon ic-amber"><i data-lucide="calendar-clock" class="w-5 h-5"></i></div>
            <span class="ds-badge b-amber">PENDING</span>
        </div>
        <p class="text-xs font-bold tracking-widest uppercase mb-1 text-slate-500">Cuti Menunggu</p>
        <h3 class="text-4xl font-bold leading-none text-amber-600">{{ $cutiMenungguCount ?? 0 }}</h3>
        <p class="text-sm mt-1 text-slate-500">menunggu persetujuan HR</p>
    </div>

    {{-- Card 4: Departemen --}}
    <div class="ds-card">
        <div class="flex justify-between items-start mb-4">
            <div class="ds-icon ic-purple"><i data-lucide="building-2" class="w-5 h-5"></i></div>
            <span class="ds-badge b-gray">UNIT</span>
        </div>
        <p class="text-xs font-bold tracking-widest uppercase mb-1 text-slate-500">Departemen</p>
        <h3 class="text-4xl font-bold leading-none text-slate-900">{{ $totalDepartemen ?? 0 }}</h3>
        <p class="text-sm mt-1 text-slate-500">unit departemen aktif</p>
    </div>

</div>

{{-- ROW 2 — PAYROLL & OVERTIME --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    
    {{-- Card: Gaji Dibayar --}}
    <div class="ds-card">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase mb-2 text-slate-500">Total Gaji Dibayar Bulan Ini</p>
                <h3 class="text-2xl font-bold text-slate-900">Rp{{ number_format($totalGajiBayar ?? 0,0,',','.') }}</h3>
                <p class="text-sm mt-2 text-slate-500">Status <span class="font-bold text-custom-500">dibayar</span> · {{ \Carbon\Carbon::now()->locale('id')->format('F') }}</p>
            </div>
            <div class="ds-icon ic-teal"><i data-lucide="banknote" class="w-5 h-5"></i></div>
        </div>
    </div>

    {{-- Card: Total Jam Lembur --}}
    <div class="ds-card">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase mb-2 text-slate-500">Total Jam Lembur</p>
                <h3 class="text-2xl font-bold text-slate-900">{{ round($totalJamLembur ?? 0,1) }}<span class="text-sm font-semibold text-slate-500 ml-1">jam</span></h3>
                <p class="text-sm mt-2 text-slate-500">rata-rata <span class="font-bold">{{ round(($totalJamLembur ?? 0) / 30, 1) }}</span> jam/hari</p>
            </div>
            <div class="ds-icon ic-amber"><i data-lucide="timer" class="w-5 h-5"></i></div>
        </div>
    </div>

    {{-- Card: Surat Menunggu --}}
    <div class="ds-card ds-warn">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase mb-2 text-slate-500">Surat Menunggu</p>
                <h3 class="text-2xl font-bold text-amber-600">{{ $suratMenungguCount ?? 0 }}</h3>
                <p class="text-sm mt-2 text-slate-500">sedang dalam proses approval</p>
            </div>
            <div class="ds-icon ic-amber"><i data-lucide="file-clock" class="w-5 h-5"></i></div>
        </div>
    </div>

    {{-- Card: Selesai Hari Ini --}}
    <div class="ds-card">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-xs font-bold tracking-widest uppercase mb-2 text-slate-500">Selesai Hari Ini</p>
                <h3 class="text-2xl font-bold text-green-600">{{ $suratSelesaiHariIni ?? 0 }}</h3>
                <p class="text-sm mt-2 text-slate-500">surat yang sudah disetujui</p>
            </div>
            <div class="ds-icon ic-green"><i data-lucide="file-check-2" class="w-5 h-5"></i></div>
        </div>
    </div>

</div>

{{-- ROW 3 — CHARTS --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    
    {{-- Chart: Absensi 7 Hari Terakhir --}}
    <div class="ds-section">
        <div class="ds-section-title">Absensi 7 Hari Terakhir</div>
        <div class="relative h-48"><canvas id="chartAbsensi"></canvas></div>
    </div>

    {{-- Chart: Jam Lembur 7 Hari Terakhir --}}
    <div class="ds-section">
        <div class="ds-section-title" style="border-left-color: #f59e0b;">Jam Lembur 7 Hari Terakhir</div>
        <div class="relative h-48"><canvas id="chartLembur"></canvas></div>
    </div>

</div>

{{-- ROW 4 — LISTS --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">
    
    {{-- List: Cuti Terbaru --}}
    <div class="ds-section">
        <div class="ds-section-title">Pengajuan Cuti Terbaru</div>
        @php $palBg=['#dcf5e7','#dbeafe','#ede9fe','#fef3c7','#ffe4e6']; $palFg=['#14532d','#1e3a8a','#4c1d95','#78350f','#7f1d1d']; @endphp
        @forelse($cutiMenungguTerbaru ?? [] as $cuti)
            @php $ci=$loop->index%5; @endphp
            <div class="ds-row">
                <div class="ds-avatar" style="background:{{ $palBg[$ci] }};color:{{ $palFg[$ci] }};">{{ strtoupper(substr($cuti->employee_name??'K',0,2)) }}</div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold m-0 truncate text-slate-900">{{ $cuti->employee_name ?? '-' }}</p>
                    <p class="text-xs mt-1 text-slate-500">{{ date('d M',strtotime($cuti->date_from??now())) }} – {{ date('d M Y',strtotime($cuti->date_to??now())) }}</p>
                </div>
                <span class="ds-badge b-amber">{{ $cuti->status ?? 'menunggu' }}</span>
            </div>
        @empty
            <div class="text-center py-6">
                <i data-lucide="calendar-check" class="w-6 h-6 mx-auto mb-2 block text-slate-400"></i>
                <p class="text-xs m-0 text-slate-500">Tidak ada cuti menunggu</p>
            </div>
        @endforelse
    </div>

    {{-- List: Karyawan Lembur Terbanyak (optional, bisa disesuaikan) --}}
    <div class="ds-section">
        <div class="ds-section-title">Karyawan Lembur Terbanyak</div>
        @forelse($cutiMenungguTerbaru ?? [] as $item)
            @php $ci=$loop->index%5; @endphp
            <div class="ds-row">
                <div class="ds-avatar" style="background:{{ $palBg[$ci] }};color:{{ $palFg[$ci] }};">{{ strtoupper(substr($item->employee_name??'K',0,2)) }}</div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold m-0 truncate text-slate-900">{{ $item->employee_name ?? '-' }}</p>
                    <p class="text-xs mt-1 text-slate-500">Total lembur bulan ini</p>
                </div>
                <span class="ds-badge b-blue">8.5 jam</span>
            </div>
        @empty
            <div class="text-center py-6">
                <i data-lucide="timer" class="w-6 h-6 mx-auto mb-2 block text-slate-400"></i>
                <p class="text-xs m-0 text-slate-500">Tidak ada data lembur</p>
            </div>
        @endforelse
    </div>

</div>

@endrole{{-- END HR --}}


{{-- ══════════════════════════════════════════════════════════
     ROLE: SUPERVISOR — MONITORING + APPROVAL
═══════════════════════════════════════════════════════════ --}}
@role('supervisor')

{{-- ROW 1 — 4 STAT CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">

    {{-- Card 1: Total Karyawan --}}
    <div class="ds-card">
        <div class="flex justify-between items-start mb-4">
            <div class="ds-icon ic-blue"><i data-lucide="users" class="w-5 h-5"></i></div>
            <span class="ds-badge b-blue">LIHAT</span>
        </div>
        <p class="text-xs font-bold tracking-widest uppercase mb-1 text-slate-500">Total Karyawan</p>
        <h3 class="text-4xl font-bold leading-none text-blue-600">{{ $totalKaryawan ?? 0 }}</h3>
        <p class="text-sm mt-1 text-slate-500">karyawan aktif</p>
    </div>

    {{-- Card 2: Hadir Hari Ini --}}
    <div class="ds-card">
        <div class="flex justify-between items-start mb-4">
            <div class="ds-icon ic-green"><i data-lucide="check-circle" class="w-5 h-5"></i></div>
            <span class="ds-badge b-blue">HARI INI</span>
        </div>
        <p class="text-xs font-bold tracking-widest uppercase mb-1 text-slate-500">Hadir Hari Ini</p>
        <h3 class="text-4xl font-bold leading-none text-blue-600">{{ $hadirHariIni ?? 0 }}</h3>
        <p class="text-sm mt-1 text-slate-500">
            dari {{ $totalKaryawan ?? 0 }} karyawan
            <span class="font-bold text-blue-600">({{ ($totalKaryawan ?? 0) > 0 ? round((($hadirHariIni ?? 0)/($totalKaryawan))*100) : 0 }}%)</span>
        </p>
    </div>

    {{-- Card 3: Cuti Menunggu --}}
    <div class="ds-card ds-warn">
        <div class="flex justify-between items-start mb-4">
            <div class="ds-icon ic-amber"><i data-lucide="calendar-clock" class="w-5 h-5"></i></div>
            <span class="ds-badge b-amber">PENDING</span>
        </div>
        <p class="text-xs font-bold tracking-widest uppercase mb-1 text-slate-500">Cuti Menunggu</p>
        <h3 class="text-4xl font-bold leading-none text-amber-600">{{ $cutiMenungguCount ?? 0 }}</h3>
        <p class="text-sm mt-1 text-slate-500">menunggu persetujuan</p>
    </div>

    {{-- Card 4: Surat Perlu Approval --}}
    <div class="ds-card">
        <div class="flex justify-between items-start mb-4">
            <div class="ds-icon ic-red"><i data-lucide="file-check" class="w-5 h-5"></i></div>
            <span class="ds-badge b-red">SUBMITTED</span>
        </div>
        <p class="text-xs font-bold tracking-widest uppercase mb-1 text-slate-500">Surat Perlu Approval</p>
        <h3 class="text-4xl font-bold leading-none text-red-600">{{ $suratSubmitted ?? 0 }}</h3>
        <p class="text-sm mt-1 text-slate-500">menunggu persetujuan Anda</p>
    </div>

</div>

{{-- ROW 2 — CHART + LIST SURAT --}}
<div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-6">

    {{-- Chart: Absensi 7 Hari Terakhir --}}
    <div class="ds-section">
        <div class="ds-section-title">Absensi 7 Hari Terakhir</div>
        <div class="relative h-48"><canvas id="chartAbsensi"></canvas></div>
    </div>

    {{-- List: Surat Menunggu Approval --}}
    <div class="ds-section">
        <div class="flex items-center justify-between mb-4">
            <div class="ds-section-title m-0">Surat Menunggu Approval Saya</div>
            <a href="{{ route('surat.index') }}" class="text-xs font-bold text-custom-500 hover:underline">Lihat semua →</a>
        </div>
        @php $palBg=['#dcf5e7','#dbeafe','#ede9fe','#fef3c7','#ffe4e6']; $palFg=['#14532d','#1e3a8a','#4c1d95','#78350f','#7f1d1d']; @endphp
        @forelse($suratMenungguList ?? [] as $item)
            @php $ci=$loop->index%5; @endphp
            <div class="ds-row">
                <div class="ds-avatar" style="background:{{ $palBg[$ci] }};color:{{ $palFg[$ci] }};">
                    <i data-lucide="file-text" class="w-3.5 h-3.5"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="text-xs font-bold m-0 truncate text-slate-900">{{ $item->nomor_surat ?? 'Surat-'.$item->id }}</p>
                    <p class="text-xs mt-0.5 text-slate-500">{{ $item->user->name ?? '-' }} · {{ $item->jenis_surat }}</p>
                </div>
                <span class="ds-badge b-red">submitted</span>
            </div>
        @empty
            <div class="text-center py-6">
                <i data-lucide="file-check" class="w-6 h-6 mx-auto mb-2 block text-slate-400"></i>
                <p class="text-xs m-0 text-slate-500">Tidak ada surat menunggu</p>
            </div>
        @endforelse
    </div>

</div>

@endrole{{-- END SUPERVISOR --}}


{{-- ══════════════════════════════════════════════════════════
     ROLE: STAFF — SURAT PRIBADI
═══════════════════════════════════════════════════════════ --}}
@role('staff')

{{-- ROW 1 — 3 STAT CARDS --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">

    {{-- Card 1: Total Surat --}}
    <div class="ds-card">
        <div class="flex justify-between items-start mb-4">
            <div class="ds-icon ic-purple"><i data-lucide="file-text" class="w-5 h-5"></i></div>
            <span class="ds-badge b-purple">SAYA</span>
        </div>
        <p class="text-xs font-bold tracking-widest uppercase mb-1 text-slate-500">Total Surat Saya</p>
        <h3 class="text-4xl font-bold leading-none text-purple-600">{{ count($suratStaff ?? []) }}</h3>
        <p class="text-sm mt-1 text-slate-500">surat yang dibuat</p>
    </div>

    {{-- Card 2: Menunggu Approval --}}
    <div class="ds-card ds-warn">
        <div class="flex justify-between items-start mb-4">
            <div class="ds-icon ic-amber"><i data-lucide="clock" class="w-5 h-5"></i></div>
            <span class="ds-badge b-amber">PENDING</span>
        </div>
        <p class="text-xs font-bold tracking-widest uppercase mb-1 text-slate-500">Menunggu Approval</p>
        <h3 class="text-4xl font-bold leading-none text-amber-600">{{ $suratStaffPendingCount ?? 0 }}</h3>
        <p class="text-sm mt-1 text-slate-500">dalam proses review</p>
    </div>

    {{-- Card 3: Perlu Revisi --}}
    <div class="ds-card ds-danger">
        <div class="flex justify-between items-start mb-4">
            <div class="ds-icon ic-red"><i data-lucide="alert-circle" class="w-5 h-5"></i></div>
            <span class="ds-badge b-red">REVISI</span>
        </div>
        <p class="text-xs font-bold tracking-widest uppercase mb-1 text-slate-500">Perlu Revisi</p>
        <h3 class="text-4xl font-bold leading-none text-red-600">{{ $suratStaffRevisiCount ?? 0 }}</h3>
        <p class="text-sm mt-1 text-slate-500">perlu diperbaiki</p>
    </div>

</div>

{{-- ROW 2 — LIST SURAT --}}
<div class="ds-section mb-6">
    <div class="flex justify-between items-center mb-4">
        <div class="ds-section-title m-0">Surat Saya</div>
        <a href="{{ route('surat.create') }}" class="inline-flex items-center gap-1.5 px-3 py-2 bg-custom-500 text-white rounded-lg text-xs font-bold transition-all hover:bg-custom-600">
            + Buat Surat
        </a>
    </div>

    @php $palBg=['#dcf5e7','#dbeafe','#ede9fe','#fef3c7','#ffe4e6']; $palFg=['#14532d','#1e3a8a','#4c1d95','#78350f','#7f1d1d']; @endphp
    @forelse($suratStaff ?? [] as $surat)
        <div class="ds-row">
            <div class="ds-avatar" style="background:{{ $palBg[$loop->index%5] }};color:{{ $palFg[$loop->index%5] }};">
                <i data-lucide="file-text" class="w-4 h-4"></i>
            </div>
            <div class="flex-1 min-w-0">
                <p class="text-xs font-bold m-0 truncate text-slate-900">{{ $surat->nomor_surat ?? 'Surat-'.$surat->id }}</p>
                <p class="text-xs mt-0.5 text-slate-500">{{ substr($surat->perihal ?? 'Surat', 0, 45) }}{{ strlen($surat->perihal ?? '') > 45 ? '...' : '' }}</p>
            </div>
            <div class="flex-shrink-0">
                @if($surat->status === 'submitted')
                    <span class="ds-badge b-blue">diajukan</span>
                @elseif($surat->status === 'approved_supervisor')
                    <span class="ds-badge b-amber">approval owner</span>
                @elseif($surat->status === 'approved_owner')
                    <span class="ds-badge b-green">✓ disetujui</span>
                @elseif($surat->status === 'revised')
                    <span class="ds-badge b-red">⚠ revisi</span>
                @elseif($surat->status === 'rejected')
                    <span class="ds-badge b-red">ditolak</span>
                @else
                    <span class="ds-badge b-green">{{ $surat->status }}</span>
                @endif
            </div>
        </div>
    @empty
        <div class="text-center py-8">
            <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 block text-slate-400"></i>
            <p class="text-sm m-0 text-slate-500">Belum ada surat</p>
            <a href="{{ route('surat.create') }}" class="inline-block mt-3 px-4 py-2 bg-custom-500 text-white rounded-lg text-xs font-bold hover:bg-custom-600 transition-all">
                Buat Surat Pertama
            </a>
        </div>
    @endforelse
</div>

@endrole{{-- END STAFF --}}

</div>{{-- end .sng --}}
</div>{{-- end container-fluid --}}
</div>{{-- end wrapper --}}

{{-- Chart.js — hanya untuk HR & Supervisor --}}
@role('hr|supervisor')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function(){
    const font = { family: 'Plus Jakarta Sans' };
    const opts = {
        responsive: true, maintainAspectRatio: false,
        plugins: {
            legend: { position:'bottom', labels: { font:{...font,size:11,weight:'600'}, usePointStyle:true, pointStyleWidth:8, padding:16 } },
            tooltip: { backgroundColor:'#f0f7f2', titleColor:'#14321f', bodyColor:'#3d6650', borderColor:'rgba(148,188,163,.4)', borderWidth:1, titleFont:{...font,weight:'700',size:12}, bodyFont:{...font,size:11}, padding:10, cornerRadius:10 }
        },
        scales: {
            x: { grid:{display:false}, ticks:{font:{...font,size:10,weight:'600'},color:'#7aaa8e'} },
            y: { beginAtZero:true, grid:{color:'rgba(148,188,163,.18)'}, ticks:{font:{...font,size:10},color:'#7aaa8e'} }
        }
    };
    
    // Chart Absensi (Line Chart)
    const ctxA = document.getElementById('chartAbsensi');
    if(ctxA){
        new Chart(ctxA, {
            type:'line',
            data:{
                labels: @json($chartAbsensi['labels'] ?? []),
                datasets:[
                    { label:'Hadir',  data:@json($chartAbsensi['datasets'][0]['data'] ?? []), borderColor:'#1a9e5c', backgroundColor:'rgba(26,158,92,.1)',  tension:.4, fill:true,  pointRadius:4, pointBackgroundColor:'#1a9e5c', borderWidth:2.5 },
                    { label:'Izin',   data:@json($chartAbsensi['datasets'][1]['data'] ?? []), borderColor:'#d97706', backgroundColor:'rgba(217,119,6,.06)', tension:.4, fill:false, pointRadius:3, pointBackgroundColor:'#d97706', borderWidth:2 },
                    { label:'Sakit',  data:@json($chartAbsensi['datasets'][2]['data'] ?? []), borderColor:'#2563eb', backgroundColor:'rgba(37,99,235,.06)',  tension:.4, fill:false, pointRadius:3, pointBackgroundColor:'#2563eb', borderWidth:2 },
                    { label:'Alpha',  data:@json($chartAbsensi['datasets'][3]['data'] ?? []), borderColor:'#e11d48', backgroundColor:'rgba(225,29,72,.06)',   tension:.4, fill:false, pointRadius:3, pointBackgroundColor:'#e11d48', borderWidth:2 },
                ]
            },
            options: opts
        });
    }
    
    // Chart Lembur (Bar Chart) — hanya untuk HR
    @role('hr')
    const ctxL = document.getElementById('chartLembur');
    if(ctxL){
        new Chart(ctxL, {
            type:'bar',
            data:{
                labels: @json($chartAbsensi['labels'] ?? []),
                datasets:[
                    { label:'Jam Lembur', data:@json($chartAbsensi['datasets'][0]['data'] ?? []), backgroundColor:'#04A54C', borderRadius:6, borderSkipped:false }
                ]
            },
            options: {
                responsive: true, maintainAspectRatio: false,
                plugins: {
                    legend: { display:true, position:'bottom', labels: { font:{...font,size:11,weight:'600'}, usePointStyle:true, pointStyleWidth:8, padding:16 } },
                    tooltip: { backgroundColor:'#dcf5e7', titleColor:'#14532d', bodyColor:'#04A54C', borderColor:'rgba(4,165,76,.3)', borderWidth:1, titleFont:{...font,weight:'700',size:12}, bodyFont:{...font,size:11}, padding:10, cornerRadius:10 }
                },
                scales: { 
                    x: { grid:{display:false}, ticks:{font:{...font,size:10,weight:'600'},color:'#7aaa8e'} },
                    y: { beginAtZero:true, grid:{color:'rgba(148,188,163,.18)'}, ticks:{font:{...font,size:10},color:'#7aaa8e'} }
                }
            }
        });
    }
    @endrole
})();
</script>
@endrole

@endsection