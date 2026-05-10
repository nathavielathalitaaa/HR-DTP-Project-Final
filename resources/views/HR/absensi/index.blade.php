@extends('layouts.master')

<style>
  .pg-folder-wrap { position: relative; }
  .folder-corner-tab {
    position: absolute; top: 0; right: 0;
    width: 80px; height: 80px;
    background: linear-gradient(135deg, #7fbf9f, #5f9f83);
    clip-path: polygon(100% 0, 0 0, 100% 100%);
    z-index: 2; pointer-events: none;
    border-top-right-radius: 28px;
  }
  .container-folder {
    background: rgba(255, 255, 255, 0.8);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255, 255, 255, 0.4);
    border-radius: 28px;
    padding: 24px;
    position: relative; z-index: 1;
    box-shadow: 0 10px 30px rgba(0,0,0,0.05);
    clip-path: polygon(
      0 0, calc(100% - 80px) 0,
      100% 80px, 100% 100%, 0 100%
    );
  }

  .skeleton {
    background: linear-gradient(90deg, #f0f0f0 25%, #e0e0e0 50%, #f0f0f0 75%);
    background-size: 200% 100%;
    animation: skeleton-loading 1.5s infinite;
  }
  @keyframes skeleton-loading {
    0% { background-position: 200% 0; }
    100% { background-position: -200% 0; }
  }

  /* ── Responsive: Mobile ── */
  @media (max-width: 639px) {
    .folder-corner-tab {
      width: 40px; height: 40px;
    }
    .container-folder {
      padding: 16px;
      clip-path: polygon(
        0 0, calc(100% - 40px) 0,
        100% 40px, 100% 100%, 0 100%
      );
    }
    
    /* Table -> Card List */
    #alternativePagination_wrapper thead { display: none; }
    #alternativePagination_wrapper tbody tr {
      display: flex;
      flex-direction: column;
      gap: 6px;
      padding: 12px;
      border: 1px solid #e2e8f0;
      border-radius: 12px;
      margin-bottom: 12px;
      background: rgba(255,255,255,0.5);
    }
    #alternativePagination_wrapper tbody td {
      display: flex;
      justify-content: space-between;
      align-items: center;
      padding: 4px 0 !important;
      border: none !important;
    }
    #alternativePagination_wrapper tbody td::before {
      content: attr(data-label);
      font-weight: 600;
      font-size: 11px;
      text-transform: uppercase;
      color: #64748b;
    }
  }
</style>

@section('content')
    {{-- header --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
        <div>
            @if(auth()->user()->hasRole('hr'))
                <h1 class="text-3xl font-playfair font-bold text-[#1A2B24]">Attendance Recap</h1>
                <p class="text-sm text-gray-500 mt-1">Data imported from fingerprint machine</p>
            @else
                <h1 class="text-3xl font-playfair font-bold text-[#1A2B24]">My Attendance</h1>
                <p class="text-sm text-gray-500 mt-1">View your attendance history this month</p>
            @endif
        </div>
        <div class="flex items-center gap-3">
            <form method="GET" action="{{ route('hr/absensi/page') }}" id="filterForm">
                <input type="month" name="month" value="{{ $bulan }}" 
                    class="px-4 py-2 bg-white/50 border border-gray-200 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/30 transition"
                    onchange="this.form.submit()">
            </form>
        </div>
    </div>

    <div class="pg-folder-wrap">
      <div class="folder-corner-tab"></div>
      <div class="container-folder">

        <div class="flex flex-col sm:flex-row sm:items-center mb-8 gap-4 border-b border-gray-100 pb-6">
            <div class="grow">
                <h6 class="text-xl font-bold text-gray-800">Monthly Attendance</h6>
                <p class="text-xs text-gray-400 mt-1 uppercase tracking-widest font-semibold">{{ \Carbon\Carbon::parse($bulan)->format('F Y') }}</p>
            </div>
            @if(auth()->user()->hasRole('hr'))
                <div class="flex flex-wrap items-center gap-2">
                    <a href="{{ route('hr/absensi/import') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#4F6560] text-white rounded-xl text-sm font-bold shadow transition">
                        <i data-lucide="file-up" class="w-4 h-4"></i> Import
                    </a>
                    <a href="{{ route('hr/absensi/export/excel', ['bulan' => $bulan]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-50 text-emerald-700 rounded-xl text-sm font-bold hover:bg-emerald-100 transition">
                        <i data-lucide="sheet" class="w-4 h-4"></i> Excel
                    </a>
                    <a href="{{ route('hr/absensi/export/pdf', ['bulan' => $bulan]) }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-rose-50 text-rose-700 rounded-xl text-sm font-bold hover:bg-rose-100 transition">
                        <i data-lucide="file-text" class="w-4 h-4"></i> PDF
                    </a>
                </div>
            @endif
        </div>


            <div class="skeleton-wrapper w-full">
                <div class="space-y-4">
                    @for($i=0; $i<6; $i++)
                    <div class="flex items-center gap-4 p-4 border border-gray-100 rounded-xl bg-gray-50/50">
                        <div class="skeleton w-10 h-10 rounded-full flex-shrink-0"></div>
                        <div class="flex-grow space-y-2">
                            <div class="skeleton h-4 w-1/3"></div>
                            <div class="skeleton h-3 w-1/4"></div>
                        </div>
                        <div class="skeleton w-20 h-6 rounded-full flex-shrink-0"></div>
                    </div>
                    @endfor
                </div>
            </div>

            <div class="real-content hidden w-full overflow-x-auto">
                <table id="alternativePagination" class="display" style="width:100%">
                    <thead><tr>
                        <th>No</th>
                        <th>Employee Name</th>
                        <th>Department</th>
                        <th>Days Required</th>
                        <th>Present</th>
                        <th>Absent</th>
                        <th>Late</th>
                        <th>Overtime</th>
                        @if(auth()->user()->hasAnyRole(['hr', 'admin', 'super-admin']))
                            <th>Actions</th>
                        @endif
                    </tr></thead>
                    <tbody>
                        @foreach($rekapList as $key => $absensi)
                            @php $data = $absensi->rekap; @endphp
                            <tr>
                                <td data-label="No">{{ ++$key }}</td>
                                <td data-label="Employee Name" class="font-medium text-slate-800">{{ $absensi->user->name ?? '-' }}</td>
                                <td data-label="Department">{{ $absensi->user->profile->jabatan ?? '-' }}</td>
                                <td data-label="Days Required">{{ $data['hari_dibutuhkan'] ?? 0 }}</td>
                                <td data-label="Present">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">{{ $data['hari_hadir'] ?? 0 }} days</span>
                                </td>
                                <td data-label="Absent">
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">{{ $data['hari_tidak_hadir'] ?? 0 }} days</span>
                                </td>
                                <td data-label="Late">
                                    <span class="text-xs text-slate-500">{{ $data['terlambat_count'] ?? 0 }}x / {{ $data['terlambat_menit'] ?? 0 }} min</span>
                                </td>
                                <td data-label="Overtime">
                                    <span class="text-xs text-slate-500">{{ isset($data['lembur_menit']) ? round($data['lembur_menit']/60, 2) : 0 }} hours</span>
                                </td>
                                @if(auth()->user()->hasAnyRole(['hr', 'admin', 'super-admin']))
                                    <td data-label="Actions">
                                        <a href="javascript:void(0)" onclick="deleteAbsensi({{ $absensi->id }})"
                                           class="inline-flex items-center justify-center rounded-lg size-8 bg-red-50 text-red-500 hover:text-white hover:bg-red-500 transition shadow-sm">
                                            <i data-lucide="trash-2" class="size-4"></i>
                                        </a>
                                    </td>
                                @endif
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
          </div>{{-- /container-folder --}}
        </div>{{-- /pg-folder-wrap --}}

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (!document.getElementById('alternativePagination')) return;
    var isAdmin = {{ auth()->user()->hasAnyRole(['hr', 'admin', 'super-admin']) ? 'true' : 'false' }};
    new DataTable('#alternativePagination', {
        pagingType: 'full_numbers',
        columnDefs: [{ orderable: false, targets: [0, isAdmin ? 8 : 7] }],
        language: {
            search: 'Search:',
            lengthMenu: 'Show _MENU_ entries',
            info: 'Showing _START_ to _END_ of _TOTAL_ entries',
            paginate: { first:'First', last:'Last', next:'Next', previous:'Previous' },
            emptyTable: 'No data available'
        }
    });
});
@if(auth()->user()->hasRole('hr'))
function deleteAbsensi(id) {
    if (!confirm('Are you sure you want to delete this recap?')) return;
    fetch('/hr/absensi/' + id, {
        method: 'DELETE',
        headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').content, 'Accept': 'application/json' }
    }).then(r => r.json()).then(() => location.reload()).catch(() => location.reload());
}
@endif
</script>
@endpush

@endsection