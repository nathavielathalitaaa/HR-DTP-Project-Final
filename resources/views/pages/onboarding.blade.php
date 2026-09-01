<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Onboarding — HR Sinergi Hotel & Villa</title>

    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>

    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }

        body {
            font-family: 'Poppins', sans-serif;
            height: 100vh;
            display: flex;
            overflow: hidden;
            background: #E8EDEA;
        }

        /* ── KIRI ── */
        .left-panel {
            flex: 1.4;
            background: linear-gradient(145deg, #C5D9CE 0%, #80BB9B 50%, #4F6560 100%);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 60px;
            position: relative;
            overflow: hidden;
        }

        .left-panel::before {
            content: '';
            position: absolute;
            width: 500px; height: 500px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.15);
            top: -100px; right: -150px;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            width: 300px; height: 300px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.1);
            bottom: -80px; left: -80px;
        }

        .left-brand {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .left-brand img {
            width: 180px;
            height: auto;
            display: block;
            margin: 0 auto 24px;
        }

        .left-brand h1 {
            font-family: 'Playfair Display', serif;
            font-size: 52px;
            font-weight: 700;
            color: white;
            line-height: 1.1;
            margin-bottom: 16px;
            letter-spacing: -0.5px;
        }

        .left-brand h1 em {
            font-style: italic;
            font-weight: 400;
        }

        .left-brand p {
            font-size: 15px;
            color: rgba(255,255,255,0.75);
            font-weight: 300;
            letter-spacing: 0.02em;
            max-width: 340px;
            line-height: 1.7;
        }

        .deco-card {
            position: absolute;
            bottom: 48px; left: 48px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(12px);
            border: 1px solid rgba(255,255,255,0.25);
            border-radius: 16px;
            padding: 16px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            z-index: 1;
        }
        .deco-icon {
            width: 40px; height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex; align-items: center; justify-content: center;
            color: white;
        }
        .deco-text p { font-size: 13px; color: white; font-weight: 500; margin: 0; }
        .deco-text span { font-size: 11px; color: rgba(255,255,255,0.65); }

        /* ── KANAN ── */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: linear-gradient(135deg, #E8EDEA 0%, #D4E4DC 100%);
            overflow-y: auto;
        }

        /* ── CARD ── */
        .ob-card {
            background: #FFFFFF;
            border-radius: 28px;
            padding: 48px 44px;
            width: 100%;
            max-width: 460px;
            box-shadow: 0 4px 32px rgba(79,101,96,0.1);
        }

        .ob-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 28px;
            font-weight: 700;
            color: #1A2B24;
            margin-bottom: 4px;
            line-height: 1.2;
        }

        .ob-card .subtitle {
            font-size: 13px;
            font-weight: 300;
            color: #6B7280;
            margin-bottom: 6px;
        }

        /* ── PROGRESS STEPS ── */
        .steps {
            display: flex;
            align-items: center;
            gap: 0;
            margin-bottom: 32px;
        }

        .step-item {
            display: flex;
            align-items: center;
            gap: 10px;
            flex: 1;
        }

        .step-item:last-child { flex: 0; }

        .step-circle {
            width: 36px; height: 36px;
            border-radius: 50%;
            border: 2px solid #E5E7EB;
            display: flex; align-items: center; justify-content: center;
            font-size: 13px; font-weight: 500;
            color: #9CA3AF;
            flex-shrink: 0;
            transition: all 0.2s;
        }

        .step-item.active .step-circle {
            background: #4F6560;
            border-color: #4F6560;
            color: white;
        }

        .step-item.done .step-circle {
            background: #80BB9B;
            border-color: #80BB9B;
            color: white;
        }

        .step-label {
            font-size: 12px;
            font-weight: 500;
            color: #9CA3AF;
        }
        .step-item.active .step-label,
        .step-item.done .step-label { color: #4F6560; }

        .step-line {
            flex: 1;
            height: 1px;
            background: #E5E7EB;
            margin: 0 12px;
        }
        .step-line.done { background: #80BB9B; }

        /* ── FORM ELEMENTS ── */
        .form-group { margin-bottom: 20px; }

        .form-group label {
            display: block;
            font-size: 11px;
            font-weight: 500;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 8px;
        }

        .hv-input {
            width: 100%;
            background: #F0F4F2;
            border: none;
            border-radius: 9999px;
            padding: 13px 20px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #1A2B24;
            outline: none;
            transition: box-shadow 0.2s;
            letter-spacing: 0.05em;
        }
        .hv-input:focus { box-shadow: 0 0 0 2px #80BB9B; }
        .hv-input::placeholder { color: #9CA3AF; letter-spacing: 0; }

        /* ── UPLOAD AREA ── */
        .upload-area {
            border: 2px dashed #D1E8DC;
            border-radius: 16px;
            padding: 32px 20px;
            text-align: center;
            cursor: pointer;
            transition: all 0.2s;
            background: #F6FAF8;
        }
        .upload-area:hover {
            border-color: #80BB9B;
            background: #EDF7F1;
        }
        .upload-area input { display: none; }
        .upload-icon {
            width: 44px; height: 44px;
            background: #E8F5EE;
            border-radius: 12px;
            display: flex; align-items: center; justify-content: center;
            margin: 0 auto 12px;
            color: #4F6560;
        }
        .upload-title {
            font-size: 14px;
            font-weight: 500;
            color: #1A2B24;
            margin-bottom: 4px;
        }
        .upload-sub {
            font-size: 12px;
            color: #6B7280;
            font-weight: 300;
        }

        /* ── PREVIEW TTD ── */
        #ttd-preview {
            max-height: 100px;
            object-fit: contain;
            margin-top: 16px;
            border-radius: 8px;
            display: none;
            margin-left: auto;
            margin-right: auto;
        }

        .ttd-saved-box {
            background: #F6FAF8;
            border: 1px solid #D1E8DC;
            border-radius: 16px;
            padding: 20px;
            text-align: center;
            margin-bottom: 24px;
        }
        .ttd-saved-box img {
            max-height: 100px;
            object-fit: contain;
            display: block;
            margin: 0 auto 10px;
        }
        .ttd-saved-label {
            font-size: 11px;
            font-weight: 500;
            color: #6B7280;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 8px;
        }
        .ttd-check {
            font-size: 12px;
            color: #4F6560;
            font-weight: 500;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
        }

        /* ── INFO BOX ── */
        .info-box {
            background: #F0F7F3;
            border-left: 3px solid #80BB9B;
            border-radius: 0 10px 10px 0;
            padding: 12px 16px;
            font-size: 12px;
            color: #4F6560;
            margin-bottom: 20px;
            font-weight: 300;
            line-height: 1.5;
        }

        /* ── ERROR ── */
        .error-msg {
            background: #fee2e2;
            border-radius: 10px;
            padding: 10px 14px;
            font-size: 12px;
            color: #991b1b;
            margin-top: 6px;
        }

        /* ── BUTTON ── */
        .btn-submit {
            width: 100%;
            background: #4F6560;
            color: white;
            border: none;
            border-radius: 9999px;
            padding: 14px 24px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            margin-top: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }
        .btn-submit:hover { background: #3d504c; }
        .btn-submit:active { transform: scale(0.99); }
        .btn-submit:disabled { opacity: 0.7; cursor: not-allowed; }

        /* ── RESPONSIVE ── */
        @media (max-width: 1023px) {
            .left-panel { flex: 1.1; padding: 40px; }
            .left-brand h1 { font-size: 42px; }
        }

        @media (max-width: 768px) {
            body { 
                flex-direction: column; 
                height: auto; 
                min-height: 100vh; 
                overflow-x: hidden;
                overflow-y: auto;
            }
            .left-panel { 
                flex: none; 
                min-height: 280px; 
                padding: 48px 24px; 
                border-radius: 0 0 40px 40px; 
                text-align: center;
            }
            .left-brand h1 { font-size: 34px; margin-bottom: 12px; }
            .left-brand p { font-size: 13px; margin: 0 auto; line-height: 1.6; }
            .left-brand img { width: 140px; margin-bottom: 20px; }
            
            .deco-card { display: none; }
            .left-panel::before, .left-panel::after { display: none; }

            .right-panel { 
                flex: none; 
                padding: 24px 16px 48px; 
                overflow-y: visible; 
            }
            .ob-card { 
                padding: 32px 20px; 
                border-radius: 24px; 
                max-width: 100%; 
                margin-top: -40px;
                position: relative;
                z-index: 10;
            }
            .ob-card h2 { font-size: 22px; }
            .ob-card .subtitle { font-size: 12px; margin-bottom: 24px; }
            
            .steps { margin-bottom: 24px; }
            .step-circle { width: 32px; height: 32px; font-size: 12px; }
            .step-label { font-size: 10px; }
            .step-line { margin: 0 8px; }

            .hv-input { padding: 12px 16px; font-size: 14px; }
            .upload-area { padding: 24px 16px; }
            .upload-icon { width: 36px; height: 36px; }
            .upload-title { font-size: 13px; }
            .upload-sub { font-size: 11px; }

            .btn-submit { padding: 12px 20px; font-size: 13px; }
        }

        @media (max-width: 380px) {
            .left-panel { min-height: 240px; padding: 36px 20px; }
            .left-brand h1 { font-size: 28px; }
            .ob-card { padding: 28px 16px; }
            .step-label { display: none; } /* Hide labels on very small screens to save space */
        }
    </style>
</head>
<body>

{{-- PANEL KIRI --}}
<div class="left-panel">
    <div class="left-brand">
        <img src="{{ asset('assets/images/Logo Sinergi putih.png') }}" alt="Sinergi">
        <h1>HR <em>Sinergi</em><br>Hotel & Villa</h1>
        <p>Sistem manajemen SDM terpadu untuk operasional hotel yang lebih efisien dan terstruktur.</p>
    </div>
    <div class="deco-card">
        <div class="deco-icon">
            <i data-lucide="shield-check" style="width:18px;height:18px;"></i>
        </div>
        <div class="deco-text">
            <p>Dokumen Aman & Terverifikasi</p>
            <span>Approval digital dengan TTD resmi</span>
        </div>
    </div>
</div>

{{-- PANEL KANAN --}}
<div class="right-panel">
    <div class="ob-card">

        <h2>Selamat Datang,<br>{{ $user->name }}</h2>
        <p class="subtitle">Lengkapi profil Anda sebelum mulai menggunakan sistem</p>
        <p style="font-family:'Poppins',sans-serif;font-size:12px;color:#6B7280;font-style:italic;margin-top:4px;margin-bottom:32px;">Onboarding diperlukan karena Anda terdaftar sebagai approver pada salah satu jenis surat di sistem. Tanda tangan digital dan PIN digunakan untuk memverifikasi persetujuan dokumen resmi.</p>

        {{-- Progress Steps --}}
        <div class="steps">
            <div class="step-item {{ $step === 'ttd' ? 'active' : ($profile->ttd_path || $profile->signature_path ? 'done' : '') }}">
                <div class="step-circle">
                    @if($profile->ttd_path || $profile->signature_path)
                        <i data-lucide="check" style="width:14px;height:14px;"></i>
                    @else
                        1
                    @endif
                </div>
                <span class="step-label">Tanda Tangan</span>
            </div>
            <div class="step-line {{ $profile->ttd_path || $profile->signature_path ? 'done' : '' }}"></div>
            <div class="step-item {{ $step === 'pin' ? 'active' : ($profile->pin ? 'done' : '') }}">
                <div class="step-circle">
                    @if($profile->pin)
                        <i data-lucide="check" style="width:14px;height:14px;"></i>
                    @else
                        2
                    @endif
                </div>
                <span class="step-label">PIN Approval</span>
            </div>
        </div>

        {{-- STEP 1: Upload TTD --}}
        @if($step === 'ttd')
        <div x-data="{ loading: false }">
            <form method="POST" action="{{ route('onboarding.ttd') }}" enctype="multipart/form-data" @submit="loading = true">
                @csrf

                <div class="form-group">
                    <label>Upload Tanda Tangan Digital</label>
                    <div class="upload-area" onclick="document.getElementById('ttd').click()">
                        <div class="upload-icon">
                            <i data-lucide="upload" style="width:20px;height:20px;"></i>
                        </div>
                        <p class="upload-title">Klik untuk upload file</p>
                        <p class="upload-sub">Format PNG, JPG, JPEG · Maks. 2MB</p>
                        <input type="file" id="ttd" name="ttd" accept=".png,.jpg,.jpeg"
                            @change="
                                const file = $el.files[0];
                                if (!file) return;
                                const reader = new FileReader();
                                reader.onload = function(e) {
                                    const img = document.getElementById('ttd-preview');
                                    img.src = e.target.result;
                                    img.style.display = 'block';
                                };
                                reader.readAsDataURL(file);
                            ">
                        <img id="ttd-preview" alt="Preview TTD">
                    </div>
                    @error('ttd')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="info-box">
                    Tanda tangan digital Anda akan digunakan pada dokumen resmi yang Anda setujui. Gunakan tanda tangan asli dengan latar belakang transparan (PNG) untuk hasil terbaik.
                </div>

                <button class="btn-submit" type="submit" :disabled="loading">
                    <span x-text="loading ? 'Menyimpan...' : 'Simpan & Lanjutkan'"></span>
                    <i data-lucide="arrow-right" style="width:16px;height:16px;" x-show="!loading"></i>
                </button>
            </form>
        </div>
        @endif

        {{-- STEP 2: Set PIN --}}
        @if($step === 'pin')
        <div x-data="{ loading: false }">
            <div class="ttd-saved-box">
                <p class="ttd-saved-label">Tanda Tangan Tersimpan</p>
                <img src="{{ route('profile.ttd.preview') }}" alt="Tanda Tangan">
                <div class="ttd-check">
                    <i data-lucide="check-circle" style="width:14px;height:14px;"></i>
                    Tanda tangan berhasil disimpan
                </div>
            </div>

            <form method="POST" action="{{ route('onboarding.pin') }}" @submit="loading = true">
                @csrf

                <div class="form-group">
                    <label>PIN Approval (6 digit)</label>
                    <input type="password" name="pin" class="hv-input"
                           maxlength="6" required placeholder="••••••"
                           style="letter-spacing: 6px; text-align: center; font-size: 18px;">
                    @error('pin')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label>Konfirmasi PIN</label>
                    <input type="password" name="pin_confirmation" class="hv-input"
                           maxlength="6" required placeholder="••••••"
                           style="letter-spacing: 6px; text-align: center; font-size: 18px;">
                    @error('pin_confirmation')
                        <div class="error-msg">{{ $message }}</div>
                    @enderror
                </div>

                <div class="info-box">
                    PIN 6 digit ini digunakan untuk mengkonfirmasi tanda tangan digital Anda saat menyetujui dokumen. Jangan bagikan PIN kepada siapapun.
                </div>

                <button class="btn-submit" type="submit" :disabled="loading">
                    <span x-text="loading ? 'Memproses...' : 'Selesai & Masuk Sistem'"></span>
                    <i data-lucide="log-in" style="width:16px;height:16px;" x-show="!loading"></i>
                </button>
            </form>
        </div>
        @endif

    </div>
</div>

<script>
    lucide.createIcons();

    // PIN: hanya angka
    document.querySelectorAll('input[maxlength="6"]').forEach(input => {
        input.addEventListener('input', () => {
            input.value = input.value.replace(/[^0-9]/g, '');
        });
        input.addEventListener('keypress', (e) => {
            if (!/[0-9]/.test(e.key)) e.preventDefault();
        });
    });
</script>

</body>
</html>