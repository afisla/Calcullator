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
            --koperasi-glow: rgba(150,164,128,0.25);
            --koperasi-light: #96A480;
            --kantin: #BA797D;
            --kantin-glow: rgba(186,121,125,0.25);
            --kantin-light: #BA797D;
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

        @media (min-width: 769px) {
            body {
                overflow: hidden;
                height: 100vh;
            }
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
        }        /* Main layout */
        .container {
            position: relative; z-index: 1;
            max-width: 1040px;
            margin: 0 auto;
            padding: 1rem 1.5rem;
            padding-top: 2rem;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
        }

        /* Nav Header */
        .nav-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 1.5rem;
            animation: fade-down 0.8s ease both;
        }

        .btn-back {
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
            background: rgba(255,255,255,0.04);
            border: 1px solid var(--border);
            padding: 0.5rem 1.1rem;
            border-radius: 50px;
            color: var(--text-secondary);
            text-decoration: none;
            font-size: 0.8rem;
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
            width: 14px; height: 14px;
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
            padding: 0.25rem 0.75rem;
            border-radius: 50px;
            font-size: 0.65rem;
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            margin-bottom: 0.5rem;
        }
        .intro h1 {
            font-size: clamp(1.5rem, 3.5vw, 2.2rem);
            font-weight: 800;
            margin-bottom: 0.35rem;
        }
        .intro p {
            color: var(--text-secondary);
            font-size: 0.9rem;
        }

        /* Unit selection grid */
        .cards-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
            width: 100%;
            animation: fade-up 0.9s ease 0.2s both;
        }

        .unit-card {
            position: relative;
            background: var(--bg-card);
            border: 2px solid var(--border);
            border-radius: 32px;
            padding: 2.5rem 1.75rem;
            text-decoration: none; color: inherit;
            cursor: pointer;
            transition: transform 0.4s cubic-bezier(.34,1.56,.64,1), box-shadow 0.4s ease, border-color 0.4s ease;
            overflow: hidden;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .unit-card::before {
            content: '';
            position: absolute; inset: 0;
            border-radius: 32px;
            opacity: 0;
            transition: opacity 0.4s ease;
        }
        .unit-card.kantin::before {
            background: radial-gradient(ellipse at 30% 30%, rgba(245,158,11,0.12) 0%, transparent 70%);
        }

        .unit-card:hover {
            transform: translateY(-10px) scale(1.02);
        }
        .unit-card.kantin:hover {
            border-color: rgba(245,158,11,0.5);
            box-shadow: 0 30px 60px rgba(245,158,11,0.2), 0 0 0 1px rgba(245,158,11,0.2) inset;
        }
        .unit-card:hover::before { opacity: 1; }

        .unit-card::after {
            content: '';
            position: absolute;
            top: -50%; left: -50%;
            width: 200%; height: 200%;
            background: transparent;
            transform: translateX(-100%);
            transition: transform 0.8s ease;
        }
        .unit-card:hover::after { transform: translateX(100%); }

        .card-icon-wrap {
            position: relative;
            display: inline-flex;
            align-items: center; justify-content: center;
            width: 60px; height: 60px;
            border-radius: 18px;
            margin-bottom: 1rem;
        }
        .kantin .card-icon-wrap {
            background: rgba(245,158,11,0.12);
            border: 1px solid rgba(245,158,11,0.25);
        }
        .card-emoji { font-size: 2rem; }

        .card-title {
            font-size: 1.35rem; font-weight: 800;
            margin-bottom: 0.5rem; line-height: 1.2;
            letter-spacing: -0.01em;
        }

        .card-desc {
            font-size: 0.85rem; color: var(--text-secondary);
            line-height: 1.5; margin-bottom: 2.25rem;
            flex-grow: 1;
        }

        .card-cta {
            display: flex; align-items: center; justify-content: space-between;
            padding-top: 1.25rem;
            border-top: 2px solid var(--border);
        }
        .cta-text {
            font-size: 0.95rem; font-weight: 600;
        }
        .kantin .cta-text { color: var(--kantin-light); }

        .cta-arrow {
            display: flex; align-items: center; justify-content: center;
            width: 36px; height: 36px; border-radius: 50%;
            transition: transform 0.3s ease;
        }
        .kantin .cta-arrow { background: rgba(245,158,11,0.15); }
        .unit-card:hover .cta-arrow { transform: translateX(6px); }
        .cta-arrow svg { width: 16px; height: 16px; }
        .kantin .cta-arrow svg { color: var(--kantin); }

        /* Animations */
        @keyframes fade-down {
            from { opacity: 0; transform: translateY(-20px); }
            to   { opacity: 1; transform: translateY(0); }
        }
        @keyframes fade-up {
            from { opacity: 0; transform: translateY(20px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        @media (max-width: 768px) {
            .cards-grid { grid-template-columns: 1fr; gap: 1rem; }
            body { overflow-y: auto; }
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
        <p>Akses dasbor pengelolaan pesanan untuk Koperasi atau Kantin</p>
    </section>

    <!-- Cards Grid -->
    <main class="cards-grid">
        <!-- Koperasi Unit -->
        @if($koperasiStore)
        <a href="{{ route('store.login', $koperasiStore) }}" class="unit-card kantin" id="unit-koperasi">
            <div class="card-content">
                <div class="card-icon-wrap">
                    <span class="card-emoji">🏪</span>
                </div>
                <h2 class="card-title">Koperasi Sekolah</h2>
                <p class="card-desc">Kelola persediaan barang/stok koperasi, dasbor finansial, serta kelola antrean pesanan seragam dan ATK.</p>
            </div>
            <div class="card-cta">
                <span class="cta-text">Login Koperasi</span>
                <div class="cta-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>
        </a>
        @endif

        <!-- Kantin Unit -->
        <a href="{{ route('portal.owner', ['unit' => 'kantin']) }}" class="unit-card kantin" id="unit-kantin">
            <div class="card-content">
                <div class="card-icon-wrap">
                    <span class="card-emoji">🍽️</span>
                </div>
                <h2 class="card-title">Kantin Sekolah</h2>
                <p class="card-desc">Pilih warung makan atau stand jajanan Anda untuk memproses pesanan makanan/minuman siswa.</p>
            </div>
            <div class="card-cta">
                <span class="cta-text">Pilih Warung Kantin</span>
                <div class="cta-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>
        </a>

        <!-- Admin Panel -->
        <a href="{{ route('admin.login') }}" class="unit-card kantin" id="unit-admin">
            <div class="card-content">
                <div class="card-icon-wrap" style="display: flex; align-items: center; justify-content: center;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#BA797D" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                        <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                        <circle cx="12" cy="7" r="4"></circle>
                    </svg>
                </div>
                <h2 class="card-title">Admin Sekolah</h2>
                <p class="card-desc">Manajemen toko, akun pengelola, dan laporan keuangan keseluruhan.</p>
            </div>
            <div class="card-cta">
                <span class="cta-text">Login Admin Panel</span>
                <div class="cta-arrow">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5">
                        <path d="M5 12h14M12 5l7 7-7 7"/>
                    </svg>
                </div>
            </div>
        </a>
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
