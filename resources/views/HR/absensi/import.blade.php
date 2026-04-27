@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">Import Rekap Absensi dari Excel</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="{{ route('hr/absensi/page') }}" class="text-slate-400 dark:text-zink-200">Absensi</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        Import
                    </li>
                </ul>
            </div>

            <div class="card">
                <div class="card-body">
                    <div class="mb-6">
                        <h6 class="text-lg font-bold text-slate-900">Import Rekap Absensi</h6>
                        <p class="text-sm text-slate-500 mt-1">Unggah file Excel dengan data rekap absensi karyawan untuk bulan tertentu</p>
                    </div>

                    @if ($errors->any())
                        <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200">
                            <h6 class="text-sm font-bold text-red-700 mb-2">Terjadi Kesalahan:</h6>
                            <ul class="text-sm text-red-600 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <form action="{{ route('hr/absensi/import/store') }}" method="POST" enctype="multipart/form-data" class="space-y-6">
                        @csrf

                        <!-- Info Box -->
                        <div class="p-4 rounded-lg bg-blue-50 border border-blue-200">
                            <h6 class="text-sm font-bold text-blue-900 mb-2">Format Excel yang Diharapkan:</h6>
                            <p class="text-sm text-blue-800 mb-3">Kolom harus berurutan sesuai berikut:</p>
                            <div class="text-xs text-blue-700 font-mono bg-white p-2 rounded border border-blue-100">
                                NO | NAMA | JABATAN | Sakit (dg srt) | Sakit (tanpa srt) | Ijin | Alfa | Hari kerja | Off
                            </div>
                            <p class="text-xs text-blue-700 mt-3">
                                <strong>Catatan:</strong> Baris dengan NO yang berupa angka Romawi (I, II, III, dst) atau NaN akan dilewati (header).
                                Baris dengan NAMA kosong juga akan dilewati.
                            </p>
                        </div>

                        <!-- Periode Selection -->
                        <div>
                            <label class="inline-block mb-2 text-base font-medium text-slate-700">Pilih Periode / Bulan <span class="text-red-500">*</span></label>
                            <select name="bulan" required class="w-full px-3 py-2 rounded-lg border border-slate-200 text-slate-900 focus:outline-none focus:border-custom-500 focus:ring-1 focus:ring-custom-500 dark:border-zink-500 dark:text-zink-100 dark:bg-zink-700">
                                <option value="">-- Pilih Bulan --</option>
                                @foreach ($months as $value => $label)
                                    <option value="{{ $value }}">{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('bulan')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- File Upload -->
                        <div>
                            <label class="inline-block mb-2 text-base font-medium text-slate-700">Upload File Excel <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <input type="file" name="file" accept=".xlsx,.xls" required 
                                    class="block w-full px-3 py-2 rounded-lg border border-slate-200 text-slate-900 cursor-pointer file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-custom-50 file:text-custom-700 hover:file:bg-custom-100 focus:outline-none focus:border-custom-500 focus:ring-1 focus:ring-custom-500 dark:border-zink-500 dark:text-zink-100 dark:bg-zink-700 dark:file:bg-zink-600 dark:file:text-zink-100">
                            </div>
                            <p class="text-xs text-slate-500 mt-2">Format: .xlsx atau .xls | Ukuran maksimal: 5MB</p>
                            @error('file')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Buttons -->
                        <div class="flex gap-3 pt-4">
                            <button type="submit" class="inline-flex items-center gap-2 px-6 py-2 bg-custom-500 text-white rounded-lg font-semibold hover:bg-custom-600 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="upload" class="w-4 h-4">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                Import Sekarang
                            </button>
                            <a href="{{ route('hr/absensi/page') }}" class="inline-flex items-center gap-2 px-6 py-2 border border-slate-300 text-slate-700 rounded-lg font-semibold hover:bg-slate-50 transition-all">
                                Kembali
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page-content -->
@endsection
