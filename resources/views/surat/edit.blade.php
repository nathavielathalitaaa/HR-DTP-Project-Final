@extends('layouts.master')
@section('content')

    {{-- Header --}}
    <div class="flex items-center justify-between mb-8">
        <div>
            <h1 class="text-3xl font-playfair font-semibold text-[#1A2B24]">Revisi Surat</h1>
            <p class="text-sm text-gray-500 mt-1">Upload file PDF yang sudah direvisi sesuai catatan</p>
        </div>
        <a href="{{ route('surat.show', $surat->id) }}"
           class="px-5 py-2.5 rounded-xl border border-gray-200 bg-white hover:bg-gray-50 text-sm font-medium text-gray-600 transition shadow-sm">
            Kembali
        </a>
    </div>

    <div class="max-w-2xl bg-white rounded-3xl p-9 border border-gray-100 shadow-sm">

        {{-- Catatan Revisi --}}
        <div class="mb-6 p-5 rounded-2xl border" style="background:rgba(251,191,36,0.08);border-color:rgba(251,191,36,0.3);">
            <div class="flex items-center gap-2 mb-2">
                <i data-lucide="alert-triangle" class="w-5 h-5 text-amber-500"></i>
                <p class="text-sm font-bold text-amber-800">Catatan Revisi dari Approver</p>
            </div>
            <p class="text-sm text-amber-700 mt-1">{{ $surat->catatan_revisi }}</p>
        </div>

        @if ($errors->any())
            <div class="mb-6 p-4 rounded-xl" style="background:rgba(239,68,68,0.08);border-left:3px solid #ef4444;">
                <p class="text-sm font-semibold text-red-800 mb-2">Terjadi Kesalahan:</p>
                <ul class="text-sm text-red-700 list-disc list-inside">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('surat.update', $surat->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Jenis Surat (readonly) --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-[#1A2B24] mb-2">Jenis Surat</label>
                <div class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-600">
                    {{ ucfirst(str_replace('_', ' ', $surat->jenis_surat)) }}
                </div>
                <p class="text-xs text-gray-400 mt-1">Jenis surat tidak dapat diubah saat revisi</p>
                <input type="hidden" name="jenis_surat" value="{{ $surat->jenis_surat }}">
                <input type="hidden" name="surat_type_id" value="{{ $surat->surat_type_id }}">
            </div>

            {{-- Perihal (readonly) --}}
            <div class="mb-6">
                <label class="block text-sm font-bold text-[#1A2B24] mb-2">Perihal</label>
                <div class="w-full px-4 py-3 rounded-xl border border-gray-200 bg-gray-50 text-sm text-gray-600 leading-relaxed">
                    {{ $surat->perihal }}
                </div>
                <p class="text-xs text-gray-400 mt-1">Perihal tidak dapat diubah saat revisi</p>
                <input type="hidden" name="perihal" value="{{ $surat->perihal }}">
            </div>

            {{-- Upload File PDF Baru --}}
            <div class="mb-8">
                <label for="file_pdf" class="block text-sm font-bold text-[#1A2B24] mb-2">
                    File PDF Baru <span class="text-red-500">*</span>
                </label>
                <input type="file" id="file_pdf" name="file_pdf" accept=".pdf" required
                    class="w-full px-4 py-3 rounded-xl border border-gray-200 text-sm transition-all bg-white
                           file:mr-4 file:py-2 file:px-4 file:rounded-xl file:border-0 file:text-sm file:font-bold
                           file:bg-[#80BB9B]/20 file:text-[#4F6560] hover:file:bg-[#80BB9B]/30">
                <p class="text-xs text-gray-400 mt-2">Format: PDF, Ukuran maksimal: 5MB</p>
                @error('file_pdf')
                    <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                @enderror
            </div>

            {{-- Buttons --}}
            <div class="flex gap-3 pt-4 border-t border-gray-100">
                <button type="submit"
                    class="inline-flex items-center gap-2 px-8 py-2.5 bg-amber-500 hover:bg-amber-600 text-white rounded-xl text-sm font-bold shadow transition">
                    <i data-lucide="send" class="w-4 h-4"></i>
                    Kirim Revisi
                </button>
                <a href="{{ route('surat.show', $surat->id) }}"
                   class="px-6 py-2.5 border border-gray-300 text-gray-700 bg-white rounded-xl text-sm font-semibold hover:bg-gray-50 transition">
                    Batal
                </a>
            </div>
        </form>
    </div>

@endsection
