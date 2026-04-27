@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden">
                <div class="grow">
                    <h5 class="text-16">data penggajian</h5>
                    <p class="text-sm text-slate-500 dark:text-zink-200 mt-1">Kelola data gaji dan generate slip gaji karyawan</p>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1  before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="#!" class="text-slate-400 dark:text-zink-200">manajemen hr</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        data penggajian
                    </li>
                </ul>
            </div>
            <div class="card">
                <div class="card-body">
                    <div class="flex items-center mb-4 gap-4">
                        <div>
                            <label class="text-sm font-medium">pilih periode:</label>
                            <form method="GET" action="{{ route('hr/penggajian/page') }}" class="flex gap-2 mt-2">
                                <input type="month" name="periode" value="{{ $periode }}" class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 w-40">
                                <button type="submit" class="text-white btn bg-custom-500 border-custom-500 hover:text-white hover:bg-custom-600 hover:border-custom-600">filter</button>
                            </form>
                        </div>
                        <div>
                            <form action="{{ route('hr/penggajian/generate') }}" method="POST" class="mt-8">
                                @csrf
                                <input type="hidden" name="periode" value="{{ $periode }}">
                                <button type="submit" class="text-white btn bg-green-500 border-green-500 hover:text-white hover:bg-green-600 hover:border-green-600">generate slip gaji</button>
                            </form>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                    <table id="alternativePagination" class="display" style="width:100%">
                        <thead>
                            <tr>
                                <th>no</th>
                                <th>nama karyawan</th>
                                <th>periode</th>
                                <th>gaji pokok</th>
                                <th>gaji bersih</th>
                                <th>status</th>
                                <th>aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($penggajianList as $key => $penggajian)
                                <tr class="px-3.5 py-2.5 first:pl-5 last:pr-5 border-y border-slate-200 dark:border-zink-500">
                                    <td>{{ ++$key }}</td>
                                    <td>{{ $penggajian->user->name ?? '-' }}</td>
                                    <td>{{ $penggajian->periode }}</td>
                                    <td>Rp. {{ number_format($penggajian->gaji_pokok, 2, ',', '.') }}</td>
                                    <td>Rp. {{ number_format($penggajian->gaji_bersih, 2, ',', '.') }}</td>
                                    <td>
                                        @if($penggajian->status == 'draft')
                                            <span style="color:#6b7280" class="font-medium">{{ $penggajian->status }}</span>
                                        @elseif($penggajian->status == 'diproses')
                                            <span style="color:#f59e0b" class="font-medium">{{ $penggajian->status }}</span>
                                        @elseif($penggajian->status == 'dibayar')
                                            <span style="color:#22c55e" class="font-medium">{{ $penggajian->status }}</span>
                                        @else
                                            <span>{{ $penggajian->status }}</span>
                                        @endif
                                    </td>
                                    <td>
                                        <div class="flex gap-2">
                                            <a href="{{ route('hr/penggajian/show', $penggajian->id) }}" class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 bg-slate-100 text-slate-500 hover:text-custom-500 hover:bg-custom-100 dark:bg-zink-600 dark:text-zink-200"><i data-lucide="eye" class="size-4"></i></a>
                                            <form action="{{ route('hr/penggajian/bayar') }}" method="POST" style="display:inline;">
                                                @csrf
                                                <input type="hidden" name="id" value="{{ $penggajian->id }}">
                                                <button type="submit" class="flex items-center justify-center transition-all duration-200 ease-linear rounded-md size-8 bg-slate-100 text-slate-500 hover:text-green-500 hover:bg-green-100 dark:bg-zink-600 dark:text-zink-200"><i data-lucide="check" class="size-4"></i></button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-4 text-slate-500">tidak ada data penggajian</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Page-content -->
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
                paginate: {
                    first: 'Pertama', last: 'Terakhir',
                    next: 'Selanjutnya', previous: 'Sebelumnya'
                },
                emptyTable: 'Tidak ada data'
            }
        });
    }
});
</script>
@endpush
@endsection
