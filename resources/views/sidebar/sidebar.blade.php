<style>
/* ── SIDEBAR DESIGN TOKENS ── */
.sb-wrap { display:flex; flex-direction:column; height:100vh; padding:0; overflow:hidden; }

.sb-logo {
    display:flex; align-items:center; gap:11px;
    padding:20px 18px 16px;
    border-bottom:1px solid rgba(255,255,255,.06);
    flex-shrink:0;
}
.sb-logo img { height:34px; filter:brightness(0) invert(1); opacity:.92; flex-shrink:0; }
.sb-logo-text p { margin:0; line-height:1.3; }
.sb-logo-sup  { font-size:9px; font-weight:700; letter-spacing:.12em; text-transform:uppercase; color:rgba(134,239,172,.55); }
.sb-logo-name { font-size:13px; font-weight:800; color:#fff; }

.sb-nav { flex:1; overflow-y:auto; padding:12px 12px 0; scrollbar-width:none; }
.sb-nav::-webkit-scrollbar { display:none; }

.sb-label {
    font-size:9px; font-weight:700; letter-spacing:.14em; text-transform:uppercase;
    color:rgba(134,239,172,.45); padding:12px 10px 4px;
}

.sb-item {
    display:flex; align-items:center; gap:10px;
    padding:10px 12px; border-radius:14px; margin-bottom:2px;
    font-size:13.5px; font-weight:500; color:rgba(187,247,208,.72);
    text-decoration:none; cursor:pointer; border:none; background:none; width:100%;
    position:relative;
}
.sb-item.active {
    background:rgba(255,255,255,.12);
    color:#fff; font-weight:700;
    box-shadow:inset 0 0 0 1px rgba(255,255,255,.08);
}
.sb-item.active::before {
    content:''; position:absolute; left:0; top:50%; transform:translateY(-50%);
    width:3px; height:60%; border-radius:0 3px 3px 0;
    background:linear-gradient(180deg,#4ade80,#22c55e);
}

.sb-ico {
    width:32px; height:32px; border-radius:10px; flex-shrink:0;
    display:flex; align-items:center; justify-content:center;
}
.sb-ico i { width:15px; height:15px; }

.sic-blue   { background:rgba(59,130,246,.2);  color:#93c5fd; }
.sic-green  { background:rgba(34,197,94,.2);   color:#86efac; }
.sic-amber  { background:rgba(245,158,11,.2);  color:#fcd34d; }
.sic-teal   { background:rgba(20,184,166,.2);  color:#5eead4; }
.sic-rose   { background:rgba(244,63,94,.2);   color:#fda4af; }
.sic-slate  { background:rgba(148,163,184,.15);color:#cbd5e1; }

.sb-chev { margin-left:auto; flex-shrink:0; transition:transform .2s ease; }
.sb-chev.open { transform:rotate(180deg); }

.sb-sub { overflow:hidden; max-height:0; transition:max-height .25s ease; }
.sb-sub.open { max-height:400px; }

.sb-sub-item {
    display:flex; align-items:center; gap:10px;
    padding:7px 14px 7px 18px; border-radius:10px; margin-bottom:1px;
    font-size:12.5px; font-weight:500; color:rgba(187,247,208,.6);
    text-decoration:none;
}
.sb-sub-item.active { color:#fff; font-weight:700; }
.sb-sub-dot {
    width:5px; height:5px; border-radius:50%; flex-shrink:0;
    background:rgba(134,239,172,.4);
}
.sb-sub-item.active .sb-sub-dot { background:#4ade80; }

.sb-divider { height:1px; background:rgba(255,255,255,.06); margin:8px 10px; }

.sb-footer {
    padding:12px 18px 16px; flex-shrink:0;
    border-top:1px solid rgba(255,255,255,.06);
    text-align:center;
}
.sb-footer-text { font-size:10px; color:rgba(134,239,172,.5); line-height:1.6; }
.sb-footer-text strong { color:rgba(255,255,255,.65); }

.sb-user {
    display:flex; align-items:center; gap:10px;
    padding:10px 12px; margin:8px 0 4px;
    background:rgba(255,255,255,.06); border-radius:14px;
}
.sb-avatar {
    width:34px; height:34px; border-radius:10px; flex-shrink:0;
    background:linear-gradient(135deg,#166534,#16a34a);
    display:flex; align-items:center; justify-content:center;
    font-size:13px; font-weight:800; color:#fff;
}
.sb-user-name { font-size:12.5px; font-weight:700; color:#fff; margin:0; }
.sb-user-role { font-size:10px; color:rgba(134,239,172,.6); font-weight:600;
    text-transform:uppercase; letter-spacing:.06em; margin:0; }
</style>

@php
    $authUser    = auth()->user();
    $userJabatan = $authUser->profile?->jabatan;
    $isHR        = $authUser->hasRole('hr');
    $isSupervisor= $authUser->hasRole('supervisor');
    $isStaff     = $authUser->hasRole('staff');
    $isApprover  = !empty($userJabatan); // punya jabatan = bisa approve
    $jabatanLabel = match($userJabatan) {
        'hod'        => 'Head of Department',
        'purchasing' => 'Purchasing',
        'owner_rep'  => 'Owner Representative',
        'direktur'   => 'Direktur',
        default      => ucfirst($authUser->role_name ?? 'User'),
    };
@endphp

<div class="sb-wrap">

    {{-- ── LOGO ── --}}
    <div class="sb-logo">
        <img src="{{ URL::to('assets/images/logo-sinergi.png') }}"
             alt="Sinergi"
             onerror="this.style.display='none'">
        <div class="sb-logo-text">
            <p class="sb-logo-sup">HR System</p>
            <p class="sb-logo-name">Sinergi Hotel & Villa</p>
        </div>
    </div>

    {{-- ── USER BADGE ── --}}
    <div style="padding:10px 12px 0;">
        <div class="sb-user">
            <div class="sb-avatar">{{ strtoupper(substr($authUser->name ?? 'U', 0, 1)) }}</div>
            <div style="min-width:0;">
                <p class="sb-user-name" style="white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">
                    {{ $authUser->name }}
                </p>
                <p class="sb-user-role">{{ $jabatanLabel }}</p>
            </div>
        </div>
    </div>

    {{-- ── NAVIGASI ── --}}
    <div class="sb-nav">

        {{-- DASBOR — semua role --}}
        <a href="{{ route('home') }}"
           class="sb-item {{ request()->routeIs('home') ? 'active' : '' }}">
            <span class="sb-ico sic-blue"><i data-lucide="monitor-dot"></i></span>
            Dasbor
        </a>

        {{-- ══ ROLE: HR (akses penuh manajemen SDM) ══ --}}
        @if($isHR)

        <div class="sb-label">Manajemen</div>

        <div>
            <button onclick="sbToggle(this)"
                    class="sb-item {{ request()->is('hr/*') && !request()->routeIs('surat.*') ? 'active' : '' }}">
                <span class="sb-ico sic-green"><i data-lucide="layout-grid"></i></span>
                Manajemen SDM
                <i data-lucide="chevron-down" class="sb-chev w-4 h-4 {{ request()->is('hr/*') ? 'open' : '' }}"></i>
            </button>
            <div class="sb-sub {{ request()->is('hr/*') && !request()->routeIs('surat.*') ? 'open' : '' }}">
                <a href="{{ route('hr/employee/list') }}" class="sb-sub-item {{ request()->routeIs('hr/employee/list') ? 'active' : '' }}">
                    <span class="sb-sub-dot"></span>Daftar Karyawan
                </a>
                <a href="{{ route('hr/absensi/page') }}" class="sb-sub-item {{ request()->routeIs('hr/absensi/page') ? 'active' : '' }}">
                    <span class="sb-sub-dot"></span>Absensi
                </a>
                <a href="{{ route('hr/shift/page') }}" class="sb-sub-item {{ request()->routeIs('hr/shift/page') ? 'active' : '' }}">
                    <span class="sb-sub-dot"></span>Shift Karyawan
                </a>
                <a href="{{ route('hr/shift/jadwal') }}" class="sb-sub-item {{ request()->routeIs('hr/shift/jadwal') ? 'active' : '' }}">
                    <span class="sb-sub-dot"></span>Jadwal Shift
                </a>
                <a href="{{ route('hr/penggajian/page') }}" class="sb-sub-item {{ request()->routeIs('hr/penggajian/page') ? 'active' : '' }}">
                    <span class="sb-sub-dot"></span>Penggajian
                </a>
                <a href="{{ route('hr/department/page') }}" class="sb-sub-item {{ request()->routeIs('hr/department/page') ? 'active' : '' }}">
                    <span class="sb-sub-dot"></span>Departemen
                </a>
                <a href="{{ route('hr/holidays/page') }}" class="sb-sub-item {{ request()->routeIs('hr/holidays/page') ? 'active' : '' }}">
                    <span class="sb-sub-dot"></span>Hari Libur
                </a>
                <a href="{{ route('hr.approval-flow.index') }}"
                   class="sb-sub-item {{ request()->routeIs('hr.approval-flow.*') ? 'active' : '' }}">
                    <span class="sb-sub-dot"></span>Alur Approval
                </a>
            </div>
        </div>

        <div>
            <button onclick="sbToggle(this)"
                    class="sb-item {{ request()->routeIs('hr/leave/*') ? 'active' : '' }}">
                <span class="sb-ico sic-amber"><i data-lucide="calendar-clock"></i></span>
                Kelola Cuti
                @php $cutiCount = \App\Models\Leave::where('status','menunggu')->count(); @endphp
                @if($cutiCount > 0)
                    <span style="margin-left:auto;background:#d97706;color:#fff;font-size:10px;font-weight:700;padding:1px 7px;border-radius:99px;">
                        {{ $cutiCount }}
                    </span>
                @else
                    <i data-lucide="chevron-down" class="sb-chev w-4 h-4 {{ request()->routeIs('hr/leave/*') ? 'open' : '' }}"></i>
                @endif
            </button>
            <div class="sb-sub {{ request()->routeIs('hr/leave/*') ? 'open' : '' }}">
                <a href="{{ route('hr/leave/employee/page') }}" class="sb-sub-item {{ request()->routeIs('hr/leave/employee/page') ? 'active' : '' }}">
                    <span class="sb-sub-dot"></span>Cuti Karyawan
                </a>
                <a href="{{ route('hr/leave/hr/page') }}" class="sb-sub-item {{ request()->routeIs('hr/leave/hr/page') ? 'active' : '' }}">
                    <span class="sb-sub-dot"></span>Input Cuti (HR)
                </a>
            </div>
        </div>

        <div class="sb-label">Halaman</div>

        <a href="{{ route('surat.index') }}" class="sb-item {{ request()->routeIs('surat.*') ? 'active' : '' }}">
            <span class="sb-ico sic-teal"><i data-lucide="mail"></i></span>
            Surat
            @php
                $suratWaitingCount = \App\Models\DocumentApproval::where('status','waiting')
                    ->where('document_type','LIKE','surat_%')
                    ->count();
            @endphp
            @if($suratWaitingCount > 0)
                <span style="margin-left:auto;background:#ef4444;color:#fff;font-size:10px;font-weight:700;padding:1px 7px;border-radius:99px;">
                    {{ $suratWaitingCount }}
                </span>
            @endif
        </a>

        @endif {{-- END HR --}}


        {{-- ══ ROLE: SUPERVISOR (monitoring) ══ --}}
        @if($isSupervisor && !$isApprover)

        <div class="sb-label">Monitoring</div>

        <div>
            <button onclick="sbToggle(this)"
                    class="sb-item {{ request()->is('hr/*') ? 'active' : '' }}">
                <span class="sb-ico sic-green"><i data-lucide="eye"></i></span>
                Monitoring
                <i data-lucide="chevron-down" class="sb-chev w-4 h-4 {{ request()->is('hr/*') ? 'open' : '' }}"></i>
            </button>
            <div class="sb-sub {{ request()->is('hr/*') ? 'open' : '' }}">
                <a href="{{ route('hr/absensi/page') }}" class="sb-sub-item {{ request()->routeIs('hr/absensi/page') ? 'active' : '' }}">
                    <span class="sb-sub-dot"></span>Absensi
                </a>
                <a href="{{ route('hr/shift/page') }}" class="sb-sub-item {{ request()->routeIs('hr/shift/page') ? 'active' : '' }}">
                    <span class="sb-sub-dot"></span>Shift Karyawan
                </a>
                <a href="{{ route('hr/leave/hr/page') }}" class="sb-sub-item {{ request()->routeIs('hr/leave/hr/page') ? 'active' : '' }}">
                    <span class="sb-sub-dot"></span>Cuti Karyawan
                </a>
            </div>
        </div>

        <div class="sb-label">Halaman</div>

        <a href="{{ route('surat.index') }}" class="sb-item {{ request()->routeIs('surat.*') ? 'active' : '' }}">
            <span class="sb-ico sic-teal"><i data-lucide="mail"></i></span>
            Surat
            @php
                $suratWaitingCount = \App\Models\DocumentApproval::where('status','waiting')
                    ->where('jabatan', auth()->user()->profile?->jabatan ?? '__none__')
                    ->where('document_type','LIKE','surat_%')
                    ->count();
            @endphp
            @if($suratWaitingCount > 0)
                <span style="margin-left:auto;background:#ef4444;color:#fff;font-size:10px;font-weight:700;padding:1px 7px;border-radius:99px;">
                    {{ $suratWaitingCount }}
                </span>
            @endif
        </a>

        @endif {{-- END SUPERVISOR --}}


        {{-- ══ APPROVER — supervisor dengan jabatan (HOD/Purchasing/Owner Rep/Direktur) ══ --}}
        @if($isApprover)

        <div class="sb-label">Approval</div>

        <a href="{{ route('surat.index') }}" class="sb-item {{ request()->routeIs('surat.*') ? 'active' : '' }}">
            <span class="sb-ico sic-teal"><i data-lucide="file-check"></i></span>
            Approval Surat
            @php
                $suratWaitingCount = \App\Models\DocumentApproval::where('status','waiting')
                    ->where('jabatan', auth()->user()->profile?->jabatan ?? '__none__')
                    ->where('document_type','LIKE','surat_%')
                    ->count();
            @endphp
            @if($suratWaitingCount > 0)
                <span style="margin-left:auto;background:#ef4444;color:#fff;font-size:10px;font-weight:700;padding:1px 7px;border-radius:99px;">
                    {{ $suratWaitingCount }}
                </span>
            @endif
        </a>

        @endif {{-- END APPROVER --}}


        {{-- ══ ROLE: STAFF ══ --}}
        @if($isStaff)

        <div class="sb-label">Halaman Saya</div>

        <a href="{{ route('hr/absensi/page') }}" class="sb-item {{ request()->routeIs('hr/absensi/page') ? 'active' : '' }}">
            <span class="sb-ico sic-blue"><i data-lucide="calendar-check"></i></span>
            Absensi Saya
        </a>

        <a href="{{ route('surat.index') }}" class="sb-item {{ request()->routeIs('surat.*') ? 'active' : '' }}">
            <span class="sb-ico sic-amber"><i data-lucide="mail"></i></span>
            Surat Saya
            @php
                $suratWaitingCount = \App\Models\DocumentApproval::where('status','waiting')
                    ->where('jabatan', auth()->user()->profile?->jabatan ?? '__none__')
                    ->where('document_type','LIKE','surat_%')
                    ->count();
            @endphp
            @if($suratWaitingCount > 0)
                <span style="margin-left:auto;background:#ef4444;color:#fff;font-size:10px;font-weight:700;padding:1px 7px;border-radius:99px;">
                    {{ $suratWaitingCount }}
                </span>
            @endif
        </a>

        @endif {{-- END STAFF --}}


        {{-- ── BOTTOM — semua role ── --}}
        <div class="sb-divider"></div>

        <a href="{{ route('profile.show') }}"
           class="sb-item {{ request()->routeIs('profile.*') ? 'active' : '' }}">
            <span class="sb-ico sic-green"><i data-lucide="user-circle"></i></span>
            Profil Saya
        </a>

        <a href="{{ route('logout/page') }}" class="sb-item" style="margin-bottom:8px;">
            <span class="sb-ico sic-rose"><i data-lucide="log-out"></i></span>
            Keluar
        </a>

    </div>{{-- end .sb-nav --}}

    <div class="sb-footer">
        <p class="sb-footer-text">
            © {{ date('Y') }} Sinergi Hotel & Villa Malang<br>
            <strong>HRIS System v1.0</strong>
        </p>
    </div>

</div>{{-- end .sb-wrap --}}

<script>
function sbToggle(btn) {
    const sub  = btn.nextElementSibling;
    const chev = btn.querySelector('.sb-chev');
    if (!sub) return;
    sub.classList.toggle('open');
    if (chev) chev.classList.toggle('open');
}
document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.sb-sub').forEach(function (sub) {
        if (sub.classList.contains('open')) {
            const btn  = sub.previousElementSibling;
            const chev = btn ? btn.querySelector('.sb-chev') : null;
            if (chev) chev.classList.add('open');
        }
    });
});
</script>