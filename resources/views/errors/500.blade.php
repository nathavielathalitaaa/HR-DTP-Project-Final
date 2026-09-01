<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>500 — Server Error · SinergiHRS</title>
    <link rel="shortcut icon" href="{{ asset('assets/images/favicon.ico') }}">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,700;1,400&family=Poppins:wght@300;400;500&display=swap" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        body {
            min-height: 100vh;
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #F6F6F6 0%, #E3EFE8 60%, #C5DDD0 100%);
            background-attachment: fixed;
            display: flex; align-items: center; justify-content: center;
            padding: 24px;
        }
        body::before {
            content: '';
            position: fixed; top: -120px; right: -120px;
            width: 480px; height: 480px; border-radius: 50%;
            background: radial-gradient(circle, rgba(128,187,155,0.18) 0%, transparent 70%);
            pointer-events: none;
        }
        body::after {
            content: '';
            position: fixed; bottom: -80px; left: -80px;
            width: 360px; height: 360px; border-radius: 50%;
            background: radial-gradient(circle, rgba(79,101,96,0.12) 0%, transparent 70%);
            pointer-events: none;
        }
        .card {
            background: #FFFFFF; border-radius: 28px; padding: 52px 48px;
            max-width: 480px; width: 100%;
            box-shadow: 0 4px 32px rgba(79,101,96,0.1);
            text-align: center; position: relative; z-index: 1;
        }
        .logo { width: 140px; height: auto; display: block; margin: 0 auto 36px; }
        .error-num {
            font-family: 'Playfair Display', serif;
            font-size: 100px; font-weight: 700;
            color: #4F6560; line-height: 1;
            margin-bottom: 4px; letter-spacing: -4px;
        }
        .error-num span { color: #80BB9B; }
        .divider {
            width: 48px; height: 3px;
            background: linear-gradient(90deg, #80BB9B, #4F6560);
            border-radius: 20px; margin: 20px auto;
        }
        .error-title {
            font-family: 'Playfair Display', serif;
            font-size: 22px; font-weight: 700;
            color: #1A2B24; margin-bottom: 10px;
        }
        .error-desc {
            font-size: 13px; font-weight: 300;
            color: #6B7280; line-height: 1.7; margin-bottom: 32px;
        }
        .btn-group { display: flex; gap: 10px; justify-content: center; flex-wrap: wrap; }
        .btn-primary {
            display: inline-flex; align-items: center; gap: 7px;
            background: #4F6560; color: #FFFFFF; border: none;
            border-radius: 999px; padding: 11px 24px;
            font-family: 'Poppins', sans-serif; font-size: 13px;
            font-weight: 500; text-decoration: none; cursor: pointer;
            transition: background 0.2s;
        }
        .btn-primary:hover { background: #3d504c; color: #FFFFFF; }
        .btn-outline {
            display: inline-flex; align-items: center; gap: 7px;
            background: transparent; color: #4F6560;
            border: 1px solid #D1D5DB; border-radius: 999px;
            padding: 10px 24px; font-family: 'Poppins', sans-serif;
            font-size: 13px; font-weight: 400; text-decoration: none;
            cursor: pointer; transition: all 0.2s;
        }
        .btn-outline:hover { border-color: #4F6560; color: #4F6560; }
        .btn-icon { width: 15px; height: 15px; stroke-width: 1.8; }
        .footer-note { margin-top: 28px; font-size: 11px; color: #9CA3AF; font-weight: 300; }
        @media (max-width: 480px) { .card { padding: 40px 28px; } .error-num { font-size: 72px; } }
    </style>
</head>
<body>
    <div class="card">
        <img src="{{ asset('assets/images/logo-sinergi.png') }}" alt="Sinergi Hotel & Vila" class="logo">
        <div class="error-num">5<span>0</span>0</div>
        <div class="divider"></div>
        <h1 class="error-title">Terjadi Kesalahan Server</h1>
        <p class="error-desc">
            Sistem mengalami gangguan sementara.<br>
            Coba muat ulang halaman atau hubungi HR Admin jika masalah berlanjut.
        </p>
        <div class="btn-group">
            <a href="{{ route('home') }}" class="btn-primary">
                <i data-lucide="layout-dashboard" class="btn-icon"></i>
                Kembali ke Dashboard
            </a>
            <a href="javascript:location.reload()" class="btn-outline">
                <i data-lucide="refresh-cw" class="btn-icon"></i>
                Muat Ulang
            </a>
        </div>
        <p class="footer-note">SinergiHRS · Sinergi Hotel & Vila Malang</p>
    </div>
    <script>lucide.createIcons();</script>
</body>
</html>
