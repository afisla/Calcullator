@extends('layouts.app')

@section('title', 'K2Hub')

@push('styles')
    <style>
        /* ======= HERO SECTION ======= */
        .hero-section {
            background: #1E3A5F;
            margin: -16px -16px 20px -16px;
            padding: 28px 20px 36px;
            position: relative;
            overflow: hidden;
        }

        .hero-section::before {
            content: '';
            position: absolute;
            top: -30px;
            right: -30px;
            width: 160px;
            height: 160px;
            background: radial-gradient(circle, rgba(245, 158, 11, 0.15), transparent 70%);
            border-radius: 50%;
        }

        .hero-section::after {
            content: '';
            position: absolute;
            bottom: -40px;
            left: -20px;
            width: 120px;
            height: 120px;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.1), transparent 70%);
            border-radius: 50%;
        }

        .hero-greeting {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.7);
            font-weight: 400;
            margin-bottom: 4px;
        }

        .hero-title {
            font-size: 22px;
            font-weight: 800;
            color: #fff;
            line-height: 1.25;
            margin-bottom: 8px;
        }

        .hero-title span {
            font-family: 'Fredoka One', cursive;
            background: linear-gradient(90deg, #96A480, #BA797D, #F9E6A7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            -webkit-text-stroke: 1px #5c4534;
            display: inline-block;
            filter: drop-shadow(0 2px 4px rgba(101, 86, 75, 0.25));
        }

        .hero-subtitle {
            font-size: 13px;
            color: rgba(255, 255, 255, 0.65);
            margin-bottom: 16px;
            line-height: 1.5;
        }

        .hero-stats {
            display: flex;
            gap: 12px;
        }

        .hero-stat {
            background: rgba(255, 255, 255, 0.12);
            backdrop-filter: blur(8px);
            border: 1px solid rgba(255, 255, 255, 0.18);
            border-radius: 10px;
            padding: 8px 14px;
            text-align: center;
        }

        .hero-stat-value {
            font-size: 18px;
            font-weight: 800;
            color: #fbbf24;
            line-height: 1;
        }

        .hero-stat-label {
            font-size: 10px;
            color: rgba(255, 255, 255, 0.65);
            font-weight: 500;
            margin-top: 2px;
        }

        /* ======= SECTION DIVIDER ======= */
        .section-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 14px;
        }

        .section-title {
            font-size: 15px;
            font-weight: 700;
            color: var(--text-dark);
        }

        .section-count {
            font-size: 12px;
            color: var(--text-muted);
            font-weight: 500;
        }

        /* ======= STORE GRID ======= */
        .store-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
        }

        /* ======= STORE CARD ======= */
        .store-card {
            background: white;
            border-radius: var(--radius-md);
            box-shadow: var(--shadow-sm);
            border: 1.5px solid var(--border-color);
            overflow: hidden;
            text-decoration: none;
            display: block;
            transition: all 0.25s ease;
            position: relative;
        }

        .store-card:hover {
            transform: translateY(-3px);
            box-shadow: var(--shadow-md);
            border-color: var(--green-200);
        }

        .store-card:active {
            transform: scale(0.97);
        }

        .store-card.closed {
            opacity: 0.65;
            filter: grayscale(30%);
            pointer-events: none;
        }

        /* Open indicator bar */
        .store-card-bar {
            height: 4px;
            background: var(--green-600);
        }

        .store-card.closed .store-card-bar {
            background: var(--gray-100);
        }

        .store-card-body {
            padding: 14px 12px 12px;
        }

        .store-icon {
            font-size: 32px;
            margin-bottom: 8px;
            display: block;
            filter: drop-shadow(0 2px 4px rgba(0, 0, 0, 0.1));
        }

        .store-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--text-dark);
            line-height: 1.3;
            margin-bottom: 4px;
        }

        .store-category {
            font-size: 11px;
            color: var(--text-muted);
            font-weight: 400;
            margin-bottom: 10px;
            line-height: 1.4;
        }

        .store-status {
            display: inline-flex;
            align-items: center;
            gap: 4px;
            padding: 4px 10px;
            border-radius: 99px;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 0.3px;
        }

        .store-status.open {
            background: var(--green-100);
            color: var(--green-800);
        }

        .store-status.closed {
            background: var(--gray-100);
            color: var(--gray-500);
        }

        .store-status-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
        }

        .store-status.open .store-status-dot {
            background: var(--green-600);
        }

        .store-status.closed .store-status-dot {
            background: var(--gray-500);
        }

        /* Lock overlay for closed stores */
        .store-lock {
            position: absolute;
            top: 10px;
            right: 10px;
            font-size: 16px;
            opacity: 0.5;
        }

        /* ======= KOPERASI CARD (wide / special) ======= */
        .store-card-wide {
            grid-column: 1 / -1;
        }

        .store-card-wide .store-card-body {
            display: flex;
            align-items: center;
            gap: 14px;
            padding: 16px;
        }

        .store-card-wide .store-icon {
            font-size: 44px;
            margin-bottom: 0;
            flex-shrink: 0;
        }

        .store-card-wide .store-name {
            font-size: 15px;
        }

        /* ======= BOTTOM INFO ======= */
        .info-banner {
            background: #F8FAFC;
            border: 1.5px solid var(--gold-100);
            border-radius: var(--radius-md);
            padding: 14px 16px;
            display: flex;
            align-items: flex-start;
            gap: 10px;
            margin-top: 16px;
        }

        .info-banner-icon {
            font-size: 22px;
            flex-shrink: 0;
            margin-top: 1px;
        }

        .info-banner-text {
            font-size: 13px;
            color: var(--gold-600);
            font-weight: 500;
            line-height: 1.5;
        }

        /* ======= PULSE ANIMATION (for open stores) ======= */
        @keyframes pulse-green {

            0%,
            100% {
                opacity: 1;
            }

            50% {
                opacity: 0.5;
            }
        }

        .store-status.open .store-status-dot {
            animation: pulse-green 2s infinite;
        }
    </style>
@endpush

@section('content')

    <!-- HERO SECTION -->
    <div class="hero-section">
        <div class="hero-greeting">🌅 Selamat datang!</div>
        <h1 class="hero-title">Portal Layanan <span>K2Hub</span></h1>
        <p class="hero-subtitle">Pesan makanan favoritmu tanpa antre panjang — bayar cashless, langsung jadi! ✨</p>
        <div class="hero-stats">
            <div class="hero-stat">
                <div class="hero-stat-value">{{ $stores->where('is_open', true)->count() }}</div>
                <div class="hero-stat-label">Toko Buka</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">{{ $stores->count() }}</div>
                <div class="hero-stat-label">Total Toko</div>
            </div>
            <div class="hero-stat">
                <div class="hero-stat-value">⚡</div>
                <div class="hero-stat-label">Cashless</div>
            </div>
        </div>
    </div>

    <!-- STORE LIST -->
    <div class="section-header">
        <div class="section-title">🏪 Pilih Toko</div>
        <div class="section-count">{{ $stores->where('is_open', true)->count() }} dari {{ $stores->count() }} buka</div>
    </div>

    <div class="store-grid">
        @foreach($stores as $index => $store)
            @php $isWide = ($index === 0); @endphp

            @if($store->is_open)
                <a href="/toko/{{ $store->id }}" class="store-card {{ $isWide ? 'store-card-wide' : '' }}">
            @else
                    <div class="store-card closed {{ $isWide ? 'store-card-wide' : '' }}">
                @endif

                    <div class="store-card-bar"></div>

                    @if(!$store->is_open)
                        <span class="store-lock">🔒</span>
                    @endif

                    <div class="store-card-body">
                        <span class="store-icon">{{ $store->icon_emoji }}</span>
                        <div>
                            <div class="store-name">{{ $store->name }}</div>
                            <div class="store-category">{{ $store->category }}</div>
                            <div class="store-status {{ $store->is_open ? 'open' : 'closed' }}">
                                <span class="store-status-dot"></span>
                                {{ $store->is_open ? 'Buka' : 'Tutup' }}
                            </div>
                        </div>
                    </div>

                    @if($store->is_open)
                        </a>
                    @else
                </div>
            @endif
        @endforeach
    </div>

    <!-- INFO BANNER -->
    <div class="info-banner">
        <div class="info-banner-icon">💡</div>
        <div class="info-banner-text">
            <strong>Cara memesan:</strong> Pilih toko yang buka → Tambah menu ke keranjang → Isi nama → Bayar QRIS. Status
            pesanan akan otomatis update di HP-mu!
        </div>
    </div>

@endsection