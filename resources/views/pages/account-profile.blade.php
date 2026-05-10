@extends('layouts.master')

<style>
  @import url('https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Poppins:wght@300;400;500;600&display=swap');

  .profile-page * { font-family: 'Poppins', sans-serif; }

  .bento-grid {
    display: grid;
    grid-template-columns: 1fr 1.2fr 1fr;
    gap: 24px;
    align-items: start;
  }
  @media (max-width: 1023px) {
    .bento-grid { grid-template-columns: 1fr; }
  }

  .bento-card {
    background: rgba(255,255,255,0.82);
    backdrop-filter: blur(12px);
    -webkit-backdrop-filter: blur(12px);
    border: 1px solid rgba(255,255,255,0.45);
    border-radius: 20px;
    box-shadow: 0 2px 16px rgba(0,0,0,0.06);
    padding: 24px;
  }
  @media (max-width: 639px) {
    .bento-card {
      padding: 16px;
      border-radius: 16px;
    }
  }

  .bento-card-placeholder {
    background: rgba(255,255,255,0.4);
    border: 2px dashed rgba(128,187,155,0.35);
    border-radius: 20px;
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
  }

  .card-title {
    font-family: 'Playfair Display', serif;
    font-size: 1rem;
    font-weight: 600;
    color: #1a1a1a;
    margin-bottom: 16px;
  }

  .field-label {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: #9CA3AF;
    margin-bottom: 3px;
  }

  .field-value {
    font-size: 13.5px;
    font-weight: 500;
    color: #1F2937;
  }

  .soft-input {
    width: 100%;
    background: #F4F5F7;
    border: 1px solid #E9EAEC;
    border-radius: 10px;
    padding: 9px 13px;
    font-size: 13.5px;
    color: #1F2937;
    outline: none;
    transition: border-color 0.2s;
  }
  .soft-input:focus { border-color: #80BB9B; }

  .edit-pill {
    font-size: 11px;
    font-weight: 600;
    padding: 4px 14px;
    border-radius: 999px;
    background: #F0FAF4;
    color: #4F8A6A;
    border: 1px solid #C1E4D0;
    cursor: pointer;
    transition: all 0.2s;
  }
  .edit-pill:hover { background: #80BB9B; color: #fff; border-color: #80BB9B; }

  .profile-hero {
    position: relative;
    width: 100%;
    height: 320px;
    border-radius: 20px;
    overflow: hidden;
    box-shadow: 0 8px 32px rgba(0,0,0,0.13);
  }

  .profile-hero img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    display: block;
  }
  @media (max-width: 639px) {
    .profile-hero {
      height: 280px;
    }
  }

  .profile-hero-initials {
    width: 100%;
    height: 100%;
    display: flex;
    align-items: center;
    justify-content: center;
    background: linear-gradient(135deg, #e8f5ee 0%, #c8e6d4 100%);
    font-family: 'Playfair Display', serif;
    font-size: 5rem;
    color: #4F8A6A;
    font-weight: 700;
  }

  .profile-hero-overlay {
    position: absolute;
    bottom: 0; left: 0; right: 0;
    background: linear-gradient(to top, rgba(0,0,0,0.72) 0%, transparent 100%);
    padding: 32px 24px 24px;
  }

  .profile-hero-upload {
    position: absolute;
    top: 14px; left: 14px;
    width: 36px; height: 36px;
    border-radius: 50%;
    background: rgba(255,255,255,0.88);
    display: flex; align-items: center; justify-content: center;
    cursor: pointer;
    border: none;
    box-shadow: 0 2px 8px rgba(0,0,0,0.15);
    transition: all 0.2s;
    z-index: 10;
  }
  .profile-hero-upload:hover { background: #fff; transform: scale(1.08); }

  .ttd-preview-box {
    background: #fff;
    border: 2px dashed #E5E7EB;
    border-radius: 14px;
    padding: 20px;
    text-align: center;
  }

  .sng-box-danger {
    background: #fee2e2;
    border-left: 4px solid #ef4444;
    border-radius: 10px;
    padding: 10px 14px;
    color: #991b1b;
    font-size: 13px;
  }

  .sng-box-info {
    background: #F6F6F6;
    border-left: 4px solid #80BB9B;
    border-radius: 10px;
    padding: 10px 14px;
    color: #4F6560;
    font-size: 13px;
  }

  [x-cloak] { display: none !important; }
</style>

@section('content')
<div class="profile-page">

  {{-- breadcrumb --}}
  <div class="mb-5">
    <nav class="text-sm" aria-label="breadcrumb">
      <ol class="flex items-center gap-2">
        <li><a href="{{ route('home') }}" style="color:#80BB9B;font-weight:600;" class="hover:opacity-80">Home</a></li>
        <li style="color:#6B7280;">/</li>
        <li style="color:#2C2C2A;font-weight:600;">My Profile</li>
      </ol>
    </nav>
  </div>

  {{-- page header --}}
  <div class="flex items-center justify-between mb-6">
    <h2 style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:#1a1a1a;">
      Account Profile {{ $user->id === auth()->id() ? 'Mine' : $user->name }}
    </h2>
    @if(auth()->user()->hasRole('hr') && isset($user) && $user->id !== auth()->id())
    <a href="{{ route('hr/employee/edit', $user->id) }}" class="hivi-btn-primary">
      <i data-lucide="edit" class="w-4 h-4"></i> Edit Employee Data
    </a>
    @endif
  </div>

  <div class="skeleton-wrapper w-full">
    <div class="bento-grid">
      {{-- LEFT SKELETON --}}
      <div class="flex flex-col gap-6">
        <div class="bento-card">
          <div class="skeleton h-6 w-1/2 mb-4"></div>
          <div class="space-y-4 mt-2">
            <div><div class="skeleton h-3 w-1/4 mb-2"></div><div class="skeleton h-4 w-2/3"></div></div>
            <div><div class="skeleton h-3 w-1/4 mb-2"></div><div class="skeleton h-4 w-1/2"></div></div>
          </div>
        </div>
        <div class="bento-card">
          <div class="skeleton h-6 w-1/2 mb-4"></div>
          <div class="skeleton h-32 w-full rounded-xl"></div>
        </div>
      </div>
      {{-- CENTER SKELETON --}}
      <div class="flex flex-col gap-6">
        <div class="bento-card !p-0 overflow-hidden">
          <div class="skeleton w-full h-[280px] sm:h-[320px]"></div>
        </div>
        <div class="bento-card">
          <div class="skeleton h-6 w-1/3 mb-4"></div>
          <div class="space-y-4">
             <div><div class="skeleton h-3 w-1/4 mb-2"></div><div class="skeleton h-4 w-full"></div></div>
             <div><div class="skeleton h-3 w-1/4 mb-2"></div><div class="skeleton h-4 w-3/4"></div></div>
          </div>
        </div>
      </div>
      {{-- RIGHT SKELETON --}}
      <div class="flex flex-col gap-6">
        <div class="bento-card">
          <div class="skeleton h-6 w-1/2 mb-4"></div>
          <div class="space-y-4">
            <div><div class="skeleton h-3 w-1/4 mb-2"></div><div class="skeleton h-4 w-2/3"></div></div>
            <div><div class="skeleton h-3 w-1/4 mb-2"></div><div class="skeleton h-4 w-2/3"></div></div>
            <div><div class="skeleton h-3 w-1/4 mb-2"></div><div class="skeleton h-4 w-1/2"></div></div>
          </div>
        </div>
        <div class="bento-card">
          <div class="skeleton h-6 w-1/2 mb-4"></div>
          <div class="space-y-4">
            <div class="skeleton h-10 w-full rounded-xl"></div>
            <div class="skeleton h-10 w-full rounded-xl"></div>
          </div>
        </div>
      </div>
    </div>
  </div>

  {{-- REAL CONTENT --}}
  <div class="real-content hidden w-full">
    <div class="bento-grid">

    {{-- ===== LEFT COLUMN ===== --}}
    <div class="flex flex-col gap-6">

      {{-- Account Information Card --}}
      <div class="bento-card">
        <div class="flex items-center justify-between mb-4">
          <span class="card-title" style="margin-bottom:0;">Account Information</span>
          <button type="button" class="edit-pill" onclick="document.getElementById('account-form-wrap').classList.toggle('hidden')">Edit</button>
        </div>

        {{-- read-only view --}}
        <div id="account-read-view" class="space-y-4">
          <div>
            <p class="field-label">Full Name</p>
            <p class="field-value">{{ $user->name }}</p>
          </div>
          <div>
            <p class="field-label">Phone Number</p>
            <p class="field-value">{{ $user->phone_number ?? '—' }}</p>
          </div>
        </div>

        {{-- editable form (toggle) --}}
        <div id="account-form-wrap" class="hidden mt-4">
          <form action="{{ route('profile.update', $user->id) }}" method="POST">
            @csrf
            <div class="mb-3">
              <label class="field-label block mb-1">Full Name</label>
              <input type="text" name="name" value="{{ old('name', $user->name) }}" required class="soft-input" placeholder="Full name">
              @error('name') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-3">
              <label class="field-label block mb-1">Phone Number</label>
              <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="soft-input" placeholder="08xxxxxxxxxx">
              @error('phone_number') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
              <label class="field-label block mb-1">Location</label>
              <input type="text" name="location" value="{{ old('location', $user->location) }}" class="soft-input" placeholder="Location">
              @error('location') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="hivi-btn-primary w-full">
              <i data-lucide="save" class="w-4 h-4"></i> Save Changes
            </button>
          </form>
        </div>
      </div>

      {{-- Digital Signature Card --}}
      <div class="bento-card" x-data="{ showUpload: {{ ($profile->signature_path || $profile->ttd_path) ? 'false' : 'true' }} }">
        <p class="card-title">Digital Signature</p>

        @if($profile->signature_path || $profile->ttd_path)
          <div class="ttd-preview-box mb-4">
            <img src="{{ Storage::url($profile->signature_path ?? $profile->ttd_path) }}?v={{ time() }}"
                 alt="Signature"
                 style="max-height:90px;width:auto;object-fit:contain;display:block;margin:0 auto;"
                 onerror="this.parentElement.innerHTML='<p class=\'text-xs text-gray-400\'>Preview failed</p>'">
          </div>
          <div class="flex items-center gap-3 mb-3">
            <button @click="showUpload = !showUpload" type="button" class="hivi-btn-secondary text-xs px-3 py-1.5">
              <i data-lucide="refresh-cw" class="w-3 h-3"></i>
              <span x-text="showUpload ? 'Cancel' : 'Replace'"></span>
            </button>
            <form action="{{ route('profile.signature.delete', $user->id) }}" method="POST" class="inline" onsubmit="return confirm('Delete this signature?')">
              @csrf @method('DELETE')
              <button type="submit" style="background:none;border:none;padding:0;cursor:pointer;color:#EF4444;display:flex;align-items:center;gap:4px;font-size:12px;">
                <i data-lucide="trash-2" class="w-4 h-4"></i>
              </button>
            </form>
          </div>
        @else
          <div class="sng-box-danger mb-4 flex items-center gap-2">
            <i data-lucide="alert-circle" class="w-4 h-4"></i> No signature yet
          </div>
        @endif

        <div x-show="showUpload" x-transition>
          <form action="{{ route('profile.signature.upload', $user->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="ttd-preview-box cursor-pointer hover:bg-gray-50 transition-colors mb-3">
              <input type="file" name="signature" id="signature" accept="image/png,image/jpeg,image/jpg" required class="hidden" onchange="previewSignature(event)">
              <label for="signature" class="cursor-pointer block">
                <i data-lucide="upload-cloud" class="w-7 h-7 mx-auto mb-1 text-gray-400"></i>
                <p class="text-xs font-medium text-gray-600">Click to upload</p>
              </label>
            </div>
            <div id="signature-preview-container" class="hidden mb-3">
              <div class="ttd-preview-box">
                <img id="signature-preview-img" src="" alt="Preview" style="max-height:80px;width:auto;object-fit:contain;display:block;margin:0 auto;">
              </div>
            </div>
            <button type="submit" class="hivi-btn-primary w-full text-xs">
              <i data-lucide="upload" class="w-3.5 h-3.5"></i> Upload Signature
            </button>
          </form>
        </div>

        <p class="text-center mt-3" style="font-size:10.5px;color:#9CA3AF;">Supported formats: PNG, JPG. Max size: 2MB</p>
      </div>

    </div>
    {{-- END LEFT --}}

    {{-- ===== CENTER COLUMN ===== --}}
    <div class="flex flex-col gap-6">

      {{-- Profile Hero Card --}}
      <div class="profile-hero">
        @php
          $fullName = $user->name ?? 'User';
          $parts = explode(' ', trim($fullName));
          $initials = '';
          foreach ($parts as $part) { if (!empty($part)) $initials .= strtoupper(substr($part, 0, 1)); }
          if (strlen($initials) > 2) $initials = substr($initials, 0, 2);
        @endphp

        @if($user->avatar)
          <img id="avatar-preview" src="{{ URL::to('assets/images/user/'.$user->avatar) }}" alt="{{ $user->name }}">
        @else
          <div id="avatar-initials" class="profile-hero-initials">{{ $initials }}</div>
          <img id="avatar-preview" src="" alt="{{ $user->name }}" style="display:none;width:100%;height:100%;object-fit:cover;position:absolute;top:0;left:0;">
        @endif

        {{-- upload button --}}
        <label for="photo-upload" class="profile-hero-upload" title="Change photo">
          <i data-lucide="camera" style="width:16px;height:16px;color:#4F6560;"></i>
        </label>
        <input type="file" id="photo-upload" class="hidden" accept="image/*" onchange="uploadPhoto(event)">

        {{-- overlay --}}
        <div class="profile-hero-overlay">
          <p style="font-family:'Playfair Display',serif;font-size:1.5rem;font-weight:700;color:#fff;line-height:1.2;margin-bottom:4px;">
            {{ $user->name }}
          </p>
          @if($user->role_name)
            <p style="font-size:13px;color:rgba(255,255,255,0.78);font-weight:400;">
              {{ $user->role_name }}@if($user->position) · {{ $user->position }}@endif
            </p>
          @endif
        </div>
      </div>

      {{-- Security & PIN Card --}}
      <div class="bento-card" x-data="{ showEmailForm: false, showPasswordForm: false, showPinForm: false }">
        <p class="card-title">Security & PIN</p>

        {{-- PIN --}}
        <div class="mb-5">
          <div class="flex items-center justify-between mb-3">
            <div>
              <p class="field-label" style="margin-bottom:2px;">PIN Approval</p>
              @if($profile->pin)
                <span class="text-xs text-green-600 flex items-center gap-1"><i data-lucide="check-circle" class="w-3 h-3"></i> PIN set</span>
              @else
                <span class="text-xs text-amber-600 flex items-center gap-1"><i data-lucide="alert-circle" class="w-3 h-3"></i> No PIN yet</span>
              @endif
            </div>
            <button @click="showPinForm = !showPinForm" type="button" class="edit-pill">
              <span x-text="showPinForm ? 'Close' : 'Set PIN'"></span>
            </button>
          </div>
          <form action="{{ route('profile.pin') }}" method="POST" x-show="showPinForm" x-transition class="bg-gray-50 p-4 rounded-xl border border-gray-100">
            @csrf
            @if($profile->pin)
              <div class="mb-3">
                <label class="field-label block mb-1">Current PIN</label>
                <input type="password" name="current_pin" inputmode="numeric" maxlength="6" class="soft-input tracking-widest text-center" placeholder="••••••">
                @error('current_pin') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
              </div>
            @endif
            <div class="mb-3">
              <label class="field-label block mb-1">New PIN (6 digits)</label>
              <input type="password" name="pin" inputmode="numeric" maxlength="6" required class="soft-input tracking-widest text-center" placeholder="••••••">
              @error('pin') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
              <label class="field-label block mb-1">Confirm PIN</label>
              <input type="password" name="pin_confirmation" inputmode="numeric" maxlength="6" required class="soft-input tracking-widest text-center" placeholder="••••••">
            </div>
            <button type="submit" class="hivi-btn-primary w-full">Save PIN</button>
          </form>
        </div>

        {{-- Email --}}
        <div class="border-t border-gray-100 pt-4 mb-4">
          <div class="flex items-center justify-between mb-3">
            <p class="field-label" style="margin-bottom:0;">Change Email</p>
            <button @click="showEmailForm = !showEmailForm" type="button" class="edit-pill">
              <span x-text="showEmailForm ? 'Close' : 'Edit'"></span>
            </button>
          </div>
          <form action="{{ route('profile.email') }}" method="POST" x-show="showEmailForm" x-transition class="bg-gray-50 p-4 rounded-xl border border-gray-100">
            @csrf
            <div class="mb-3">
              <label class="field-label block mb-1">New Email</label>
              <input type="email" name="email" required class="soft-input" placeholder="name@email.com">
              @error('email') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
              <label class="field-label block mb-1">Current Password</label>
              <input type="password" name="password" required class="soft-input" placeholder="••••••••">
              @error('password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <button type="submit" class="hivi-btn-primary w-full">Save Email</button>
          </form>
        </div>

        {{-- Password --}}
        <div class="border-t border-gray-100 pt-4">
          <div class="flex items-center justify-between mb-3">
            <p class="field-label" style="margin-bottom:0;">Change Password</p>
            <button @click="showPasswordForm = !showPasswordForm" type="button" class="edit-pill">
              <span x-text="showPasswordForm ? 'Close' : 'Edit'"></span>
            </button>
          </div>
          <form action="{{ route('profile.password') }}" method="POST" x-show="showPasswordForm" x-transition class="bg-gray-50 p-4 rounded-xl border border-gray-100">
            @csrf
            <div class="mb-3">
              <label class="field-label block mb-1">Current Password</label>
              <input type="password" name="current_password" required class="soft-input" placeholder="••••••••">
              @error('current_password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-3">
              <label class="field-label block mb-1">New Password</label>
              <input type="password" name="new_password" required class="soft-input" placeholder="Min. 8 characters">
              @error('new_password') <p class="text-xs text-red-600 mt-1">{{ $message }}</p> @enderror
            </div>
            <div class="mb-4">
              <label class="field-label block mb-1">Confirm New Password</label>
              <input type="password" name="new_password_confirmation" required class="soft-input" placeholder="Repeat new password">
            </div>
            <button type="submit" class="hivi-btn-primary w-full">Save Password</button>
          </form>
        </div>
      </div>

    </div>
    {{-- END CENTER --}}

    {{-- ===== RIGHT COLUMN ===== --}}
    <div class="flex flex-col gap-6">

      {{-- Employee Data Card --}}
      <div class="bento-card">
        <p class="card-title">Employee Data</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div>
            <p class="field-label">Approval Position</p>
            <p class="field-value">
              @if($profile->jabatan)
                <span class="hivi-badge hivi-badge-blue">{{ ucfirst(str_replace('_',' ',$profile->jabatan)) }}</span>
              @else —@endif
            </p>
          </div>
          <div>
            <p class="field-label">Contract End Date</p>
            <p class="field-value">{{ $profile->tgl_kontrak_akhir ? \Carbon\Carbon::parse($profile->tgl_kontrak_akhir)->format('d M Y') : '—' }}</p>
          </div>
          <div>
            <p class="field-label">Last Education</p>
            <p class="field-value">{{ $profile->pendidikan_terakhir ?? '—' }}</p>
          </div>
          <div>
            <p class="field-label">Marital Status</p>
            <p class="field-value">{{ ucfirst(str_replace('_',' ', $profile->status_pernikahan ?? '—')) }}</p>
          </div>
          <div>
            <p class="field-label">Join Date</p>
            <p class="field-value">{{ $profile->tgl_bergabung ? \Carbon\Carbon::parse($profile->tgl_bergabung)->format('d M Y') : ($user->join_date ? \Carbon\Carbon::parse($user->join_date)->format('d M Y') : '—') }}</p>
          </div>
          <div>
            <p class="field-label">Number of Children</p>
            <p class="field-value">{{ $profile->jumlah_anak ?? 0 }}</p>
          </div>
        </div>
      </div>

      {{-- Population Data Card --}}
      <div class="bento-card">
        <p class="card-title">Population Data</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
          <div>
            <p class="field-label">NIK</p>
            <p class="field-value" style="font-family:monospace;">{{ $profile->nik ?? '—' }}</p>
          </div>
          <div>
            <p class="field-label">No. KK</p>
            <p class="field-value" style="font-family:monospace;">{{ $profile->no_kk ?? '—' }}</p>
          </div>
          <div>
            <p class="field-label">NPWP</p>
            <p class="field-value" style="font-family:monospace;">{{ $profile->npwp ?? '—' }}</p>
          </div>
          <div>
            <p class="field-label">Health BPJS</p>
            <p class="field-value" style="font-family:monospace;">{{ $profile->bpjs_kesehatan ?? '—' }}</p>
          </div>
          <div>
            <p class="field-label">Employment BPJS</p>
            <p class="field-value" style="font-family:monospace;">{{ $profile->bpjs_ketenagakerjaan ?? '—' }}</p>
          </div>
        </div>
      </div>

      {{-- Address Card --}}
      <div class="bento-card">
        <p class="card-title">Address</p>
        <div class="space-y-3">
          <div>
            <p class="field-label">Full Address</p>
            <p class="field-value">{{ $profile->alamat ?? '—' }}</p>
          </div>
          <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <div>
              <p class="field-label">City</p>
              <p class="field-value">{{ $profile->kota ?? '—' }}</p>
            </div>
            <div>
              <p class="field-label">Province</p>
              <p class="field-value">{{ $profile->provinsi ?? '—' }}</p>
            </div>
            <div>
              <p class="field-label">Postal Code</p>
              <p class="field-value">{{ $profile->kode_pos ?? '—' }}</p>
            </div>
          </div>
        </div>
      </div>
    </div>
    {{-- END RIGHT --}}

  </div>
  {{-- END BENTO GRID --}}

  </div>
  {{-- END REAL CONTENT --}}

</div>

<script>
  function previewSignature(event) {
    const file = event.target.files[0];
    if (!file) return;
    if (file.size > 2 * 1024 * 1024) {
      alert('Max file size is 2MB!');
      event.target.value = '';
      document.getElementById('signature-preview-container').classList.add('hidden');
      return;
    }
    const reader = new FileReader();
    reader.onload = function(e) {
      document.getElementById('signature-preview-img').src = e.target.result;
      document.getElementById('signature-preview-container').classList.remove('hidden');
    };
    reader.readAsDataURL(file);
  }

  async function uploadPhoto(event) {
    const file = event.target.files[0];
    if (!file) return;
    const formData = new FormData();
    formData.append('photo', file);
    formData.append('_token', '{{ csrf_token() }}');
    try {
      const response = await fetch('{{ route("profile.photo") }}', {
        method: 'POST',
        body: formData,
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
      });
      const result = await response.json();
      if (result.success) {
        const preview = document.getElementById('avatar-preview');
        const initials = document.getElementById('avatar-initials');
        preview.src = result.url;
        preview.style.display = 'block';
        if (initials) initials.style.display = 'none';
        Swal.fire({ icon: 'success', title: 'Success', text: 'Profile photo updated', timer: 1500, showConfirmButton: false });
      } else {
        Swal.fire({ icon: 'error', title: 'Failed', text: result.message || 'Upload failed' });
      }
    } catch (error) {
      console.error('Upload error:', error);
      Swal.fire({ icon: 'error', title: 'Error', text: 'System error occurred' });
    }
  }
</script>
</div>{{-- /profile-page --}}
@endsection
