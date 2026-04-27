@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Daftar Surat</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">Surat</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        Daftar Surat
                    </li>
                </ul>
            </div>

            @if ($message = Session::get('success'))
                <div class="mb-4 padding-3 relative text-base text-green-800 bg-green-50 rounded-lg dark:bg-green-900/20 dark:text-green-300" role="alert">
                    {{ $message }}
                </div>
            @endif
            @if ($message = Session::get('error'))
                <div class="mb-4 padding-3 relative text-base text-red-800 bg-red-50 rounded-lg dark:bg-red-900/20 dark:text-red-300" role="alert">
                    {{ $message }}
                </div>
            @endif

            @php
                $myWaiting = \App\Models\DocumentApproval::where('status','waiting')
                    ->where('jabatan', auth()->user()->profile?->jabatan ?? '__none__')
                    ->where('document_type','LIKE','surat_%')
                    ->count();
            @endphp
            @if($myWaiting > 0)
            <div class="mb-4 flex items-center gap-3 px-4 py-3 rounded-xl"
                 style="background:rgba(239,68,68,0.08); border-left:3px solid #ef4444;">
                <i data-lucide="bell-ring" class="w-4 h-4 text-red-500 flex-shrink-0"></i>
                <p class="text-sm text-red-700 font-medium">
                    Ada <strong>{{ $myWaiting }} surat</strong> yang menunggu approval Anda.
                    Scroll ke bawah untuk melihat daftar.
                </p>
            </div>
            @endif

            <div class="ds-section">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h5 class="text-xl font-bold text-slate-900">Daftar Surat</h5>
                        <p class="text-sm text-slate-500 mt-1">Kelola semua surat masuk dan proses approval</p>
                    </div>
                    <div class="shrink-0">
                        @can('create', App\Models\Surat::class)
                            <a href="{{ route('surat.create') }}" class="ds-btn btn-green">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Buat Surat Baru
                            </a>
                        @endcan
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="ds-table">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nomor Surat</th>
                                <th>Jenis Surat</th>
                                <th>Perihal</th>
                                <th>Status</th>
                                <th>Tanggal</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($surats as $key => $surat)
                                @php
                                    $isMyTurn = $surat->approvals->contains(fn($a) => $a->status === 'waiting' && $a->jabatan === auth()->user()->profile?->jabatan);
                                @endphp
                                <tr class="{{ $isMyTurn ? 'bg-red-50 border-l-2 border-red-400' : '' }}">
                                    <td>{{ $surats->firstItem() + $key }}</td>
                                    <td>
                                        <a href="{{ route('surat.show', $surat->id) }}" class="text-custom-500 font-semibold hover:text-custom-600">{{ $surat->nomor_surat }}</a>
                                    </td>
                                    <td>{{ $surat->jenis_surat }}</td>
                                    <td>{{ substr($surat->perihal, 0, 50) }}{{ strlen($surat->perihal) > 50 ? '...' : '' }}</td>
                                    <td>
                                        @if($surat->status === 'submitted')
                                            <span class="ds-badge b-blue">Diajukan</span>
                                        @elseif($surat->status === 'approved_supervisor')
                                            <span class="ds-badge b-amber">Approval Owner</span>
                                        @elseif($surat->status === 'approved_owner')
                                            <span class="ds-badge b-green">Disetujui</span>
                                        @elseif($surat->status === 'rejected')
                                            <span class="ds-badge b-red">Ditolak</span>
                                        @elseif($surat->status === 'revised')
                                            <span class="ds-badge b-amber">Perlu Revisi</span>
                                        @endif
                                    </td>
                                    <td class="text-sm">{{ $surat->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        @php
                                            $jabatan = Auth::user()->profile?->jabatan;
                                            $waitingStep = $surat->approvals->where('status', 'waiting')->first();
                                            $bisaApprove = $jabatan 
                                                && $waitingStep 
                                                && $waitingStep->jabatan === $jabatan 
                                                && $surat->status === 'submitted'
                                                && !Auth::user()->hasRole('staff');
                                        @endphp
                                        <div class="flex gap-2">
                                            @can('view', $surat)
                                                <a href="{{ route('surat.show', $surat->id) }}" class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg border border-slate-300 text-slate-700 hover:bg-slate-50 transition-all">
                                                    Lihat
                                                </a>
                                            @endcan

                                            @if($bisaApprove)
                                                <button type="button"
                                                    onclick="quickApprove('{{ route('surat.approve', $surat->id) }}')"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg bg-green-50 text-green-700 border border-green-200">
                                                    <i data-lucide="check" class="w-3 h-3"></i> Setuju
                                                </button>
                                                <button type="button"
                                                    onclick="quickReject('{{ route('surat.reject', $surat->id) }}')"
                                                    class="inline-flex items-center gap-1 px-3 py-1.5 text-xs font-semibold rounded-lg bg-red-50 text-red-600 border border-red-200">
                                                    <i data-lucide="x" class="w-3 h-3"></i> Tolak
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-8">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 block text-slate-400"></i>
                                        <p class="text-slate-500">Tidak ada data surat</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
                <div class="mt-6">
                    {{ $surats->links() }}
                </div>
            </div>
        </div>
    </div>

<!-- Modal Approve -->
<div id="modalApprove" class="fixed inset-0 z-50 hidden items-center justify-center" style="background:rgba(0,0,0,.4);padding-left:250px;">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6 overflow-y-auto max-h-[90vh] my-auto">
        <h6 class="text-base font-bold text-slate-900 mb-4">Setujui Surat</h6>
        <form id="formApprove" method="POST">
            @csrf
            <div class="mb-3">
                <label class="block text-xs font-semibold text-slate-600 mb-1">Catatan (opsional)</label>
                <textarea name="catatan" rows="3"
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm"
                    placeholder="Catatan (opsional)..."></textarea>
            </div>
            <div class="mb-4">
                <label class="block text-xs font-semibold text-slate-600 mb-1">
                    PIN Anda <span class="text-red-500">*</span>
                </label>
                <input type="password" name="pin" maxlength="6"
                    class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm focus:border-custom-500 focus:ring-1 focus:ring-custom-100"
                    placeholder="Masukkan PIN untuk konfirmasi" required>
                <p class="text-xs text-slate-400 mt-1">PIN digunakan sebagai konfirmasi tanda tangan digital Anda</p>
            </div>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModals()"
                    class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg text-sm font-semibold">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-custom-500 text-white rounded-lg text-sm font-bold">
                    Setujui
                </button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Reject -->
<div id="modalReject" class="fixed inset-0 z-50 hidden items-center justify-center" style="background:rgba(0,0,0,.4);">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6 my-auto">
        <h6 class="text-base font-bold text-slate-900 mb-4">Tolak Surat</h6>
        <form id="formReject" method="POST">
            @csrf
            <textarea name="catatan_revisi" rows="3" required
                class="w-full px-3 py-2 rounded-lg border border-red-200 text-sm mb-4"
                placeholder="Alasan penolakan (wajib diisi)..."></textarea>
            <div class="flex gap-3 justify-end">
                <button type="button" onclick="closeModals()"
                    class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg text-sm font-semibold">
                    Batal
                </button>
                <button type="submit"
                    class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-bold">
                    Tolak
                </button>
            </div>
        </form>
    </div>
</div>

@push('scripts')
<script>
function quickApprove(url) {
    document.getElementById('formApprove').action = url;
    document.getElementById('modalApprove').classList.remove('hidden');
    document.getElementById('modalApprove').classList.add('flex');
}
function quickReject(url) {
    document.getElementById('formReject').action = url;
    document.getElementById('modalReject').classList.remove('hidden');
    document.getElementById('modalReject').classList.add('flex');
}
function closeModals() {
    ['modalApprove','modalReject'].forEach(id => {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    });
}
['modalApprove','modalReject'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) closeModals();
    });
});
</script>
@endpush

    <!-- Modal Reject -->
    <div id="modalReject" class="fixed inset-0 z-50 hidden items-center justify-center" style="background:rgba(0,0,0,.4);">
        <div class="bg-white rounded-2xl shadow-xl w-full max-w-md mx-4 p-6">
            <h6 class="text-base font-bold text-slate-900 mb-4">Tolak Surat</h6>
            <form id="formReject" method="POST">
                @csrf
                <textarea name="catatan_revisi" rows="3" required class="w-full px-3 py-2 rounded-lg border border-red-200 text-sm mb-4" placeholder="Alasan penolakan (wajib diisi)..."></textarea>
                <div class="flex gap-3 justify-end">
                    <button type="button" onclick="closeModals()" class="px-4 py-2 border border-slate-200 text-slate-600 rounded-lg text-sm font-semibold">Batal</button>
                    <button type="submit" class="px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-bold">Tolak</button>
                </div>
            </form>
        </div>
    </div>

@push('scripts')
<script>
function quickApprove(url) {
    document.getElementById('formApprove').action = url;
    document.getElementById('modalApprove').classList.remove('hidden');
    document.getElementById('modalApprove').classList.add('flex');
}
function quickReject(url) {
    document.getElementById('formReject').action = url;
    document.getElementById('modalReject').classList.remove('hidden');
    document.getElementById('modalReject').classList.add('flex');
}
function closeModals() {
    ['modalApprove','modalReject'].forEach(id => {
        document.getElementById(id).classList.add('hidden');
        document.getElementById(id).classList.remove('flex');
    });
}
['modalApprove','modalReject'].forEach(id => {
    document.getElementById(id).addEventListener('click', function(e) {
        if (e.target === this) closeModals();
    });
});
</script>
@endpush
@endsection
