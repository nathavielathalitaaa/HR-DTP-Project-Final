
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-playfair font-bold text-[#1A2B24]">Shift Configuration</h1>
            <p class="text-sm text-gray-500 mt-1 uppercase tracking-wider font-medium">Define and manage operational work hours</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <button data-modal-target="addShiftModal" type="button" class="w-full sm:w-auto px-6 py-3 bg-[#4F6560] text-white rounded-2xl text-sm font-bold hover:bg-[#3d504c] shadow-lg shadow-[#4F6560]/20 transition-all duration-300 flex items-center justify-center gap-2 group">
                <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform duration-300"></i> 
                New Shift Definition
            </button>
        </div>
    </div>

    {{-- Main Container --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-[40px] shadow-sm border border-white/60 p-8 overflow-hidden relative">
        <div class="flex flex-col lg:flex-row items-center justify-between mb-8 gap-6 px-2">
            <div>
                <h5 class="text-xl font-playfair font-bold text-[#1A2B24]">Shift Profiles</h5>
                <p class="text-xs text-gray-500 uppercase tracking-widest mt-1">Operational schedule parameters</p>
            </div>
            <div class="flex items-center gap-4 w-full lg:w-auto">
                <div class="relative w-full sm:w-64 group">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-[#80BB9B] transition-colors"></i>
                    <input type="text" id="customSearch" placeholder="Search shift name..." 
                        class="pl-12 pr-6 py-3 bg-white/50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition w-full shadow-sm placeholder:text-gray-400 font-medium">
                </div>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar rounded-3xl border border-gray-100/50 shadow-inner bg-white/30">
            <table id="alternativePagination" class="w-full text-sm border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-5 text-left font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">No</th>
                        <th class="px-6 py-5 text-left font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">Shift Profile</th>
                        <th class="px-6 py-5 text-center font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">Clock In</th>
                        <th class="px-6 py-5 text-center font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">Clock Out</th>
                        <th class="px-6 py-5 text-center font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">Tolerance</th>
                        <th class="px-6 py-5 text-left font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">Remarks</th>
                        <th class="px-6 py-5 text-right font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($shiftList as $key => $shift)
                    <tr class="group hover:bg-[#80BB9B]/5 transition-all duration-200">
                        <td class="px-6 py-4 font-medium text-gray-400">{{ ++$key }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-[10px] text-gray-500 font-bold uppercase">
                                    {{ substr($shift->nama_shift, 0, 1) }}
                                </div>
                                <span class="font-bold text-[#1A2B24]">{{ $shift->nama_shift }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-emerald-50 text-emerald-600 rounded-lg text-xs font-bold border border-emerald-100">
                                {{ $shift->jam_masuk }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <span class="px-3 py-1 bg-rose-50 text-rose-600 rounded-lg text-xs font-bold border border-rose-100">
                                {{ $shift->jam_keluar }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-black text-[#4F6560]">
                            {{ $shift->toleransi_menit }} <span class="text-[10px] text-gray-400 font-bold ml-0.5">Min</span>
                        </td>
                        <td class="px-6 py-4 text-gray-500 italic font-medium">{{ $shift->keterangan ?? '-' }}</td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <button class="w-8 h-8 rounded-xl bg-[#80BB9B]/10 text-[#4F6560] flex items-center justify-center hover:bg-[#4F6560] hover:text-white transition-all shadow-sm" data-modal-target="addShiftModal" onclick="editShift({{ $shift->id }}, '{{ $shift->nama_shift }}', '{{ $shift->jam_masuk }}', '{{ $shift->jam_keluar }}', {{ $shift->toleransi_menit }}, '{{ $shift->keterangan }}')">
                                    <i data-lucide="pencil" class="w-4 h-4"></i>
                                </button>
                                <button class="w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm" data-modal-target="deleteModal" onclick="deleteShift({{ $shift->id }})">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center">
                                    <i data-lucide="clock" class="w-8 h-8 text-gray-200"></i>
                                </div>
                                <p class="text-sm font-medium italic">No shift profiles defined yet</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modals --}}
    <div id="addShiftModal" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[32rem] bg-white/95 backdrop-blur-xl shadow-2xl rounded-[32px] border border-white/60 overflow-hidden">
            <div class="flex items-center justify-between p-6 border-b border-gray-100">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#80BB9B]/20 flex items-center justify-center text-[#4F6560]">
                        <i data-lucide="clock" class="w-5 h-5"></i>
                    </div>
                    <h5 class="text-lg font-playfair font-bold text-[#1A2B24]" id="modalTitle">Define Work Shift</h5>
                </div>
                <button data-modal-close="addShiftModal" class="w-8 h-8 flex items-center justify-center rounded-full hover:bg-rose-50 text-gray-400 hover:text-rose-500 transition-colors">
                    <i data-lucide="x" class="w-5 h-5"></i>
                </button>
            </div>
            <div class="p-8">
                <form action="{{ route('hr/shift/store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_update" id="id_update" value="">
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Shift Profile Name</label>
                            <input type="text" name="nama_shift" id="nama_shift" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition font-medium" placeholder="e.g. Morning Shift" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Clock In Time</label>
                            <input type="time" name="jam_masuk" id="jam_masuk" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition font-bold" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Clock Out Time</label>
                            <input type="time" name="jam_keluar" id="jam_keluar" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition font-bold" required>
                        </div>
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Tolerance (Min)</label>
                            <input type="number" name="toleransi_menit" id="toleransi_menit" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition font-black" min="0" max="60" required>
                        </div>
                        <div class="sm:col-span-2">
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Description / Remarks</label>
                            <input type="text" name="keterangan" id="keterangan" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition" placeholder="Optional notes...">
                        </div>
                    </div>
                    <div class="flex justify-end gap-3 mt-10">
                        <button type="reset" data-modal-close="addShiftModal" class="px-6 py-3 border border-gray-100 text-gray-500 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-colors">Cancel</button>
                        <button type="submit" class="px-8 py-3 bg-[#4F6560] text-white rounded-2xl text-sm font-bold hover:bg-[#3d504c] shadow-lg shadow-[#4F6560]/20 transition-all">Save Profile</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div id="deleteModal" modal-center="" class="fixed flex flex-col hidden transition-all duration-300 ease-in-out left-2/4 z-drawer -translate-x-2/4 -translate-y-2/4 show">
        <div class="w-screen md:w-[28rem] bg-white/95 backdrop-blur-xl shadow-2xl rounded-[32px] border border-white/60 p-8 text-center">
            <div class="w-20 h-20 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center mx-auto mb-6">
                <i data-lucide="alert-triangle" class="w-10 h-10"></i>
            </div>
            <h5 class="text-xl font-playfair font-bold text-[#1A2B24] mb-2">Confirm Deletion</h5>
            <p class="text-sm text-gray-500 mb-8">Are you sure you want to permanently remove this shift profile? This action cannot be undone.</p>
            <div class="flex justify-center gap-3">
                <button type="button" data-modal-close="deleteModal" class="px-6 py-3 border border-gray-100 text-gray-500 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-colors">No, Keep It</button>
                <form id="deleteForm" action="{{ route('hr/shift/delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_delete" id="id_delete_shift" value="">
                    <button type="submit" class="px-8 py-3 bg-rose-500 text-white rounded-2xl text-sm font-bold hover:bg-rose-600 shadow-lg shadow-rose-500/20 transition-all">Yes, Delete</button>
                </form>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 8px;
        }
        .custom-scrollbar::-webkit-scrollbar-track {
            background: rgba(0,0,0,0.02);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(128, 187, 155, 0.2);
            border-radius: 10px;
        }
        .custom-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(128, 187, 155, 0.4);
        }
    </style>

    <script>
        function editShift(id, namaShift, jamMasuk, jamKeluar, toleransi, keterangan) {
            document.getElementById('id_update').value = id;
            document.getElementById('nama_shift').value = namaShift;
            document.getElementById('jam_masuk').value = jamMasuk;
            document.getElementById('jam_keluar').value = jamKeluar;
            document.getElementById('toleransi_menit').value = toleransi;
            document.getElementById('keterangan').value = keterangan;
            document.getElementById('modalTitle').textContent = 'Modify Shift Profile';
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
        const table = new DataTable('#alternativePagination', {
            pagingType: 'full_numbers',
            pageLength: 10,
            dom: 'tip',
            columnDefs: [
                { orderable: false, targets: [0, 6] }
            ],
            language: {
                emptyTable: 'No shifts found',
                search: 'Search:',
                lengthMenu: 'Show _MENU_ entries',
                info: 'Showing _START_ to _END_ of _TOTAL_ entries',
                paginate: { first: 'First', last: 'Last', next: 'Next', previous: 'Previous' }
            }
        });

        const searchInput = document.getElementById('customSearch');
        if (searchInput) {
            searchInput.addEventListener('keyup', function() {
                table.search(this.value).draw();
            });
        }
    }
});
</script>
@endpush
