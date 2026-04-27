@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">data shift</h5>
                    <p class="text-sm text-slate-500 dark:text-zink-200 mt-1">Atur jadwal shift dan parameter kerja karyawan</p>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">manajemen hr</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        data shift
                    </li>
                </ul>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center">
                        <h6 class="text-15 grow">data shift</h6>
                        <div class="shrink-0">
                            <button data-modal-target="addShiftModal" type="button" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">
                                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" data-lucide="plus" class="lucide lucide-plus inline-block size-4">
                                    <path d="M5 12h14"></path>
                                    <path d="M12 5v14"></path>
                                </svg> 
                                <span class="align-middle">tambah shift</span>
                            </button>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                    <table id="alternativePagination" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>no</th>
                                <th>nama shift</th>
                                <th>jam masuk</th>
                                <th>jam keluar</th>
                                <th>toleransi (menit)</th>
                                <th>keterangan</th>
                                <th>aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($shiftList as $key => $shift)
                                <tr class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $shift->nama_shift }}</td>
                                    <td>{{ $shift->jam_masuk }}</td>
                                    <td>{{ $shift->jam_keluar }}</td>
                                    <td>{{ $shift->toleransi_menit }}</td>
                                    <td>{{ $shift->keterangan ?? '-' }}</td>
                                    <td>
                                        <div class="flex gap-2">
                                            <button class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 text-slate-500 bg-slate-100 hover:text-white hover:bg-slate-500 dark:bg-zink-600 dark:text-zink-200 dark:hover:text-white dark:hover:bg-zink-500" data-modal-target="addShiftModal" onclick="editShift({{ $shift->id }}, '{{ $shift->nama_shift }}', '{{ $shift->jam_masuk }}', '{{ $shift->jam_keluar }}', {{ $shift->toleransi_menit }}, '{{ $shift->keterangan }}')"><i data-lucide="pencil" class="size-4"></i></button>
                                            <button class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 bg-slate-100 text-slate-500 hover:text-red-500 hover:bg-red-100 dark:bg-zink-600 dark:text-zink-200 dark:hover:text-red-500 dark:hover:bg-red-500/20" data-modal-target="deleteModal" onclick="deleteShift({{ $shift->id }})"><i data-lucide="trash-2" class="size-4"></i></button>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-slate-500">tidak ada data shift</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page-content -->

    <!-- tambah/edit shift modal -->
    <div id="addShiftModal" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[30rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                <h5 class="text-16" id="modalTitle">tambah shift</h5>
                <button data-modal-close="addShiftModal" class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="max-h-[calc(theme('height.screen')_-_180px)] p-4 overflow-y-auto">
                <form action="{{ route('hr/shift/store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_update" id="id_update" value="">
                    <div class="grid grid-cols-1 gap-4">
                        <div>
                            <label class="inline-block mb-2 text-base font-medium">nama shift</label>
                            <input type="text" name="nama_shift" id="nama_shift" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700" required>
                        </div>
                        <div>
                            <label class="inline-block mb-2 text-base font-medium">jam masuk</label>
                            <input type="time" name="jam_masuk" id="jam_masuk" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700" required>
                        </div>
                        <div>
                            <label class="inline-block mb-2 text-base font-medium">jam keluar</label>
                            <input type="time" name="jam_keluar" id="jam_keluar" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700" required>
                        </div>
                        <div>
                            <label class="inline-block mb-2 text-base font-medium">toleransi (menit)</label>
                            <input type="number" name="toleransi_menit" id="toleransi_menit" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700" min="0" max="60" required>
                        </div>
                        <div>
                            <label class="inline-block mb-2 text-base font-medium">keterangan</label>
                            <input type="text" name="keterangan" id="keterangan" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700">
                        </div>
                    </div>
                    <div class="flex justify-end gap-2 mt-4">
                        <button type="reset" data-modal-close="addShiftModal" class="text-red-500 bg-white btn hover:text-red-500 hover:bg-red-100 focus:text-red-500 focus:bg-red-100 active:text-red-500 active:bg-red-100 dark:bg-zink-600 dark:hover:bg-red-500/10 dark:focus:bg-red-500/10 dark:active:bg-red-500/10">batal</button>
                        <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600 focus:text-white focus:bg-custom-600 focus:border-custom-600 focus:ring focus:ring-custom-100 active:text-white active:bg-custom-600 active:border-custom-600 active:ring active:ring-custom-100 dark:ring-custom-400/20">simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- end tambah/edit shift modal -->

    <!-- delete shift confirm modal -->
    <div id="deleteModal" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[25rem] bg-white shadow rounded-md dark:bg-zink-600">
            <div class="flex items-center justify-between p-4 border-b dark:border-zink-500">
                <h5 class="text-16">hapus data</h5>
                <button data-modal-close="deleteModal" class="transition-all duration-200 ease-linear text-slate-400 hover:text-red-500">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-4">
                <p class="text-slate-600 dark:text-zink-200">apakah anda yakin ingin menghapus data shift ini?</p>
                <div class="flex justify-end gap-2 mt-4">
                    <button type="button" data-modal-close="deleteModal" class="text-slate-500 bg-white btn hover:bg-slate-100 focus:bg-slate-100 active:bg-slate-100 dark:bg-zink-600 dark:hover:bg-zink-500 dark:focus:bg-zink-500 dark:active:bg-zink-500">batal</button>
                    <form id="deleteForm" action="{{ route('hr/shift/delete') }}" method="POST" style="display:inline;">
                        @csrf
                        <input type="hidden" name="id_delete" id="id_delete_shift" value="">
                        <button type="submit" class="text-white btn bg-red-500 border-red-500 hover:text-white hover:bg-red-600 hover:border-red-600 focus:text-white focus:bg-red-600 focus:border-red-600 focus:ring focus:ring-red-100 active:text-white active:bg-red-600 active:border-red-600 active:ring active:ring-red-100 dark:ring-red-400/20">hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <!-- end delete shift confirm modal -->

    <script>
        function editShift(id, namaShift, jamMasuk, jamKeluar, toleransi, keterangan) {
            document.getElementById('id_update').value = id;
            document.getElementById('nama_shift').value = namaShift;
            document.getElementById('jam_masuk').value = jamMasuk;
            document.getElementById('jam_keluar').value = jamKeluar;
            document.getElementById('toleransi_menit').value = toleransi;
            document.getElementById('keterangan').value = keterangan;
            document.getElementById('modalTitle').textContent = 'ubah shift';
        }

        function deleteShift(id) {
            document.getElementById('id_delete_shift').value = id;
        }
    </script>
@endsection
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    if (document.getElementById('alternativePagination')) {
        new DataTable('#alternativePagination', {
            pagingType: 'full_numbers',
            columnDefs: [
                { orderable: false, targets: [0, 6] }
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
