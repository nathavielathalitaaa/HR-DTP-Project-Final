@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">daftar absensi</h5>
                    <p class="text-sm text-slate-500 dark:text-zink-200 mt-1">Catat dan pantau kehadiran karyawan harian dengan mudah</p>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">manajemen hr</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        daftar absensi
                    </li>
                </ul>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center mb-4 gap-2">
                        <h6 class="text-15 grow">daftar absensi</h6>
                        <div class="shrink-0 flex gap-2">
                            <button data-modal-target="addAbsensiModal" type="button" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="plus" class="lucide lucide-plus inline-block size-4">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5v14"></path>
                                </svg> 
                                <span class="align-middle">tambah absensi</span>
                            </button>
                            <a href="{{ route('hr/absensi/import') }}"
                               class="inline-flex items-center gap-2 px-4 py-2 border border-custom-500 text-custom-500 rounded-lg text-sm font-bold hover:bg-custom-50 transition-all">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="upload" class="w-4 h-4">
                                    <path d="M21 15v4a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-4"></path>
                                    <polyline points="17 8 12 3 7 8"></polyline>
                                    <line x1="12" y1="3" x2="12" y2="15"></line>
                                </svg>
                                Import Excel
                            </a>
                        </div>
                    </div>

                    <!-- form filter bulan -->
                    <form method="GET" action="{{ route('hr/absensi/page') }}" class="mb-4">
                        <div class="flex gap-2 items-center">
                            <label class="text-sm font-medium">filter bulan:</label>
                            <input type="month" name="bulan" value="{{ $bulan }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 w-40" onchange="this.form.submit()">
                        </div>
                    </form>

                    <div class="overflow-x-auto">
                    <table id="alternativePagination" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>no</th>
                                <th>nama karyawan</th>
                                <th>tanggal</th>
                                <th>jam masuk</th>
                                <th>jam keluar</th>
                                <th>status</th>
                                <th>keterangan</th>
                                <th>aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($absensiList as $key => $absensi)
                                <tr class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $absensi->user->name ?? '-' }}</td>
                                    <td>{{ \Carbon\Carbon::parse($absensi->tanggal)->format('d M Y') }}</td>
                                    <td>{{ $absensi->jam_masuk ?? '-' }}</td>
                                    <td>{{ $absensi->jam_keluar ?? '-' }}</td>
                                    <td>
                                        @if($absensi->status == 'hadir')
                                            <span style="color:#22c55e" class="font-medium">{{ $absensi->status }}</span>
                                        @elseif($absensi->status == 'sakit')
                                            <span style="color:#f59e0b" class="font-medium">{{ $absensi->status }}</span>
                                        @elseif($absensi->status == 'alpha')
                                            <span style="color:#ef4444" class="font-medium">{{ $absensi->status }}</span>
                                        @elseif($absensi->status == 'izin')
                                            <span style="color:#3b82f6" class="font-medium">{{ $absensi->status }}</span>
                                        @else
                                            <span>{{ $absensi->status }}</span>
                                        @endif
                                    </td>
                                    <td>{{ $absensi->keterangan ?? '-' }}</td>
                                    <td>
                                        <div class="flex gap-2">
                                            <a class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200 dark:hover:text-red-500 dark:hover:bg-red-500/20" href="javascript:void(0)" onclick="deleteAbsensi({{ $absensi->id }})"><i data-lucide="trash-2" class="size-4"></i></a>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4 text-slate-500">tidak ada data absensi</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page-content -->

    <!-- tambah absensi modal -->
    <div id="addAbsensiModal" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                <h5 class="text-16">input absensi manual</h5>
                <button data-modal-close="addAbsensiModal" class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                <form action="{{ route('hr/absensi/store') }}" method="POST">
                    @csrf
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="inline-block mb-2 text-base font-medium">pilih karyawan</label>
                            <select name="user_id" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700" required>
                                <option value="">-- pilih karyawan --</option>
                                @foreach(\App\Models\User::all() as $user)
                                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label class="inline-block mb-2 text-base font-medium">tanggal</label>
                            <input type="date" name="tanggal" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700" required>
                        </div>
                        <div>
                            <label class="inline-block mb-2 text-base font-medium">jam masuk</label>
                            <input type="time" name="jam_masuk" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700">
                        </div>
                        <div>
                            <label class="inline-block mb-2 text-base font-medium">jam keluar</label>
                            <input type="time" name="jam_keluar" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700">
                        </div>
                        <div>
                            <label class="inline-block mb-2 text-base font-medium">status</label>
                            <select name="status" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700" required>
                                <option value="">-- pilih status --</option>
                                <option value="hadir">hadir</option>
                                <option value="izin">izin</option>
                                <option value="sakit">sakit</option>
                                <option value="alpha">alpha</option>
                                <option value="cuti">cuti</option>
                            </select>
                        </div>
                        <div>
                            <label class="inline-block mb-2 text-base font-medium">keterangan</label>
                            <textarea name="keterangan" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="reset" data-modal-close="addAbsensiModal" class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-600 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">batal</button>
                        <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end tambah absensi modal -->
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('alternativePagination')) {
        new DataTable('#alternativePagination', {
            pagingType: 'full_numbers',
            columnDefs: [
                { orderable: false, targets: [0, 7] }
            ],
            language: {
                search: 'Cari:',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_ - _END_ dari _TOTAL_ data',
                paginate: { first: 'Pertama', last: 'Terakhir', next: 'Selanjutnya', previous: 'Sebelumnya' },
                emptyTable: 'Tidak ada data'
            }
        });
    }
});
</script>
@endpush

@endsection