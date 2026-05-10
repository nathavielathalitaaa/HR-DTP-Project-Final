{{-- ═══════════════════════════════════════════════
     floating pill sidebar – placed directly under <body>
     class: .hv-sidebar  (styled in master.blade.php)
     ═══════════════════════════════════════════════ --}}
<div class="hv-sidebar" id="hv-sidebar">

    {{-- ── top nav icons ── --}}
    <div class="hv-sidebar-nav">

        {{-- profile --}}
        <a href="{{ route('profile.show') }}"
           class="{{ request()->routeIs('profile.show') ? 'active' : '' }}"
           title="Profile">
            <i data-lucide="user-circle"></i>
        </a>

        {{-- dashboard --}}
        <a href="{{ route('home') }}"
           class="{{ request()->routeIs('home') ? 'active' : '' }}"
           title="Dashboard">
            <i data-lucide="monitor"></i>
        </a>

        {{-- karyawan (hr only) --}}
        @if(auth()->user()->hasRole('hr'))
        <a href="{{ route('hr/employee/list') }}"
           class="{{ request()->routeIs('hr/employee/list') ? 'active' : '' }}"
           title="Employees">
            <i data-lucide="layout-grid"></i>
        </a>
        @endif

        {{-- absensi (hr only) --}}
        @if(auth()->user()->hasRole('hr'))
        <a href="{{ route('hr/absensi/page') }}"
           class="{{ request()->routeIs('hr/absensi/page') ? 'active' : '' }}"
           title="Attendance">
            <i data-lucide="calendar-range"></i>
        </a>
        <a href="{{ route('hr/absensi/ai') }}"
           class="{{ request()->routeIs('hr/absensi/ai*') ? 'active' : '' }}"
           title="AI Summary">
            <i data-lucide="sparkles"></i>
        </a>
        @endif

        {{-- surat --}}
        <a href="{{ route('surat.index') }}"
           class="{{ request()->routeIs('surat.*') && !request()->routeIs('surat-type.*') ? 'active' : '' }}"
           title="Letters">
            <i data-lucide="mail"></i>
        </a>

        {{-- jenis surat (hr only) --}}
        @if(auth()->user()->hasRole('hr'))
        <a href="{{ route('surat-type.index') }}"
           class="{{ request()->routeIs('surat-type.*') ? 'active' : '' }}"
           title="Letter Types">
            <i data-lucide="file-cog"></i>
        </a>
        @endif




    </div>

    {{-- ── logout at bottom ── --}}
    <div class="hv-sidebar-bottom">
        <a href="{{ route('logout') }}" class="hv-sidebar-logout" title="Logout">
            <i data-lucide="log-out"></i>
        </a>
    </div>

</div>