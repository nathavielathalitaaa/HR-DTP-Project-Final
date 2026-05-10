@extends('layouts.master')

<style>
  /* ── Responsive: Mobile ── */
  @media (max-width: 639px) {
    .surat-actions {
      width: 100%;
    }
    .surat-actions button, .surat-actions a {
      width: 100%;
      min-height: 44px;
      justify-content: center;
    }
  }

  /* ── Header Layout Fix ── */
  .custom-header-fix {
    display: flex !important;
    flex-direction: row !important;
    justify-content: space-between !important;
    align-items: center !important;
    flex-wrap: wrap !important;
    gap: 16px !important;
  }
  .custom-header-title {
    flex: 1 !important;
    min-width: 0 !important;
  }
  .custom-header-btn {
    flex-shrink: 0 !important;
    width: auto !important;
  }
  .custom-header-btn a {
    white-space: nowrap !important;
    width: auto !important;
    color: #ffffff !important;
    background-color: #4F6560 !important;
  }
</style>

@section('content')

    <div class="flex flex-col sm:flex-row sm:justify-between sm:items-center mb-6 gap-4 custom-header-fix">
      <div class="custom-header-title">
        <h1 class="text-3xl font-playfair font-bold text-[#1A2B24]">
          Approval Center
        </h1>
        <p class="text-xs text-gray-500 mt-1">
          Review and approve employee requests
        </p>
      </div>
      @can('create', App\Models\Surat::class)
      <div class="w-full sm:w-auto shrink-0 custom-header-btn">
          <a href="{{ route('surat.create') }}" class="flex items-center gap-2 px-4 py-2 bg-[#4F6560] text-white rounded-xl text-sm font-semibold hover:bg-[#3d504c] transition shadow-sm">
              <i data-lucide="plus" class="w-4 h-4"></i> Create New Letter
          </a>
      </div>
      @endcan
    </div>

    @if ($message = Session::get('success'))
        <div class="mb-4 px-4 py-3 relative text-base text-green-800 bg-green-50 rounded-lg" role="alert">
            {{ $message }}
        </div>
    @endif
    @if ($message = Session::get('error'))
        <div class="mb-4 px-4 py-3 relative text-base text-red-800 bg-red-50 rounded-lg" role="alert">
            {{ $message }}
        </div>
    @endif

    {{-- notifikasi approval — hanya tampil jika user punya jabatan (bukan staff) --}}
    @php
        $user = auth()->user();
        $myWaiting = \App\Models\DocumentApproval::where('status', 'waiting')
            ->where('document_type', 'LIKE', 'surat_%')
            ->where(function($q) use ($user) {
                $q->where('assigned_user_id', $user->id)
                  ->orWhere(function($sq) use ($user) {
                      $jabatan = $user->profile?->jabatan;
                      $sq->whereNull('assigned_user_id');
                      if ($jabatan) {
                          $sq->where('jabatan', $jabatan);
                      } else {
                          $sq->where('jabatan', '___NONE___');
                      }
                  });
            })
            ->count();
    @endphp
    @if($myWaiting > 0)
    <div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl"
         style="background:rgba(239,68,68,0.08); border-left:3px solid #ef4444;">
        <i data-lucide="bell-ring" class="w-4 h-4 text-red-500 flex-shrink-0"></i>
        <p class="text-sm text-red-700 font-medium">
            There are <strong>{{ $myWaiting }} letters</strong> waiting for your approval.
        </p>
    </div>
    @endif

    <div class="w-full">
        <div class="skeleton-wrapper w-full">
            <div class="space-y-4">
                @for($i=0; $i<4; $i++)
                <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-white rounded-2xl shadow-sm border border-gray-100 px-6 py-5 gap-4">
                    <div class="flex items-center gap-4 w-full">
                        <div class="skeleton w-12 h-12 rounded-full flex-shrink-0"></div>
                        <div class="flex-grow space-y-2">
                            <div class="skeleton h-4 w-1/3"></div>
                            <div class="skeleton h-3 w-1/4"></div>
                            <div class="skeleton h-3 w-1/5"></div>
                        </div>
                    </div>
                    <div class="flex gap-2 shrink-0">
                        <div class="skeleton h-8 w-20 rounded-xl"></div>
                        <div class="skeleton h-8 w-20 rounded-xl"></div>
                    </div>
                </div>
                @endfor
            </div>
        </div>
        <div class="real-content hidden w-full">
          <div class="space-y-4">

    @forelse($surats as $key => $surat)
        @php
            $user = auth()->user();
            $waitingStep = $surat->approvals->where('status', 'waiting')->first();
            
            $isMyTurn = false;
            if ($waitingStep && !$user->hasRole('staff')) {
                if ($waitingStep->assigned_user_id) {
                    $isMyTurn = (int)$waitingStep->assigned_user_id === (int)$user->id;
                } else {
                    $jabatan = $user->profile?->jabatan;
                    $isMyTurn = $jabatan && $waitingStep->jabatan === $jabatan;
                }
            }
            $bisaApprove = $isMyTurn && $surat->status === 'submitted';
        @endphp

        <div class="flex flex-col sm:flex-row sm:items-center justify-between bg-white rounded-2xl shadow-sm border border-gray-100 hover:shadow-md transition px-6 py-5 gap-4 {{ $isMyTurn ? 'border-l-4 border-l-red-400' : '' }}">
            
            <!-- left content (keep data same) -->
            <div class="flex items-center gap-4">
                <!-- avatar -->
                <div class="w-12 h-12 rounded-full bg-[#80BB9B]/30 flex items-center justify-center font-semibold text-[#4F6560] text-lg flex-shrink-0">
                    {{ strtoupper(substr($surat->user->name ?? 'U',0,1)) }}
                </div>

                <div>
                    <p class="font-semibold text-gray-800 text-base">
                        {{ $surat->user->name ?? 'Unknown' }}
                    </p>
                    <p class="text-sm text-gray-500 mt-0.5">
                        {{ $surat->suratType ? $surat->suratType->nama : ucfirst(str_replace('_', ' ', $surat->jenis_surat)) }} | {{ $surat->nomor_surat }}
                    </p>
                    <p class="text-xs text-gray-400 mt-1">
                        {{ $surat->created_at->format('d M Y H:i') }}
                    </p>
                </div>
            </div>

            <!-- right actions -->
            <div class="flex flex-col sm:items-end gap-3 sm:gap-2 border-t sm:border-0 pt-3 sm:pt-0 border-gray-100">
                <div>
                    @if($surat->status === 'submitted')
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-700">Submitted</span>
                    @elseif($surat->status === 'approved_owner')
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">Fully Approved</span>
                    @elseif($surat->status === 'rejected')
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">Rejected</span>
                    @elseif($surat->status === 'revised')
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-amber-100 text-amber-700">Needs Revision</span>
                    @else
                        <span class="px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-700">{{ ucfirst($surat->status) }}</span>
                    @endif
                </div>

                <div class="surat-actions flex flex-col sm:flex-row gap-2 w-full sm:w-auto mt-2 sm:mt-0">
                    @can('view', $surat)
                    <a href="{{ route('surat.show', $surat->id) }}"
                       class="px-4 py-2 rounded-xl border border-gray-300 bg-white text-gray-700 text-sm font-medium hover:bg-gray-50 transition flex items-center justify-center">
                        View
                    </a>
                    @endcan

                    @if($bisaApprove)
                    <button type="button"
                        onclick="quickApprove('{{ route('surat.approve', $surat->id) }}')"
                        class="px-4 py-2 bg-[#4F6560] text-white rounded-xl text-sm font-medium hover:bg-[#3d504c] transition shadow-sm flex items-center justify-center">
                        <i data-lucide="check" class="w-4 h-4 mr-1"></i> Approve
                    </button>
                    <button type="button"
                        onclick="quickReject('{{ route('surat.reject', $surat->id) }}')"
                        class="px-4 py-2 bg-red-50 text-red-600 border border-red-200 rounded-xl text-sm font-medium hover:bg-red-100 transition flex items-center justify-center">
                        <i data-lucide="x" class="w-4 h-4 mr-1"></i> Reject
                    </button>
                    @endif
                </div>
            </div>
        </div>

    @empty
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-16 text-center">
            <div class="w-16 h-16 bg-[#80BB9B]/10 rounded-2xl flex items-center justify-center mx-auto mb-4">
                <i data-lucide="file-text" class="w-8 h-8 text-[#80BB9B]"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-800 mb-2">No Letters Yet</h3>
            <p class="text-sm text-gray-500 mb-6">Create a new letter to start the approval process.</p>
            @can('create', App\Models\Surat::class)
            <a href="{{ route('surat.create') }}" class="inline-flex items-center gap-2 px-5 py-2.5 bg-[#4F6560] text-white rounded-xl text-sm font-semibold hover:bg-[#3d504c] transition shadow-sm">
                <i data-lucide="plus" class="w-4 h-4"></i> Create First Letter
            </a>
            @endcan
        </div>
    @endforelse

    </div>

    <div class="mt-6">
        {{ $surats->links() }}
    </div>

          </div>{{-- /space-y-4 real-content --}}
        </div>{{-- /real-content --}}
    </div>{{-- /w-full --}}

{{-- modal approve --}}
<div id="modalApprove" class="fixed inset-0 z-50 hidden items-center justify-center"
     style="background:rgba(0,0,0,.4);">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6 overflow-y-auto max-h-[90vh]">
        <h6 class="text-base font-bold text-slate-900 mb-4">Approve Letter</h6>
        <form id="formApprove" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Notes (optional)</label>
                <textarea name="catatan" rows="2"
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm"
                    placeholder="Add notes if any..."></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-600 mb-1">
                    Your PIN <span class="text-red-500">*</span>
                </label>
                <input type="password" name="pin" maxlength="6"
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm focus:border-custom-500 focus:ring-1 focus:ring-custom-100"
                    placeholder="Enter 6-digit PIN" required>
                <p class="text-xs text-slate-400 mt-1">PIN is used as confirmation for your digital signature</p>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModals()"
                    class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg text-sm font-semibold">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-custom-500 text-white rounded-lg text-sm font-bold">
                    <i data-lucide="shield-check" class="w-4 h-4 inline mr-1"></i>
                    Approve with Signature
                </button>
            </div>
        </form>
    </div>
</div>

{{-- modal reject --}}
<div id="modalReject" class="fixed inset-0 z-50 hidden items-center justify-center"
     style="background:rgba(0,0,0,.4);">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
        <h6 class="text-base font-bold text-slate-900 mb-4">Reject Letter</h6>
        <form id="formReject" method="POST">
            @csrf
            <div class="mb-4">
                <label class="block text-xs font-semibold text-red-700 mb-1">
                    Rejection Reason <span class="text-red-500">*</span>
                </label>
                <textarea name="catatan_revisi" rows="3" required
                    class="w-full px-3 py-2 rounded-lg border border-red-200 text-sm"
                    placeholder="Write the reason for rejection clearly..."></textarea>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModals()"
                    class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg text-sm font-semibold">
                    Cancel
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-bold">
                    Reject Letter
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function quickApprove(url) {
    document.getElementById('formApprove').action = url;
    const modal = document.getElementById('modalApprove');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function quickReject(url) {
    document.getElementById('formReject').action = url;
    const modal = document.getElementById('modalReject');
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeModals() {
    ['modalApprove', 'modalReject'].forEach(id => {
        const el = document.getElementById(id);
        el.classList.add('hidden');
        el.classList.remove('flex');
    });
}

// Klik backdrop untuk tutup modal
['modalApprove', 'modalReject'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) closeModals();
    });
});
</script>
@endpush

@endsection