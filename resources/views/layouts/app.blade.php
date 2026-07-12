<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#E7648E">
    <meta name="description" content="K2Hub — Sistem Pemesanan Koperasi & Kantin SMP Al Amanah">

    <title>@yield('title', 'K2Hub')</title>

    <!-- Google Fonts: Nunito + Fredoka One -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ===================== K2HUB DESIGN SYSTEM ===================== */
        :root {
            /* === K2Hub Color Palette === */
            --k2-primary:      #BA797D;   /* Button utama */
            --k2-primary-dark: #96A480;   /* Sidebar/Navbar */
            --k2-light-pink:   #F9E6A7;   /* Light Accent */
            --k2-green:        #96A480;   /* Success Green */
            --k2-neutral:      #F9E6A7;   /* Border */
            --k2-white:        #F8F5F2;   /* Card/Form */
            --k2-dark:         #BA797D;   /* Teks utama */
            --k2-bg:           #F8F5F2;   /* Background utama */
            --k2-card-bg:      #F8F5F2;

            /* Status colors */
            --status-pending:    #F9E6A7;
            --status-paid:       #96A480;
            --status-processing: #F9E6A7;
            --status-ready:      #96A480;
            --status-completed:  #96A480;
            --status-rejected:   #BA797D;

            /* Shadows */
            --shadow-xs: none;
            --shadow-sm: none;
            --shadow-md: none;
            --shadow-lg: none;

            /* Radius */
            --r-sm:  10px;
            --r-md:  16px;
            --r-lg:  22px;
            --r-xl:  30px;
            --r-full: 999px;
        }

        /* ===================== RESET ===================== */
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            font-family: 'Nunito', -apple-system, BlinkMacSystemFont, sans-serif;
            background: var(--k2-bg);
            color: var(--k2-dark);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ===================== HEADER ===================== */
        .app-header {
            background: #BA797D;
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 2px 16px rgba(186, 121, 125, 0.15);
        }

        .header-gold-line {
            height: 3px;
            background: #F9E6A7;
        }

        .header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 12px 16px;
            max-width: 520px;
            margin: 0 auto;
            min-height: 72px;
            box-sizing: border-box;
        }

        .header-brand {
            display: flex;
            align-items: center;
            gap: 10px;
            text-decoration: none;
        }

        .header-logo-wrap {
            width: 48px;
            height: 48px;
            background: rgba(255,255,255,0.22);
            border: 2px solid rgba(255,255,255,0.4);
            border-radius: var(--r-md);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            backdrop-filter: blur(6px);
            flex-shrink: 0;
        }

        .logo-k2hub-gradient {
            font-family: 'Fredoka One', cursive;
            font-size: 22px;
            letter-spacing: 0.5px;
            line-height: 1;
            background: linear-gradient(90deg, #96A480, #BA797D, #F9E6A7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            -webkit-text-stroke: 1px #5c4534;
            display: inline-block;
            filter: drop-shadow(0 2px 4px rgba(101, 86, 75, 0.25));
        }

        .brand-text-k2 {
            font-family: 'Fredoka One', cursive;
            font-size: 22px;
            color: #ffffff;
            letter-spacing: 0.5px;
            line-height: 1;
            text-shadow: 0 2px 8px rgba(0,0,0,0.15);
        }

        .brand-text-hub {
            font-family: 'Fredoka One', cursive;
            font-size: 22px;
            color: #F9E6A7;
            letter-spacing: 0.5px;
        }

        .header-subtitle {
            font-size: 12px;
            color: rgba(255,255,255,0.75);
            font-weight: 500;
            margin-top: 1px;
        }

        .header-cart-btn {
            background: rgba(255,255,255,0.20);
            border: 1.5px solid rgba(255,255,255,0.35);
            color: white;
            padding: 7px 13px;
            border-radius: var(--r-sm);
            text-decoration: none;
            font-size: 13px;
            font-weight: 700;
            display: flex;
            align-items: center;
            gap: 6px;
            backdrop-filter: blur(8px);
            transition: all 0.2s;
        }

        .header-cart-btn:hover { background: rgba(255,255,255,0.30); }

        .cart-badge {
            background: #F9E6A7;
            color: #BA797D;
            font-size: 11px;
            font-weight: 900;
            padding: 1px 7px;
            border-radius: var(--r-full);
            min-width: 20px;
            text-align: center;
        }

        .btn-back {
            display: flex;
            align-items: center;
            gap: 5px;
            color: rgba(255,255,255,0.90);
            text-decoration: none;
            font-size: 14px;
            font-weight: 600;
            padding: 6px 12px;
            border-radius: var(--r-sm);
            transition: all 0.2s;
            background: rgba(255,255,255,0.15);
        }

        .btn-back:hover { background: rgba(255,255,255,0.25); color: white; }

        /* ===================== LAYOUT ===================== */
        .main-content {
            max-width: 520px;
            margin: 0 auto;
            padding: 16px 16px 40px;
        }

        @media (min-width: 768px) {
            .main-content, .header-inner {
                max-width: 900px !important;
            }
        }
        @media (min-width: 1024px) {
            .main-content, .header-inner {
                max-width: 1100px !important;
            }
        }

        /* ===================== RESPONSIVE GRIDS ===================== */
        .product-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        @media (min-width: 768px) {
            .product-grid {
                grid-template-columns: repeat(3, 1fr);
            }
        }
        @media (min-width: 1024px) {
            .product-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }

        .warung-grid {
            display: grid;
            grid-template-columns: 1fr;
            gap: 12px;
        }
        @media (min-width: 768px) {
            .warung-grid {
                grid-template-columns: repeat(2, 1fr);
            }
        }

        /* ===================== CARDS ===================== */
        .card {
            background: var(--k2-card-bg);
            border-radius: var(--r-md);
            box-shadow: var(--shadow-sm);
            border: 1px solid rgba(231,100,142,0.10);
            overflow: hidden;
        }

        .card-body { padding: 16px; }

        .card-header {
            padding: 12px 16px;
            background: #F8FAFC;
            border-bottom: 1px solid rgba(231,100,142,0.10);
            font-weight: 700;
            font-size: 14px;
            color: var(--k2-primary-dark);
        }

        /* ===================== ALERTS ===================== */
        .alert {
            padding: 12px 16px;
            border-radius: var(--r-sm);
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success { background: #f0fdf4; color: #166534; border: 1px solid #bbf7d0; }
        .alert-error   { background: #fff0f5; color: #9b1c4a; border: 1px solid #fda4c4; }
        .alert-info    { background: #f0f9ff; color: #0369a1; border: 1px solid #bae6fd; }
        .alert-warning { background: #fffbeb; color: #92400e; border: 1px solid #fde68a; }

        /* ===================== BUTTONS ===================== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 11px 20px;
            border-radius: var(--r-sm);
            font-size: 14px;
            font-weight: 700;
            font-family: 'Nunito', inherit;
            cursor: pointer;
            border: none;
            transition: all 0.2s;
            text-decoration: none;
            white-space: nowrap;
            letter-spacing: 0.2px;
        }

        .btn:active { transform: scale(0.97); }

        .btn-primary {
            background: #BA797D;
            color: white;
            box-shadow: 0 4px 14px rgba(186,121,125,0.15);
        }
        .btn-primary:hover { background: #a56468; }

        .btn-green {
            background: #96A480;
            color: var(--k2-white);
            box-shadow: 0 4px 12px rgba(150,164,128,0.15);
        }
        .btn-green:hover { background: #82906c; }

        .btn-outline {
            background: transparent;
            color: #BA797D;
            border: 2px solid #BA797D;
        }
        .btn-outline:hover { background: rgba(186,121,125,0.07); }

        .btn-danger {
            background: #EF4444;
            color: white;
            box-shadow: 0 4px 12px rgba(239,68,68,0.30);
        }

        .btn-gray {
            background: rgba(218,214,211,0.5);
            color: var(--k2-dark);
            border: 1px solid var(--k2-neutral);
        }

        .btn-sm  { padding: 7px 13px; font-size: 12px; border-radius: 8px; }
        .btn-lg  { padding: 15px 24px; font-size: 16px; border-radius: var(--r-md); }
        .btn-xl  { padding: 18px 32px; font-size: 18px; border-radius: var(--r-lg); }
        .btn-block { width: 100%; }

        /* ===================== FORMS ===================== */
        .form-group { margin-bottom: 14px; }

        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 700;
            color: var(--k2-dark);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 11px 14px;
            border: 2px solid #E5E7EB;
            border-radius: var(--r-sm);
            font-family: 'Nunito', sans-serif;
            font-size: 15px;
            font-weight: 500;
            color: var(--k2-dark);
            background: #fff;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--k2-primary);
            box-shadow: 0 0 0 3px rgba(231,100,142,0.15);
        }

        .form-control.is-invalid { border-color: #ef4444; }
        .invalid-feedback { color: #ef4444; font-size: 12px; margin-top: 4px; font-weight: 600; }

        /* ===================== BADGES ===================== */
        .badge {
            display: inline-flex;
            align-items: center;
            padding: 3px 10px;
            border-radius: var(--r-full);
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .badge-pending    { background: #f1f5f9; color: #64748b; }
        .badge-paid       { background: #dbeafe; color: #1d4ed8; }
        .badge-processing { background: #ffedd5; color: #c2410c; }
        .badge-ready      { background: #dcfce7; color: #15803d; }
        .badge-completed  { background: #ede9fe; color: #7c3aed; }
        .badge-rejected   { background: #fee2e2; color: #dc2626; }
        .badge-open       { background: #dcfce7; color: #15803d; }
        .badge-closed     { background: #f1f5f9; color: #64748b; }

        /* ===================== SECTION TITLE ===================== */
        .section-title {
            font-size: 17px;
            font-weight: 800;
            color: var(--k2-dark);
            margin-bottom: 12px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .section-title-sub {
            font-size: 12px;
            font-weight: 500;
            color: #94a3b8;
            margin-left: auto;
        }

        /* ===================== DIVIDER ===================== */
        .divider {
            height: 1px;
            background: #E5E7EB;
            margin: 16px 0;
        }

        /* ===================== TOAST ===================== */
        #toast-container {
            position: fixed;
            bottom: 20px;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            width: calc(100% - 32px);
            max-width: 480px;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .toast {
            background: var(--k2-dark);
            color: white;
            padding: 12px 18px;
            border-radius: var(--r-sm);
            font-size: 14px;
            font-weight: 600;
            box-shadow: var(--shadow-lg);
            animation: toastIn 0.35s cubic-bezier(0.34,1.56,0.64,1);
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .toast-success { background: #15803d; }
        .toast-error   { background: #be123c; }
        .toast-info    { background: #1d4ed8; }

        @keyframes toastIn {
            from { transform: translateY(30px); opacity: 0; }
            to   { transform: translateY(0); opacity: 1; }
        }

        /* ===================== MODAL ===================== */
        .modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(45,27,61,0.55);
            backdrop-filter: blur(6px);
            z-index: 200;
            display: flex;
            align-items: flex-end;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s;
        }

        .modal-overlay.active {
            opacity: 1;
            pointer-events: all;
        }

        .modal-sheet {
            background: white;
            border-radius: var(--r-lg) var(--r-lg) 0 0;
            padding: 24px 20px 32px;
            width: 100%;
            max-width: 520px;
            max-height: 90vh;
            overflow-y: auto;
            transform: translateY(100%);
            transition: transform 0.35s cubic-bezier(0.34,1.20,0.64,1);
        }

        .modal-overlay.active .modal-sheet { transform: translateY(0); }

        .modal-handle {
            width: 40px;
            height: 4px;
            background: var(--k2-neutral);
            border-radius: var(--r-full);
            margin: 0 auto 20px;
        }

        .modal-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--k2-dark);
            margin-bottom: 4px;
        }

        .modal-subtitle {
            font-size: 13px;
            color: #94a3b8;
            margin-bottom: 20px;
            font-weight: 500;
        }

        /* ===================== EMPTY STATE ===================== */
        .empty-state {
            text-align: center;
            padding: 48px 24px;
        }

        .empty-icon {
            font-size: 60px;
            margin-bottom: 16px;
            display: block;
        }

        .empty-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--k2-dark);
            margin-bottom: 8px;
        }

        .empty-text {
            font-size: 14px;
            color: #94a3b8;
            line-height: 1.6;
            margin-bottom: 24px;
            font-weight: 500;
        }

        /* ===================== SPINNER ===================== */
        .spinner {
            width: 20px;
            height: 20px;
            border: 3px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        .spinner-pink {
            border-color: rgba(231,100,142,0.2);
            border-top-color: var(--k2-primary);
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ===================== PRODUCT CARD ===================== */
        .product-card {
            background: white;
            border-radius: var(--r-md);
            overflow: hidden;
            box-shadow: var(--shadow-xs);
            border: 1px solid rgba(231,100,142,0.09);
            transition: box-shadow 0.2s, transform 0.2s;
            display: flex;
            flex-direction: column;
            height: 100%;
        }

        .product-card:hover {
            box-shadow: var(--shadow-md);
            transform: translateY(-2px);
        }

        .product-img {
            width: 100%;
            aspect-ratio: 4/3;
            object-fit: cover;
            background: #F1F5F9;
        }

        .product-img-placeholder {
            width: 100%;
            aspect-ratio: 4/3;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 40px;
            background: #F1F5F9;
        }

        .product-info {
            padding: 10px 12px 12px;
            display: flex;
            flex-direction: column;
            flex-grow: 1;
        }

        .product-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--k2-dark);
            margin-bottom: 3px;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .product-price {
            font-size: 15px;
            font-weight: 800;
            color: #96A480;
            margin-bottom: 6px;
        }

        .product-stock {
            font-size: 11px;
            font-weight: 600;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .product-stock.low { color: #f97316; }
        .product-stock.out { color: #ef4444; }

        /* ===================== QTY CONTROLS ===================== */
        .qty-control {
            display: flex;
            align-items: center;
            gap: 0;
            border: 2px solid #F9E6A7;
            border-radius: var(--r-sm);
            overflow: hidden;
            width: fit-content;
            margin-left: auto;
            margin-top: 6px;
        }

        .qty-btn {
            background: var(--k2-primary);
            color: white;
            border: none;
            width: 26px;
            height: 26px;
            font-size: 14px;
            font-weight: 800;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: opacity 0.15s;
            flex-shrink: 0;
        }

        .qty-btn:hover { opacity: 0.85; }
        .qty-btn:active { opacity: 0.7; }

        .qty-num {
            min-width: 28px;
            text-align: center;
            font-weight: 800;
            font-size: 12px;
            color: var(--k2-dark);
            padding: 0 4px;
        }

        /* ===================== SEARCH ===================== */
        .search-wrap {
            position: relative;
            margin-bottom: 14px;
        }

        .search-icon {
            position: absolute;
            left: 12px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 16px;
            color: var(--k2-primary);
            pointer-events: none;
        }

        .search-input {
            width: 100%;
            padding: 11px 14px 11px 38px;
            border: 2px solid #F9E6A7;
            border-radius: var(--r-full);
            font-family: 'Nunito', sans-serif;
            font-size: 14px;
            font-weight: 500;
            color: var(--k2-dark);
            background: white;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s;
        }

        .search-input:focus {
            border-color: var(--k2-primary);
            box-shadow: 0 0 0 3px rgba(231,100,142,0.15);
        }

        .search-input::placeholder { color: #c4a8b4; }

        /* ===================== STATUS TRACKER ===================== */
        .status-tracker {
            display: flex;
            align-items: center;
            gap: 0;
            padding: 16px 0;
        }

        .tracker-step {
            display: flex;
            flex-direction: column;
            align-items: center;
            flex: 1;
            position: relative;
        }

        .tracker-icon {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 16px;
            border: 2px solid var(--k2-neutral);
            background: white;
            z-index: 1;
            transition: all 0.3s;
        }

        .tracker-step.active .tracker-icon {
            background: var(--k2-primary);
            border-color: var(--k2-primary);
            box-shadow: 0 0 0 4px rgba(231,100,142,0.20);
        }

        .tracker-step.done .tracker-icon {
            background: #A9D770;
            border-color: #7eba44;
        }

        .tracker-label {
            font-size: 10px;
            font-weight: 700;
            color: #94a3b8;
            margin-top: 5px;
            text-align: center;
        }

        .tracker-step.active .tracker-label { color: var(--k2-primary); }
        .tracker-step.done .tracker-label { color: #7eba44; }

        .tracker-line {
            flex: 1;
            height: 2px;
            background: var(--k2-neutral);
            margin-top: -18px;
            position: relative;
            z-index: 0;
        }

        .tracker-line.done { background: #A9D770; }

        /* ===================== RESPONSIVE ===================== */
        @media (max-width: 400px) {
            .main-content { padding: 12px 12px 40px; }
            .brand-text-k2, .brand-text-hub { font-size: 19px; }
        }

        /* ===================== ANIMATIONS ===================== */
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .animate-in {
            animation: fadeInUp 0.4s ease forwards;
        }

        /* ===================== FLOATING CART ===================== */
        .float-cart {
            position: fixed;
            bottom: 20px;
            right: 16px;
            z-index: 99;
            background: #BA797D;
            color: white;
            width: 56px;
            height: 56px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            box-shadow: 0 4px 20px rgba(186,121,125,0.25);
            text-decoration: none;
            transition: all 0.2s;
        }

        .float-cart:hover { transform: scale(1.08); }

        .float-cart-badge {
            position: absolute;
            top: -4px;
            right: -4px;
            background: #F9E6A7;
            color: #BA797D;
            font-size: 10px;
            font-weight: 900;
            padding: 2px 6px;
            border-radius: var(--r-full);
            min-width: 20px;
            text-align: center;
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- ====== App Header ====== -->
<header class="app-header">
    <div class="header-gold-line"></div>
    <div class="header-inner">
        @hasSection('header-left')
            @yield('header-left')
        @else
            <a href="/dashboard" class="header-brand">
                <div class="header-logo-wrap">🏫</div>
                <div>
                    <div>
                        <span class="logo-k2hub-gradient">K2Hub</span>
                    </div>
                    <div class="header-subtitle">Koperasi & Kantin SMP Al Amanah</div>
                </div>
            </a>
        @endif

        @php $cart = session('cart'); @endphp
        @if($cart && !empty($cart['items']))
            <a href="/keranjang" class="header-cart-btn">
                🛒 <span class="cart-badge">{{ count($cart['items']) }}</span>
            </a>
        @endif
    </div>
</header>

<!-- ====== Flash Messages ====== -->
<div style="max-width:520px;margin:0 auto;padding:0 16px;">
    @if(session('success'))
        <div class="alert alert-success" style="margin-top:12px;">✅ {{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error" style="margin-top:12px;">❌ {{ session('error') }}</div>
    @endif
    @if(session('info'))
        <div class="alert alert-info" style="margin-top:12px;">ℹ️ {{ session('info') }}</div>
    @endif
</div>

<!-- ====== Main Content ====== -->
<main class="main-content">
    @yield('content')
</main>

<!-- ====== Toast Container ====== -->
<div id="toast-container"></div>

<script>
    // ====== CSRF ======
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

    // ====== Toast ======
    function showToast(message, type = 'success', duration = 3200) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        const icons = { success: '✅', error: '❌', info: 'ℹ️' };
        toast.innerHTML = `<span>${icons[type] || '📢'}</span><span>${message}</span>`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.transition = 'opacity 0.4s';
            toast.style.opacity = '0';
            setTimeout(() => toast.remove(), 400);
        }, duration);
    }

    // ====== AJAX Helper ======
    async function apiPost(url, data = {}) {
        const resp = await fetch(url, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': CSRF_TOKEN,
                'Accept': 'application/json',
            },
            body: JSON.stringify(data),
        });
        return resp.json();
    }

    // ====== Auto dismiss alerts ======
    setTimeout(() => {
        document.querySelectorAll('.alert:not(.alert-permanent)').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 4500);
</script>

@stack('scripts')
</body>
</html>
