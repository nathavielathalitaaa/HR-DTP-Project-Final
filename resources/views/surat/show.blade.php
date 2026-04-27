@extends('layouts.master')

@section('content')
<div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
<div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

{{-- Breadcrumb --}}
<div class="flex items-center justify-between py-4 print:hidden">
    <div>
        <h5 class="text-base font-bold text-slate-900">Detail Surat</h5>
        <p class="text-xs text-slate-500 mt-0.5">{{ $surat->nomor_surat }}</p>
    </div>
    <div class="flex items-center gap-1 text-xs text-slate-500">
        <a href="{{ route('surat.index') }}" class="text-slate-400">Surat</a>
        <span class="opacity-40">/</span>
        <span class="font-semibold text-custom-500">Detail</span>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

    {{-- ── KOLOM KIRI — Info Surat ──────────────────── --}}
    <div class="lg:col-span-2 flex flex-col gap-5">

        {{-- Info dasar --}}
        <div class="ds-section">
            <div class="flex items-start justify-between mb-5">
                <div>
                    <h5 class="text-lg font-bold text-slate-900">{{ $surat->nomor_surat }}</h5>
                    <p class="text-sm text-slate-500 mt-1">{{ $surat->jenis_surat }}</p>
                </div>
                <span class="ds-badge {{ $surat->status_color }}">{{ $surat->status_label }}</span>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <p class="text-xs font-bold tracking-widest uppercase text-slate-400 mb-1">Perihal</p>
                    <p class="text-sm text-slate-900 font-medium">{{ $surat->perihal }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold tracking-widest uppercase text-slate-400 mb-1">Pembuat</p>
                    <p class="text-sm text-slate-900 font-medium">{{ $surat->user->name ?? '-' }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold tracking-widest uppercase text-slate-400 mb-1">Tanggal Dibuat</p>
                    <p class="text-sm text-slate-900">{{ $surat->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-xs font-bold tracking-widest uppercase text-slate-400 mb-1">File PDF</p>
                    @if($surat->file_pdf)
                        @can('download', $surat)
                            <a href="{{ route('surat.download', $surat->id) }}"
                               class="inline-flex items-center gap-1.5 px-3 py-1.5 bg-custom-500 text-white rounded-lg text-xs font-bold">
                                <i data-lucide="download" class="w-3 h-3"></i> Download PDF
                            </a>
                        @else
                            <p class="text-xs text-slate-400">File tersedia (akses terbatas)</p>
                        @endcan
                    @else
                        <p class="text-xs text-slate-400">Tidak ada file</p>
                    @endif
                </div>
            </div>
        </div>

        {{-- Catatan Revisi --}}
        @if($surat->catatan_revisi)
        <div class="ds-section" style="border-left:3px solid #f97316;background:#fff7ed;">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="alert-triangle" class="w-4 h-4 text-amber-600"></i>
                <p class="text-xs font-bold tracking-widest uppercase text-amber-700">Catatan Revisi</p>
            </div>
            <p class="text-sm text-amber-800">{{ $surat->catatan_revisi }}</p>
        </div>
        @endif

        {{-- ── FORM APPROVE — hanya tampil jika giliran jabatan user ── --}}
        @if($canApprove && $surat->status === 'submitted')
        <div class="ds-section" style="border-left:3px solid #04A54C;">
            <div class="flex items-center gap-2 mb-4">
                <i data-lucide="shield-check" class="w-4 h-4 text-custom-500"></i>
                <h6 class="text-sm font-bold text-slate-900">
                    Giliran Anda — {{ $waitingStep->label ?? 'Approval' }}
                </h6>
            </div>

            {{-- Form Approve --}}
            <form action="{{ route('surat.approve', $surat->id) }}" method="POST" class="mb-4">
                @csrf
                <div class="mb-3">
                    <label class="block text-xs font-semibold text-slate-600 mb-1">Catatan (opsional)</label>
                    <textarea name="catatan" rows="2"
                        class="w-full px-3 py-2 rounded-lg border border-slate-200 text-sm focus:border-custom-500 focus:ring-1 focus:ring-custom-100"
                        placeholder="Tambahkan catatan jika ada..."></textarea>
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
                <button type="submit"
                    class="inline-flex items-center gap-2 px-4 py-2 bg-custom-500 text-white rounded-lg text-sm font-bold">
                    <i data-lucide="shield-check" class="w-4 h-4"></i>
                    Setujui dengan TTD
                </button>
            </form>

            <hr style="border-color:rgba(0,0,0,.06);margin:16px 0;">

            {{-- Form Reject --}}
            <div x-data="{ open: false }">
                <button @click="open = !open"
                    class="inline-flex items-center gap-2 px-4 py-2 border border-red-200 text-red-600 rounded-lg text-sm font-bold bg-red-50">
                    <i data-lucide="x" class="w-4 h-4"></i>
                    Tolak & Minta Revisi
                </button>

                <div x-show="open" x-transition class="mt-4">
                    <form action="{{ route('surat.reject', $surat->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="block text-xs font-semibold text-red-700 mb-1">
                                Alasan Penolakan <span class="text-red-500">*</span>
                            </label>
                            <textarea name="catatan_revisi" rows="3"
                                class="w-full px-3 py-2 rounded-lg border border-red-200 text-sm focus:border-red-400 focus:ring-1 focus:ring-red-100"
                                placeholder="Tuliskan alasan penolakan secara jelas..." required></textarea>
                            @error('catatan_revisi')
                                <p class="text-xs text-red-500 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                        <button type="submit"
                            class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 text-white rounded-lg text-sm font-bold"
                            onclick="return confirm('Yakin ingin menolak surat ini?')">
                            <i data-lucide="x-circle" class="w-4 h-4"></i>
                            Tolak Surat
                        </button>
                    </form>
                </div>
            </div>
        </div>
        @endif

        {{-- ── FORM REVISI — hanya untuk staff pemilik ── --}}
        @if($surat->status === 'revised' && Auth::id() === $surat->user_id)
        <div class="ds-section" style="border-left:3px solid #f59e0b;background:#fffbeb;">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="edit" class="w-4 h-4 text-amber-600"></i>
                <h6 class="text-sm font-bold text-amber-800">Surat Perlu Direvisi</h6>
            </div>
            <p class="text-xs text-amber-700 mb-3">
                Surat Anda ditolak. Silakan perbaiki sesuai catatan di atas, lalu upload ulang.
            </p>
            <a href="{{ route('surat.edit', $surat->id) }}"
               class="inline-flex items-center gap-2 px-4 py-2 bg-amber-500 text-white rounded-lg text-sm font-bold">
                <i data-lucide="upload" class="w-4 h-4"></i>
                Revisi & Upload Ulang
            </a>
        </div>
        @endif


    </div>{{-- end kolom kiri --}}

    {{-- ── KOLOM KANAN — Timeline Approval ─────────── --}}
    <div class="lg:col-span-1">
        <div class="ds-section h-fit">
            <div class="flex items-center gap-2 mb-5">
                <i data-lucide="git-branch" class="w-4 h-4 text-custom-500"></i>
                <h6 class="text-sm font-bold text-slate-900 uppercase tracking-wide">Status Approval</h6>
            </div>

            @if($steps->isEmpty())
                <div class="text-center py-6">
                    <i data-lucide="clock" class="w-6 h-6 mx-auto mb-2 block text-slate-300"></i>
                    <p class="text-xs text-slate-400">Belum ada data approval</p>
                </div>
            @else
                {{-- Timeline vertikal --}}
                <div class="relative">
                    {{-- Garis vertikal --}}
                    <div class="absolute left-4 top-5 bottom-5 w-0.5"
                         style="background:linear-gradient(180deg,#04A54C,rgba(4,165,76,.1));"></div>

                    <div class="flex flex-col gap-0">
                    @foreach($steps as $step)
                    @php
                        $isApproved = $step->status === 'approved';
                        $isWaiting  = $step->status === 'waiting';
                        $isRejected = $step->status === 'rejected';
                        $isPending  = $step->status === 'pending';
                    @endphp

                    <div class="relative flex gap-4 pb-6 last:pb-0">
                        {{-- Dot --}}
                        <div class="relative z-10 flex-shrink-0 w-8 h-8 rounded-full flex items-center justify-center border-2
                            {{ $isApproved ? 'bg-custom-500 border-custom-500' : '' }}
                            {{ $isWaiting  ? 'bg-white border-custom-500 shadow-sm' : '' }}
                            {{ $isRejected ? 'bg-red-500 border-red-500' : '' }}
                            {{ $isPending  ? 'bg-white border-slate-200' : '' }}
                        ">
                            @if($isApproved)
                                <i data-lucide="check" class="w-3.5 h-3.5 text-white"></i>
                            @elseif($isWaiting)
                                <i data-lucide="clock" class="w-3.5 h-3.5 text-custom-500"></i>
                            @elseif($isRejected)
                                <i data-lucide="x" class="w-3.5 h-3.5 text-white"></i>
                            @else
                                <span class="text-xs font-bold text-slate-300">{{ $step->step_order }}</span>
                            @endif
                        </div>

                        {{-- Konten --}}
                        <div class="flex-1 min-w-0 pt-1">
                            <div class="flex items-center justify-between gap-2 mb-0.5">
                                <p class="text-xs font-bold text-slate-900">{{ $step->label }}</p>
                                @if($isApproved)
                                    <span class="text-xs font-semibold text-custom-500">✓ Disetujui</span>
                                @elseif($isWaiting)
                                    <span class="text-xs font-semibold text-amber-600 animate-pulse">Menunggu</span>
                                @elseif($isRejected)
                                    <span class="text-xs font-semibold text-red-500">✗ Ditolak</span>
                                @else
                                    <span class="text-xs text-slate-300">Pending</span>
                                @endif
                            </div>

                            @if($step->approver)
                                <p class="text-xs text-slate-500">{{ $step->approver->name }}</p>
                            @else
                                <p class="text-xs text-slate-300">Menunggu approver</p>
                            @endif

                            @if($step->actioned_at)
                                <p class="text-xs text-slate-400 mt-0.5">
                                    {{ $step->actioned_at->format('d M Y, H:i') }}
                                </p>
                            @endif

                            @if($step->catatan && ($isApproved || $isRejected))
                                <div class="mt-1.5 px-2 py-1.5 rounded-md text-xs
                                    {{ $isRejected ? 'bg-red-50 text-red-700' : 'bg-green-50 text-green-700' }}">
                                    "{{ $step->catatan }}"
                                </div>
                            @endif
                        </div>
                    </div>
                    @endforeach
                    </div>
                </div>
            @endif

            {{-- Summary --}}
            @if($steps->isNotEmpty())
            <div class="mt-5 pt-4 border-t border-slate-100">
                @php
                    $approved = $steps->where('status','approved')->count();
                    $total    = $steps->count();
                @endphp
                <div class="flex justify-between items-center text-xs mb-1.5">
                    <span class="text-slate-500 font-medium">Progress Approval</span>
                    <span class="font-bold text-custom-500">{{ $approved }}/{{ $total }}</span>
                </div>
                <div class="h-2 bg-slate-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full bg-custom-500"
                         style="width:{{ $total > 0 ? round(($approved/$total)*100) : 0 }}%;
                                background:linear-gradient(90deg,#04A54C,#4ade80);"></div>
                </div>
                <p class="text-xs text-slate-400 mt-1.5 text-right">
                    {{ $total > 0 ? round(($approved/$total)*100) : 0 }}% selesai
                </p>
            </div>

            {{-- TTD Section --}}
            @php $approvedSteps = $steps->where('status', 'approved'); @endphp
            @if($approvedSteps->isNotEmpty())
            <div class="mt-5 pt-4 border-t border-slate-100">
                <p class="text-xs font-bold tracking-widest uppercase text-slate-400 mb-3">Tanda Tangan</p>
                <div class="flex flex-wrap gap-4">
                    @foreach($approvedSteps as $step)
                    <div class="text-center min-w-[80px]">
                        @if($step->ttd_snapshot)
                            @php
                                // ttd_snapshot format: "ttd/2.png"
                                // full path: storage/app/private/ttd/2.png
                                $ttdPath = storage_path('app/private/' . $step->ttd_snapshot);
                            @endphp
                            @if(file_exists($ttdPath))
                                {{-- Gunakan route ttd preview milik approver --}}
                                <div class="h-14 flex items-center justify-center mb-1">
                                    <img src="{{ route('profile.ttd.preview') }}?_uid={{ $step->approver_id }}"
                                         class="max-h-14 w-auto object-contain"
                                         alt="TTD {{ $step->label }}"
                                         onerror="this.style.display='none'">
                                </div>
                            @else
                                <div class="h-14 flex items-center justify-center mb-1">
                                    <div class="w-16 h-10 border border-dashed border-slate-200 rounded flex items-center justify-center">
                                        <span class="text-xs text-slate-300">-</span>
                                    </div>
                                </div>
                            @endif
                        @endif
                        <div class="border-t border-slate-300 pt-1 mt-1">
                            <p class="text-xs font-bold text-slate-700">{{ $step->approver?->name ?? '-' }}</p>
                            <p class="text-xs text-slate-400">{{ $step->label }}</p>
                            <p class="text-xs text-slate-300">{{ $step->actioned_at?->format('d M Y') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            {{-- Download Cover PDF --}}
            @if($surat->status === 'approved_owner' && $surat->cover_pdf_path)
            <div class="mt-4">
                <a href="{{ \Illuminate\Support\Facades\Storage::url($surat->cover_pdf_path) }}" target="_blank"
                   class="inline-flex items-center justify-center gap-2 px-4 py-2 bg-emerald-600 text-white rounded-lg text-sm font-bold w-full">
                    <i data-lucide="file-check" class="w-4 h-4"></i>
                    Download Lembar Persetujuan (PDF)
                </a>
            </div>
            @endif
            @endif

        </div>
    </div>{{-- end kolom kanan --}}

</div>{{-- end grid --}}

</div>
</div>
@endsection