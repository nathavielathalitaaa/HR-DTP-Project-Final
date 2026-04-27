@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Revisi Surat</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="{{ route('surat.index') }}" class="text-slate-400 dark:text-zink-200">Surat</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        Revisi Surat
                    </li>
                </ul>
            </div>

            <div class="ds-section">
                <h5 class="text-xl font-bold text-slate-900 mb-2">Revisi Surat: {{ $surat->nomor_surat }}</h5>
                <p class="text-sm text-slate-500 mb-6">Upload file PDF yang sudah direvisi sesuai catatan di bawah</p>

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

                <div class="mb-6 p-4 rounded-lg" style="background: rgba(249, 115, 22, 0.1); border-left: 3px solid #f97316;">
                    <p class="text-xs font-bold tracking-widest uppercase text-amber-700 mb-2">Catatan Revisi</p>
                    <p class="text-sm text-amber-800">{{ $surat->catatan_revisi }}</p>
                </div>

                <form action="{{ route('surat.update', $surat->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="mb-6">
                        <label for="jenis_surat" class="block text-sm font-semibold text-slate-700 mb-1">Jenis Surat</label>
                        <select id="jenis_surat" name="jenis_surat" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-custom-500 focus:ring-2 focus:ring-custom-100 text-sm transition-all bg-slate-50" readonly disabled>
                            <option value="{{ $surat->jenis_surat }}" selected>{{ $surat->jenis_surat }}</option>
                        </select>
                        <p class="text-xs text-slate-500 mt-2">Jenis surat tidak dapat diubah saat revisi</p>
                    </div>

                    <div class="mb-6">
                        <label for="perihal" class="block text-sm font-semibold text-slate-700 mb-1">Perihal</label>
                        <textarea id="perihal" name="perihal" rows="4" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-custom-500 focus:ring-2 focus:ring-custom-100 text-sm transition-all bg-slate-50" readonly disabled>{{ $surat->perihal }}</textarea>
                        <p class="text-xs text-slate-500 mt-2">Perihal tidak dapat diubah saat revisi</p>
                    </div>

                    <div class="mb-6">
                        <label for="file_pdf" class="block text-sm font-semibold text-slate-700 mb-1">File PDF Baru <span class="text-red-500">*</span></label>
                        <input type="file" id="file_pdf" name="file_pdf" accept=".pdf" class="w-full px-4 py-3 rounded-xl border border-slate-200 focus:border-custom-500 focus:ring-2 focus:ring-custom-100 text-sm transition-all" required>
                        <p class="text-xs text-slate-500 mt-2">Format: PDF, Ukuran maksimal: 5MB</p>
                        @error('file_pdf')
                            <span class="text-xs text-red-500 mt-1 block">{{ $message }}</span>
                        @enderror
                    </div>

                    <div class="flex gap-3 pt-2">
                        <button type="submit" class="ds-btn" style="background: linear-gradient(135deg, #f97316 0%, #ea580c 100%); color: white;">
                            <i data-lucide="send" class="w-4 h-4"></i>
                            Kirim Revisi
                        </button>
                        <a href="{{ route('surat.show', $surat->id) }}" class="ds-btn btn-outline">
                            <i data-lucide="x" class="w-4 h-4"></i>
                            Batal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
