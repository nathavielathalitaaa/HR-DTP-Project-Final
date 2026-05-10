
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-playfair font-bold text-[#1A2B24]">Shift Scheduling</h1>
            <p class="text-sm text-gray-500 mt-1 uppercase tracking-wider font-medium">Assign and track employee work shifts</p>
        </div>
    </div>

    {{-- Assignment Form Card --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-[40px] shadow-sm border border-white/60 p-8 mb-8 relative overflow-hidden">
        <div class="flex items-center gap-3 mb-8">
            <div class="w-10 h-10 rounded-xl bg-[#80BB9B]/20 flex items-center justify-center text-[#4F6560]">
                <i data-lucide="user-plus" class="w-5 h-5"></i>
            </div>
            <h5 class="text-xl font-playfair font-bold text-[#1A2B24]">Assign Shift to Employee</h5>
        </div>

        <form action="{{ route('hr/shift/jadwal/store') }}" method="POST">
            @csrf
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Select Employee</label>
                    <select name="user_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition font-medium appearance-none" required>
                        <option value="">-- Choose Employee --</option>
                        @foreach($karyawanList as $karyawan)
                            <option value="{{ $karyawan->id }}">{{ $karyawan->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Choose Shift</label>
                    <select name="shift_id" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition font-medium appearance-none" required>
                        <option value="">-- Select Shift Profile --</option>
                        @foreach($shiftList as $shift)
                            <option value="{{ $shift->id }}">{{ $shift->nama_shift }} ({{ $shift->jam_masuk }} - {{ $shift->jam_keluar }})</option>
                        @endforeach
                    </select>
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest">Start Date</label>
                    <input type="date" name="tanggal_mulai" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition font-bold" required>
                </div>
                <div class="space-y-2">
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest">End Date (Optional)</label>
                    <input type="date" name="tanggal_selesai" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition font-bold">
                </div>
            </div>
            <div class="flex justify-end">
                <button type="submit" class="px-8 py-3 bg-[#4F6560] text-white rounded-2xl text-sm font-bold hover:bg-[#3d504c] shadow-lg shadow-[#4F6560]/20 transition-all flex items-center gap-2 group">
                    <i data-lucide="save" class="w-4 h-4 group-hover:scale-110 transition-transform"></i>
                    Assign Schedule
                </button>
            </div>
        </form>
    </div>

    {{-- Weekly Schedule Card --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-[40px] shadow-sm border border-white/60 p-8 overflow-hidden">
        <div class="flex flex-col sm:flex-row items-center justify-between mb-8 gap-4 px-2">
            <div>
                <h5 class="text-xl font-playfair font-bold text-[#1A2B24]">Active Weekly Schedule</h5>
                <p class="text-xs text-[#4F6560] font-black mt-1 bg-[#80BB9B]/10 px-3 py-1 rounded-lg inline-block">
                    {{ \Carbon\Carbon::parse($mulaiMinggu)->format('d M Y') }} - {{ \Carbon\Carbon::parse($akhirMinggu)->format('d M Y') }}
                </p>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar rounded-3xl border border-gray-100/50 shadow-inner bg-white/30">
            <table class="w-full text-sm border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-5 text-left font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">No</th>
                        <th class="px-6 py-5 text-left font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">Employee</th>
                        <th class="px-6 py-5 text-left font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">Assigned Shift</th>
                        <th class="px-6 py-5 text-center font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">Start Date</th>
                        <th class="px-6 py-5 text-center font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">End Date</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50">
                    @forelse($jadwalList as $key => $jadwal)
                    <tr class="group hover:bg-[#80BB9B]/5 transition-all duration-200">
                        <td class="px-6 py-4 font-medium text-gray-400">{{ ++$key }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-9 h-9 rounded-xl bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-[10px] text-gray-500 font-bold uppercase">
                                    {{ substr($jadwal->user->name ?? '?', 0, 1) }}
                                </div>
                                <span class="font-bold text-[#1A2B24]">{{ $jadwal->user->name ?? '-' }}</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-[#4F6560]/10 text-[#4F6560] rounded-lg text-xs font-black border border-[#4F6560]/20">
                                {{ $jadwal->shift->nama_shift ?? '-' }}
                            </span>
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-gray-500">
                            {{ \Carbon\Carbon::parse($jadwal->tanggal_mulai)->format('d M, Y') }}
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-gray-400 italic">
                            {{ $jadwal->tanggal_selesai ? \Carbon\Carbon::parse($jadwal->tanggal_selesai)->format('d M, Y') : 'Permanent/Ongoing' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center">
                                    <i data-lucide="calendar-days" class="w-8 h-8 text-gray-200"></i>
                                </div>
                                <p class="text-sm font-medium italic">No schedules assigned for this week</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
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

@endsection
