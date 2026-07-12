<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin — Kantin Al-Amanah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: #F8F5F2;
            color: #BA797D;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
        }

        .card {
            position: relative; z-index: 1;
            background: #F8F5F2;
            border: 1px solid #F9E6A7;
            border-radius: 28px;
            padding: 2.75rem 2.25rem;
            width: 100%; max-width: 420px;
            animation: rise 0.7s cubic-bezier(.34,1.56,.64,1) both;
        }
        @keyframes rise {
            from { opacity: 0; transform: translateY(30px) scale(0.97); }
            to   { opacity: 1; transform: translateY(0) scale(1); }
        }

        .card-header { text-align: center; margin-bottom: 2rem; }
        .admin-icon {
            display: inline-flex; align-items: center; justify-content: center;
            width: 72px; height: 72px; border-radius: 20px;
            background: rgba(186,121,125,0.15);
            border: 1px solid rgba(186,121,125,0.3);
            font-size: 2rem; margin-bottom: 1.25rem;
        }
        .card-header h1 { font-size: 1.6rem; font-weight: 800; margin-bottom: 0.3rem; color: #2C2526; }
        .card-header p { font-size: 0.88rem; color: #96A480; }
        .card-header h1 { font-size: 1.6rem; font-weight: 800; margin-bottom: 0.3rem; color: #BA797D; }

        /* Alert */
        .alert {
            display: flex; align-items: flex-start; gap: 0.6rem;
            padding: 0.75rem 1rem;
            border-radius: 12px;
            font-size: 0.83rem;
            margin-bottom: 1.25rem;
            line-height: 1.5;
        }
        .alert-error { background: rgba(186,121,125,0.1); border: 1px solid #F9E6A7; color: #BA797D; }
        .alert-success { background: rgba(150,164,128,0.1); border: 1px solid #F9E6A7; color: #96A480; }

        /* Form */
        .form-group { margin-bottom: 1.1rem; }
        .form-label {
            display: block;
            font-size: 0.8rem; font-weight: 600;
            color: #96A480;
            margin-bottom: 0.45rem;
            letter-spacing: 0.04em;
        }
        .input-wrap { position: relative; }
        .input-icon {
            position: absolute; left: 0.9rem; top: 50%; transform: translateY(-50%);
            color: #96A480; pointer-events: none;
        }
        .input-icon svg { width: 16px; height: 16px; }
        .form-input {
            width: 100%;
            background: #F8F5F2;
            border: 1px solid #F9E6A7;
            border-radius: 12px;
            padding: 0.75rem 1rem 0.75rem 2.6rem;
            font-size: 0.92rem; font-family: inherit;
            color: #BA797D;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .form-input::placeholder { color: #96A480; }
        .form-input:focus {
            border-color: #BA797D;
        }
        .form-input.is-error { border-color: #BA797D; }
        .error-msg { font-size: 0.75rem; color: #BA797D; margin-top: 0.35rem; }

        .btn-submit {
            width: 100%;
            background: #BA797D;
            border: none; border-radius: 12px;
            padding: 0.85rem;
            font-size: 0.95rem; font-weight: 700; font-family: inherit;
            color: #F8F5F2; cursor: pointer;
            margin-top: 0.5rem;
            transition: all 0.25s;
        }
        .btn-submit:hover { opacity: 0.9; }
        .btn-submit:active { transform: translateY(0); }

        .back-home {
            display: block; text-align: center;
            margin-top: 1.5rem;
            font-size: 0.8rem; color: #96A480;
            text-decoration: none;
            transition: color 0.2s;
        }
        .back-home:hover { color: #BA797D; }

        /* Credentials hint */
        .hint-box {
            margin-top: 1.5rem;
            background: #F8F5F2;
            border: 1px solid #F9E6A7;
            border-radius: 12px;
            padding: 0.85rem 1rem;
        }
        .hint-box p { font-size: 0.75rem; color: #96A480; font-weight: 500; margin-bottom: 0.3rem; }
        .hint-box code { font-size: 0.75rem; color: #BA797D; font-family: monospace; }
    </style>
</head>
<body>
<div class="bg"></div>

<div class="card">
    <div class="card-header">
        <div class="admin-icon">
            <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#BA797D" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                <circle cx="12" cy="7" r="4"></circle>
            </svg>
        </div>
        <h1>Admin Panel</h1>
        <p>Kantin Al-Amanah — Manajemen Unit Usaha</p>
    </div>

    @if(session('error'))
        <div class="alert alert-error">⚠️ {{ session('error') }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif

    <form method="POST" action="{{ route('admin.login.post') }}" id="admin-login-form">
        @csrf

        <div class="form-group">
            <label class="form-label" for="email">Email Admin</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <path d="M4 4h16c1.1 0 2 .9 2 2v12c0 1.1-.9 2-2 2H4c-1.1 0-2-.9-2-2V6c0-1.1.9-2 2-2z"/><polyline points="22,6 12,13 2,6"/>
                    </svg>
                </span>
                <input type="email" id="email" name="email" class="form-input {{ $errors->has('email') ? 'is-error' : '' }}"
                    placeholder="admin@kantin-alamanah.com"
                    value="{{ old('email') }}" required autocomplete="email">
            </div>
            @error('email')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label" for="password">Password</label>
            <div class="input-wrap">
                <span class="input-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                        <rect x="3" y="11" width="18" height="11" rx="2" ry="2"/><path d="M7 11V7a5 5 0 0110 0v4"/>
                    </svg>
                </span>
                <input type="password" id="password" name="password" class="form-input {{ $errors->has('password') ? 'is-error' : '' }}"
                    placeholder="••••••••" required autocomplete="current-password">
            </div>
            @error('password')
                <p class="error-msg">{{ $message }}</p>
            @enderror
        </div>

        <button type="submit" class="btn-submit" id="login-submit">
            Masuk ke Admin Panel
        </button>
    </form>

    <a href="{{ route('welcome') }}" class="back-home">← Kembali ke Beranda</a>


</div>
</body>
</html>
