@extends('layouts.master')
@section('content')
    
    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-playfair font-bold text-[#1A2B24]">Attendance Tracker</h1>
            <p class="text-sm text-gray-500 mt-1 uppercase tracking-wider font-medium">Real-time daily attendance monitoring</p>
        </div>
        <div class="flex items-center gap-3">
            <div class="relative group">
                <i data-lucide="calendar-days" class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-[#4F6560] group-focus-within:text-[#80BB9B] transition-colors"></i>
                <input type="text" data-provider="flatpickr" data-date-format="d M, Y" data-range-date="true" readonly="readonly" placeholder="Select Date Range"
                    class="pl-10 pr-4 py-2 bg-white/50 backdrop-blur-sm border border-white/60 rounded-xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/30 transition w-64 shadow-sm font-medium text-[#1A2B24]">
            </div>
        </div>
    </div>

    {{-- Analytics Cards --}}
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        {{-- Total Employees --}}
        <div class="bg-white/70 backdrop-blur-md p-6 rounded-[32px] border border-white/40 shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-blue-50/50 flex items-center justify-center text-blue-500 group-hover:scale-110 transition-transform">
                    <i data-lucide="users" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold text-blue-400 bg-blue-50 px-2 py-1 rounded-full uppercase">Global</span>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.1em] mb-1">Total Employees</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-bold text-[#1A2B24] counter-value" data-target="{{ $totalEmployee }}">0</h3>
                    <span class="text-xs font-medium text-emerald-500">Active</span>
                </div>
            </div>
        </div>

        {{-- Absent Today --}}
        <div class="bg-white/70 backdrop-blur-md p-6 rounded-[32px] border border-white/40 shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-rose-50/50 flex items-center justify-center text-rose-500 group-hover:scale-110 transition-transform">
                    <i data-lucide="user-x" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold text-rose-400 bg-rose-50 px-2 py-1 rounded-full uppercase">Today</span>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.1em] mb-1">Absent Today</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-bold text-[#1A2B24] counter-value" data-target="{{ $absenHariIni }}">0</h3>
                    <span class="text-xs font-medium text-rose-400">Missing</span>
                </div>
            </div>
        </div>

        {{-- Present Today --}}
        <div class="bg-white/70 backdrop-blur-md p-6 rounded-[32px] border border-white/40 shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-emerald-50/50 flex items-center justify-center text-emerald-500 group-hover:scale-110 transition-transform">
                    <i data-lucide="user-check" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold text-emerald-400 bg-emerald-50 px-2 py-1 rounded-full uppercase">Live</span>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.1em] mb-1">Present Today</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-bold text-[#1A2B24] counter-value" data-target="{{ $hadirHariIni }}">0</h3>
                    <span class="text-xs font-medium text-emerald-500">Checked-in</span>
                </div>
            </div>
        </div>

        {{-- Working Days --}}
        <div class="bg-white/70 backdrop-blur-md p-6 rounded-[32px] border border-white/40 shadow-sm hover:shadow-md transition-all duration-300 group">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 rounded-2xl bg-[#80BB9B]/10 flex items-center justify-center text-[#4F6560] group-hover:scale-110 transition-transform">
                    <i data-lucide="calendar-check" class="w-6 h-6"></i>
                </div>
                <span class="text-[10px] font-bold text-[#4F6560] bg-[#80BB9B]/20 px-2 py-1 rounded-full uppercase">Month</span>
            </div>
            <div>
                <p class="text-xs font-bold text-gray-400 uppercase tracking-[0.1em] mb-1">Working Days</p>
                <div class="flex items-baseline gap-2">
                    <h3 class="text-3xl font-bold text-[#1A2B24] counter-value" data-target="{{ $hariKerja }}">0</h3>
                    <span class="text-xs font-medium text-[#4F6560]">Days</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Main Matrix Table --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-[40px] shadow-sm border border-white/60 p-8">
        <div class="flex flex-col lg:flex-row items-center justify-between mb-8 gap-6">
            <div class="flex items-center gap-6 w-full lg:w-auto">
                <div class="relative w-full sm:w-80 group">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-[#80BB9B] transition-colors"></i>
                    <input type="text" placeholder="Search employee name..." 
                        class="pl-12 pr-6 py-3 bg-white/50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition w-full shadow-sm placeholder:text-gray-400">
                </div>
                <div class="hidden sm:flex items-center gap-2 px-4 py-2 bg-[#80BB9B]/5 rounded-xl border border-[#80BB9B]/10">
                    <div class="w-2 h-2 rounded-full bg-emerald-500 animate-pulse"></div>
                    <span class="text-xs font-semibold text-[#4F6560] uppercase tracking-wider">Live Updates</span>
                </div>
            </div>
            
            <div class="flex items-center gap-2">
                <span class="text-sm font-medium text-gray-400">Period:</span>
                <span class="px-4 py-2 bg-white/50 border border-white/60 rounded-xl text-sm font-bold text-[#4F6560] shadow-sm">
                    {{ date('F Y') }}
                </span>
            </div>
        </div>

        <div class="matrix-wrapper relative overflow-hidden rounded-[24px] border border-gray-100 shadow-inner">
            <div class="overflow-x-auto custom-scrollbar">
                <table class="w-full text-sm border-separate border-spacing-0">
                    <thead>
                        <tr class="bg-gray-50/30">
                            <th class="sticky left-0 z-20 bg-[#FDFDFD] px-6 py-5 text-left font-bold text-[#4F6560] border-b border-gray-100 whitespace-nowrap shadow-[4px_0_10px_-4px_rgba(0,0,0,0.05)] uppercase tracking-widest text-[10px]">
                                Employee Details
                            </th>
                            @for($i=1; $i<=31; $i++)
                                <th class="px-3 py-5 text-center font-bold text-[11px] border-b border-gray-100 transition-colors {{ date('d') == $i ? 'bg-[#80BB9B]/10 text-[#4F6560] border-b-2 border-b-[#80BB9B]' : 'text-gray-400' }}">
                                    {{ str_pad($i, 2, '0', STR_PAD_LEFT) }}
                                </th>
                            @endfor
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        @php
                            $employees = ['Patricia Garcia', 'Tonya Johnson', 'Willie Torres', 'Jose White', 'Juliette Fecteau', 'Jonas Frederiksen', 'Kim Broberg', 'Nancy Reynolds', 'Thomas Hatfield', 'Holly Kavanaugh'];
                        @endphp
                        @foreach($employees as $name)
                        <tr class="group hover:bg-[#80BB9B]/5 transition-all duration-200">
                            <td class="sticky left-0 z-10 bg-white group-hover:bg-[#F9FBFA] px-6 py-4 font-bold text-[#1A2B24] border-b border-gray-50 whitespace-nowrap shadow-[4px_0_10px_-4px_rgba(0,0,0,0.03)] transition-colors">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center text-[10px] text-gray-500 font-bold uppercase">
                                        {{ substr($name, 0, 2) }}
                                    </div>
                                    <span>{{ $name }}</span>
                                </div>
                            </td>
                            @for($i=1; $i<=31; $i++)
                                <td class="px-3 py-4 text-center border-b border-gray-50 transition-colors {{ date('d') == $i ? 'bg-[#80BB9B]/5' : '' }}">
                                    @php $status = rand(1,15); @endphp
                                    @if($status > 4)
                                        <div class="relative group/ttd">
                                            <i data-lucide="check-circle-2" class="w-4 h-4 text-emerald-500 mx-auto opacity-70 group-hover:opacity-100 transition-all"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-[#1A2B24] text-[10px] text-white rounded opacity-0 group-hover/ttd:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-30">
                                                Present (08:00)
                                            </div>
                                        </div>
                                    @elseif($status == 2)
                                        <div class="relative group/ttd">
                                            <i data-lucide="x-circle" class="w-4 h-4 text-rose-400 mx-auto opacity-70 group-hover:opacity-100 transition-all"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-rose-600 text-[10px] text-white rounded opacity-0 group-hover/ttd:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-30">
                                                Absent
                                            </div>
                                        </div>
                                    @elseif($status == 3)
                                        <div class="relative group/ttd">
                                            <i data-lucide="clock" class="w-4 h-4 text-amber-500 mx-auto opacity-70 group-hover:opacity-100 transition-all"></i>
                                            <div class="absolute bottom-full left-1/2 -translate-x-1/2 mb-2 px-2 py-1 bg-amber-600 text-[10px] text-white rounded opacity-0 group-hover/ttd:opacity-100 pointer-events-none transition-opacity whitespace-nowrap z-30">
                                                Late
                                            </div>
                                        </div>
                                    @else
                                        <span class="text-gray-200 font-light">-</span>
                                    @endif
                                </td>
                            @endfor
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-8 flex flex-wrap gap-6 items-center text-[11px] font-bold text-gray-400 uppercase tracking-widest border-t border-gray-50 pt-6">
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-emerald-500/20 flex items-center justify-center">
                    <div class="w-1.5 h-1.5 rounded-full bg-emerald-500"></div>
                </div>
                <span>Present</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-rose-500/20 flex items-center justify-center">
                    <div class="w-1.5 h-1.5 rounded-full bg-rose-500"></div>
                </div>
                <span>Absent</span>
            </div>
            <div class="flex items-center gap-2">
                <div class="w-3 h-3 rounded-full bg-amber-500/20 flex items-center justify-center">
                    <div class="w-1.5 h-1.5 rounded-full bg-amber-500"></div>
                </div>
                <span>Late</span>
            </div>
            <div class="flex items-center gap-2">
                <span class="text-gray-300 font-normal">-</span>
                <span>Off Day</span>
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


@section('script')

@endsection
@endsection
