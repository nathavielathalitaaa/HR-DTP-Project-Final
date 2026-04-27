<!DOCTYPE html>
<html lang="id" class="light scroll-smooth">
<head>
    <meta charset="utf-8">
    <title>Onboarding - HR Sinergi</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="shortcut icon" href="{{ URL::to('assets/images/favicon.ico') }}">
    <link rel="stylesheet" href="{{ URL::to('assets/css/starcode2.css') }}">

    <style>
        :root {
            --bg-base: #e8f2eb;
            --bg-inner: #ebf5ee;
            --green-main: #1a9e5c;
            --text-dark: #14321f;
            --text-body: #3d6650;
            --text-muted: #7aaa8e;
        }

        * {
            font-family: 'Plus Jakarta Sans', sans-serif;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            background: var(--bg-base);
        }

        /* ===== LAYOUT ===== */
        .layout {
            display: grid;
            grid-template-columns: 1fr;
            min-height: 100vh;
        }

        @media (min-width: 1024px) {
            .layout {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* ===== LEFT (DESKTOP) ===== */
        .left {
            display: none;
            background: linear-gradient(135deg, #1a9e5c, #2db870);
            color: white;
            padding: 60px;
            align-items: center;
            justify-content: center;
        }

        .left-content {
            max-width: 400px;
        }

        .left img {
            height: 50px;
            margin-bottom: 20px;
        }

        @media (min-width: 1024px) {
            .left {
                display: flex;
            }
        }

        /* ===== RIGHT ===== */
        .right {
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        /* ===== CARD ===== */
        .card {
            width: 100%;
            max-width: 520px;
            background: white;
            border-radius: 20px;
            padding: 40px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.08);
        }

        @media (max-width: 640px) {
            .card {
                border-radius: 0;
                min-height: 100vh;
                padding: 24px;
            }
        }

        /* TEXT */
        .title {
            text-align: center;
            font-size: 24px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .subtitle {
            text-align: center;
            font-size: 14px;
            margin-bottom: 30px;
            color: var(--text-body);
        }

        /* PROGRESS */
        .steps {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .step {
            text-align: center;
            flex: 1;
        }

        .circle {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            border: 2px solid #ccc;
            margin: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
        }

        .active .circle,
        .done .circle {
            background: var(--green-main);
            color: white;
            border-color: var(--green-main);
        }

        /* FORM */
        .form-group {
            margin-bottom: 20px;
        }

        .label {
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 6px;
            display: block;
        }

        .input {
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            border: 1px solid #ccc;
            background: var(--bg-inner);
        }

        /* UPLOAD */
        .upload {
            border: 2px dashed #ccc;
            border-radius: 14px;
            padding: 30px;
            text-align: center;
            cursor: pointer;
        }

        .upload:hover {
            border-color: var(--green-main);
        }

        .upload input {
            display: none;
        }

        /* BUTTON */
        .btn {
            width: 100%;
            padding: 12px;
            border-radius: 12px;
            border: none;
            background: linear-gradient(135deg, #1a9e5c, #2db870);
            color: white;
            font-weight: bold;
            cursor: pointer;
        }

        /* INFO */
        .info {
            background: #f0fdf4;
            padding: 10px;
            border-left: 4px solid var(--green-main);
            font-size: 12px;
            margin: 10px 0;
        }

        .error {
            color: red;
            font-size: 12px;
        }

        /* TTD Preview */
        .ttd-preview-box {
            background: var(--bg-inner);
            border: 2px dashed rgba(26, 158, 92, 0.3);
            border-radius: 12px;
            padding: 16px;
            text-align: center;
            margin-bottom: 20px;
        }

        .ttd-preview-box img {
            max-height: 120px;
            object-fit: contain;
            display: block;
            margin: auto;
        }

        .ttd-preview-label {
            font-size: 12px;
            color: var(--text-muted);
            margin-bottom: 8px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            font-weight: 600;
        }

        .ttd-check {
            color: var(--green-main);
            font-weight: 600;
            font-size: 12px;
            margin-top: 8px;
        }

        #ttd-preview {
            max-height: 100px;
            object-fit: contain;
            margin-top: 10px;
            border-radius: 8px;
            display: none;
        }

        button:disabled {
            opacity: 0.7;
            cursor: not-allowed;
        }
    </style>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body>

<div class="layout">

    <!-- LEFT -->
    <div class="left">
        <div class="left-content">
            <img src="{{ URL::to('assets\images\Logo Sinergi putih.png') }}">
            <h2>HR Sinergi</h2>
            <p>Kelola dokumen dengan aman dan efisien</p>
        </div>
    </div>

    <!-- RIGHT -->
    <div class="right">
        <div class="card">

            <h1 class="title">Selamat Datang, {{ $user->name }}</h1>
            <p class="subtitle">Lengkapi profil Anda</p>

            <!-- PROGRESS -->
            <div class="steps">
                <div class="step {{ $step === 'ttd' ? 'active' : ($profile->ttd_path ? 'done' : '') }}">
                    <div class="circle">1</div>
                    <small>TTD</small>
                </div>
                <div class="step {{ $step === 'pin' ? 'active' : ($profile->pin ? 'done' : '') }}">
                    <div class="circle">2</div>
                    <small>PIN</small>
                </div>
            </div>

            <!-- STEP 1 -->
            @if($step === 'ttd')
            <div x-data="{ loading: false }">
                <form method="POST" action="{{ route('onboarding.ttd') }}" enctype="multipart/form-data" @submit="loading = true">
                    @csrf

                    <div class="form-group">
                        <label class="label">Upload TTD</label>

                        <div class="upload" onclick="document.getElementById('ttd').click()">
                            Klik untuk upload PNG
                            <input type="file" id="ttd" name="ttd" accept=".png" @change="
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
                        </div>

                        <img id="ttd-preview" alt="Preview">

                        @error('ttd')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="info">
                        TTD digunakan untuk dokumen resmi
                    </div>

                    <button class="btn" type="submit" :disabled="loading" x-text="loading ? 'Menyimpan...' : 'Simpan Tanda Tangan →'"></button>
                </form>
            </div>
            @endif

            <!-- STEP 2 -->
            @if($step === 'pin')
            <div x-data="{ loading: false }">
                <div class="ttd-preview-box">
                    <div class="ttd-preview-label">Tanda Tangan Tersimpan</div>
                    <img src="{{ route('profile.ttd.preview') }}" alt="Tanda Tangan" style="max-height:120px; object-fit:contain;">
                    <div class="ttd-check">✓ Tanda tangan tersimpan dengan baik</div>
                </div>

                <form method="POST" action="{{ route('onboarding.pin') }}" @submit="loading = true">
                    @csrf

                    <div class="form-group">
                        <label class="label">PIN (6 digit)</label>
                        <input type="password" name="pin" class="input" maxlength="6" required>
                        @error('pin')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="form-group">
                        <label class="label">Konfirmasi PIN</label>
                        <input type="password" name="pin_confirmation" class="input" maxlength="6" required>
                        @error('pin_confirmation')
                            <div class="error">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="info">
                        PIN digunakan untuk approval dokumen. Jangan bagikan PIN Anda.
                    </div>

                    <button class="btn" type="submit" :disabled="loading" x-text="loading ? 'Memproses...' : 'Selesai & Masuk Sistem →'"></button>
                </form>
            </div>
            @endif

        </div>
    </div>

</div>

<script>
// PIN input: only allow numbers
document.querySelectorAll('input[maxlength="6"]').forEach(input => {
    input.addEventListener('input', () => {
        input.value = input.value.replace(/[^0-9]/g, '');
    });

    input.addEventListener('keypress', (e) => {
        if (!/[0-9]/.test(e.key)) {
            e.preventDefault();
        }
    });
});
</script>

</body>
</html>