@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Buat Surat Baru</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="{{ route('surat.index') }}" class="text-slate-400 dark:text-zink-200">Surat</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        Buat Surat Baru
                    </li>
                </ul>
            </div>

            <div class="ds-section">
                <h5 class="text-xl font-bold text-slate-900 mb-2">Buat Surat Baru</h5>
                <p class="text-sm text-slate-500 mb-6">Isi form di bawah untuk membuat surat baru</p>

                @if ($errors->any())
                    <div class="mb-6 p-4 rounded-lg" style="background: rgba(239, 68, 68, 0.1); border-left: 3px solid #ef4444;">
                        <p class="text-sm font-semibold text-red-800 mb-2">Terjadi Kesalahan:</p>
                        <ul class="text-sm text-red-700 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('surat.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="mb-6">
                        <label for="jenis_surat" class="block text-sm font-semibold text-slate-700 mb-1">Jenis Surat <span class="text-red-500">*</span></label>
                        <select id="jenis_surat" name="jenis_surat" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-custom-500 focus:ring-2 focus:ring-custom-100 text-sm transition-all" required>
                            <option value="">-- Pilih Jenis Surat --</option>
                            <option value="resign" @if(old('jenis_surat') === 'resign') selected @endif>Resign</option>
                            <option value="permohonan" @if(old('jenis_surat') === 'permohonan') selected @endif>Permohonan</option>
                            <option value="surat_tugas" @if(old('jenis_surat') === 'surat_tugas') selected @endif>Surat Tugas</option>
                            <option value="rekomendasi" @if(old('jenis_surat') === 'rekomendasi') selected @endif>Rekomendasi</option>
                            <option value="izin" @if(old('jenis_surat') === 'izin') selected @endif>Izin</option>
                            <option value="lainnya" @if(old('jenis_surat') === 'lainnya') selected @endif>Lainnya</option>
                        </select>
                        @error('jenis_surat')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="perihal" class="block text-sm font-semibold text-slate-700 mb-1">Perihal <span class="text-red-500">*</span></label>
                        <textarea id="perihal" name="perihal" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-custom-500 focus:ring-2 focus:ring-custom-100 text-sm transition-all" placeholder="Jelaskan perihal surat Anda" required>{{ old('perihal') }}</textarea>
                        @error('perihal')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="mb-6">
                        <label for="file_pdf" class="block text-sm font-semibold text-slate-700 mb-1">File PDF</label>
                        <div class="relative">
                            <input type="file" id="file_pdf" name="file_pdf" accept=".pdf" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-custom-500 focus:ring-2 focus:ring-custom-100 text-sm transition-all">
                        </div>
                        <p class="text-xs text-slate-500 mt-2">Format: PDF, Ukuran maksimal: 5MB</p>
                        @error('file_pdf')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="ds-btn btn-green">
                            <i data-lucide="save" class="w-4 h-4"></i>
                            Simpan Surat
                        </button>
                        <a href="{{ route('surat.index') }}" class="ds-btn btn-outline">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
