@extends('layouts.master')
@section('content')
    <!-- Page-content -->
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">
            <!-- Page Header -->
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h4 class="text-xl font-bold text-slate-900">Approval Flow Manager</h4>
                    <p class="text-sm text-slate-500 mt-0.5">Kelola alur persetujuan untuk setiap jenis surat</p>
                </div>
                <div class="shrink-0">
                    <a href="{{ route('hr.approval-flow.audit') }}" class="ds-btn btn-outline">
                        <i data-lucide="history" class="w-4 h-4"></i>
                        Riwayat Approval
                    </a>
                </div>
            </div>

            <!-- Flash Messages -->
            @if ($errors->any())
                <div class="mb-4 p-4 rounded-lg bg-red-50 border border-red-200">
                    <ul class="text-sm text-red-600">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Grid of Document Type Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
                @foreach ($approvalFlows as $docType => $flowData)
                    <div class="ds-section rounded-lg border border-slate-200 bg-white p-6 shadow-sm dark:border-zink-500 dark:bg-zink-700">
                        <!-- Card Header -->
                        <div class="mb-6 pb-4 border-b border-slate-200 dark:border-zink-500">
                            <h5 class="text-lg font-semibold text-slate-900 dark:text-white">{{ $flowData['display_name'] }}</h5>
                            <p class="text-xs text-slate-500 dark:text-zink-300 mt-1">Document Type: {{ $docType }}</p>
                        </div>

                        <!-- Approval Steps List -->
                        @if ($flowData['steps']->count() > 0)
                            <div class="mb-6 space-y-3">
                                @foreach ($flowData['steps'] as $step)
                                    <div class="flex items-center justify-between p-3 rounded-lg bg-slate-50 dark:bg-zink-600 border border-slate-200 dark:border-zink-500">
                                        <div class="flex items-center gap-3">
                                            <span class="inline-flex items-center justify-center w-8 h-8 rounded-full bg-custom-100 text-custom-600 font-semibold text-sm dark:bg-custom-500/20 dark:text-custom-400">
                                                {{ $step->step_order }}
                                            </span>
                                            <div>
                                                <p class="text-sm font-medium text-slate-900 dark:text-white">{{ $step->label }}</p>
                                                <p class="text-xs text-slate-500 dark:text-zink-300">{{ ucfirst($step->jabatan) }}</p>
                                            </div>
                                        </div>
                                        <form action="{{ route('hr.approval-flow.destroy', $step->id) }}" method="POST" class="inline" onsubmit="return confirm('Hapus step ini? Pastikan tidak ada surat sedang dalam proses approval.')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="inline-flex items-center justify-center w-8 h-8 rounded-md transition-all duration-200 text-slate-500 hover:text-red-500 hover:bg-red-50 dark:hover:bg-red-500/20">
                                                <i data-lucide="x" class="w-4 h-4"></i>
                                            </button>
                                        </form>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="mb-6 p-4 rounded-lg bg-slate-50 dark:bg-zink-600 text-center">
                                <p class="text-sm text-slate-500 dark:text-zink-300">Belum ada approval step</p>
                            </div>
                        @endif

                        <!-- Add New Step Form -->
                        <form action="{{ route('hr.approval-flow.store') }}" method="POST" class="space-y-3 pt-4 border-t border-slate-200 dark:border-zink-500">
                            @csrf
                            <input type="hidden" name="document_type" value="{{ $docType }}">

                            <!-- Jabatan Dropdown -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-zink-200 mb-2">Jabatan</label>
                                <select name="jabatan" id="jabatan_{{ $loop->index }}" 
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 text-sm"
                                    onchange="autoFillLabel({{ $loop->index }})">
                                    <option value="">-- Pilih Jabatan --</option>
                                    @php
                                        $labelMapping = \App\Http\Controllers\ApprovalFlowController::getLabelMapping();
                                        $jabatanList = \App\Http\Controllers\ApprovalFlowController::getValidJabatan();
                                    @endphp
                                    @foreach ($jabatanList as $jabatan)
                                        <option value="{{ $jabatan }}">{{ $labelMapping[$jabatan] ?? ucfirst($jabatan) }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <!-- Label Field (auto-fill) -->
                            <div>
                                <label class="block text-sm font-medium text-slate-700 dark:text-zink-200 mb-2">Label</label>
                                <input type="text" name="label" id="label_{{ $loop->index }}" 
                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 disabled:bg-slate-100 dark:disabled:bg-zink-600 disabled:border-slate-300 dark:disabled:border-zink-500 dark:disabled:text-zink-200 disabled:text-slate-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 text-sm"
                                    placeholder="Label akan otomatis terisi" readonly>
                            </div>

                            <!-- Add Button -->
                            <button type="submit" class="ds-btn btn-green w-full text-sm">
                                <i data-lucide="plus" class="w-4 h-4"></i>
                                Tambah Step
                            </button>
                        </form>
                    </div>
                @endforeach
            </div>

            <!-- Section: Surat Sedang Menunggu Approval -->
            @if ($waitingApprovals->count() > 0)
                <div class="ds-section rounded-lg border border-slate-200 bg-white p-6 shadow-sm dark:border-zink-500 dark:bg-zink-700">
                    <div class="mb-6 pb-4 border-b border-slate-200 dark:border-zink-500">
                        <h5 class="text-lg font-semibold text-slate-900 dark:text-white">Surat Sedang Menunggu Approval</h5>
                        <p class="text-sm text-slate-500 dark:text-zink-300 mt-1">{{ $waitingApprovals->count() }} surat yang sedang menunggu persetujuan</p>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-sm">
                            <thead>
                                <tr class="border-b border-slate-200 dark:border-zink-500">
                                    <th class="px-4 py-3 text-left font-semibold text-slate-900 dark:text-white">Nomor Surat</th>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-900 dark:text-white">Jenis Surat</th>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-900 dark:text-white">Step Saat Ini</th>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-900 dark:text-white">Jabatan</th>
                                    <th class="px-4 py-3 text-left font-semibold text-slate-900 dark:text-white">Alihkan Ke</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($waitingApprovals as $approval)
                                    @php
                                        $suratJenis = str_replace('surat_', '', $approval->document_type);
                                        $labelMapping = \App\Http\Controllers\ApprovalFlowController::getLabelMapping();
                                        $jabatanList = \App\Http\Controllers\ApprovalFlowController::getValidJabatan();
                                    @endphp
                                    <tr class="border-b border-slate-200 dark:border-zink-500 hover:bg-slate-50 dark:hover:bg-zink-600">
                                        <td class="px-4 py-4">
                                            @if ($approval->surat)
                                                <a href="{{ route('surat.show', $approval->surat->id) }}" class="text-custom-600 hover:text-custom-700 font-medium">
                                                    {{ $approval->surat->nomor_surat }}
                                                </a>
                                            @else
                                                <span class="text-slate-400">-</span>
                                            @endif
                                        </td>
                                        <td class="px-4 py-4 text-slate-900 dark:text-white capitalize">{{ ucfirst(str_replace('_', ' ', $suratJenis)) }}</td>
                                        <td class="px-4 py-4">
                                            <span class="inline-flex items-center gap-1 px-3 py-1 rounded-full bg-blue-100 text-blue-700 dark:bg-blue-500/20 dark:text-blue-400 text-xs font-medium">
                                                <i data-lucide="arrow-right" class="w-3 h-3"></i>
                                                {{ $approval->label }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-4 text-slate-900 dark:text-white">{{ $labelMapping[$approval->jabatan] ?? ucfirst($approval->jabatan) }}</td>
                                        <td class="px-4 py-4">
                                            <form action="{{ route('hr.approval-flow.reassign') }}" method="POST" class="flex items-center gap-2 flex-wrap">
                                                @csrf
                                                <input type="hidden" name="document_approval_id" value="{{ $approval->id }}">

                                                <!-- Jabatan Dropdown -->
                                                <select name="jabatan_baru" id="reassign_jabatan_{{ $approval->id }}"
                                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 text-xs py-2 px-2"
                                                    onchange="autoFillReassignLabel({{ $approval->id }})">
                                                    <option value="">-- Jabatan --</option>
                                                    @foreach ($jabatanList as $jabatan)
                                                        <option value="{{ $jabatan }}">{{ $labelMapping[$jabatan] ?? ucfirst($jabatan) }}</option>
                                                    @endforeach
                                                </select>

                                                <!-- Label Field (auto-fill) -->
                                                <input type="text" name="label_baru" id="reassign_label_{{ $approval->id }}"
                                                    class="form-input border-slate-200 dark:border-zink-500 focus:outline-none focus:border-custom-500 dark:text-zink-100 dark:bg-zink-700 dark:focus:border-custom-800 placeholder:text-slate-400 dark:placeholder:text-zink-200 text-xs py-2 px-2 w-32"
                                                    placeholder="Label" readonly>

                                                <!-- Reassign Button -->
                                                <button type="submit" class="inline-flex items-center gap-1 px-3 py-2 rounded-md bg-amber-500 hover:bg-amber-600 text-white text-xs font-medium transition-all duration-200">
                                                    <i data-lucide="shuffle" class="w-3 h-3"></i>
                                                    Alihkan
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @else
                <div class="ds-section rounded-lg border border-slate-200 bg-white p-6 shadow-sm dark:border-zink-500 dark:bg-zink-700">
                    <div class="text-center py-8">
                        <div class="mb-4 p-4 rounded-full bg-slate-100 dark:bg-zink-600 inline-flex">
                            <i data-lucide="check-circle" class="w-8 h-8 text-slate-400 dark:text-zink-300"></i>
                        </div>
                        <h3 class="text-lg font-semibold text-slate-900 dark:text-white mb-1">Semua Surat Disetujui</h3>
                        <p class="text-sm text-slate-500 dark:text-zink-300">Tidak ada surat yang sedang menunggu persetujuan</p>
                    </div>
                </div>
            @endif
        </div>
    </div>

    <!-- JavaScript for auto-fill label -->
    <script>
        const labelMapping = {
            'hod': 'Head of Department',
            'hr': 'Human Resources',
            'purchasing': 'Purchasing',
            'owner_rep': 'Owner Representative',
            'direktur': 'Direktur'
        };

        function autoFillLabel(index) {
            const jabatanSelect = document.getElementById(`jabatan_${index}`);
            const labelInput = document.getElementById(`label_${index}`);
            
            if (jabatanSelect.value && labelMapping[jabatanSelect.value]) {
                labelInput.value = labelMapping[jabatanSelect.value];
            } else {
                labelInput.value = '';
            }
        }

        function autoFillReassignLabel(approvalId) {
            const jabatanSelect = document.getElementById(`reassign_jabatan_${approvalId}`);
            const labelInput = document.getElementById(`reassign_label_${approvalId}`);
            
            if (jabatanSelect.value && labelMapping[jabatanSelect.value]) {
                labelInput.value = labelMapping[jabatanSelect.value];
            } else {
                labelInput.value = '';
            }
        }
    </script>
@endsection
