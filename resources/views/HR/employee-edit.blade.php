@extends('layouts.master')
@section('content')
        <div class="flex items-center justify-between mb-8">
            <div>
                <h1 class="text-3xl font-playfair font-semibold text-[#1A2B24]">Edit Employee Data</h1>
                <p class="text-sm text-gray-500 mt-1">Update full information for {{ $user->name }}</p>
            </div>
            <a href="{{ route('hr/employee/list') }}" class="px-5 py-2.5 rounded-xl border border-gray-200 bg-white/50 hover:bg-white text-sm font-medium text-gray-600 transition shadow-sm backdrop-blur">
                <i data-lucide="arrow-left" class="w-4 h-4 inline-block mr-1"></i> Back
            </a>
        </div>

        <div style="background:rgba(255,255,255,0.8);backdrop-filter:blur(24px);border-radius:24px;padding:32px;border:1px solid rgba(255,255,255,0.4);box-shadow:0 1px 2px 0 rgba(0,0,0,0.05);">
            <form action="{{ route('hr/employee/update') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="id" value="{{ $user->id }}">
                <input type="hidden" name="old_photo" value="{{ $user->avatar }}">

                <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
                    <!-- left column: photo & basic account -->
                    <div class="lg:col-span-1 space-y-6">
                        <div class="bg-white/80 backdrop-blur rounded-3xl p-6 shadow-sm border border-white/40 text-center">
                            <p class="text-xs font-bold uppercase tracking-widest text-[#4F6560]/60 mb-4">Profile Photo</p>
                            <div class="relative inline-block">
                                <img id="edit-photo-preview" src="{{ $user->avatar ? URL::to('assets/images/user/'.$user->avatar) : URL::to('assets/images/profile.png') }}"
                                    class="w-32 h-32 rounded-full object-cover border-4 border-slate-100 mx-auto">
                                <label for="photo-input" class="absolute bottom-0 right-0 w-10 h-10 bg-[#4F6560] rounded-full flex items-center justify-center cursor-pointer hover:bg-[#3d504c] shadow-lg border-2 border-white transition">
                                    <i data-lucide="camera" class="w-5 h-5 text-white"></i>
                                </label>
                                <input id="photo-input" name="photo" type="file" class="hidden" accept="image/*" onchange="previewPhoto(this)">
                            </div>
                            <p class="text-xs text-[#4F6560]/60 mt-3">Click camera to change photo</p>
                        </div>

                        <div class="bg-white/80 backdrop-blur rounded-3xl p-6 shadow-sm border border-white/40">
                            <p class="text-xs font-bold uppercase tracking-widest text-[#4F6560]/60 mb-4">Account Status</p>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">Employee ID</label>
                                    <input type="text" value="{{ $user->user_id }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-slate-50 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]" readonly>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">Role</label>
                                    <select name="role_name" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]">
                                        @foreach($roleName as $role)
                                            <option value="{{ $role->role_type }}" {{ $user->role_name == $role->role_type ? 'selected' : '' }}>{{ $role->role_type }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">Activity Status</label>
                                    <select name="status" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]">
                                        @foreach($statusUser as $status)
                                            <option value="{{ $status->type_name }}" {{ $user->status == $status->type_name ? 'selected' : '' }}>{{ $status->type_name }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- right column: full details -->
                    <div class="lg:col-span-2 space-y-6">
                        <div class="bg-white/80 backdrop-blur rounded-3xl p-6 shadow-sm border border-white/40">
                            <p class="text-xs font-bold uppercase tracking-widest text-[#4F6560]/60 mb-4 border-b pb-2">Job Information</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">Full Name</label>
                                    <input type="text" name="name" value="{{ $user->name }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">Work Email</label>
                                    <input type="email" name="email" value="{{ $user->email }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]" required>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">Phone Number</label>
                                    <input type="tel" name="phone_number" value="{{ $user->phone_number }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">Department</label>
                                    <input type="text" name="department" value="{{ $user->department }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">Position</label>
                                    <select name="position" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]">
                                        <option value="">-- Select Position --</option>
                                        @foreach($position as $pos)
                                            <option value="{{ $pos->position }}" {{ $user->position == $pos->position ? 'selected' : '' }}>{{ $pos->position }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">Approval Role</label>
                                    <select name="jabatan" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]">
                                        <option value="">-- None --</option>
                                        <option value="hod" {{ $user->profile?->jabatan == 'hod' ? 'selected' : '' }}>Head of Department</option>
                                        <option value="hr" {{ $user->profile?->jabatan == 'hr' ? 'selected' : '' }}>Human Resources</option>
                                        <option value="purchasing" {{ $user->profile?->jabatan == 'purchasing' ? 'selected' : '' }}>Purchasing</option>
                                        <option value="owner_rep" {{ $user->profile?->jabatan == 'owner_rep' ? 'selected' : '' }}>Owner Representative</option>
                                        <option value="direktur" {{ $user->profile?->jabatan == 'direktur' ? 'selected' : '' }}>Director</option>
                                    </select>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">Join Date</label>
                                    <input type="date" name="join_date" value="{{ $user->join_date ? \Carbon\Carbon::parse($user->join_date)->format('Y-m-d') : '' }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">Last Education</label>
                                    <select name="pendidikan_terakhir" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]">
                                        <option value="">-- Select --</option>
                                        @foreach(['SD','SMP','SMA/SMK','D3','S1','S2','S3'] as $edu)
                                            <option value="{{ $edu }}" {{ $user->profile?->pendidikan_terakhir == $edu ? 'selected' : '' }}>{{ $edu }}</option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/80 backdrop-blur rounded-3xl p-6 shadow-sm border border-white/40">
                            <p class="text-xs font-bold uppercase tracking-widest text-[#4F6560]/60 mb-4 border-b pb-2">National ID Data</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div>
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">National ID (NIK)</label>
                                    <input type="text" name="nik" value="{{ $user->profile?->nik }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]" maxlength="16">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">Family Card No (KK)</label>
                                    <input type="text" name="no_kk" value="{{ $user->profile?->no_kk }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">Tax ID (NPWP)</label>
                                    <input type="text" name="npwp" value="{{ $user->profile?->npwp }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">Marital Status</label>
                                    <select name="status_pernikahan" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]">
                                        <option value="belum_menikah" {{ $user->profile?->status_pernikahan == 'belum_menikah' ? 'selected' : '' }}>Single</option>
                                        <option value="menikah" {{ $user->profile?->status_pernikahan == 'menikah' ? 'selected' : '' }}>Married</option>
                                        <option value="cerai" {{ $user->profile?->status_pernikahan == 'cerai' ? 'selected' : '' }}>Divorced</option>
                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white/80 backdrop-blur rounded-3xl p-6 shadow-sm border border-white/40">
                            <p class="text-xs font-bold uppercase trackingest text-[#4F6560]/60 mb-4 border-b pb-2">Full Address</p>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                                <div class="sm:col-span-2">
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">Address</label>
                                    <textarea name="alamat" rows="2" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]">{{ $user->profile?->alamat }}</textarea>
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">City</label>
                                    <input type="text" name="kota" value="{{ $user->profile?->kota }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-[#1A2B24] mb-1">Province</label>
                                    <input type="text" name="provinsi" value="{{ $user->profile?->provinsi }}" class="w-full px-4 py-2 rounded-xl border border-gray-200 bg-white/70 focus:outline-none focus:ring-2 focus:ring-[#80BB9B]">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center justify-end gap-3 mt-8">
                            <button type="submit" class="px-8 py-3 bg-[#4F6560] text-white rounded-xl text-md font-bold hover:bg-[#3d504c] shadow-sm transition">
                                <i data-lucide="save" class="w-5 h-5 inline mr-2"></i> Save Changes
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

<script>
    function previewPhoto(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => document.getElementById('edit-photo-preview').src = e.target.result;
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection
