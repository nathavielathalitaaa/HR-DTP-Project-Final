@extends('layouts.master')

@section('content')
<style>
    .al-page-header {
        margin-bottom: 32px;
    }
    .al-back-btn {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 13px;
        color: #6B7280;
        text-decoration: none;
        margin-bottom: 16px;
        transition: color 0.2s;
    }
    .al-back-btn:hover { color: #1A2B24; }
    .al-title {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        font-weight: 700;
        color: #1A2B24;
        margin: 0 0 6px;
    }
    .al-subtitle {
        font-size: 14px;
        color: #6B7280;
        margin: 0;
    }

    /* Stats Row */
    .al-stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 16px;
        margin-bottom: 24px;
    }
    .al-stat-card {
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: 20px;
        padding: 20px 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .al-stat-label {
        font-size: 11px;
        color: #9CA3AF;
        text-transform: uppercase;
        letter-spacing: 1px;
        margin-bottom: 8px;
    }
    .al-stat-val {
        font-family: 'Playfair Display', serif;
        font-size: 32px;
        font-weight: 700;
        color: #4F6560;
    }

    /* Filter Card */
    .al-filter-card {
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: 20px;
        padding: 20px 24px;
        margin-bottom: 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .al-filter-grid {
        display: grid;
        grid-template-columns: 2fr 1fr 1fr 1fr auto;
        gap: 12px;
        align-items: end;
    }
    .al-filter-label {
        font-size: 11px;
        font-weight: 600;
        color: #6B7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin-bottom: 6px;
        display: block;
    }
    .al-input {
        width: 100%;
        padding: 9px 14px;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        font-size: 13px;
        color: #1A2B24;
        background: white;
        transition: border-color 0.2s;
        box-sizing: border-box;
    }
    .al-input:focus {
        outline: none;
        border-color: #4F6560;
        box-shadow: 0 0 0 2px rgba(79,101,96,0.1);
    }
    .al-btn-filter {
        padding: 9px 20px;
        background: #4F6560;
        color: white;
        border: none;
        border-radius: 12px;
        font-size: 13px;
        font-weight: 600;
        cursor: pointer;
        transition: background 0.2s;
        white-space: nowrap;
    }
    .al-btn-filter:hover { background: #3d504c; }
    .al-btn-reset {
        padding: 9px 16px;
        background: transparent;
        color: #6B7280;
        border: 1px solid #E5E7EB;
        border-radius: 12px;
        font-size: 13px;
        cursor: pointer;
        text-decoration: none;
        display: inline-flex;
        align-items: center;
        gap: 4px;
        transition: all 0.2s;
        white-space: nowrap;
    }
    .al-btn-reset:hover { border-color: #9CA3AF; color: #374151; }

    /* Table */
    .al-table-card {
        background: rgba(255,255,255,0.7);
        backdrop-filter: blur(20px);
        border: 1px solid rgba(255,255,255,0.4);
        border-radius: 20px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.04);
    }
    .al-table {
        width: 100%;
        border-collapse: collapse;
    }
    .al-table thead th {
        padding: 14px 20px;
        text-align: left;
        font-size: 11px;
        font-weight: 700;
        color: #9CA3AF;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        border-bottom: 1px solid rgba(0,0,0,0.05);
        background: rgba(249,250,251,0.8);
    }
    .al-table tbody tr {
        border-bottom: 1px solid rgba(0,0,0,0.04);
        transition: background 0.15s;
    }
    .al-table tbody tr:last-child { border-bottom: none; }
    .al-table tbody tr:hover { background: rgba(79,101,96,0.04); }
    .al-table td {
        padding: 14px 20px;
        vertical-align: middle;
    }

    /* User cell */
    .al-user-cell {
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .al-ava {
        width: 34px;
        height: 34px;
        border-radius: 50%;
        background: rgba(79,101,96,0.15);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 13px;
        font-weight: 700;
        color: #4F6560;
        flex-shrink: 0;
        overflow: hidden;
    }
    .al-ava img { width: 100%; height: 100%; object-fit: cover; border-radius: 50%; }
    .al-user-name { font-size: 13px; font-weight: 600; color: #1A2B24; }
    .al-user-role { font-size: 11px; color: #9CA3AF; }

    /* Action badge */
    .al-badge {
        display: inline-flex;
        align-items: center;
        gap: 4px;
        padding: 3px 10px;
        border-radius: 999px;
        font-size: 11px;
        font-weight: 600;
    }
    .al-badge-login    { background: #ECFDF5; color: #065F46; }
    .al-badge-logout   { background: #F1F5F9; color: #475569; }
    .al-badge-create   { background: #EFF6FF; color: #1D4ED8; }
    .al-badge-update   { background: #FFFBEB; color: #92400E; }
    .al-badge-delete   { background: #FEF2F2; color: #991B1B; }
    .al-badge-approve  { background: #F0FDF4; color: #166534; }
    .al-badge-default  { background: #F3F4F6; color: #4B5563; }

    /* Description */
    .al-desc { font-size: 13px; color: #374151; }
    .al-time { font-size: 11px; color: #9CA3AF; }

    /* Pagination */
    .al-pagination {
        display: flex;
        justify-content: between;
        align-items: center;
        padding: 16px 20px;
        border-top: 1px solid rgba(0,0,0,0.05);
        gap: 8px;
        background: rgba(249,250,251,0.5);
    }
    .al-page-info {
        flex: 1;
        font-size: 13px;
        color: #6B7280;
    }

    /* Empty state */
    .al-empty {
        padding: 60px 20px;
        text-align: center;
    }
    .al-empty-icon {
        width: 56px;
        height: 56px;
        background: #F3F4F6;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto 16px;
    }
    .al-empty p { font-size: 14px; color: #9CA3AF; }
</style>

<div class="al-page-header">
    <a href="{{ route('home') }}" class="al-back-btn">
        <i data-lucide="arrow-left" class="w-4 h-4"></i> Back to Dashboard
    </a>
    <h1 class="al-title">Activity Log</h1>
    <p class="al-subtitle">Complete audit trail of all system-wide user activities</p>
</div>

{{-- Stats --}}
<div class="al-stats">
    <div class="al-stat-card">
        <p class="al-stat-label">Total Events</p>
        <div class="al-stat-val">{{ number_format($totalLogs) }}</div>
    </div>
    <div class="al-stat-card">
        <p class="al-stat-label">Showing</p>
        <div class="al-stat-val">{{ number_format($logs->total()) }}</div>
    </div>
    <div class="al-stat-card">
        <p class="al-stat-label">Current Page</p>
        <div class="al-stat-val">{{ $logs->currentPage() }} / {{ $logs->lastPage() }}</div>
    </div>
</div>

{{-- Filters --}}
<div class="al-filter-card">
    <form method="GET" action="{{ route('activity.log') }}">
        <div class="al-filter-grid">
            <div>
                <label class="al-filter-label">Search Description</label>
                <input type="text" name="search" class="al-input" placeholder="Search activity..." value="{{ request('search') }}">
            </div>
            <div>
                <label class="al-filter-label">Filter by User</label>
                <select name="user_id" class="al-input">
                    <option value="">All Users</option>
                    @foreach($users as $u)
                        <option value="{{ $u->id }}" {{ request('user_id') == $u->id ? 'selected' : '' }}>{{ $u->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="al-filter-label">Date From</label>
                <input type="date" name="date_from" class="al-input" value="{{ request('date_from') }}">
            </div>
            <div>
                <label class="al-filter-label">Date To</label>
                <input type="date" name="date_to" class="al-input" value="{{ request('date_to') }}">
            </div>
            <div style="display:flex;gap:8px;">
                <button type="submit" class="al-btn-filter">
                    <i data-lucide="search" class="w-3.5 h-3.5" style="display:inline;vertical-align:middle;margin-right:4px;"></i>Filter
                </button>
                @if(request()->hasAny(['search','user_id','action','date_from','date_to']))
                    <a href="{{ route('activity.log') }}" class="al-btn-reset">
                        <i data-lucide="x" class="w-3.5 h-3.5"></i> Clear
                    </a>
                @endif
            </div>
        </div>
    </form>
</div>

{{-- Table --}}
<div class="al-table-card">
    @if($logs->count())
    <table class="al-table">
        <thead>
            <tr>
                <th>User</th>
                <th>Action</th>
                <th>Description</th>
                <th>IP Address</th>
                <th>Time</th>
            </tr>
        </thead>
        <tbody>
            @foreach($logs as $log)
            @php
                $action = strtolower($log->action ?? '');
                $badgeClass = match(true) {
                    str_contains($action, 'login')   => 'al-badge-login',
                    str_contains($action, 'logout')  => 'al-badge-logout',
                    str_contains($action, 'create')  => 'al-badge-create',
                    str_contains($action, 'update') || str_contains($action, 'password') || str_contains($action, 'pin') => 'al-badge-update',
                    str_contains($action, 'delete')  => 'al-badge-delete',
                    str_contains($action, 'approve') => 'al-badge-approve',
                    default                          => 'al-badge-default',
                };
            @endphp
            <tr>
                <td>
                    <div class="al-user-cell">
                        <div class="al-ava">
                            @if($log->user?->avatar)
                                <img src="{{ asset('assets/images/user/'.$log->user->avatar) }}" alt="">
                            @else
                                {{ strtoupper(substr($log->user?->name ?? 'S', 0, 1)) }}
                            @endif
                        </div>
                        <div>
                            <div class="al-user-name">{{ $log->user?->name ?? 'System' }}</div>
                            <div class="al-user-role">{{ ucfirst($log->user?->role_name ?? '-') }}</div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="al-badge {{ $badgeClass }}">
                        {{ str_replace('_', ' ', $log->action) }}
                    </span>
                </td>
                <td>
                    <p class="al-desc">{{ $log->description ?? '-' }}</p>
                </td>
                <td>
                    <span class="al-time" style="font-size:12px;font-family:monospace;color:#6B7280;">{{ $log->ip_address ?? '-' }}</span>
                </td>
                <td>
                    <p class="al-desc" style="font-size:12px;">{{ $log->created_at->format('d M Y, H:i') }}</p>
                    <p class="al-time">{{ $log->created_at->diffForHumans() }}</p>
                </td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- Pagination --}}
    <div class="al-pagination">
        <span class="al-page-info">
            Showing {{ $logs->firstItem() }}–{{ $logs->lastItem() }} of {{ number_format($logs->total()) }} entries
        </span>
        {{ $logs->links() }}
    </div>

    @else
    <div class="al-empty">
        <div class="al-empty-icon">
            <i data-lucide="inbox" class="w-6 h-6 text-gray-400"></i>
        </div>
        <p>No activity logs match your filter.</p>
        @if(request()->hasAny(['search','user_id','action','date_from','date_to']))
            <a href="{{ route('activity.log') }}" class="al-btn-reset" style="margin: 12px auto 0; display: inline-flex;">Clear filters</a>
        @endif
    </div>
    @endif
</div>
@endsection
