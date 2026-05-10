@extends('layouts.master')

@section('content')

    {{-- breadcrumb / header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-playfair font-semibold text-[#1A2B24]">Letter Details</h1>
            <p class="text-sm text-gray-500 mt-1">Full information and approval status</p>
        </div>
        <div class="flex items-center gap-3">
            <a href="{{ route('surat.index') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-sm font-medium text-gray-600 transition shadow-sm">
                Back
            </a>
            
            @if($surat->status === 'approved_owner' && ($surat->cover_pdf_path || $surat->hasFinalPdf()))
            <a href="{{ $surat->hasFinalPdf() ? asset('storage/' . $surat->final_pdf_path) : Storage::url($surat->cover_pdf_path) }}"
               target="_blank"
               class="inline-flex items-center gap-2 px-5 py-2.5 bg-emerald-600 text-white rounded-xl text-sm font-semibold hover:bg-emerald-700 transition shadow-sm">
                <i data-lucide="download" class="w-4 h-4 inline-block"></i> Download Letter
            </a>
            @elseif($surat->file_pdf)
                @can('download', $surat)
                <a href="{{ route('surat.download', $surat->id) }}"
                   class="inline-flex items-center gap-2 px-5 py-2.5 border border-[#4F6560] text-[#4F6560] bg-white hover:bg-gray-50 rounded-xl text-sm font-semibold transition shadow-sm">
                    <i data-lucide="file-text" class="w-4 h-4 inline-block"></i> View Document
                </a>
                @endcan
            @endif

            @if(Auth::id() === $surat->user_id)
                @if($surat->canBeEdited())
                <a href="{{ route('surat.edit', $surat->id) }}" class="px-5 py-2.5 bg-amber-500 text-white rounded-xl text-sm font-semibold hover:bg-amber-600 transition shadow-sm">
                    <i data-lucide="edit" class="w-4 h-4 inline-block mr-1"></i> Edit
                </a>
                @endif
                
                @if($surat->canBeDeleted())
                <form action="{{ route('surat.destroy', $surat->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this letter?')" class="inline">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="px-5 py-2.5 bg-rose-500 text-white rounded-xl text-sm font-semibold hover:bg-rose-600 transition shadow-sm">
                        <i data-lucide="trash-2" class="w-4 h-4 inline-block mr-1"></i> Delete
                    </button>
                </form>
                @endif
            @endif
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">

        {{-- ── kolom kiri — detail card (2 col) ──────────────────── --}}
        <div class="lg:col-span-2 space-y-6">

            <div class="bg-white p-[28px] rounded-[20px] shadow-[0_2px_12px_rgba(0,0,0,0.05)] border border-gray-100">
                <div class="flex justify-between items-start mb-8 border-b border-gray-100 pb-6">
                    <div>
                        <h1 class="text-[28px] font-playfair font-bold text-[#1A2B24]">{{ $surat->nomor_surat }}</h1>
                        <p class="text-[13px] font-poppins font-light text-[#6B7280] mt-1">{{ $surat->suratType ? $surat->suratType->nama : ucfirst(str_replace('_', ' ', $surat->jenis_surat)) }}</p>
                    </div>
                    <div>
                        @if($surat->status === 'approved_owner')
                            <span class="px-4 py-1.5 rounded-full text-xs font-semibold bg-green-100 text-green-700 border border-green-200">Finished</span>
                        @elseif($surat->status === 'rejected')
                            <span class="px-4 py-1.5 rounded-full text-xs font-semibold bg-red-100 text-red-700 border border-red-200">Rejected</span>
                        @elseif($surat->status === 'revised')
                            <span class="px-4 py-1.5 rounded-full text-xs font-semibold bg-amber-100 text-amber-700 border border-amber-200">Needs Revision</span>
                        @else
                            <span class="px-4 py-1.5 rounded-full text-xs font-semibold bg-blue-100 text-blue-700 border border-blue-200">Approval Process</span>
                        @endif
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8 text-sm">
                    <div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-1.5">Subject</p>
                        <p class="font-medium text-gray-800">{{ $surat->perihal }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-1.5">Creator</p>
                        <div class="flex items-center gap-3">
                            <div class="w-8 h-8 rounded-full bg-[#80BB9B]/20 flex items-center justify-center font-semibold text-[#4F6560] text-xs">
                                {{ strtoupper(substr($surat->user->name ?? 'U',0,1)) }}
                            </div>
                            <p class="font-medium text-gray-800">{{ $surat->user->name ?? '-' }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-1.5">Created Date</p>
                        <p class="font-medium text-gray-800">{{ $surat->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-xs font-bold tracking-widest uppercase text-gray-400 mb-1.5">Attachment</p>
                        @if($surat->file_pdf)
                            @can('download', $surat)
                                <a href="{{ route('surat.download', $surat->id) }}" class="inline-flex items-center gap-2 text-[#4F6560] hover:text-[#3d504c] font-medium hover:underline">
                                    <i data-lucide="file-text" class="w-4 h-4"></i> View Original Document
                                </a>
                            @else
                                <p class="text-gray-500 italic">File available (limited access)</p>
                            @endcan
                        @else
                            <p class="text-gray-400 italic">No attachment file</p>
                        @endif
                    </div>
                </div>
            </div>

            {{-- ── Preview posisi TTD (hanya mode stamp & ada koordinat) ── --}}
            @php
                $hasTtdCoords = $surat->ttd_coordinates && count($surat->ttd_coordinates) > 0;
                $docType = 'surat_' . $surat->jenis_surat;
                
                if ($surat->suratType) {
                    $isStampMode = true; // SuratType default to stamp
                } else {
                    $firstStep = \App\Models\ApprovalStep::where('document_type', $docType)->first();
                    $isStampMode = $firstStep?->ttd_mode === 'stamp';
                }
            @endphp
            @if($hasTtdCoords && $isStampMode)
            <div class="bg-white p-6 rounded-[20px] shadow-[0_2px_12px_rgba(0,0,0,0.05)] border border-gray-100">
                <div class="flex items-center gap-2 mb-4">
                    <i data-lucide="map-pin" class="w-5 h-5 text-[#80BB9B]"></i>
                    <h6 class="font-playfair text-lg font-semibold text-[#1A2B24]">Signature Position</h6>
                    <span class="ml-auto text-[13px] font-poppins px-4 py-1 rounded-full border border-[#4F6560] text-[#4F6560]">Mode Stamp</span>
                </div>
                <div class="flex flex-wrap gap-6 items-start">
                    {{-- minimap A4 --}}
                    <div style="position:relative;border:1px solid #E5E7EB;border-radius:12px;overflow:hidden;background:#f9fafb;width:180px;height:254px;flex-shrink:0;">
                        {{-- A4 paper grid lines --}}
                        <div style="position:absolute;inset:0;display:grid;grid-template-rows:repeat(10,1fr);opacity:0.15;">
                            @for($l=0;$l<10;$l++)
                            <div style="border-bottom:1px solid #9CA3AF;"></div>
                            @endfor
                        </div>
                        {{-- Document icon --}}
                        <div style="position:absolute;inset:0;display:flex;align-items:center;justify-content:center;pointer-events:none;">
                            <i data-lucide="file-text" style="width:48px;height:48px;color:#E5E7EB;"></i>
                        </div>
                        {{-- TTD position markers --}}
                        @foreach($surat->ttd_coordinates as $jabatan => $coord)
                        @php
                            $colors = ['hod'=>'#4F6560','hr'=>'#80BB9B','purchasing'=>'#f59e0b','owner_rep'=>'#3b82f6','direktur'=>'#8b5cf6','supervisor'=>'#06b6d4'];
                            $c = $colors[$jabatan] ?? '#4F6560';
                        @endphp
                        <div style="position:absolute;left:{{ $coord['x'] }}%;top:{{ $coord['y'] }}%;transform:translate(-50%,-50%);z-index:10;">
                            <div style="background:#E8F5EE;color:#0F6E56;font-size:8px;font-weight:700;padding:4px 12px;border-radius:999px;white-space:nowrap;box-shadow:0 2px 6px rgba(0,0,0,0.1);">
                                {{ strtoupper($jabatan ?? 'Approver') }}
                            </div>
                        </div>
                        @endforeach
                    </div>
                    {{-- Legend --}}
                    <div class="flex-1">
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3">Signature Position</p>
                        <div class="space-y-2">
                            @foreach($surat->ttd_coordinates as $jabatan => $coord)
                            @php $c = $colors[$jabatan] ?? '#4F6560'; @endphp
                            <div class="flex items-center gap-3">
                                <div style="width:10px;height:10px;border-radius:50%;background:#80BB9B;flex-shrink:0;"></div>
                                <span class="text-sm font-poppins px-3 py-1 bg-[#E8F5EE] text-[#0F6E56] rounded-full">{{ strtoupper($jabatan ?? 'Approver') }}</span>
                                <span class="text-xs text-gray-400 ml-auto">X: {{ number_format($coord['x'],1) }}% Y: {{ number_format($coord['y'],1) }}% (Hal {{ $coord['page'] ?? 1 }})</span>
                            </div>
                            @endforeach
                        </div>
                        <p class="text-xs text-gray-400 mt-4">The signature will be placed at the position above when the document is fully approved.</p>
                    </div>
                </div>
            </div>
            @endif

            {{-- catatan revisi --}}
            @if($surat->catatan_revisi)
            <div class="bg-orange-50 border border-orange-200 p-6 rounded-3xl shadow-sm">
                <div class="flex items-center gap-2 mb-2">
                    <i data-lucide="alert-triangle" class="w-5 h-5 text-orange-600"></i>
                    <p class="text-sm font-bold uppercase text-orange-800 tracking-wider">Revision Notes</p>
                </div>
                <p class="text-orange-900 mt-2">{{ $surat->catatan_revisi }}</p>
            </div>
            @endif

            {{-- ── form revisi — hanya untuk staff pemilik ── --}}
            @if($surat->status === 'revised' && Auth::id() === $surat->user_id)
            <div class="bg-amber-50 border border-amber-200 p-6 rounded-3xl shadow-sm flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                <div>
                    <div class="flex items-center gap-2 mb-1">
                        <i data-lucide="edit-3" class="w-5 h-5 text-amber-600"></i>
                        <h6 class="text-base font-bold text-amber-800">Letter Needs Revision</h6>
                    </div>
                    <p class="text-sm text-amber-700">
                        Your letter was rejected. Please fix it according to the notes and re-upload.
                    </p>
                </div>
                <a href="{{ route('surat.edit', $surat->id) }}" class="inline-flex items-center justify-center gap-2 px-5 py-3 bg-amber-500 text-white rounded-xl text-sm font-bold shadow-sm hover:bg-amber-600 transition shrink-0">
                    <i data-lucide="upload" class="w-4 h-4"></i> Upload Revision
                </a>
            </div>
            @endif

            {{-- ── Action Bar: Approval (Premium Style) ── --}}
            @if($canApprove && $surat->status === 'submitted')
            <div class="bg-white border border-[#E8F5EE] p-8 rounded-[24px] shadow-sm mt-8 flex flex-col md:flex-row items-center justify-between gap-6" style="background: linear-gradient(145deg, #ffffff 0%, #f9fbf9 100%);">
                <div class="flex items-center gap-5">
                    <div class="w-14 h-14 rounded-2xl bg-[#4F6560] flex items-center justify-center shadow-xl shadow-gray-200">
                        <i data-lucide="shield-check" class="w-7 h-7 text-white"></i>
                    </div>
                    <div>
                        <h4 class="text-xl font-playfair font-bold text-[#1A2B24]">Pending Your Approval</h4>
                        <p class="text-sm text-gray-500 mt-1">Please review the details and provide your digital signature.</p>
                    </div>
                </div>
                <div class="flex items-center gap-3 w-full md:w-auto">
                    <button type="button" 
                        onclick="openApproveModal()"
                        class="flex-1 md:flex-none px-8 py-3.5 bg-[#4F6560] text-white rounded-xl text-sm font-bold hover:bg-[#3d504c] transition shadow-lg shadow-gray-200 flex items-center justify-center gap-2">
                        <i data-lucide="check" class="w-5 h-5"></i> Approve Now
                    </button>
                    <button type="button"
                        onclick="openRejectModal()"
                        class="flex-1 md:flex-none px-8 py-3.5 bg-white text-red-500 border border-red-100 rounded-xl text-sm font-bold hover:bg-red-50 transition flex items-center justify-center gap-2">
                        <i data-lucide="x" class="w-5 h-5"></i> Reject
                    </button>
                </div>
            </div>
            @endif

        </div>

        {{-- ── kolom kanan — approval panel (1 col) ─────────── --}}
        <div class="lg:col-span-1 space-y-6">
            
            <div class="bg-white p-6 rounded-[20px] shadow-[0_2px_12px_rgba(0,0,0,0.05)] border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-lg font-playfair font-semibold text-[#1A2B24]">Status & History</h2>
                    <i data-lucide="history" class="w-5 h-5 text-[#80BB9B]"></i>
                </div>

                @php
                    $approved = $steps->where('status','approved')->count();
                    $total    = $steps->count();
                    $percentage = $total > 0 ? round(($approved/$total)*100) : 0;
                @endphp

                <div class="mb-8">
                    <div class="flex justify-between items-end mb-2">
                        <span class="text-[12px] font-poppins text-[#6B7280]">Progress</span>
                        <span class="text-[32px] font-playfair font-bold text-[#1A2B24]">{{ $percentage }}%</span>
                    </div>
                    <div class="h-2 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full bg-[#80BB9B] rounded-full transition-all duration-500" style="width: {{ $percentage }}%"></div>
                    </div>
                </div>

                <div class="flex flex-col">
                    @forelse($steps as $index => $step)
                        @php
                            $isApproved = $step->status === 'approved';
                            $isWaiting  = $step->status === 'waiting';
                            $isRejected = $step->status === 'rejected';
                            
                            $circleBg = '#F3F4F6';
                            $circleColor = '#9CA3AF';
                            if ($isApproved) {
                                $circleBg = '#80BB9B';
                                $circleColor = 'white';
                            } elseif ($isWaiting) {
                                $circleBg = '#4F6560';
                                $circleColor = 'white';
                            }
                        @endphp
                        
                        <div class="flex items-center gap-3 py-[10px]" style="{{ !$loop->last ? 'border-bottom: 0.5px solid #F3F4F6;' : '' }}">
                            <div class="w-[28px] h-[28px] rounded-full flex items-center justify-center flex-shrink-0 text-[12px] font-bold" 
                                 style="background: {{ $circleBg }}; color: {{ $circleColor }};">
                                {{ $index + 1 }}
                            </div>

                            <div class="flex-1">
                                <p class="text-[13px] font-poppins font-medium text-[#1A2B24] leading-tight">
                                    {{ $step->jabatan ?? $step->label ?? 'Approver' }}
                                </p>
                                <p class="text-[11px] font-poppins font-light text-[#6B7280]">
                                    @if($isApproved)
                                        {{ $step->approver->name ?? 'User' }} · {{ $step->actioned_at->format('d/m/y H:i') }}
                                    @elseif($isWaiting)
                                        Turn of {{ $step->assignedUser->name ?? ($step->jabatan ?? 'Approver') }}
                                    @else
                                        Waiting
                                    @endif
                                </p>
                            </div>
                            
                            @if($isApproved)
                                <i data-lucide="check-circle-2" class="w-4 h-4 text-[#80BB9B]"></i>
                            @elseif($isWaiting)
                                <i data-lucide="clock" class="w-4 h-4 text-[#4F6560] animate-pulse"></i>
                            @endif
                        </div>
                    @empty
                        <div class="text-center py-6">
                            <p class="text-sm text-gray-400 italic">No history data yet.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            {{-- download buttons --}}
            <div class="flex flex-col gap-3">
                {{-- Tombol download PDF asli (lampiran dari pembuat) --}}
                @if($surat->file_pdf)
                    @can('download', $surat)
                    <a href="{{ route('surat.download', $surat->id) }}" target="_blank"
                       class="inline-flex items-center justify-center gap-2 px-5 py-4 bg-[#4F6560] text-white rounded-full text-[13px] font-poppins font-medium shadow-sm hover:bg-[#3d504c] transition w-full">
                        <i data-lucide="file-text" class="w-5 h-5"></i>
                        Download Original Document
                    </a>
                    @endcan
                @endif
                
                {{-- Lembar Persetujuan (cover PDF yang dibuat ApprovalService) --}}
                @if($surat->status === 'approved_owner' && $surat->cover_pdf_path)
                @php
                    $coverExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($surat->cover_pdf_path);
                @endphp
                @if($coverExists)
                <div style="margin-top:16px;padding-top:16px;border-top:1px solid #F0F4F2;">
                    <p style="font-size:11px;font-weight:500;color:#6B7280;text-transform:uppercase;letter-spacing:0.06em;margin-bottom:8px;">
                        Approval Sheet
                    </p>
                    <a href="{{ \Illuminate\Support\Facades\Storage::url($surat->cover_pdf_path) }}"
                       target="_blank"
                       style="display:inline-flex;align-items:center;gap:8px;background:#4F6560;color:white;border-radius:9999px;padding:10px 20px;font-family:'Poppins',sans-serif;font-size:13px;font-weight:500;text-decoration:none;">
                        <i data-lucide="file-check" style="width:15px;height:15px;"></i>
                        Download Approval Sheet (PDF)
                    </a>
                </div>
                @else
                <div style="margin-top:12px;">
                    <p style="font-size:12px;color:#9CA3AF;">
                        <i data-lucide="alert-circle" style="width:12px;height:12px;display:inline;"></i>
                        PDF file not found in storage
                    </p>
                </div>
                @endif
                @endif
                
                {{-- PDF Final (jika ada) --}}
                @if($surat->hasFinalPdf())
                <a href="{{ asset('storage/' . $surat->final_pdf_path) }}" target="_blank"
                   class="inline-flex items-center justify-center gap-2 px-5 py-4 bg-emerald-600 text-white rounded-2xl text-sm font-semibold shadow-sm hover:bg-emerald-700 border border-emerald-500 transition">
                    <i data-lucide="download" class="w-5 h-5"></i>
                    Download Final PDF (Signed)
                </a>
                @endif
                
                {{-- Fallback jika tidak ada file apapun --}}
                @if(!$surat->file_pdf && !$surat->cover_pdf_path && !$surat->hasFinalPdf())
                <p class="text-sm text-center text-gray-400 italic py-4">No files available</p>
                @endif
            </div>
        </div>

    </div>

@endsection

@push('modals')
{{-- ── Modal: Approve Letter ── --}}
<div id="modalApprove" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/40 backdrop-blur-sm px-4">
    <div class="bg-white w-full max-w-[480px] rounded-[32px] overflow-hidden shadow-2xl animate-in fade-in zoom-in duration-200">
        <div class="p-8 pt-10 text-center">
            <div class="w-20 h-20 bg-emerald-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="shield-check" class="w-10 h-10 text-emerald-600"></i>
            </div>
            <h3 class="text-2xl font-playfair font-bold text-[#1A2B24] mb-2">Approve Document</h3>
            <p class="text-sm text-gray-500 px-6">Please enter your 6-digit PIN to confirm your digital signature and approve this document.</p>
        </div>

        <form action="{{ route('surat.approve', $surat->id) }}" method="POST" class="p-8 pt-0">
            @csrf
            <div class="space-y-5">
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Notes (Optional)</label>
                    <textarea name="catatan" rows="2" 
                        class="w-full bg-gray-50 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-emerald-100 transition resize-none"
                        placeholder="Add any additional comments..."></textarea>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Security PIN <span class="text-red-500">*</span></label>
                    <div class="relative">
                        <i data-lucide="lock" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400"></i>
                        <input type="password" name="pin" maxlength="6" required
                            class="w-full bg-gray-50 border-none rounded-2xl py-4 pl-11 pr-4 text-lg font-bold tracking-[0.5em] focus:ring-2 focus:ring-emerald-100 transition"
                            placeholder="••••••">
                    </div>
                </div>
            </div>

            <div class="mt-8 flex flex-col gap-3">
                <button type="submit" 
                    class="w-full py-4 bg-[#4F6560] text-white rounded-2xl text-sm font-bold shadow-lg shadow-emerald-100 hover:bg-[#3d504c] transition flex items-center justify-center gap-2">
                    <i data-lucide="check" class="w-5 h-5"></i> Confirm Approval
                </button>
                <button type="button" onclick="closeModals()"
                    class="w-full py-4 bg-white text-gray-500 rounded-2xl text-sm font-bold hover:bg-gray-50 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>

{{-- ── Modal: Reject Letter ── --}}
<div id="modalReject" class="fixed inset-0 z-[100] hidden items-center justify-center bg-black/40 backdrop-blur-sm px-4">
    <div class="bg-white w-full max-w-[480px] rounded-[32px] overflow-hidden shadow-2xl animate-in fade-in zoom-in duration-200">
        <div class="p-8 pt-10 text-center">
            <div class="w-20 h-20 bg-rose-50 rounded-full flex items-center justify-center mx-auto mb-6">
                <i data-lucide="alert-triangle" class="w-10 h-10 text-rose-500"></i>
            </div>
            <h3 class="text-2xl font-playfair font-bold text-[#1A2B24] mb-2">Reject Document</h3>
            <p class="text-sm text-gray-500 px-6">Are you sure you want to reject this document? You must provide a reason for the requester.</p>
        </div>

        <form action="{{ route('surat.reject', $surat->id) }}" method="POST" class="p-8 pt-0">
            @csrf
            <div>
                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Rejection Reason <span class="text-red-500">*</span></label>
                <textarea name="catatan_revisi" rows="4" required
                    class="w-full bg-rose-50/30 border-none rounded-2xl p-4 text-sm focus:ring-2 focus:ring-rose-100 transition resize-none"
                    placeholder="Describe why this document is being rejected..."></textarea>
            </div>

            <div class="mt-8 flex flex-col gap-3">
                <button type="submit" 
                    class="w-full py-4 bg-rose-500 text-white rounded-2xl text-sm font-bold shadow-lg shadow-rose-100 hover:bg-rose-600 transition flex items-center justify-center gap-2">
                    <i data-lucide="x-circle" class="w-5 h-5"></i> Confirm Rejection
                </button>
                <button type="button" onclick="closeModals()"
                    class="w-full py-4 bg-white text-gray-500 rounded-2xl text-sm font-bold hover:bg-gray-50 transition">
                    Cancel
                </button>
            </div>
        </form>
    </div>
</div>
@endpush

@push('scripts')
<script>
    function openApproveModal() {
        const modal = document.getElementById('modalApprove');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function openRejectModal() {
        const modal = document.getElementById('modalReject');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        document.body.style.overflow = 'hidden';
    }

    function closeModals() {
        ['modalApprove', 'modalReject'].forEach(id => {
            const el = document.getElementById(id);
            el.classList.add('hidden');
            el.classList.remove('flex');
        });
        document.body.style.overflow = 'auto';
    }

    // Close on backdrop click
    window.addEventListener('click', function(e) {
        if (e.target.id === 'modalApprove' || e.target.id === 'modalReject') {
            closeModals();
        }
    });
</script>
@endpush