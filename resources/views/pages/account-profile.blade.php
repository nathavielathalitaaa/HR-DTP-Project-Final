@extends('layouts.master')

<style>
  /* Soft UI Input Styling */
  .sng-input {
    background: var(--bg-inner);
    border: 1px solid rgba(148, 188, 163, 0.3);
    border-radius: 12px;
    padding: 10px 14px;
    box-shadow: inset 3px 3px 7px var(--sh-dark), inset -3px -3px 7px var(--sh-light);
    color: var(--text-dark);
    font-size: 14px;
  }
  .sng-input:focus {
    outline: none;
    border-color: rgba(26, 158, 92, 0.5);
  }
  .sng-input::placeholder {
    color: var(--text-muted);
  }

  /* Soft UI Button Styling */
  .sng-btn-primary {
    background: linear-gradient(135deg, #1a9e5c, #2db870);
    color: white;
    border-radius: 12px;
    padding: 10px 24px;
    font-weight: 700;
    box-shadow: 4px 4px 10px var(--sh-dark), -4px -4px 10px var(--sh-light);
    border: none;
    cursor: pointer;
    transition: all 0.2s ease;
  }
  .sng-btn-primary:hover {
    transform: translateY(-2px);
    box-shadow: 6px 6px 12px var(--sh-dark), -6px -6px 12px var(--sh-light);
  }

  /* Section Title */
  .stitle {
    font-size: 11px;
    font-weight: 700;
    letter-spacing: 0.07em;
    text-transform: uppercase;
    color: var(--text-body);
    display: flex;
    align-items: center;
    gap: 8px;
    margin-bottom: 16px;
    padding-left: 10px;
    border-left: 4px solid var(--green-main);
    border-radius: 99px;
  }

  /* TTD Preview Box */
  .ttd-preview-box {
    background: var(--bg-inner);
    border: 2px dashed rgba(148, 188, 163, 0.5);
    border-radius: 16px;
    padding: 24px;
    box-shadow: inset 3px 3px 8px var(--sh-dark), inset -3px -3px 8px var(--sh-light);
    text-align: center;
  }

  /* Warning/Danger Box */
  .sng-box-danger {
    background: #fff5f5;
    border-left: 4px solid #e11d48;
    border-radius: 12px;
    padding: 12px 16px;
    color: #831843;
  }

  /* Info Box */
  .sng-box-info {
    background: #f0fdf4;
    border-left: 4px solid var(--green-main);
    border-radius: 12px;
    padding: 12px 16px;
    color: #15803d;
  }
</style>

@section('content')
    <!-- Page-content with soft UI background -->
    <div class="group-data-[sidebar-size=lg]:ltr:md:ml-vertical-menu group-data-[sidebar-size=lg]:rtl:md:mr-vertical-menu group-data-[sidebar-size=md]:ltr:ml-vertical-menu-md group-data-[sidebar-size=md]:rtl:mr-vertical-menu-md group-data-[sidebar-size=sm]:ltr:ml-vertical-menu-sm group-data-[sidebar-size=sm]:rtl:mr-vertical-menu-sm pt-[calc(theme('spacing.header')_*_1)] pb-[calc(theme('spacing.header')_*_0.8)] px-4 group-data-[navbar=bordered]:pt-[calc(theme('spacing.header')_*_1.3)] group-data-[navbar=hidden]:pt-0 group-data-[layout=horizontal]:mx-auto group-data-[layout=horizontal]:max-w-screen-2xl group-data-[layout=horizontal]:px-0 group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:ltr:md:ml-auto group-data-[layout=horizontal]:group-data-[sidebar-size=lg]:rtl:md:mr-auto group-data-[layout=horizontal]:md:pt-[calc(theme('spacing.header')_*_1.6)] group-data-[layout=horizontal]:px-3 group-data-[layout=horizontal]:group-data-[navbar=hidden]:pt-[calc(theme('spacing.header')_*_0.9)]">
        <div class="container-fluid group-data-[content=boxed]:max-w-boxed mx-auto">

            <!-- Page Background with soft UI -->
            <div style="background: var(--bg-base); border-radius: 20px; padding: 24px; margin: 0 -10px;">

            <!-- Breadcrumb -->
            <div class="mb-6">
                <nav class="text-sm" aria-label="breadcrumb">
                    <ol class="flex items-center gap-2">
                        <li><a href="{{ route('home') }}" style="color: var(--green-main); font-weight: 600;" class="hover:opacity-80">Beranda</a></li>
                        <li style="color: var(--text-muted);">/</li>
                        <li style="color: var(--text-dark); font-weight: 600;">Profil Saya</li>
                    </ol>
                </nav>
            </div>

            <!-- Page Title -->
            <h2 style="color: var(--text-dark);" class="text-2xl font-bold mb-6">Profil Akun Saya</h2>

            <!-- Main Grid: left col = user card, right col = tabs -->
            <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">

                <!-- Left Column: User Info Card (Soft UI) -->
                <div class="lg:col-span-1">
                    <div class="ds-card">
                        <!-- Avatar Circle with Initials -->
                        @php
                            $fullName = $user->name ?? 'User';
                            $parts = explode(' ', trim($fullName));
                            $initials = '';
                            foreach ($parts as $part) {
                                if (!empty($part)) {
                                    $initials .= strtoupper(substr($part, 0, 1));
                                }
                            }
                            if (strlen($initials) > 2) {
                                $initials = substr($initials, 0, 2);
                            }
                        @endphp
                        <div class="flex justify-center mb-6">
                            @if($user->avatar)
                                <div class="w-20 h-20 rounded-full border-4 border-custom-100 overflow-hidden flex items-center justify-center bg-custom-50">
                                    <img src="{{ URL::to('assets/images/user/'.$user->avatar) }}" alt="{{ $user->name }}" class="w-full h-full object-cover">
                                </div>
                            @else
                                <div class="w-20 h-20 rounded-full border-4 border-custom-100 bg-gradient-to-br from-custom-400 to-custom-500 flex items-center justify-center">
                                    <span style="color: white;" class="text-2xl font-bold">{{ $initials }}</span>
                                </div>
                            @endif
                        </div>

                        <!-- Name -->
                        <h3 style="color: var(--text-dark);" class="text-xl font-bold text-center mb-2">
                            {{ $user->name }}
                        </h3>

                        <!-- Role Badge -->
                        @if($user->role_name)
                            <div class="flex justify-center mb-4">
                                <span class="ds-badge b-green">{{ $user->role_name }}</span>
                            </div>
                        @endif

                        <!-- Department -->
                        @if($user->position)
                            <p style="color: var(--text-body);" class="text-center text-sm font-medium mb-4">
                                {{ $user->position }}
                            </p>
                        @endif

                        <!-- Divider -->
                        <div style="border-color: rgba(26, 158, 92, 0.2);" class="border-t my-4"></div>

                        <!-- Details -->
                        <div class="space-y-4">
                            <!-- Email -->
                            <div class="flex items-start gap-3">
                                <i data-lucide="mail" style="color: var(--text-muted);" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <p style="color: var(--text-muted); font-size: 11px;" class="font-bold tracking-wider uppercase">Email</p>
                                    <p style="color: var(--text-dark);" class="text-sm break-all">{{ $user->email }}</p>
                                </div>
                            </div>

                            <!-- Phone -->
                            @if($user->phone_number)
                                <div class="flex items-start gap-3">
                                    <i data-lucide="phone" style="color: var(--text-muted);" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                                    <div>
                                        <p style="color: var(--text-muted); font-size: 11px;" class="font-bold tracking-wider uppercase">Telepon</p>
                                        <p style="color: var(--text-dark);" class="text-sm">{{ $user->phone_number }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Location -->
                            @if($user->location)
                                <div class="flex items-start gap-3">
                                    <i data-lucide="map-pin" style="color: var(--text-muted);" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                                    <div>
                                        <p style="color: var(--text-muted); font-size: 11px;" class="font-bold tracking-wider uppercase">Lokasi</p>
                                        <p style="color: var(--text-dark);" class="text-sm">{{ $user->location }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Join Date -->
                            @if($user->join_date)
                                <div class="flex items-start gap-3">
                                    <i data-lucide="calendar" style="color: var(--text-muted);" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                                    <div>
                                        <p style="color: var(--text-muted); font-size: 11px;" class="font-bold tracking-wider uppercase">Tanggal Bergabung</p>
                                        <p style="color: var(--text-dark);" class="text-sm">{{ $user->join_date->format('d M Y') }}</p>
                                    </div>
                                </div>
                            @endif

                            <!-- Status -->
                            <div class="flex items-start gap-3">
                                <i data-lucide="check-circle" style="color: var(--green-main);" class="w-4 h-4 flex-shrink-0 mt-0.5"></i>
                                <div>
                                    <p style="color: var(--text-muted); font-size: 11px;" class="font-bold tracking-wider uppercase">Status</p>
                                    <span class="ds-badge b-green">
                                        {{ ucfirst($user->status ?? 'aktif') }}
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Right Column: Tabs with Forms -->
                <div class="lg:col-span-2" x-data="{ activeTab: 'info' }">

                    <!-- Tab Navigation Buttons -->
                    <div class="flex gap-3 mb-6 overflow-x-auto pb-2">
                        <button
                            @click="activeTab = 'info'"
                            :class="activeTab === 'info' ? 'bg-custom-500 text-white font-bold' : 'text-slate-500 font-medium hover:text-slate-700'"
                            style="padding: 6px 16px; border-radius: 99px; font-size: 14px; border: none; cursor: pointer; transition: all 0.2s ease;"
                            class="whitespace-nowrap">
                            <i data-lucide="user" class="w-4 h-4 inline-block mr-2"></i>
                            Informasi Dasar
                        </button>
                        <button
                            @click="activeTab = 'ttd'"
                            :class="activeTab === 'ttd' ? 'bg-custom-500 text-white font-bold' : 'text-slate-500 font-medium hover:text-slate-700'"
                            style="padding: 6px 16px; border-radius: 99px; font-size: 14px; border: none; cursor: pointer; transition: all 0.2s ease;"
                            class="whitespace-nowrap">
                            <i data-lucide="edit" class="w-4 h-4 inline-block mr-2"></i>
                            TTD & PIN
                        </button>
                        <button
                            @click="activeTab = 'keamanan'"
                            :class="activeTab === 'keamanan' ? 'bg-custom-500 text-white font-bold' : 'text-slate-500 font-medium hover:text-slate-700'"
                            style="padding: 6px 16px; border-radius: 99px; font-size: 14px; border: none; cursor: pointer; transition: all 0.2s ease;"
                            class="whitespace-nowrap">
                            <i data-lucide="shield" class="w-4 h-4 inline-block mr-2"></i>
                            Keamanan
                        </button>
                    </div>

                    <!-- Tab Content Panels -->
                    <div class="ds-section"

                        <!-- Tab: Informasi Dasar -->
                        <div x-show="activeTab === 'info'">
                            <h4 style="color: var(--text-dark);" class="text-lg font-bold mb-6 flex items-center gap-2">
                                <span class="stitle" style="margin: 0; padding: 0; border: none;">Informasi Dasar</span>
                            </h4>
                            
                            <form action="{{ route('profile.update') }}" method="POST">
                                @csrf

                                <!-- Name -->
                                <div class="mb-6">
                                    <label for="name" style="color: var(--text-dark);" class="block text-sm font-bold mb-2">Nama Lengkap</label>
                                    <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required class="w-full sng-input"
                                        placeholder="Masukkan nama lengkap">
                                    @error('name')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Phone Number -->
                                <div class="mb-6">
                                    <label for="phone_number" style="color: var(--text-dark);" class="block text-sm font-bold mb-2">Nomor Telepon</label>
                                    <input type="text" name="phone_number" id="phone_number" value="{{ old('phone_number', $user->phone_number) }}" class="w-full sng-input"
                                        placeholder="Contoh: 08123456789">
                                    @error('phone_number')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Location -->
                                <div class="mb-6">
                                    <label for="location" style="color: var(--text-dark);" class="block text-sm font-bold mb-2">Lokasi</label>
                                    <input type="text" name="location" id="location" value="{{ old('location', $user->location) }}" class="w-full sng-input"
                                        placeholder="Masukkan lokasi">
                                    @error('location')
                                        <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                    @enderror
                                </div>

                                <!-- Submit Button -->
                                <button type="submit" class="w-full sng-btn-primary">
                                    <i data-lucide="save" class="w-4 h-4 inline-block mr-2"></i>
                                    Simpan Perubahan
                                </button>
                            </form>
                        </div>

                        <!-- Tab: Tanda Tangan & PIN -->
                        <div x-show="activeTab === 'ttd'">
                            <h4 style="color: var(--text-dark);" class="text-lg font-bold mb-6 flex items-center gap-2">
                                <span class="stitle" style="margin: 0; padding: 0; border: none;">Tanda Tangan & PIN</span>
                            </h4>

                            <!-- Section 1: TTD Upload -->
                            <div class="pb-6 mb-6" style="border-bottom: 1px solid rgba(148, 188, 163, 0.2);">
                                <h5 style="color: var(--text-dark);" class="text-md font-bold mb-4 flex items-center gap-2">
                                    <i data-lucide="pen-tool" class="w-5 h-5" style="color: var(--green-main);"></i>
                                    Tanda Tangan Digital
                                </h5>

                                <!-- Current TTD Preview -->
                                @if($profile->ttd_path)
                                    <div class="mb-6">
                                        <p style="color: var(--text-body);" class="text-sm font-medium mb-3">Tanda tangan Anda saat ini:</p>
                                        <div class="ttd-preview-box">
                                            <img src="{{ route('profile.ttd.preview') }}" alt="TTD" class="w-full max-w-xs mx-auto h-auto">
                                        </div>
                                    </div>
                                @else
                                    <div class="sng-box-danger mb-6">
                                        <i data-lucide="alert-circle" class="w-4 h-4 inline-block mr-2"></i>
                                        Belum ada tanda tangan yang diunggah
                                    </div>
                                @endif

                                <!-- Upload Form -->
                                <form action="{{ route('profile.ttd') }}" method="POST" enctype="multipart/form-data">
                                    @csrf
                                    <div class="mb-6">
                                        <label for="ttd" style="color: var(--text-dark);" class="block text-sm font-bold mb-3">Upload Tanda Tangan Baru</label>
                                        <div class="relative ttd-preview-box border-dashed hover:opacity-80 transition-opacity cursor-pointer group">
                                            <input type="file" name="ttd" id="ttd" accept=".png,.jpg,.jpeg,image/png,image/jpeg" required class="hidden" onchange="previewTtd(event)">
                                            <label for="ttd" class="cursor-pointer block">
                                                <i data-lucide="upload-cloud" class="w-8 h-8 mx-auto mb-2" style="color: var(--text-muted);"></i>
                                                <p style="color: var(--text-dark);" class="text-sm font-medium">Klik untuk memilih file</p>
                                                <p style="color: var(--text-muted);" class="text-xs mt-1">PNG, JPG, atau JPEG (Max 2MB)</p>
                                            </label>
                                        </div>
                                        @error('ttd')
                                            <p class="text-xs text-red-600 mt-2">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div class="sng-box-info mb-6">
                                        <i data-lucide="info" class="w-4 h-4 inline-block mr-2"></i>
                                        Upload file PNG transparan untuk hasil terbaik. Ukuran maksimal: 2MB.
                                    </div>

                                    <button type="submit" class="w-full sng-btn-primary">
                                        <i data-lucide="save" class="w-4 h-4 inline-block mr-2"></i>
                                        Simpan Tanda Tangan
                                    </button>
                                </form>
                            </div>

                            <!-- Section 2: PIN Setup -->
                            <div>
                                <h5 style="color: var(--text-dark);" class="text-md font-bold mb-4 flex items-center gap-2">
                                    <i data-lucide="key" class="w-5 h-5" style="color: var(--green-main);"></i>
                                    PIN Approval
                                </h5>

                                <!-- PIN Status Badge -->
                                <div class="mb-6">
                                    @if($profile->pin)
                                        <span class="ds-badge b-green">
                                            <i data-lucide="check-circle" class="w-3 h-3 inline-block mr-1"></i>
                                            PIN sudah diatur
                                        </span>
                                    @else
                                        <span class="ds-badge b-amber">
                                            <i data-lucide="alert-circle" class="w-3 h-3 inline-block mr-1"></i>
                                            Belum ada PIN
                                        </span>
                                    @endif
                                </div>

                                <!-- PIN Form -->
                                <form action="{{ route('profile.pin') }}" method="POST">
                                    @csrf

                                    <!-- Current PIN (if already set) -->
                                    @if($profile->pin)
                                        <div class="mb-6">
                                            <label for="current_pin" style="color: var(--text-dark);" class="block text-sm font-bold mb-2">PIN Lama</label>
                                            <input type="password" name="current_pin" id="current_pin" inputmode="numeric" maxlength="6" class="w-full sng-input tracking-widest"
                                                placeholder="••••••">
                                            @error('current_pin')
                                                <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                            @enderror
                                        </div>
                                    @endif

                                    <!-- New PIN -->
                                    <div class="mb-6">
                                        <label for="pin" style="color: var(--text-dark);" class="block text-sm font-bold mb-2">PIN Baru (6 digit angka)</label>
                                        <input type="password" name="pin" id="pin" inputmode="numeric" maxlength="6" required class="w-full sng-input tracking-widest"
                                            placeholder="••••••">
                                        @error('pin')
                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- PIN Confirmation -->
                                    <div class="mb-6">
                                        <label for="pin_confirmation" style="color: var(--text-dark);" class="block text-sm font-bold mb-2">Konfirmasi PIN Baru</label>
                                        <input type="password" name="pin_confirmation" id="pin_confirmation" inputmode="numeric" maxlength="6" required class="w-full sng-input tracking-widest"
                                            placeholder="••••••">
                                        @error('pin_confirmation')
                                            <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <!-- Info Box -->
                                    <div class="sng-box-info mb-6">
                                        <i data-lucide="info" class="w-4 h-4 inline-block mr-2"></i>
                                        PIN digunakan saat menyetujui dokumen. Jangan bagikan PIN Anda.
                                    </div>

                                    <button type="submit" class="w-full sng-btn-primary">
                                        <i data-lucide="save" class="w-4 h-4 inline-block mr-2"></i>
                                        Simpan PIN
                                    </button>
                                </form>
                            </div>
                        </div>

                        <!-- Tab: Keamanan -->
                        <div x-show="activeTab === 'keamanan'" class="p-6">
                            <h4 class="text-lg font-semibold text-slate-800 dark:text-zink-100 mb-6">Keamanan Akun</h4>

                            <!-- Last Login Info -->
                            <div class="rounded-lg border border-slate-200 dark:border-zink-500 p-4 mb-4 bg-slate-50 dark:bg-zink-600">
                                <div class="flex items-center gap-3">
                                    <i data-lucide="clock" class="w-5 h-5 text-slate-400 dark:text-zink-400"></i>
                                    <div>
                                        <p class="text-sm text-slate-600 dark:text-zink-300">Login Terakhir</p>
                                        <p class="text-md font-medium text-slate-800 dark:text-zink-100">
                                            Sekarang
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <!-- Change Password Section (UI only, no functionality yet) -->
                            <div class="rounded-lg border border-slate-200 dark:border-zink-500 p-4 bg-slate-50 dark:bg-zink-600">
                                <div class="flex items-start justify-between">
                                    <div class="flex items-start gap-3">
                                        <i data-lucide="key" class="w-5 h-5 text-slate-400 dark:text-zink-400 mt-0.5"></i>
                                        <div>
                                            <p class="text-sm font-medium text-slate-700 dark:text-zink-200">Ubah Kata Sandi</p>
                                            <p class="text-xs text-slate-600 dark:text-zink-400 mt-1">Perbarui kata sandi akun Anda secara berkala untuk keamanan maksimal</p>
                                        </div>
                                    </div>
                                    <button type="button" class="px-4 py-2 text-sm font-medium text-slate-700 dark:text-zink-200 bg-white dark:bg-zink-700 border border-slate-300 dark:border-zink-500 rounded-lg hover:bg-slate-50 dark:hover:bg-zink-600 transition-colors duration-200" disabled>
                                        <i data-lucide="lock" class="w-4 h-4 inline-block mr-2"></i>
                                        Coming Soon
                                    </button>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function previewTtd(event) {
            const file = event.target.files[0];
            if (file) {
                console.log('File selected:', file.name);
            }
        }
    </script>
@endsection
