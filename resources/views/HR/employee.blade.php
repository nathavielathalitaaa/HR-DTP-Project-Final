@extends('layouts.master')
@section('content')

    {{-- Header Section --}}
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between mb-8 gap-4">
        <div>
            <h1 class="text-3xl font-playfair font-bold text-[#1A2B24]">Staff Directory</h1>
            <p class="text-[13px] font-light text-[#6B7280] mt-1">Manage data and information for all Sinergi Hotel employees</p>
        </div>
        <div class="flex items-center gap-3 w-full sm:w-auto">
            <button onclick="window.print()" class="p-3 bg-white/80 backdrop-blur border border-white/60 text-[#4F6560] rounded-2xl hover:bg-white transition-all shadow-sm group">
                <i data-lucide="printer" class="w-5 h-5 group-hover:scale-110 transition-transform"></i>
            </button>
            <button onclick="document.getElementById('addEmployeeModal').classList.remove('hidden')"
                class="inline-flex items-center gap-2 px-6 py-3 bg-[#4F6560] hover:bg-[#3d504c] text-white rounded-2xl text-sm font-bold shadow-lg shadow-[#4F6560]/20 transition-all whitespace-nowrap group">
                <i data-lucide="plus" class="w-4 h-4 group-hover:rotate-90 transition-transform duration-300"></i> 
                Add New Staff
            </button>
        </div>
    </div>

    {{-- Main Container --}}
    <div class="bg-white/80 backdrop-blur-xl rounded-[40px] shadow-sm border border-white/60 p-8 overflow-hidden relative">
        {{-- Filters and Search Row --}}
        <div class="flex flex-col lg:flex-row items-center justify-between mb-8 gap-6 px-2">
            <div class="flex items-center gap-4 w-full lg:w-auto">
                <div class="relative w-full sm:w-80 group">
                    <i data-lucide="search" class="absolute left-4 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400 group-focus-within:text-[#80BB9B] transition-colors"></i>
                    <input type="text" id="empSearchInput" placeholder="Search staff by name or department..." onkeyup="filterEmployees()"
                        class="pl-12 pr-6 py-3 bg-white/50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition w-full shadow-sm placeholder:text-gray-400 font-medium">
                </div>
            </div>

            <div class="flex items-center gap-2">
                <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Total Personnel:</span>
                <span class="px-3 py-1 bg-[#80BB9B]/10 rounded-lg text-xs font-black text-[#4F6560] border border-[#80BB9B]/20">
                    {{ count($employeeList) }} Members
                </span>
            </div>
        </div>

        <div class="overflow-x-auto custom-scrollbar rounded-3xl border border-gray-100/50 shadow-inner bg-white/30">
            <table id="alternativePagination" class="w-full text-sm border-separate border-spacing-0">
                <thead>
                    <tr class="bg-gray-50/50">
                        <th class="px-6 py-5 text-left font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">No</th>
                        <th class="px-6 py-5 text-left font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">Employee Information</th>
                        <th class="px-6 py-5 text-left font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">Department & Role</th>
                        <th class="px-6 py-5 text-center font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">Status</th>
                        <th class="px-6 py-5 text-center font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">Join Date</th>
                        <th class="px-6 py-5 text-right font-bold text-[#4F6560] border-b border-gray-100 uppercase tracking-widest text-[10px]">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-50" id="empRowContainer">
                    @forelse($employeeList as $key => $employee)
                    @php
                        $fullName = $employee->name ?? '';
                        $parts = explode(' ', $fullName);
                        $first = $parts[0] ?? '';
                        $last = $parts[1] ?? '';
                        $initials = strtoupper(substr($first,0,1) . substr($last,0,1));
                        $colors = ['#80BB9B','#4F6560','#A8C5B5','#6B9E8A'];
                        $bgColor = $colors[$loop->index % count($colors)];
                    @endphp

                    {{-- Hidden data for JS compatibility --}}
                    <tr class="emp-data-row hidden"
                        data-id="{{ $employee->id }}"
                        data-photo="{{ $employee->avatar }}"
                        data-location="{{ $employee->location }}"
                        data-join-date="{{ $employee->join_date }}"
                        data-status="{{ $employee->status }}"
                        data-email="{{ $employee->email }}"
                        data-phone="{{ $employee->phone_number }}"
                        data-role="{{ $employee->role_name }}"
                        data-department="{{ $employee->department }}"
                        data-position="{{ $employee->position }}"
                        data-nik="{{ $employee->profile?->nik }}"
                        data-jabatan="{{ $employee->profile?->jabatan }}"
                        data-pendidikan="{{ $employee->profile?->pendidikan_terakhir }}"
                        data-alamat="{{ $employee->profile?->alamat }}">
                        <td class="user_id">{{ $employee->user_id }}</td>
                        <td class="name">{{ $employee->name }}</td>
                    </tr>

                    <tr class="group hover:bg-[#80BB9B]/5 transition-all duration-200 emp-searchable"
                        data-name="{{ strtolower($employee->name) }}"
                        data-dept="{{ strtolower($employee->department ?? '') }}">
                        <td class="px-6 py-4 font-medium text-gray-400">{{ ++$key }}</td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-4">
@php
  $avatarPath = $employee->avatar ? public_path('assets/images/user/' . $employee->avatar) : null;
  $hasAvatar = $avatarPath && file_exists($avatarPath);
@endphp
<div class="w-12 h-12 rounded-2xl flex items-center justify-center overflow-hidden border-2 border-white shadow-sm group-hover:scale-105 transition-transform" style="background-color: {{ $bgColor }}; color:#ffffff; font-weight:600; font-size:12px;">
    @if($hasAvatar)
        <img src="{{ URL::to('assets/images/user/'.$employee->avatar) }}" class="w-full h-full object-cover">
    @else
        {{ $initials }}
    @endif
</div>
                                <div class="flex flex-col">
                                    <span class="font-bold text-[#1A2B24]">{{ $employee->name }}</span>
                                    <span class="text-[11px] text-gray-400 font-medium">{{ $employee->email }}</span>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col gap-1">
                                <span class="font-bold text-[#4F6560]">{{ $employee->department ?? '-' }}</span>
                                @if($employee->profile?->jabatan)
                                    <span class="inline-flex w-fit px-2 py-0.5 rounded-lg text-[9px] font-black uppercase tracking-widest bg-blue-50 text-blue-600 border border-blue-100">
                                        {{ ucfirst(str_replace('_',' ', $employee->profile?->jabatan)) }}
                                    </span>
                                @endif
                            </div>
                        </td>
                        <td class="px-6 py-4 text-center">
                            @if(strtolower($employee->status) == 'aktif' || strtolower($employee->status) == 'active')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-emerald-50 text-emerald-600 border border-emerald-100">Active</span>
                            @elseif(strtolower($employee->status) == 'disable' || strtolower($employee->status) == 'inactive')
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-rose-50 text-rose-600 border border-rose-100">Inactive</span>
                            @else
                                <span class="inline-flex items-center px-3 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-gray-50 text-gray-500 border border-gray-100">{{ $employee->status }}</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-center font-bold text-gray-400">
                            {{ $employee->join_date ? \Carbon\Carbon::parse($employee->join_date)->format('d M, Y') : '-' }}
                        </td>
                        <td class="px-6 py-4 text-right">
                            <div class="flex justify-end gap-2 opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                                <a href="{{ $employee->user_id ? url('page/account/'.$employee->user_id) : '#' }}" 
                                   class="w-8 h-8 rounded-xl bg-[#80BB9B]/10 text-[#4F6560] flex items-center justify-center hover:bg-[#4F6560] hover:text-white transition-all shadow-sm" title="View Profile">
                                    <i data-lucide="eye" class="w-4 h-4"></i>
                                </a>
                               <a href="{{ url('hr/employee/'.$employee->id.'/edit') }}" 
                                    class="w-8 h-8 rounded-xl bg-blue-50 text-blue-600 flex items-center justify-center hover:bg-blue-500 hover:text-white transition-all shadow-sm" title="Edit">
                                        <i data-lucide="pencil" class="w-4 h-4"></i>
                                </a>
                                <button type="button" data-id="{{ $employee->id }}" class="deleteRecord w-8 h-8 rounded-xl bg-rose-50 text-rose-600 flex items-center justify-center hover:bg-rose-500 hover:text-white transition-all shadow-sm" title="Delete">
                                    <i data-lucide="trash-2" class="w-4 h-4"></i>
                                </button>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-400">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-full bg-gray-50 flex items-center justify-center">
                                    <i data-lucide="users" class="w-8 h-8 text-gray-200"></i>
                                </div>
                                <p class="text-sm font-medium italic">No personnel found in the directory</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Modals remain same logic but styled better --}}
    {{-- Add Employee Modal --}}
    <div id="addEmployeeModal" class="fixed inset-0 z-[1000] hidden">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('addEmployeeModal').classList.add('hidden')"></div>
        <div class="fixed inset-0 flex items-start justify-center p-4 overflow-y-auto">
            <div class="relative bg-white/95 backdrop-blur-xl rounded-[40px] shadow-2xl w-full max-w-2xl my-8 border border-white/60 overflow-hidden">
                <div class="flex items-center justify-between p-8 border-b border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-[#80BB9B]/20 flex items-center justify-center text-[#4F6560]">
                            <i data-lucide="user-plus" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h5 class="text-2xl font-playfair font-bold text-[#1A2B24]">Add New Staff</h5>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-0.5">Complete personnel information</p>
                        </div>
                    </div>
                    <button type="button" onclick="document.getElementById('addEmployeeModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-rose-50 text-gray-400 hover:text-rose-500 transition-colors">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                <form action="{{ route('hr/employee/save') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                        <div class="flex justify-center">
                            <div class="relative group">
                                <img id="addPhotoPreview" src="{{ URL::to('assets/images/profile.png') }}" class="w-28 h-28 rounded-[32px] object-cover border-4 border-white shadow-xl">
                                <label for="addPhoto" class="absolute -bottom-2 -right-2 w-10 h-10 bg-[#4F6560] rounded-2xl flex items-center justify-center cursor-pointer hover:bg-[#3d504c] transition-all shadow-lg">
                                    <i data-lucide="camera" class="w-5 h-5 text-white"></i>
                                </label>
                                <input type="file" id="addPhoto" name="profile_image" class="hidden" accept="image/*" onchange="previewPhoto(this, 'addPhotoPreview')">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div class="sm:col-span-2">
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Full Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition font-bold" placeholder="Employee's complete name" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Email Address <span class="text-rose-500">*</span></label>
                                <input type="email" name="email" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition font-medium" placeholder="name@company.com" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Phone Number</label>
                                <input type="tel" name="phone_number" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition font-medium" placeholder="08xxxxxxxx">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Access Role</label>
                                <select name="role_name" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition font-bold appearance-none">
                                    <option value="">-- Choose Role --</option>
                                    @foreach($roleName as $value)
                                    <option value="{{ $value->role_type }}">{{ $value->role_type }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Account Status</label>
                                <select name="status" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition font-bold appearance-none">
                                    <option value="">-- Select Status --</option>
                                    @foreach($statusUser as $value)
                                    <option value="{{ $value->type_name }}">{{ $value->type_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Department</label>
                                <input type="text" name="department" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition font-bold" placeholder="Department Name">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Job Position</label>
                                <select name="position" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition font-bold appearance-none">
                                    <option value="">-- Choose Position --</option>
                                    @foreach($position as $value)
                                    <option value="{{ $value->position }}">{{ $value->position }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 pt-6 border-t border-gray-100">
                            <div class="sm:col-span-2">
                                <h6 class="text-sm font-playfair font-bold text-[#1A2B24]">Identity & Security</h6>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Password <span class="text-rose-500">*</span></label>
                                <input type="password" name="password" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Confirm Password <span class="text-rose-500">*</span></label>
                                <input type="password" name="password_confirmation" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-[#80BB9B]/20 transition" required>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 p-8 border-t border-gray-100 bg-gray-50/50">
                        <button type="button" onclick="document.getElementById('addEmployeeModal').classList.add('hidden')" class="px-6 py-3 border border-gray-200 text-gray-500 rounded-2xl text-sm font-bold hover:bg-gray-100 transition-colors">Cancel</button>
                        <button type="submit" class="px-8 py-3 bg-[#4F6560] text-white rounded-2xl text-sm font-bold hover:bg-[#3d504c] shadow-lg shadow-[#4F6560]/20 transition-all">
                            <i data-lucide="save" class="w-4 h-4 inline mr-1"></i> Register Personnel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Edit Modal styled similarly --}}
    <div id="editEmployeeModal" class="fixed inset-0 z-[1000] hidden">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('editEmployeeModal').classList.add('hidden')"></div>
        <div class="fixed inset-0 flex items-start justify-center p-4 overflow-y-auto">
            <div class="relative bg-white/95 backdrop-blur-xl rounded-[40px] shadow-2xl w-full max-w-2xl my-8 border border-white/60 overflow-hidden">
                <div class="flex items-center justify-between p-8 border-b border-gray-100">
                    <div class="flex items-center gap-4">
                        <div class="w-12 h-12 rounded-2xl bg-blue-50 flex items-center justify-center text-blue-600">
                            <i data-lucide="user-cog" class="w-6 h-6"></i>
                        </div>
                        <div>
                            <h5 class="text-2xl font-playfair font-bold text-[#1A2B24]">Modify Staff Record</h5>
                            <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mt-0.5">Update personnel credentials</p>
                        </div>
                    </div>
                    <button type="button" onclick="document.getElementById('editEmployeeModal').classList.add('hidden')" class="w-10 h-10 flex items-center justify-center rounded-full hover:bg-rose-50 text-gray-400 hover:text-rose-500 transition-colors">
                        <i data-lucide="x" class="w-6 h-6"></i>
                    </button>
                </div>
                <form id="create-form" action="{{ route('hr/employee/update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="id" id="e_id">
                    <input type="hidden" name="old_photo" id="old_photo">
                    <div class="p-8 space-y-8 max-h-[70vh] overflow-y-auto custom-scrollbar">
                        <div class="flex justify-center">
                            <div class="relative group">
                                <img id="edit-photo-preview" src="{{ URL::to('assets/images/user.png') }}" class="w-28 h-28 rounded-[32px] object-cover border-4 border-white shadow-xl edit-user-profile-image">
                                <label for="edit-profile-img-file-input" class="absolute -bottom-2 -right-2 w-10 h-10 bg-blue-600 rounded-2xl flex items-center justify-center cursor-pointer hover:bg-blue-700 transition-all shadow-lg">
                                    <i data-lucide="camera" class="w-5 h-5 text-white"></i>
                                </label>
                                <input id="edit-profile-img-file-input" name="photo" type="file" class="hidden edit-profile-img-file-input" accept="image/*">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Employee ID</label>
                                <input type="text" id="e_employee_id" class="w-full px-4 py-3 bg-gray-100 border border-gray-100 rounded-2xl text-sm font-black text-gray-500 cursor-not-allowed" readonly>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Full Name <span class="text-rose-500">*</span></label>
                                <input type="text" name="name" id="e_name" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition font-bold" required>
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Email Address</label>
                                <input type="email" name="email" id="e_email" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Phone Number</label>
                                <input type="tel" name="phone_number" id="e_phone_number" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition font-medium">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Department</label>
                                <input type="text" name="department" id="e_department" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition font-bold">
                            </div>
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Job Position</label>
                                <select name="position" id="e_position" class="w-full px-4 py-3 bg-gray-50 border border-gray-100 rounded-2xl text-sm focus:outline-none focus:ring-2 focus:ring-blue-500/20 transition font-bold appearance-none">
                                    @foreach($position as $value)
                                    <option value="{{ $value->position }}">{{ $value->position }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="flex items-center justify-end gap-3 p-8 border-t border-gray-100 bg-gray-50/50">
                        <button type="button" onclick="document.getElementById('editEmployeeModal').classList.add('hidden')" class="px-6 py-3 border border-gray-200 text-gray-500 rounded-2xl text-sm font-bold hover:bg-gray-100 transition-colors">Discard</button>
                        <button type="submit" class="px-8 py-3 bg-blue-600 text-white rounded-2xl text-sm font-bold hover:bg-blue-700 shadow-lg shadow-blue-600/20 transition-all">
                            <i data-lucide="save" class="w-4 h-4 inline mr-1"></i> Update Profile
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    {{-- Delete Modal --}}
    <div id="deleteModal" class="fixed inset-0 z-[1000] hidden">
        <div class="fixed inset-0 bg-black/40 backdrop-blur-sm" onclick="document.getElementById('deleteModal').classList.add('hidden')"></div>
        <div class="fixed inset-0 flex items-center justify-center p-4">
            <div class="relative bg-white/95 backdrop-blur-xl rounded-[40px] shadow-2xl w-full max-w-sm p-8 text-center border border-white/60">
                <div class="w-20 h-20 rounded-full bg-rose-50 text-rose-500 flex items-center justify-center mx-auto mb-6">
                    <i data-lucide="alert-triangle" class="w-10 h-10"></i>
                </div>
                <h5 class="text-2xl font-playfair font-bold text-[#1A2B24] mb-2">Terminate Record</h5>
                <p class="text-sm text-gray-500 mb-8">Are you sure you want to permanently remove this employee? This action is irreversible.</p>
                <form action="{{ route('hr/employee/delete') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id_delete" id="e_idDelete">
                    <input type="hidden" name="del_photo" id="del_photo">
                    <div class="flex flex-col gap-3">
                        <button type="submit" class="w-full px-8 py-4 bg-rose-500 text-white rounded-2xl text-sm font-bold hover:bg-rose-600 shadow-lg shadow-rose-500/20 transition-all">Confirm Termination</button>
                        <button type="button" onclick="document.getElementById('deleteModal').classList.add('hidden')" class="w-full px-8 py-4 border border-gray-100 text-gray-500 rounded-2xl text-sm font-bold hover:bg-gray-50 transition-colors">Keep Record</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <style>
        .custom-scrollbar::-webkit-scrollbar {
            height: 8px; width: 6px;
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
    function filterEmployees() {
        var q = document.getElementById('empSearchInput').value.toLowerCase();
        document.querySelectorAll('.emp-searchable').forEach(function(row) {
            var name = row.getAttribute('data-name') || '';
            var dept = row.getAttribute('data-dept') || '';
            row.style.display = (name.includes(q) || dept.includes(q)) ? '' : 'none';
        });
    }

    $(document).on('click', '.editEmployee', function () {
        var empId = $(this).data('id');
        var row = document.querySelector('tr.emp-data-row[data-id="' + empId + '"]');
        if (!row) return;
        var data = row.dataset;
        var photo = data.photo || '';
        if (photo && photo !== 'profile.png') {
            $('#edit-photo-preview').attr('src', '/assets/images/user/' + photo);
        } else {
            $('#edit-photo-preview').attr('src', '/assets/images/profile.png');
        }
        $('#old_photo').val(photo);
        $('#e_id').val(data.id || '');
        $('#e_employee_id').val(row.querySelector('.user_id')?.textContent.trim() || '');
        $('#e_name').val(row.querySelector('.name')?.textContent.trim() || '');
        $('#e_email').val(data.email || '');
        $('#e_phone_number').val(data.phone || '');
        $('#e_department').val(data.department || '');
        $('#e_position').val(data.position || '');
        document.getElementById('editEmployeeModal').classList.remove('hidden');
    });

    $(document).on('click', '.deleteRecord', function () {
    var empId = $(this).data('id');
    document.getElementById('e_idDelete').value = empId;
    document.getElementById('deleteModal').classList.remove('hidden');
});

    function previewPhoto(input, previewId) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById(previewId).src = e.target.result;
            reader.readAsDataURL(input.files[0]);
        }
    }
    </script>
</div>
    <script>
        $(document).on('click', '.editEmployee', function () {
            var empId = $(this).data('id');
            // Find hidden TR that holds all data-* attributes
            var row = document.querySelector('tr.emp-data-row[data-id="' + empId + '"]');
            if (!row) return;
            
            // Ambil data dari data-* attribute di <tr>
            var data = row.dataset;
            
            // Handle photo
            var photo = data.photo || '';
            if (photo && photo !== 'profile.png') {
                $('#edit-photo-preview').attr('src', '/assets/images/user/' + photo);
            } else {
                $('#edit-photo-preview').attr('src', '/assets/images/profile.png');
            }
            $('#old_photo').val(photo);

            // Assign values
            $('#e_id').val(data.id || '');
            $('#e_employee_id').val(row.querySelector('.user_id')?.textContent.trim() || '');
            $('#e_name').val(row.querySelector('.name')?.textContent.trim() || '');
            $('#e_email').val(data.email || '');
            $('#e_position').val(data.position || '');
            $('#e_phone_number').val(data.phone || '');
            $('#e_location').val(data.location || '');
            $('#e_join_date').val(data.joinDate || '');
            $('#e_experience').val(data.experience || '');
            $('#e_designation').val(data.designation || '');
            
            // Select fields
            $('#e_department').val(data.department || '').trigger('change');
            $('#e_role_name').val(data.role || '').trigger('change');
            $('#e_status').val(data.status || '').trigger('change');
            
            // Profile fields
            $('#e_nik').val(data.nik || '');
            $('#e_no_kk').val(data.noKk || '');
            $('#e_npwp').val(data.npwp || '');
            $('#e_bpjs_kesehatan').val(data.bpjsKesehatan || '');
            $('#e_bpjs_ketenagakerjaan').val(data.bpjsKetenagakerjaan || '');
            $('#e_jabatan').val(data.jabatan || '').trigger('change');
            $('#e_pendidikan_terakhir').val(data.pendidikan || '').trigger('change');
            $('#e_status_pernikahan').val(data.statusPernikahan || '').trigger('change');
            $('#e_jumlah_anak').val(data.jumlahAnak || 0);
            $('#e_alamat').val(data.alamat || '');
            $('#e_kota').val(data.kota || '');
            $('#e_provinsi').val(data.provinsi || '');
            $('#e_kode_pos').val(data.kodePos || '');

            // Buka modal
            document.getElementById('editEmployeeModal').classList.remove('hidden');
        });

        $(document).on('click', '.deleteRecord', function () {
    var empId = $(this).data('id');
    document.getElementById('e_idDelete').value = empId;
    document.getElementById('deleteModal').classList.remove('hidden');
});
    </script>

    <script>
        //for add profile
        if (document.querySelector("#profile-img-file-input")) {
            document.querySelector("#profile-img-file-input").addEventListener("change", function () {
                var preview = document.querySelector(".user-profile-image");
                var file = document.querySelector(".profile-img-file-input").files[0];
                var reader = new FileReader();
                reader.addEventListener(
                    "load",
                    function () {
                        preview.src = reader.result;
                    },
                    false
                );
                if (file) {
                    reader.readAsDataURL(file);
                }
            });
        }
        //for edit profile
        if (document.querySelector("#edit-profile-img-file-input")) {
            document.querySelector("#edit-profile-img-file-input").addEventListener("change", function () {
                var preview = document.querySelector(".edit-user-profile-image");
                var file = document.querySelector(".edit-profile-img-file-input").files[0];
                var reader = new FileReader();
                reader.addEventListener(
                    "load",
                    function () {
                        preview.src = reader.result;
                    },
                    false
                );
                if (file) {
                    reader.readAsDataURL(file);
                }
            });
        }

        function previewPhoto(input, previewId) {
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                reader.onload = e => document.getElementById(previewId).src = e.target.result;
                reader.readAsDataURL(input.files[0]);
            }
        }
        // Tutup modal kalau klik backdrop
        document.getElementById('addEmployeeModal').addEventListener('click', function(e) {
            if (e.target === this || e.target === this.firstElementChild) {
                this.classList.add('hidden');
            }
        });
    </script>
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
});
</script>
@endpush
@endsection
