@extends('layouts.master')

@section('content')
<style>
    .preview-wrapper { font-family: 'Poppins', sans-serif; color: #1a1a1a; max-width: 1200px; margin: 0 auto; padding: 20px 0; }
    .preview-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 24px; flex-wrap: wrap; gap: 16px; }
    .title-wrapper { display: flex; align-items: center; gap: 12px; margin-bottom: 4px; }
    .preview-title { font-family: 'Playfair Display', serif; font-size: 24px; font-weight: 700; color: #1a1a1a; margin: 0; }
    .ai-badge { background: linear-gradient(135deg, #8b5cf6, #d946ef); color: white; padding: 4px 10px; border-radius: 9999px; font-size: 11px; font-weight: 700; display: inline-flex; align-items: center; gap: 4px; letter-spacing: 0.5px; }
    .preview-subtitle { font-size: 14px; color: #64748b; margin: 0; }
    
    .summary-grid { display: grid; grid-template-columns: repeat(1, 1fr); gap: 16px; margin-bottom: 24px; }
    @media (min-width: 768px) { .summary-grid { grid-template-columns: repeat(4, 1fr); } }
    .stat-card { background: white; border: 1px solid #e2e8f0; border-radius: 20px; padding: 20px; box-shadow: 0 4px 6px rgba(0,0,0,0.02); }
    .stat-value { font-size: 24px; font-weight: 700; color: #1a1a1a; margin-bottom: 4px; }
    .stat-label { font-size: 13px; color: #64748b; font-weight: 500; }
    
    .data-card { background: rgba(255, 255, 255, 0.85); backdrop-filter: blur(24px); border-radius: 24px; border: 1px solid rgba(255, 255, 255, 0.5); padding: 24px; box-shadow: 0 10px 30px rgba(0,0,0,0.03); margin-bottom: 24px; overflow-x: auto; }
    .data-table { width: 100%; border-collapse: collapse; text-align: left; }
    .data-table th { padding: 12px 16px; border-bottom: 2px solid #f1f5f9; font-size: 13px; font-weight: 600; color: #475569; }
    .data-table td { padding: 16px; border-bottom: 1px solid #f1f5f9; vertical-align: middle; font-size: 14px; }
    .data-table tr:last-child td { border-bottom: none; }
    
    .badge-pill { padding: 4px 10px; border-radius: 9999px; font-size: 12px; font-weight: 600; display: inline-block; }
    .badge-green { background: #dcfce7; color: #166534; }
    .badge-red { background: #fee2e2; color: #991b1b; }
    .badge-amber { background: #fef3c7; color: #92400e; }
    .badge-gray { background: #f1f5f9; color: #475569; }

    .actions { display: flex; gap: 12px; justify-content: flex-end; }
    .btn-primary { background: #4F6560; color: white; border: none; border-radius: 9999px; padding: 12px 24px; font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;}
    .btn-primary:hover { background: #3d504c; }
    .btn-secondary { background: white; color: #1a1a1a; border: 1px solid #E5E7EB; border-radius: 9999px; padding: 12px 24px; font-family: 'Poppins', sans-serif; font-size: 14px; font-weight: 600; cursor: pointer; transition: background 0.2s; display: inline-flex; align-items: center; gap: 8px; text-decoration: none;}
    .btn-secondary:hover { background: #f9fafb; }
</style>

@php
    $data = $rekapData['data'] ?? [];
    $totalKaryawan = count($data);
    $totalHadir = collect($data)->sum('hari_hadir');
    $totalDibutuhkan = collect($data)->sum('hari_dibutuhkan');
    $rataHadir = $totalDibutuhkan > 0 ? round(($totalHadir / $totalDibutuhkan) * 100, 1) : 0;
    $totalTerlambat = collect($data)->sum('terlambat_count');
    $totalLemburMenit = collect($data)->sum('lembur_menit');
@endphp

<div class="preview-wrapper">
    <div class="preview-header">
        <div>
            <div class="title-wrapper">
                <h1 class="preview-title">AI Result Preview</h1>
                <span class="ai-badge"><i data-lucide="sparkles" style="width:12px;height:12px;"></i> AI Extracted</span>
            </div>
            <p class="preview-subtitle">Period: <strong>{{ $rekapData['periode'] ?? '-' }}</strong>. Check data before saving to the database.</p>
        </div>
        <div class="actions">
            <a href="{{ route('hr/absensi/ai') }}" class="btn-secondary">
                <i data-lucide="refresh-cw" style="width:16px;height:16px;"></i> Re-upload
            </a>
            <form action="{{ route('hr/absensi/ai/save') }}" method="POST">
                @csrf
                <button type="submit" class="btn-primary">
                    <i data-lucide="save" style="width:16px;height:16px;"></i> Save to Database
                </button>
            </form>
        </div>
    </div>

    <div class="summary-grid">
        <div class="stat-card">
            <div class="stat-value">{{ $totalKaryawan }}</div>
            <div class="stat-label">Total Employees</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $rataHadir }}%</div>
            <div class="stat-label">Average Attendance</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ $totalTerlambat }}</div>
            <div class="stat-label">Total Late (Times)</div>
        </div>
        <div class="stat-card">
            <div class="stat-value">{{ round($totalLemburMenit / 60, 2) }} Hours</div>
            <div class="stat-label">Total Overtime</div>
        </div>
    </div>

    <div class="data-card">
        <table class="data-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Employee Name</th>
                    <th>Present / Required</th>
                    <th>Absent</th>
                    <th>Late</th>
                    <th>Overtime (Hours)</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $row)
                @php
                    $hadir = $row['hari_hadir'] ?? 0;
                    $butuh = $row['hari_dibutuhkan'] ?? 0;
                    $tidakHadir = $row['hari_tidak_hadir'] ?? 0;
                    $telatCount = $row['terlambat_count'] ?? 0;
                    $telatMenit = $row['terlambat_menit'] ?? 0;
                    $lemburJam = isset($row['lembur_menit']) ? round($row['lembur_menit']/60, 2) : 0;
                @endphp
                <tr>
                    <td style="color:#94a3b8; font-weight:600;">{{ $index + 1 }}</td>
                    <td style="font-weight:600; color:#1a1a1a;">{{ $row['nama'] ?? '-' }}</td>
                    <td>
                        <span class="badge-pill {{ $hadir >= $butuh && $butuh > 0 ? 'badge-green' : 'badge-gray' }}">
                            {{ $hadir }} / {{ $butuh }}
                        </span>
                    </td>
                    <td>
                        @if($tidakHadir > 2)
                            <span class="badge-pill badge-red">{{ $tidakHadir }} Days</span>
                        @else
                            <span class="badge-pill badge-gray">{{ $tidakHadir }} Days</span>
                        @endif
                    </td>
                    <td>
                        @if($telatCount > 3)
                            <span class="badge-pill badge-amber">{{ $telatCount }}x ({{ $telatMenit }}m)</span>
                        @else
                            <span class="badge-pill badge-gray">{{ $telatCount }}x ({{ $telatMenit }}m)</span>
                        @endif
                    </td>
                    <td>
                        <span class="badge-pill badge-gray">{{ $lemburJam }}</span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
