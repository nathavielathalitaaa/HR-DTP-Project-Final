@extends('layouts.master')

@section('content')
<style>
*, *::before, *::after { box-sizing: border-box; }

/* GREETING */
.hv-welcome {
    font-family: 'Playfair Display', serif;
    font-size: 36px;
    font-weight: 400;
    color: #1A2B24;
    margin: 0 0 32px 0;
    letter-spacing: -0.5px;
}

/* GRID */
.hv-row1 {
    display: grid;
    grid-template-columns: 260px 1fr 1fr 320px;
    gap: 24px;
    margin-bottom: 24px;
}

@media (max-width: 1200px) {
    .hv-row1 {
        grid-template-columns: 1fr;
        gap: 16px;
    }
}

/* FOTO */
.hv-photo-card {
    border-radius: 24px;
    overflow: hidden;
    position: relative;
    min-height: 220px;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
}
.hv-photo-card img {
    width: 100%;
    height: 100%;
    object-fit: cover;
}
.hv-photo-overlay {
    position: absolute;
    bottom: 0;
    left: 0;
    right: 0;
    padding: 24px;
    background: linear-gradient(to top, rgba(26,43,36,0.9), rgba(26,43,36,0.4));
}
.hv-photo-name {
    font-family: 'Playfair Display', serif;
    font-size: 16px;
    font-weight: 600;
    color: #fff;
    margin: 0 0 4px 0;
}
.hv-photo-role {
    font-size: 12px;
    color: rgba(255,255,255,0.8);
    margin: 0;
}

/* CARD GLOBAL */
.hv-stat,
.hv-actions-card,
.hv-cuti-card,
.hv-full-card {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(24px);
    border-radius: 24px;
    padding: 24px;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(255,255,255,0.4);
}

/* STAT */
.hv-stat {
    display: flex;
    flex-direction: column;
    justify-content: space-between;
    min-height: 200px;
}
.hv-stat.dark {
    background: #4F6560;
    color: white;
    border: none;
}
.hv-stat.dark .hv-stat-label, .hv-stat.dark .hv-stat-num {
    color: white;
}
.hv-stat-label {
    font-size: 13px;
    color: #6B7280;
}
.hv-stat-num {
    font-family: 'Playfair Display', serif;
    font-size: 56px;
    font-weight: 600;
    color: #1A2B24;
    margin: 16px 0;
    line-height: 1.2;
}
.hv-stat-bottom {
    display: flex;
    align-items: flex-end;
    justify-content: flex-start;
    margin-top: auto;
    gap: 12px;
}
.hv-stat-icon {
    width: 32px;
    height: 32px;
    color: #9CA3AF;
    flex-shrink: 0;
}
.hv-stat.dark .hv-stat-icon {
    color: rgba(255,255,255,0.7);
}

/* DARK CARD */
.hv-recent {
    background: rgba(79, 101, 96, 0.85);
    backdrop-filter: blur(8px);
    border-radius: 24px;
    padding: 24px;
    box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05);
    border: 1px solid rgba(255,255,255,0.1);
}
.hv-recent-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 12px;
}
.hv-recent-title {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    font-weight: 600;
    color: #fff;
    margin: 0;
}
.hv-recent-viewall {
    font-size: 12px;
    color: #A8C5B5;
    text-decoration: none;
    transition: color 0.2s;
}

.hv-recent-viewall:hover {
    color: #fff;
}
.hv-recent-sub {
    font-size: 11px;
    color: rgba(255,255,255,0.5);
    margin-bottom: 16px;
}
.hv-recent-item {
    display: flex;
    gap: 12px;
    margin-bottom: 14px;
}
.hv-recent-ava {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,0.2);
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-size: 14px;
    font-weight: bold;
}
.hv-recent-name {
    font-size: 12px;
    color: #fff;
}
.hv-recent-desc {
    font-size: 11px;
    color: rgba(255,255,255,0.6);
}

/* ROW 2 */
.hv-row2 {
    display: grid;
    grid-template-columns: 260px 1fr;
    gap: 24px;
    margin-top: 24px;
}

/* ACTION */
.hv-actions-title {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    margin-bottom: 16px;
    color: #1A2B24;
}
.hv-actions-list {
    display: flex;
    flex-direction: column;
    gap: 12px;
}

/* BUTTON */
.hv-btn-primary {
    width: 100%;
    padding: 12px;
    background: #4F6560;
    color: #fff !important;
    border-radius: 12px;
    font-size: 14px;
    transition: 0.2s;
    text-align: center;
    display: inline-block;
}
.hv-btn-primary:hover {
    background: #3b504c;
}
.hv-btn-outline {
    width: 100%;
    padding: 11px;
    border: 1px solid #E5E7EB;
    border-radius: 12px;
    text-align: center;
    display: inline-block;
    transition: 0.2s;
}
.hv-btn-outline:hover {
    border-color: #4F6560;
    color: #4F6560 !important;
}

/* CUTI */
.hv-cuti-header {
    display: flex;
    justify-content: space-between;
    margin-bottom: 16px;
}
.hv-cuti-title {
    font-family: 'Playfair Display', serif;
    font-size: 18px;
    color: #1A2B24;
    margin: 0;
}
.hv-cuti-viewall {
    font-size: 12px;
    color: #4F6560;
    text-decoration: none;
    font-weight: 500;
    transition: color 0.2s;
}

.hv-cuti-viewall:hover {
    color: #2F4C46;
}
.hv-cuti-item {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 16px;
    background: rgba(255, 255, 255, 0.5);
    border: 1px solid rgba(255,255,255,0.4);
    border-radius: 16px;
    margin-bottom: 12px;
    transition: all 0.2s ease;
}

.hv-cuti-item:hover {
    background: rgba(255, 255, 255, 0.8);
    box-shadow: 0 2px 4px rgba(0,0,0,0.02);
}
.hv-cuti-ava {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    background: #80BB9B;
    display: flex;
    align-items: center;
    justify-content: center;
    color: white;
    font-weight: bold;
}
.hv-cuti-name {
    font-size: 14px;
    font-weight: 500;
}
.hv-cuti-meta {
    font-size: 12px;
    color: #6B7280;
    margin-top: 2px;
}
.hv-cuti-badge {
    margin-left: auto;
    background: #FEF3C7;
    color: #92400E;
    border-radius: 999px;
    padding: 4px 12px;
    font-size: 11px;
    font-weight: 500;
    white-space: nowrap;
}

/* EMPTY */
.hv-empty {
    text-align: center;
    color: #9CA3AF;
    font-size: 12px;
    padding: 24px 0;
}

.hv-recent-empty {
    text-align: center;
    color: rgba(255,255,255,0.6);
    font-size: 12px;
    padding: 24px 0;
}

.hv-list-link {
    text-decoration: none;
    color: #1A2B24;
    font-weight: 500;
    transition: color 0.2s;
}

.hv-list-link:hover {
    color: #4F6560;
}

/* BADGE */
.hv-badge { 
    border-radius: 999px;
    padding: 4px 12px;
    font-size: 11px;
    font-weight: 500;
    display: inline-block;
}
.hv-badge-green { background:#E8F5EE; color:#2E7D5E; }
.hv-badge-amber { background:#fef3c7; color:#92400e; }
.hv-badge-red { background:#fee2e2; color:#991b1b; }
.hv-badge-blue { background:#dbeafe; color:#1e40af; }
.hv-badge-gray { background:#f3f4f6; color:#374151; }

.hv-stats-row {
    display: grid;
    gap: 24px;
    margin-bottom: 24px;
}
.hv-stats-3 { grid-template-columns: repeat(3, 1fr); }
.hv-stats-2 { grid-template-columns: repeat(2, 1fr); }

.hv-list-item {
    display: flex;
    align-items: center;
    gap: 16px;
    padding: 16px;
    background: rgba(255,255,255,0.5);
    border: 1px solid rgba(255,255,255,0.4);
    border-radius: 16px;
    margin-bottom: 12px;
}
.hv-list-icon {
    width: 40px; height: 40px; border-radius: 50%;
    background: #E8F5EE; color: #4F6560;
    display: flex; align-items: center; justify-content: center;
}
.hv-list-icon svg { width: 20px; height: 20px; }
.hv-list-name { font-weight: 500; font-size: 14px; margin-bottom: 2px; }
.hv-list-meta { font-size: 12px; color: #6B7280; }
.hv-full-title {
    font-family: 'Playfair Display', serif;
    font-size: 20px;
    color: #1A2B24;
}
</style>

<div>

<p class="hv-welcome">Welcome, {{ auth()->user()->name }}</p>

{{-- ══════ hr ══════ --}}
@if(auth()->user()->hasRole('hr'))

    {{-- row 1 --}}
    <div class="hv-row1">

        {{-- foto profil --}}
        <div class="hv-photo-card">
            @if(auth()->user()->avatar)
                <img src="{{ URL::to('assets/images/user/'.auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
            @endif
            <div class="hv-photo-overlay">
                <p class="hv-photo-name">{{ auth()->user()->name }}</p>
                <p class="hv-photo-role">{{ $userRoleName }}</p>
            </div>
        </div>

        {{-- total employees --}}
        <div class="hv-stat">
            <p class="hv-stat-label">Total Employees</p>
            <p class="hv-stat-num">{{ $totalKaryawan ?? 0 }}</p>
            <div class="hv-stat-bottom">
                <svg class="hv-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 21v-2a4 4 0 014-4h4m4 0h4a4 4 0 014 4v2M12 3a4 4 0 100 8 4 4 0 000-8zM6 9a3 3 0 100 6M18 9a3 3 0 100 6"/>
                </svg>
            </div>
        </div>

        {{-- pending approvals --}}
        <div class="hv-stat">
            <p class="hv-stat-label">Pending Approvals</p>
            <p class="hv-stat-num">{{ $suratMenungguCount ?? 0 }}</p>
            <div class="hv-stat-bottom">
                <svg class="hv-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <a href="{{ route('surat.index') }}" class="hv-list-link" style="font-size:12px;text-decoration:underline;text-underline-offset:2px;color:#1A2B24;">Need approval</a>
            </div>
        </div>

        {{-- recent activity --}}
        <div class="hv-recent">
            <div class="hv-recent-header">
                <p class="hv-recent-title">Recent Activity</p>
                <a href="{{ route('surat.index') }}" class="hv-recent-viewall">View all</a>
            </div>
            <p class="hv-recent-sub">Latest HR & approval snapshot</p>
            @php
                $recentSurats = \App\Models\Surat::with('user')->orderBy('updated_at','desc')->limit(3)->get();
            @endphp
            @if($recentSurats->count())
            <div class="hv-recent-list">
                @foreach($recentSurats as $rs)
                <div class="hv-recent-item">
                    <div class="hv-recent-ava">
                        @if($rs->user?->avatar)
                            <img src="{{ URL::to('assets/images/user/'.$rs->user->avatar) }}" alt="">
                        @else
                            {{ strtoupper(substr($rs->user?->name ?? 'K', 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <p class="hv-recent-name">{{ $rs->user?->name ?? '-' }}</p>
                        <p class="hv-recent-desc">
                            {{ ucfirst(str_replace('_',' ',$rs->jenis_surat)) }} &mdash;
                            @php echo match($rs->status){
                                'approved_owner'=>'Fully Approved',
                                'submitted'=>'Submitted',
                                'rejected'=>'Rejected',
                                'revised'=>'Needs Revision',
                                default=>ucfirst($rs->status)
                            } @endphp<br>
                            <span style="opacity:.55;">{{ $rs->updated_at->diffForHumans() }}</span>
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="hv-recent-empty">No recent activity</div>
            @endif
        </div>

    </div>{{-- /row1 --}}

    {{-- row 2 --}}
    <div class="hv-row2">

        {{-- quick actions --}}
        <div class="hv-actions-card">
            <p class="hv-actions-title">Quick Actions</p>
            <div class="hv-actions-list">
                <a href="{{ route('hr/employee/list') }}" class="hv-btn-primary">Add Employee</a>
                <a href="{{ route('surat.index') }}" class="hv-btn-outline">View Letters</a>
            </div>
        </div>


    </div>{{-- /row2 --}}


{{-- ══════ supervisor ══════ --}}
@elseif(auth()->user()->hasRole('supervisor'))

    {{-- row 1 --}}
    <div class="hv-row1">

        {{-- foto profil --}}
        <div class="hv-photo-card">
            @if(auth()->user()->avatar)
                <img src="{{ URL::to('assets/images/user/'.auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
            @endif
            <div class="hv-photo-overlay">
                <p class="hv-photo-name">{{ auth()->user()->name }}</p>
                <p class="hv-photo-role">{{ $userRoleName }}</p>
            </div>
        </div>

        {{-- total karyawan --}}
        <div class="hv-stat">
            <p class="hv-stat-label">Total Active Employees</p>
            <p class="hv-stat-num">{{ $totalKaryawan ?? 0 }}</p>
            <div class="hv-stat-bottom">
                <svg class="hv-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>

        {{-- surat menunggu --}}
        <div class="hv-stat dark">
            <p class="hv-stat-label">Letters Need Approval</p>
            <p class="hv-stat-num">{{ $suratMenungguCount ?? 0 }}</p>
            <div class="hv-stat-bottom">
                <svg class="hv-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <a href="{{ route('surat.index') }}" class="hv-list-link" style="font-size:12px;text-decoration:underline;text-underline-offset:2px;color:rgba(255,255,255,0.8);">View letters</a>
            </div>
        </div>

        {{-- recent activity --}}
        <div class="hv-recent">
            <div class="hv-recent-header">
                <p class="hv-recent-title">Needs Review</p>
                <a href="{{ route('surat.index') }}" class="hv-recent-viewall">View all</a>
            </div>
            <p class="hv-recent-sub">Letters waiting for your approval</p>
            @if(isset($suratMenungguList) && $suratMenungguList->count())
            <div class="hv-recent-list">
                @foreach($suratMenungguList->take(3) as $rs)
                <div class="hv-recent-item">
                    <div class="hv-recent-ava">
                        @if($rs->user?->avatar)
                            <img src="{{ URL::to('assets/images/user/'.$rs->user->avatar) }}" alt="">
                        @else
                            {{ strtoupper(substr($rs->user?->name ?? 'K', 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <p class="hv-recent-name">{{ $rs->user?->name ?? '-' }}</p>
                        <p class="hv-recent-desc">
                            {{ ucfirst(str_replace('_',' ',$rs->jenis_surat)) }}<br>
                            <span style="opacity:.55;">{{ $rs->created_at->diffForHumans() }}</span>
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="hv-recent-empty">No letters waiting</div>
            @endif
        </div>

    </div>{{-- /row1 --}}

    {{-- row 2 --}}
    <div class="hv-row2">

        {{-- quick actions --}}
        <div class="hv-actions-card">
            <p class="hv-actions-title">Quick Actions</p>
            <div class="hv-actions-list">
                <a href="{{ route('surat.index') }}" class="hv-btn-primary">Review Letters</a>
                <a href="{{ route('hr/attendance/main/page') }}" class="hv-btn-outline">Manage Attendance</a>
                <a href="{{ route('hr/employee/list') }}" class="hv-btn-outline">View Employees</a>
            </div>
        </div>


    </div>{{-- /row2 --}}


{{-- ══════ head_of_department ══════ --}}
@elseif(auth()->user()->hasRole('head_of_department'))

    {{-- row 1 --}}
    <div class="hv-row1">

        {{-- foto profil --}}
        <div class="hv-photo-card">
            @if(auth()->user()->avatar)
                <img src="{{ URL::to('assets/images/user/'.auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
            @endif
            <div class="hv-photo-overlay">
                <p class="hv-photo-name">{{ auth()->user()->name }}</p>
                <p class="hv-photo-role">{{ $userRoleName }}</p>
            </div>
        </div>

        {{-- total karyawan --}}
        <div class="hv-stat">
            <p class="hv-stat-label">Total Active Employees</p>
            <p class="hv-stat-num">{{ $totalKaryawan ?? 0 }}</p>
            <div class="hv-stat-bottom">
                <svg class="hv-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
            </div>
        </div>

        {{-- surat menunggu HOD --}}
        <div class="hv-stat dark">
            <p class="hv-stat-label">Letters Need HOD Approval</p>
            <p class="hv-stat-num">{{ $suratMenungguCount ?? 0 }}</p>
            <div class="hv-stat-bottom">
                <svg class="hv-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <a href="{{ route('surat.index') }}" class="hv-list-link" style="font-size:12px;text-decoration:underline;text-underline-offset:2px;color:rgba(255,255,255,0.8);">View letters</a>
            </div>
        </div>

        {{-- recent activity --}}
        <div class="hv-recent">
            <div class="hv-recent-header">
                <p class="hv-recent-title">Needs Review</p>
                <a href="{{ route('surat.index') }}" class="hv-recent-viewall">View all</a>
            </div>
            <p class="hv-recent-sub">Letters waiting for HOD approval</p>
            @if(isset($suratMenungguList) && $suratMenungguList->count())
            <div class="hv-recent-list">
                @foreach($suratMenungguList->take(3) as $rs)
                <div class="hv-recent-item">
                    <div class="hv-recent-ava">
                        @if($rs->user?->avatar)
                            <img src="{{ URL::to('assets/images/user/'.$rs->user->avatar) }}" alt="">
                        @else
                            {{ strtoupper(substr($rs->user?->name ?? 'K', 0, 1)) }}
                        @endif
                    </div>
                    <div>
                        <p class="hv-recent-name">{{ $rs->user?->name ?? '-' }}</p>
                        <p class="hv-recent-desc">
                            {{ ucfirst(str_replace('_',' ',$rs->jenis_surat)) }}<br>
                            <span style="opacity:.55;">{{ $rs->created_at->diffForHumans() }}</span>
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="hv-recent-empty">No letters waiting</div>
            @endif
        </div>

    </div>{{-- /row1 --}}

    {{-- row 2 --}}
    <div class="hv-row2">

        {{-- quick actions --}}
        <div class="hv-actions-card">
            <p class="hv-actions-title">Quick Actions</p>
            <div class="hv-actions-list">
                <a href="{{ route('surat.index') }}" class="hv-btn-primary">Approve Letters</a>
                <a href="{{ route('hr/employee/list') }}" class="hv-btn-outline">View Employees</a>
                <a href="{{ route('hr/attendance/main/page') }}" class="hv-btn-outline">Manage Attendance</a>
            </div>
        </div>


    </div>{{-- /row2 --}}


{{-- ══════ staff ══════ --}}
@elseif(auth()->user()->hasRole('staff'))

    {{-- row 1 --}}
    <div class="hv-row1">

        {{-- foto profil --}}
        <div class="hv-photo-card">
            @if(auth()->user()->avatar)
                <img src="{{ URL::to('assets/images/user/'.auth()->user()->avatar) }}" alt="{{ auth()->user()->name }}">
            @endif
            <div class="hv-photo-overlay">
                <p class="hv-photo-name">{{ auth()->user()->name }}</p>
                <p class="hv-photo-role">{{ $userRoleName }}</p>
            </div>
        </div>

        {{-- surat diajukan --}}
        <div class="hv-stat">
            <p class="hv-stat-label">Letters Submitted</p>
            <p class="hv-stat-num">{{ $suratStaffDiajukan ?? 0 }}</p>
            <div class="hv-stat-bottom">
                <svg class="hv-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <a href="{{ route('surat.index') }}" class="hv-list-link" style="font-size:12px;text-decoration:underline;text-underline-offset:2px;color:#1A2B24;">View status</a>
            </div>
        </div>

        {{-- surat disetujui --}}
        <div class="hv-stat dark">
            <p class="hv-stat-label">Letters Approved</p>
            <p class="hv-stat-num">{{ $suratStaffSelesai ?? 0 }}</p>
            <div class="hv-stat-bottom">
                <svg class="hv-stat-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
            </div>
        </div>

        {{-- surat terbaru saya --}}
        <div class="hv-recent">
            <div class="hv-recent-header">
                <p class="hv-recent-title">My Latest Letters</p>
                <a href="{{ route('surat.index') }}" class="hv-recent-viewall">View all</a>
            </div>
            <p class="hv-recent-sub">History of your letter submissions</p>
            @php
                $recentSuratStaff = isset($suratStaff) ? $suratStaff->take(3) : collect();
            @endphp
            @if($recentSuratStaff->count())
            <div class="hv-recent-list">
                @foreach($recentSuratStaff as $rs)
                <div class="hv-recent-item">
                    <div class="hv-recent-ava">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:16px;height:16px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="hv-recent-name">{{ ucfirst(str_replace('_',' ',$rs->jenis_surat)) }}</p>
                        <p class="hv-recent-desc">
                            @php echo match($rs->status){
                                'approved_owner'=>'Fully Approved',
                                'submitted'=>'Submitted',
                                'rejected'=>'Rejected',
                                'revised'=>'Needs Revision',
                                default=>ucfirst($rs->status)
                            } @endphp<br>
                            <span style="opacity:.55;">{{ $rs->created_at->diffForHumans() }}</span>
                        </p>
                    </div>
                </div>
                @endforeach
            </div>
            @else
            <div class="hv-recent-empty">No recent letters</div>
            @endif
        </div>

    </div>{{-- /row1 --}}

    {{-- row 2 --}}
    <div class="hv-row2">

        {{-- quick actions --}}
        <div class="hv-actions-card">
            <p class="hv-actions-title">Quick Actions</p>
            <div class="hv-actions-list">
                <a href="{{ route('surat.create') }}" class="hv-btn-primary">Create New Letter</a>
                <a href="{{ route('surat.create') }}" class="hv-btn-outline">Apply for Leave</a>
                <a href="{{ route('surat.index') }}" class="hv-btn-outline">My Letter List</a>
            </div>
        </div>

        {{-- daftar surat lengkap --}}
        <div class="hv-cuti-card">
            <div class="hv-cuti-header">
                <p class="hv-cuti-title">My Letter List</p>
                <a href="{{ route('surat.index') }}" class="hv-cuti-viewall">View all</a>
            </div>
            @if(isset($suratStaff) && $suratStaff->count())
            <div class="hv-cuti-list">
                @foreach($suratStaff->take(4) as $surat)
                <div class="hv-cuti-item">
                    <div class="hv-cuti-ava" style="background:#E8F5EE;color:#4F6560;">
                        <svg fill="none" viewBox="0 0 24 24" stroke="currentColor" style="width:20px;height:20px;"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>
                    </div>
                    <div>
                        <p class="hv-cuti-name">{{ ucfirst(str_replace('_',' ',$surat->jenis_surat)) }}</p>
                        <p class="hv-cuti-meta">{{ Str::limit($surat->perihal, 40) }} &nbsp;·&nbsp; {{ $surat->created_at->format('d M Y') }}</p>
                    </div>
                    @php $b=match($surat->status){'approved_owner'=>['hv-badge-green','Approved'],'submitted'=>['hv-badge-blue','Submitted'],'rejected'=>['hv-badge-red','Rejected'],'revised'=>['hv-badge-amber','Revision'],default=>['hv-badge-gray',ucfirst($surat->status)]}; @endphp
                    <span class="hv-badge {{ $b[0] }}" style="margin-left:auto;">{{ $b[1] }}</span>
                </div>
                @endforeach
            </div>
            @else
            <div class="hv-empty">No letters submitted yet</div>
            @endif
        </div>

    </div>{{-- /row2 --}}

@endif

</div>{{-- /hv-page --}}
@endsection