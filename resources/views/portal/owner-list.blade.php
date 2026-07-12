<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>K2Hub — Merchant Portal</title>
    <meta name="description" content="Pilih unit usaha Anda untuk login ke dashboard pemilik.">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg-dark: #F8F5F2;
            --bg-card: #F8F5F2;
            --border: #F9E6A7;
            --koperasi: #96A480;
            --kantin: #BA797D;
            --text-primary: #BA797D;
            --text-secondary: #96A480;
            --text-muted: #96A480;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg-dark);
            color: var(--text-primary);
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Animated background */
        .bg-animated {
            position: fixed; inset: 0; z-index: 0;
            background: #F8F5F2;
        }

        .particles { display: none; }
        .particle {
            position: absolute;
            width: 2px; height: 2px;
            background: rgba(255,255,255,0.3);
            border-radius: 50%;
            animation: float-up linear infinite;
        }
        @keyframes float-up {
            0%   { transform: translateY(100vh) scale(0); opacity: 0; }
            10%  { opacity: 1; }
            90%  { opacity: 1; }
            100% { transform: translateY(-10vh) scale(1); opacity: 0; }
        }

        /* Main layout */
        .container {
            position: relative; z-index: 1;
            max-width: 1000px;
            margin: 0 auto;
            padding: 1.5rem 1.5rem 3rem;
        }

        /* Nav Header */
        .nav-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
            animation: fade-down 0.8s ease both;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.04);
            border: 2.5px solid var(--border);
            padding: 0.6rem 1.2rem;
            border-radius: 50px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.85rem;
            font-weight: 500;
            transition: all 0.25s ease;
        }
        .btn-back:hover {
            background: rgba(255,255,255,0.08);
            border-color: rgba(255,255,255,0.2);
            color: var(--text-primary);
            transform: translateX(-4px);
        }
        .btn-back svg {
            width: 16px; height: 16px;
            transition: transform 0.25s ease;
        }

        .brand {
            font-size: 1.5rem;
            font-family: 'Fredoka One', cursive;
            letter-spacing: 0.5px;
            background: linear-gradient(90deg, #96A480, #BA797D, #F9E6A7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            -webkit-text-stroke: 1px #5c4534;
            display: inline-block;
            filter: drop-shadow(0 2px 4px rgba(101, 86, 75, 0.25));
        }

        /* Intro Section */
        .intro {
            text-align: center;
            margin-bottom: 3.5rem;
            animation: fade-down 0.8s ease 0.1s both;
        }
        .intro-badge {
            display: inline-block;
            background: rgba(16,185,129,0.12);
            color: var(--koperasi);
            border: 1px solid rgba(16,185,129,0.2);
            padding: 0.3rem 0.8rem;
            border-radius: 50px;
            font-size: 0.7rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 1rem;
        }
        .intro h1 {
            font-size: clamp(1.8rem, 4vw, 2.5rem);
            font-weight: 800;
            margin-bottom: 0.5rem;
        }
        .intro p {
            color: var(--text-secondary);
            font-size: 0.95rem;
        }

        /* Grid */
        .grid-stores {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.5rem;
            animation: fade-up 0.9s ease 0.2s both;
        }

        .store-card {
            position: relative;
            background: var(--bg-card);
            border: 2.5px solid var(--border);
            border-radius: 20px;
            padding: 2rem 1.75rem;
            text-decoration: none;
            color: inherit;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            transition: all 0.35s cubic-bezier(.25,.8,.25,1);
            overflow: hidden;
        }

        .store-card::before {
            content: '';
            position: absolute; inset: 0;
            border-radius: 20px;
            opacity: 0;
            transition: opacity 0.35s ease;
        }

        .store-card.unit-koperasi::before {
            background: radial-gradient(ellipse at 30% 30%, rgba(16,185,129,0.08) 0%, transparent 60%);
        }
        .store-card.unit-kantin::before {
            background: radial-gradient(ellipse at 30% 30%, rgba(245,158,11,0.08) 0%, transparent 60%);
        }

        .store-card:hover {
            transform: translateY(-5px);
            border-color: rgba(255,255,255,0.25);
            box-shadow: 0 15px 30px rgba(0,0,0,0.4);
        }

        .store-card.unit-koperasi:hover {
            border-color: rgba(16,185,129,0.4);
        }
        .store-card.unit-kantin:hover {
            border-color: rgba(245,158,11,0.4);
        }

        .store-card:hover::before { opacity: 1; }

        .store-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.25rem;
        }

        .emoji-box {
            font-size: 2.2rem;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            background: rgba(255,255,255,0.03);
            border: 2.5px solid var(--border);
            border-radius: 16px;
        }

        .store-badge {
            font-size: 0.65rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.05em;
            padding: 0.2rem 0.6rem;
            border-radius: 50px;
        }
        .store-card.unit-koperasi .store-badge {
            background: rgba(16,185,129,0.12);
            color: var(--koperasi);
        }
        .store-card.unit-kantin .store-badge {
            background: rgba(245,158,11,0.12);
            color: var(--kantin);
        }

        .store-info {
            flex-grow: 1;
            margin-bottom: 1.75rem;
        }

        .store-name {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 0.4rem;
        }

        .store-status {
            font-size: 0.8rem;
            display: flex;
            align-items: center;
            gap: 0.35rem;
        }
        .status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }
        .store-card.status-open .store-status { color: #10b981; }
        .store-card.status-open .status-dot { background: #10b981; }
        .store-card.status-closed .store-status { color: var(--text-muted); }
        .store-card.status-closed .status-dot { background: var(--text-muted); }

        .store-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
            border-top: 2.5px solid var(--border);
            padding-top: 1.25rem;
            font-size: 0.85rem;
            font-weight: 600;
            color: var(--text-secondary);
            transition: color 0.25s ease;
        }
        .store-card:hover .store-footer {
            color: var(--text-primary);
        }

        .arrow-key {
            display: flex;
            align-items: center;
            justify-content: center;
            transition: transform 0.25s ease;
        }
        .store-card:hover .arrow-key {
            transform: translateX(4px);
        }
        .arrow-key svg {
            width: 14px; height: 14px;
        }

        /* Animations */
        @keyframes fade-down {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 640px) {
            .nav-header { margin-bottom: 2.5rem; }
            .grid-stores { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
<div class="bg-animated"></div>
<div class="particles" id="particles"></div>

<div class="container">
    <!-- Navigation Header -->
    <header class="nav-header">
        <a href="{{ route('welcome') }}" class="btn-back">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                <path d="M19 12H5M12 19l-7-7 7-7"/>
            </svg>
            Kembali
        </a>
        <div class="brand">K2Hub</div>
    </header>

    <!-- Intro -->
    <section class="intro">
        <span class="intro-badge">Merchant Portal</span>
        <h1>Pilih Unit Usaha Anda</h1>
        <p>Silakan pilih unit usaha atau warung Anda untuk masuk ke panel pengelolaan</p>
    </section>

    <!-- Stores Grid -->
    <main class="grid-stores">
        @foreach($allStores as $store)
        <a href="{{ route('store.login', $store) }}" class="store-card unit-{{ $store->unit }} {{ $store->is_open ? 'status-open' : 'status-closed' }}">
            <div>
                <div class="store-header">
                    <div class="emoji-box">
                        {{ $store->icon_emoji }}
                    </div>
                    <span class="store-badge">
                        {{ $store->unit }}
                    </span>
                </div>
                <div class="store-info">
                    <h2 class="store-name">{{ $store->name }}</h2>
                    <div class="store-status">
                        <span class="status-dot"></span>
                        {{ $store->is_open ? 'Buka' : 'Tutup' }}
                    </div>
                </div>
            </div>
            <div class="store-footer">
                <span>Login PIN Pemilik</span>
                <span class="arrow-key">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </span>
            </div>
        </a>
        @endforeach
    </main>
</div>

<script>
    // Generate floating particles
    const container = document.getElementById('particles');
    for (let i = 0; i < 30; i++) {
        const p = document.createElement('div');
        p.className = 'particle';
        p.style.left = Math.random() * 100 + 'vw';
        p.style.animationDuration = (8 + Math.random() * 12) + 's';
        p.style.animationDelay = (Math.random() * 15) + 's';
        p.style.width = p.style.height = (1 + Math.random() * 3) + 'px';
        p.style.opacity = (0.1 + Math.random() * 0.4).toString();
        container.appendChild(p);
    }
</script>
</body>
</html>
