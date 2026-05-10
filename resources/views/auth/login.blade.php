<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — HR Sinergi Hotel & Villa</title>

    {{-- Fonts --}}
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,400;0,600;0,700;1,400&family=Poppins:wght@300;400;500;600&display=swap" rel="stylesheet">

    {{-- Lucide Icons --}}
    <script src="https://unpkg.com/lucide@latest/dist/umd/lucide.js"></script>

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

        /* Decorative circles */
        .left-panel::before {
            content: '';
            position: absolute;
            width: 500px;
            height: 500px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.15);
            top: -100px;
            right: -150px;
        }
        .left-panel::after {
            content: '';
            position: absolute;
            width: 300px;
            height: 300px;
            border-radius: 50%;
            border: 1px solid rgba(255,255,255,0.1);
            bottom: -80px;
            left: -80px;
        }

        .left-brand {
            text-align: center;
            position: relative;
            z-index: 1;
        }

        .left-brand .logo-circle {
            width: 72px;
            height: 72px;
            background: rgba(255,255,255,0.2);
            backdrop-filter: blur(8px);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 28px;
            border: 1px solid rgba(255,255,255,0.3);
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

        /* Floating card dekoratif */
        .deco-card {
            position: absolute;
            bottom: 48px;
            left: 48px;
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
        .deco-card .deco-icon {
            width: 40px;
            height: 40px;
            background: rgba(255,255,255,0.2);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        .deco-card .deco-text p {
            font-size: 13px;
            color: white;
            font-weight: 500;
            margin: 0;
        }
        .deco-card .deco-text span {
            font-size: 11px;
            color: rgba(255,255,255,0.65);
        }

        /* ── KANAN ── */
        .right-panel {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 40px;
            background: linear-gradient(135deg, #E8EDEA 0%, #D4E4DC 100%);
        }

        .login-card {
            background: #FFFFFF;
            border-radius: 28px;
            padding: 48px 44px;
            width: 100%;
            max-width: 420px;
            box-shadow: 0 4px 32px rgba(79,101,96,0.1);
        }

        .login-card .card-logo {
            width: 44px;
            height: 44px;
            background: #4F6560;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 32px;
        }

        .login-card h2 {
            font-family: 'Playfair Display', serif;
            font-size: 32px;
            font-weight: 700;
            color: #111111;
            margin-bottom: 6px;
            line-height: 1.2;
        }

        .login-card .subtitle {
            font-size: 14px;
            color: #6B7280;
            margin-bottom: 36px;
            font-weight: 300;
        }

        /* Form */
        .form-group {
            margin-bottom: 20px;
        }

        .form-group label {
            display: block;
            font-size: 12px;
            font-weight: 500;
            color: #374151;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            margin-bottom: 8px;
        }

        .input-wrap {
            position: relative;
        }

        .input-wrap .input-icon {
            position: absolute;
            left: 18px;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            pointer-events: none;
        }

        .hv-input {
            width: 100%;
            background: #F0F4F2;
            border: none;
            border-radius: 9999px;
            padding: 13px 18px 13px 46px;
            font-family: 'Poppins', sans-serif;
            font-size: 14px;
            color: #111111;
            outline: none;
            transition: box-shadow 0.2s;
        }
        .hv-input:focus {
            box-shadow: 0 0 0 2px #80BB9B;
        }
        .hv-input::placeholder { color: #9CA3AF; }

        /* Toggle password */
        .toggle-pass {
            position: absolute;
            right: 18px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #9CA3AF;
            padding: 0;
            line-height: 1;
        }

        /* Error */
        .error-msg {
            background: #fee2e2;
            border-radius: 12px;
            padding: 12px 16px;
            font-size: 13px;
            color: #991b1b;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        /* Submit button */
        .btn-login {
            width: 100%;
            background: #4F6560;
            color: white;
            border: none;
            border-radius: 9999px;
            padding: 14px 24px;
            font-family: 'Poppins', sans-serif;
            font-size: 15px;
            font-weight: 500;
            cursor: pointer;
            transition: background 0.2s, transform 0.1s;
            margin-top: 8px;
            letter-spacing: 0.01em;
        }
        .btn-login:hover { background: #3d504c; }
        .btn-login:active { transform: scale(0.99); }

        .login-footer {
            text-align: center;
            margin-top: 28px;
            font-size: 12px;
            color: #9CA3AF;
        }
        .login-footer a {
            color: #4F6560;
            text-decoration: none;
            font-weight: 500;
        }
        /* ── RESPONSIVE MOBILE ── */
@media (max-width: 768px) {
    body {
        flex-direction: column;
        height: auto;
        min-height: 100vh;
        overflow: auto;
    }

    .left-panel {
        flex: none;
        min-height: 280px;
        padding: 40px 32px;
        border-radius: 0 0 32px 32px;
    }

    .left-brand h1 {
        font-size: 36px;
    }

    .left-brand p {
        font-size: 13px;
    }

    .deco-card {
        display: none;
    }

    .left-panel::before,
    .left-panel::after {
        display: none;
    }

    .right-panel {
        flex: none;
        padding: 32px 20px 40px;
    }

    .login-card {
        padding: 36px 28px;
        border-radius: 24px;
        max-width: 100%;
    }

    .login-card h2 {
        font-size: 26px;
    }
    .left-brand img {
    width: 180px;
    height: auto;
    display: block;
    margin: 0 auto 24px;
    filter: drop-shadow(0 2px 8px rgba(0,0,0,0.15));
}
}
    </style>
</head>
<body>

    {{-- PANEL KIRI --}}
<div class="left-panel">
    <div class="left-brand">
        <img src="{{ URL::to('assets/images/Logo Sinergi putih.png') }}" 
             style="width: 180px; height: auto; margin-bottom: 24px; display: block; margin-left: auto; margin-right: auto;">
        <h1>HR <em>Sinergi</em><br>Hotel & Villa</h1>
        <p>Sistem manajemen SDM terpadu untuk operasional hotel yang lebih efisien dan terstruktur.</p>
    </div>
</div>

    {{-- PANEL KANAN --}}
    <div class="right-panel">
        <div class="login-card">

            <h2>Welcome back</h2>
            <p class="subtitle">Masuk ke akun Anda untuk melanjutkan</p>

            {{-- Error message --}}
            @if($errors->any())
            <div class="error-msg">
                <i data-lucide="alert-circle" style="width:16px;height:16px;flex-shrink:0;"></i>
                {{ $errors->first() }}
            </div>
            @endif

            @if(session('error'))
            <div class="error-msg">
                <i data-lucide="alert-circle" style="width:16px;height:16px;flex-shrink:0;"></i>
                {{ session('error') }}
            </div>
            @endif

            {{-- Form Login — JANGAN ubah action dan method --}}
            <form method="POST" action="{{ route('login') }}">
                @csrf

                {{-- Email --}}
                <div class="form-group">
                    <label for="email">Email</label>
                    <div class="input-wrap">
                        <i data-lucide="mail" class="input-icon" style="width:16px;height:16px;"></i>
                        <input
                            type="email"
                            id="email"
                            name="email"
                            class="hv-input"
                            placeholder="nama@sinergi.com"
                            value="{{ old('email') }}"
                            required
                            autocomplete="email">
                    </div>
                </div>

                {{-- Password --}}
                <div class="form-group">
                    <label for="password">Password</label>
                    <div class="input-wrap">
                        <i data-lucide="lock" class="input-icon" style="width:16px;height:16px;"></i>
                        <input
                            type="password"
                            id="password"
                            name="password"
                            class="hv-input"
                            placeholder="••••••••"
                            required
                            autocomplete="current-password">
                        <button type="button" class="toggle-pass" onclick="togglePassword()">
                            <i data-lucide="eye" id="eyeIcon" style="width:16px;height:16px;"></i>
                        </button>
                    </div>
                </div>

                <button type="submit" class="btn-login">
                    Login
                </button>
            </form>

        </div>
    </div>

    <script>
        lucide.createIcons();

        function togglePassword() {
            const input = document.getElementById('password');
            const icon  = document.getElementById('eyeIcon');
            if (input.type === 'password') {
                input.type = 'text';
                icon.setAttribute('data-lucide', 'eye-off');
            } else {
                input.type = 'password';
                icon.setAttribute('data-lucide', 'eye');
            }
            lucide.createIcons();
        }
    </script>

</body>
</html>
