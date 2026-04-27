@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <!-- Breadcrumb & Back Button -->
            <div class="flex flex-col gap-2 py-4 md:flex-row md:items-center print:hidden mb-6">
                <div class="grow">
                    <h5 class="text-lg font-semibold text-slate-600">Manajemen SDM</h5>
                </div>
                <ul class="flex items-center gap-2 text-sm font-normal shrink-0">
                    <li class="relative before:content-['\ea54'] before:font-remix ltr:before:-right-1 rtl:before:-left-1 before:absolute before:text-[18px] before:-top-[3px] ltr:pr-4 rtl:pl-4 before:text-slate-400 dark:text-zink-200">
                        <a href="{{ route('hr.approval-flow.index') }}" class="text-slate-400 dark:text-zink-200">Alur Approval</a>
                    </li>
                    <li class="text-slate-700 dark:text-zink-100">
                        Riwayat
                    </li>
                </ul>
            </div>

            <!-- Page Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h4 class="text-xl font-bold text-slate-900">Riwayat Approval Surat</h4>
                    <p class="text-sm text-slate-500 mt-0.5">Daftar lengkap approval surat yang telah diproses</p>
                </div>
                <div class="shrink-0">
                    <a href="{{ route('hr.approval-flow.index') }}" class="ds-btn btn-outline">
                        <i data-lucide="arrow-left" class="w-4 h-4"></i>
                        Kembali
                    </a>
                </div>
            </div>

            <!-- Section -->
            <div class="ds-section">
                <div class="overflow-x-auto">
                    <table class="ds-table">
                        <thead>
                            <tr>
                                <th>Nomor Surat</th>
                                <th>Jenis Surat</th>
                                <th>Step</th>
                                <th>Diproses Oleh</th>
                                <th>Status</th>
                                <th>Catatan</th>
                                <th>Waktu</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($logs as $log)
                                <tr>
                                    <td>
                                        @if($log->surat)
                                            <a href="{{ route('surat.show', $log->surat->id) }}" class="text-custom-600 font-semibold hover:text-custom-700">
                                                {{ $log->surat->nomor_surat }}
                                            </a>
                                        @else
                                            <span class="text-slate-400">-</span>
                                        @endif
                                    </td>
                                    <td>
                                        <span class="text-sm capitalize">
                                            {{ ucfirst(str_replace('_', ' ', str_replace('surat_', '', $log->document_type))) }}
                                        </span>
                                    </td>
                                    <td class="text-sm">{{ $log->label }}</td>
                                    <td class="text-sm">{{ $log->approver->name ?? '-' }}</td>
                                    <td>
                                        @if($log->status === 'approved')
                                            <span class="ds-badge b-green">
                                                <i data-lucide="check-circle" class="w-3 h-3 mr-1"></i>
                                                Disetujui
                                            </span>
                                        @elseif($log->status === 'rejected')
                                            <span class="ds-badge b-red">
                                                <i data-lucide="x-circle" class="w-3 h-3 mr-1"></i>
                                                Ditolak
                                            </span>
                                        @endif
                                    </td>
                                    <td class="text-sm text-slate-600">
                                        {{ $log->catatan ?? '-' }}
                                    </td>
                                    <td class="text-sm whitespace-nowrap">
                                        {{ $log->actioned_at?->format('d M Y, H:i') ?? '-' }}
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="text-center py-8">
                                        <i data-lucide="inbox" class="w-8 h-8 mx-auto mb-2 block text-slate-400"></i>
                                        <p class="text-slate-500">Tidak ada riwayat approval</p>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $logs->links() }}
                </div>
            </div>
        </div>
    </div>
@endsection
