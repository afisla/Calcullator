<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#0f3d1a">
    <title>@yield('title', 'Dasbor Toko — K2Hub')</title>

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        /* ==================== DESIGN SYSTEM (Warung) ==================== */
        :root {
            --green-950: #0f2f1d;
            --green-900: #14532d;
            --green-800: #166534;
            --green-700: #15803d;
            --green-600: #16A34A;
            --green-100: #DCFCE7;
            --green-50:  #F0FDF4;
            --gold-500:  #F59E0B;
            --gold-400:  #F59E0B;
            --gold-100:  #FEF3C7;
            --blue-600:  #2563EB;
            --blue-100:  #DBEAFE;
            --yellow-600:#d97706;
            --yellow-100:#fef9c3;
            --green-600b:#16A34A;
            --green-100b:#DCFCE7;
            --red-600:   #EF4444;
            --red-100:   #FEE2E2;
            --gray-600:  #6B7280;
            --gray-100:  #F8FAFC;
 
            --primary:      #BA797D;
            --primary-dark: #96A480;
            --accent:       #F9E6A7;
            --text-dark:    #BA797D;
            --text-medium:  #96A480;
            --text-muted:   #96A480;
            --bg-main:      #F8F5F2;
            --bg-card:      #F8F5F2;
            --border-color: #F9E6A7;
            --shadow-sm:    none;
            --shadow-md:    none;
            --shadow-lg:    none;
            --radius-sm:    8px;
            --radius-md:    14px;
            --radius-lg:    20px;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        html, body {
            font-family: 'Inter', -apple-system, sans-serif;
            background: var(--bg-main);
            color: var(--text-dark);
            min-height: 100vh;
            -webkit-font-smoothing: antialiased;
        }

        /* ==================== WARUNG HEADER ==================== */
        .warung-header {
            background: var(--primary-dark);
            padding: 0;
            box-shadow: 0 2px 16px rgba(30,58,95,0.15);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .gold-line { height: 3px; background: var(--primary); }

        .warung-header-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 20px;
            max-width: 1200px;
            margin: 0 auto;
        }

        .warung-brand {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .warung-brand-icon {
            width: 44px; height: 44px;
            background: var(--primary);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 22px;
            box-shadow: 0 2px 8px rgba(37,99,235,0.2);
        }

        .warung-brand-name {
            font-size: 16px;
            font-weight: 800;
            color: white;
            line-height: 1.2;
        }

        .warung-brand-sub {
            font-size: 11px;
            color: rgba(255,255,255,0.6);
            font-weight: 400;
        }

        .warung-header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .store-status-pill {
            display: flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 99px;
            font-size: 12px;
            font-weight: 700;
        }

        .store-status-pill.open {
            background: rgba(34,197,94,0.2);
            border: 1px solid rgba(34,197,94,0.4);
            color: #86efac;
        }

        .store-status-pill.closed {
            background: rgba(255,255,255,0.1);
            border: 1px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.6);
        }

        .status-dot-header {
            width: 8px; height: 8px;
            border-radius: 50%;
            animation: headerPulse 2s infinite;
        }

        .store-status-pill.open .status-dot-header { background: #22c55e; }
        .store-status-pill.closed .status-dot-header { background: #9ca3af; animation: none; }

        @keyframes headerPulse { 0%,100%{opacity:1} 50%{opacity:0.4} }

        .btn-logout {
            background: rgba(255,255,255,0.12);
            border: 1px solid rgba(255,255,255,0.2);
            color: rgba(255,255,255,0.8);
            padding: 8px 14px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
        }

        .btn-logout:hover { background: rgba(255,255,255,0.2); color: white; }

        /* ==================== MAIN LAYOUT ==================== */
        .warung-main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
        }

        /* ==================== BUTTONS ==================== */
        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 6px;
            padding: 10px 16px;
            border-radius: var(--radius-sm);
            font-size: 13px;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            border: none;
            transition: all 0.18s;
            text-decoration: none;
            white-space: nowrap;
        }

        .btn:active { transform: scale(0.96); }

        .btn-primary { background: var(--primary); color: white; }
        .btn-primary:hover { background: var(--green-700); }

        .btn-success { background: #16a34a; color: white; }
        .btn-success:hover { background: #15803d; }

        .btn-warning { background: #d97706; color: white; }
        .btn-warning:hover { background: #b45309; }

        .btn-danger { background: #dc2626; color: white; }
        .btn-danger:hover { background: #b91c1c; }

        .btn-gray { background: var(--gray-100); color: var(--gray-600); }
        .btn-gray:hover { background: #e5e7eb; }

        .btn-block { width: 100%; }
        .btn-sm { padding: 7px 12px; font-size: 12px; }

        /* ==================== ALERTS ==================== */
        .alert {
            padding: 12px 16px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .alert-success { background: var(--green-100); color: var(--green-800); border: 1px solid #a7f3d0; }
        .alert-error   { background: var(--red-100); color: #991b1b; border: 1px solid #fecaca; }
        .alert-info    { background: var(--gold-100); color: #92400e; border: 1px solid #fde68a; }

        /* ==================== FORMS ==================== */
        .form-group { margin-bottom: 16px; }
        .form-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: var(--text-medium);
            margin-bottom: 6px;
        }

        .form-control {
            width: 100%;
            padding: 12px 14px;
            border: 2px solid var(--border-color);
            border-radius: var(--radius-sm);
            font-family: inherit;
            font-size: 15px;
            color: var(--text-dark);
            background: white;
            transition: border-color 0.2s, box-shadow 0.2s;
            outline: none;
        }

        .form-control:focus {
            border-color: var(--green-600);
            box-shadow: 0 0 0 3px rgba(22,163,74,0.12);
        }

        .form-control.is-invalid { border-color: #dc2626; }
        .invalid-feedback { color: #dc2626; font-size: 12px; margin-top: 4px; }

        /* ==================== MODAL ==================== */
        .modal-backdrop {
            position: fixed;
            inset: 0;
            background: rgba(0,0,0,0.5);
            backdrop-filter: blur(4px);
            z-index: 200;
            display: flex;
            align-items: center;
            justify-content: center;
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s;
            padding: 20px;
        }

        .modal-backdrop.active { opacity: 1; pointer-events: all; }

        .modal-box {
            background: white;
            border-radius: var(--radius-lg);
            padding: 24px;
            width: 100%;
            max-width: 440px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.2);
            transform: scale(0.95);
            transition: transform 0.25s;
        }

        .modal-backdrop.active .modal-box { transform: scale(1); }

        .modal-title {
            font-size: 18px;
            font-weight: 800;
            color: var(--text-dark);
            margin-bottom: 6px;
        }

        .modal-subtitle {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 20px;
            line-height: 1.5;
        }

        /* ==================== TOAST ==================== */
        #toast-container {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .toast {
            background: var(--green-950);
            color: white;
            padding: 12px 18px;
            border-radius: var(--radius-sm);
            font-size: 14px;
            font-weight: 500;
            box-shadow: var(--shadow-md);
            animation: slideIn 0.3s ease;
            display: flex;
            align-items: center;
            gap: 10px;
            min-width: 260px;
        }

        .toast-success { background: #166534; }
        .toast-error   { background: #7f1d1d; }
        .toast-warning { background: #78350f; }

        @keyframes slideIn {
            from { transform: translateX(20px); opacity: 0; }
            to   { transform: translateX(0); opacity: 1; }
        }

        /* ==================== SPINNER ==================== */
        .spinner {
            width: 16px; height: 16px;
            border: 2px solid rgba(255,255,255,0.3);
            border-top-color: white;
            border-radius: 50%;
            animation: spin 0.7s linear infinite;
        }

        @keyframes spin { to { transform: rotate(360deg); } }

        /* ==================== RESPONSIVE ==================== */
        @media (max-width: 640px) {
            .warung-main { padding: 12px; }
            .warung-header-inner { padding: 10px 14px; }
            .warung-brand-name { font-size: 14px; }
        }
    </style>

    @stack('styles')
</head>
<body>

<!-- Header -->
<header class="warung-header">
    <div class="gold-line"></div>
    <div class="warung-header-inner">
        <div class="warung-brand">
            <div class="warung-brand-icon">@yield('store-icon', '🏪')</div>
            <div>
                <div class="warung-brand-name">@yield('store-name', 'Dasbor Warung')</div>
                <div class="warung-brand-sub">Pusat Layanan K2Hub</div>
            </div>
        </div>
        <div class="warung-header-actions">
            @yield('header-actions')
        </div>
    </div>
</header>

<!-- Alerts (As Toasts to prevent layout shift) -->
@if(session('success') || session('error') || session('info'))
<script>
    document.addEventListener('DOMContentLoaded', () => {
        @if(session('success'))
            showToast("{{ session('success') }}", 'success');
        @endif
        @if(session('error'))
            showToast("{{ session('error') }}", 'error');
        @endif
        @if(session('info'))
            showToast("{{ session('info') }}", 'warning');
        @endif
    });
</script>
@endif

<!-- Main Content -->
<main class="warung-main">
    @yield('content')
</main>

<!-- Toast Container -->
<div id="toast-container"></div>

<script>
    const CSRF_TOKEN = document.querySelector('meta[name="csrf-token"]').content;

    function showToast(message, type = 'success', duration = 3500) {
        const container = document.getElementById('toast-container');
        const toast = document.createElement('div');
        toast.className = `toast toast-${type}`;
        const icons = { success: '✅', error: '❌', warning: '⚠️' };
        toast.innerHTML = `<span>${icons[type] || '💬'}</span><span>${message}</span>`;
        container.appendChild(toast);
        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.4s';
            setTimeout(() => toast.remove(), 400);
        }, duration);
    }

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

    // Auto-dismiss alerts
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(el => {
            el.style.transition = 'opacity 0.5s';
            el.style.opacity = '0';
            setTimeout(() => el.remove(), 500);
        });
    }, 4000);
</script>

@stack('scripts')
</body>
</html>
