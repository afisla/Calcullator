<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login — {{ $store->name }}</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Nunito', sans-serif;
            background: #F8F5F2;
            color: #BA797D;
            min-height: 100vh;
            display: flex; align-items: center; justify-content: center;
            padding: 1.5rem;
            overflow-x: hidden;
        }

        @media (min-width: 769px) {
            body {
                overflow: hidden;
                height: 100vh;
            }
        }

        .bg { display: none; }

        .card {
            position: relative; z-index: 1;
            width: 100%; max-width: 380px;
            background: #FFFFFF;
            border: 2px solid #F9E6A7;
            border-radius: 32px;
            padding: 1.5rem;
            box-shadow: 0 10px 30px rgba(101, 86, 75, 0.08);
            animation: slide-up 0.6s cubic-bezier(.34,1.56,.64,1) both;
        }
        @keyframes slide-up {
            from { opacity:0; transform:translateY(30px) scale(0.97); }
            to   { opacity:1; transform:translateY(0) scale(1); }
        }

        /* Back button */
        .back-link {
            display: inline-flex; align-items: center; gap: 0.4rem;
            font-size: 0.8rem; color: #BA797D !important;
            text-decoration: none; margin-bottom: 0.75rem;
            font-weight: 700;
            transition: color 0.2s;
        }
        .back-link:hover { opacity: 0.8; }

        /* Store info */
        .store-header { text-align: center; margin-bottom: 1rem; }
        .store-emoji-wrap {
            display: inline-flex; align-items: center; justify-content: center;
            width: 60px; height: 60px; border-radius: 18px;
            background: #F8F5F2;
            border: 1.5px solid #F9E6A7;
            font-size: 2.2rem;
            margin-bottom: 0.5rem;
            animation: glow-pulse 3s ease-in-out infinite;
        }
        @keyframes glow-pulse {
            0%,100% { box-shadow: 0 0 0 0 rgba(249,230,167,0.2); }
            50%      { box-shadow: 0 0 20px 8px rgba(249,230,167,0.1); }
        }

        @if($store->unit === 'koperasi')
        .store-emoji-wrap {
            background: rgba(150,164,128,0.12);
            border-color: rgba(150,164,128,0.25);
            animation: glow-pulse-green 3s ease-in-out infinite;
        }
        @keyframes glow-pulse-green {
            0%,100% { box-shadow: 0 0 0 0 rgba(150,164,128,0.2); }
            50%      { box-shadow: 0 0 20px 8px rgba(150,164,128,0.1); }
        }
        @endif

        .store-name { font-size: 1.25rem; font-weight: 800; margin-bottom: 0.2rem; color: #BA797D; }
        .store-category { font-size: 0.8rem; color: #96A480; }
        .unit-badge {
            display: inline-flex; align-items: center;
            font-size: 0.68rem; font-weight: 600;
            padding: 0.15rem 0.6rem; border-radius: 50px;
            margin-top: 0.35rem;
        }
        .unit-badge.koperasi { background: rgba(150,164,128,0.15); color: #96A480; }
        .unit-badge.kantin   { background: rgba(186,121,125,0.15); color: #BA797D; }

        /* Status */
        .status-row {
            display: flex; align-items: center; justify-content: center; gap: 0.5rem;
            font-size: 0.75rem; color: #96A480; margin-top: 0.4rem;
        }
        .status-dot { width: 7px; height: 7px; border-radius: 50%; }
        .status-dot.open { background: #96A480; box-shadow: 0 0 6px #96A480; animation: pulse 2s infinite; }
        .status-dot.closed { background: #BA797D; }
        @keyframes pulse { 0%,100% { transform:scale(1); } 50% { transform:scale(1.4); } }

        /* Alert */
        .alert {
            padding: 0.6rem 0.8rem;
            border-radius: 10px; font-size: 0.8rem;
            margin-bottom: 1rem;
            display: flex; align-items: center; gap: 0.5rem;
        }
        .alert-error { background: rgba(186,121,125,0.1); border: 1px solid #F9E6A7; color: #BA797D; }
        .alert-success { background: rgba(150,164,128,0.1); border: 1px solid #F9E6A7; color: #96A480; }

        /* Form */
        .form-label {
            display: block; font-size: 0.75rem; font-weight: 700;
            color: #96A480; text-transform: uppercase; letter-spacing: 0.06em;
            margin-bottom: 0.5rem; text-align: center;
        }

        /* PIN numpad */
        .pin-display {
            display: flex; justify-content: center; gap: 0.6rem;
            margin-bottom: 1rem;
        }
        .pin-dot {
            width: 12px; height: 12px; border-radius: 50%;
            border: 2px solid #F9E6A7;
            transition: all 0.2s;
        }
        .pin-dot.filled { background: {{ $store->unit === 'koperasi' ? '#96A480' : '#BA797D' }}; border-color: transparent; transform: scale(1.1); }

        .pin-input-hidden {
            position: absolute; opacity: 0; width: 0; height: 0;
        }

        .numpad {
            display: grid; grid-template-columns: repeat(3, 1fr);
            gap: 0.5rem; margin-bottom: 1rem;
        }
        .numkey {
            background: white;
            border: 2px solid #F9E6A7;
            border-radius: 14px;
            padding: 0.6rem;
            font-size: 1.1rem; font-weight: 700; font-family: 'Nunito',sans-serif;
            color: #BA797D; cursor: pointer;
            transition: all 0.15s;
            text-align: center;
            box-shadow: 0 2px 6px rgba(101,86,75,0.06);
        }
        .numkey:hover { background: #FFF9F2; border-color:#BA797D; transform: scale(1.05); }
        .numkey:active { transform: scale(0.96); background:#F9E6A7; }
        .numkey.del { color: #96A480; font-size: 1.2rem; }
        .numkey.zero { grid-column: 2; }

        .btn-login {
            width: 100%;
            background: #BA797D;
            border: none; border-radius: 14px;
            padding: 0.75rem;
            font-size: 0.95rem; font-weight: 800; font-family: 'Nunito',sans-serif;
            color: white; cursor: pointer;
            transition: all 0.25s;
        }
        .btn-login:hover { background: #96A480; }
        .btn-login:active { transform: translateY(0); }
        .btn-login:disabled { opacity: 0.5; cursor: not-allowed; transform: none; }

        .pin-hint { text-align: center; font-size: 0.7rem; color: #96A480; margin-top: 0.75rem; font-weight: 600; }
    </style>
</head>
<body>
<div class="bg"></div>

<div class="card">
    <a href="{{ route('portal.owner') }}" class="back-link">
        ← Kembali ke Portal Pemilik
    </a>

    <!-- Store info -->
    <div class="store-header">
        <div class="store-emoji-wrap">{{ $store->icon_emoji }}</div>
        <div class="store-name">{{ $store->name }}</div>
        <div class="store-category">{{ $store->category }}</div>
        <div class="unit-badge {{ $store->unit }}">{{ ucfirst($store->unit) }}</div>
        <div class="status-row">
            <div class="status-dot {{ $store->is_open ? 'open' : 'closed' }}"></div>
            {{ $store->is_open ? 'Sedang Buka' : 'Sedang Tutup' }}
        </div>
    </div>

    @if($errors->any())
        <div class="alert alert-error">🚫 {{ $errors->first() }}</div>
    @endif
    @if(session('success'))
        <div class="alert alert-success">✅ {{ session('success') }}</div>
    @endif

    <!-- PIN Form -->
    <form method="POST" action="{{ route('store.login.post', $store) }}" id="pin-form">
        @csrf
        <input type="hidden" name="pin" id="pin-value">

        <div class="form-label">Masukkan PIN Toko</div>

        <!-- PIN dots display -->
        <div class="pin-display" id="pin-display">
            @for($i = 0; $i < 6; $i++)
                <div class="pin-dot" id="dot-{{ $i }}"></div>
            @endfor
        </div>

        <!-- Numpad -->
        <div class="numpad">
            @foreach([1,2,3,4,5,6,7,8,9] as $num)
                <button type="button" class="numkey" onclick="addDigit('{{ $num }}')">{{ $num }}</button>
            @endforeach
            <div></div>
            <button type="button" class="numkey zero" onclick="addDigit('0')">0</button>
            <button type="button" class="numkey del" onclick="deleteDigit()">⌫</button>
        </div>

        <button type="submit" class="btn-login" id="btn-login" disabled>
            🔓 Masuk ke Dashboard
        </button>
    </form>

    <p class="pin-hint">Login untuk mengelola pesanan dan informasi toko Anda</p>
</div>

<script>
    let pin = '';
    const MIN_PIN = 4;
    const MAX_PIN = 10;

    function updateDisplay() {
        // Update dots
        for (let i = 0; i < 6; i++) {
            const dot = document.getElementById('dot-' + i);
            dot.classList.toggle('filled', i < pin.length);
        }
        // Update hidden input
        document.getElementById('pin-value').value = pin;
        // Enable/disable submit
        document.getElementById('btn-login').disabled = pin.length < MIN_PIN;
    }

    function addDigit(d) {
        if (pin.length < MAX_PIN) {
            pin += d;
            updateDisplay();
            // Haptic-like animation on button
            const btn = event.currentTarget;
            if (btn) { btn.style.transform = 'scale(0.92)'; setTimeout(() => btn.style.transform = '', 150); }
        }
    }

    function deleteDigit() {
        if (pin.length > 0) { pin = pin.slice(0, -1); updateDisplay(); }
    }

    // Keyboard support
    document.addEventListener('keydown', function(e) {
        if (e.key >= '0' && e.key <= '9') addDigit(e.key);
        else if (e.key === 'Backspace') deleteDigit();
        else if (e.key === 'Enter' && pin.length >= MIN_PIN) document.getElementById('pin-form').submit();
    });
</script>
</body>
</html>
