<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard — Kantin Al-Amanah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        :root {
            --bg: #F8F5F2;
            --sidebar-bg: #96A480;
            --card-bg: #F8F5F2;
            --border: #F9E6A7;
            --primary: #BA797D;
            --success: #96A480;
            --warning: #F9E6A7;
            --text-primary: #BA797D;
            --text-secondary: #96A480;
            --text-muted: #96A480;
            --red: #BA797D;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: var(--bg);
            color: var(--text-primary);
            min-height: 100vh;
            display: flex;
        }

        /* Sidebar */
        .sidebar {
            width: 240px; flex-shrink: 0;
            background: var(--sidebar-bg);
            border-right: 1px solid var(--border);
            display: flex; flex-direction: column;
            height: 100vh; position: sticky; top: 0;
            overflow-y: auto;
        }
        .sidebar-logo {
            padding: 1.5rem 1.25rem;
            border-bottom: 1px solid rgba(255,255,255,0.1);
        }
        .logo-badge {
            display: flex; align-items: center; gap: 0.6rem;
        }
        .logo-icon {
            width: 36px; height: 36px; border-radius: 10px;
            background: rgba(255,255,255,0.1);
            display: flex; align-items: center; justify-content: center;
            font-size: 1.1rem;
            color: #FFFFFF;
        }
        .logo-text { font-size: 0.9rem; font-weight: 700; color: #FFFFFF; }
        .logo-sub { font-size: 0.7rem; color: rgba(255,255,255,0.6); }

        .sidebar-nav { padding: 1rem 0.75rem; flex: 1; }
        .nav-section-label {
            font-size: 0.65rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.1em;
            color: rgba(255,255,255,0.4);
            padding: 0.5rem 0.5rem 0.4rem;
            margin-top: 0.5rem;
        }
        .nav-item {
            display: flex; align-items: center; gap: 0.7rem;
            padding: 0.6rem 0.75rem;
            border-radius: 10px;
            text-decoration: none;
            font-size: 0.85rem; font-weight: 500;
            color: rgba(255,255,255,0.7);
            transition: all 0.2s;
            margin-bottom: 0.15rem;
        }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: #FFFFFF; }
        .nav-item.active { background: var(--primary); color: #FFFFFF; }
        .nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }

        .sidebar-footer {
            padding: 1rem 0.75rem;
            border-top: 1px solid rgba(255,255,255,0.1);
        }
        .admin-user-info {
            display: flex; align-items: center; gap: 0.6rem;
            padding: 0.6rem 0.5rem;
            margin-bottom: 0.5rem;
        }
        .user-avatar {
            width: 32px; height: 32px; border-radius: 50%;
            background: rgba(255,255,255,0.2);
            display: flex; align-items: center; justify-content: center;
            font-size: 0.8rem; font-weight: 700;
            flex-shrink: 0;
            color: #FFFFFF;
        }
        .user-name { font-size: 0.8rem; font-weight: 600; color: #FFFFFF; }
        .user-role { font-size: 0.7rem; color: rgba(255,255,255,0.5); }
        .logout-btn {
            display: flex; align-items: center; gap: 0.6rem;
            width: 100%;
            padding: 0.55rem 0.75rem;
            border-radius: 10px;
            background: rgba(239,68,68,0.15);
            border: 1px solid rgba(239,68,68,0.3);
            color: #fca5a5;
            font-size: 0.82rem; font-weight: 500; font-family: inherit;
            cursor: pointer;
            transition: all 0.2s;
            text-decoration: none;
        }
        .logout-btn:hover { background: rgba(239,68,68,0.25); }
        .logout-btn svg { width: 15px; height: 15px; }

        /* Main Content */
        .main { flex: 1; overflow-y: auto; }

        .top-bar {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.25rem 2rem;
            border-bottom: 1px solid var(--border);
            position: sticky; top: 0; z-index: 20;
            background: #FFFFFF;
        }
        .page-title { font-size: 1.15rem; font-weight: 700; color: var(--text-primary); }
        .page-sub { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.1rem; }
        .top-bar-right { display: flex; align-items: center; gap: 0.75rem; }
        .date-badge {
            font-size: 0.78rem; color: var(--text-secondary);
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 8px; padding: 0.35rem 0.75rem;
        }

        .content { padding: 2rem; }

        /* Alert */
        .alert {
            display: flex; align-items: flex-start; gap: 0.6rem;
            padding: 0.8rem 1rem; border-radius: 12px;
            font-size: 0.83rem; margin-bottom: 1.5rem;
        }
        .alert-success { background: rgba(22,163,74,0.1); border: 1px solid rgba(22,163,74,0.2); color: var(--success); }

        /* Stats grid */
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }

        .stat-card {
            background: #F9E6A7;
            border: 1px solid var(--border);
            border-radius: 18px;
            padding: 1.5rem;
            position: relative; overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover { transform: translateY(-3px); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

        .stat-icon {
            display: flex; align-items: center; justify-content: center;
            width: 44px; height: 44px; border-radius: 12px;
            margin-bottom: 1rem;
            font-size: 1.3rem;
        }
        .stat-card.indigo .stat-icon { background: rgba(37,99,235,0.1); }
        .stat-card.emerald .stat-icon { background: rgba(22,163,74,0.1); }
        .stat-card.amber .stat-icon { background: rgba(245,158,11,0.1); }
        .stat-card.red .stat-icon { background: rgba(239,68,68,0.1); }
        .stat-card.purple .stat-icon { background: rgba(139,92,246,0.1); }
        .stat-card.teal .stat-icon { background: rgba(13,148,136,0.1); }

        .stat-value {
            font-size: 2rem; font-weight: 800; line-height: 1;
            margin-bottom: 0.3rem;
            color: #96A480;
        }

        .stat-label { font-size: 0.8rem; color: #BA797D; }

        /* Revenue section */
        .section-title {
            font-size: 1.05rem; font-weight: 700;
            margin-bottom: 1.1rem;
            display: flex; align-items: center; gap: 0.5rem;
            color: var(--text-primary);
        }

        .revenue-table-wrap {
            background: var(--card-bg);
            border: 1px solid var(--border);
            border-radius: 18px; overflow: hidden;
            margin-bottom: 2rem;
        }

        table { width: 100%; border-collapse: collapse; }
        thead tr {
            background: #F8FAFC;
            border-bottom: 1px solid var(--border);
        }
        th {
            padding: 0.85rem 1.25rem;
            font-size: 0.72rem; font-weight: 600;
            text-transform: uppercase; letter-spacing: 0.08em;
            color: var(--text-secondary);
            text-align: left;
        }
        td {
            padding: 0.9rem 1.25rem;
            font-size: 0.85rem; color: var(--text-secondary);
            border-bottom: 1px solid var(--border);
        }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #F8FAFC; }
        .td-store { display: flex; align-items: center; gap: 0.6rem; }
        .td-emoji { font-size: 1.2rem; }
        .td-name { font-weight: 600; color: var(--text-primary); }
        .td-revenue { font-weight: 700; color: var(--text-primary); }
        .td-unit-badge {
            font-size: 0.68rem; font-weight: 600;
            padding: 0.15rem 0.5rem; border-radius: 50px;
        }
        .td-unit-badge.koperasi { background: rgba(22,163,74,0.1); color: #BA797D; }
        .td-unit-badge.kantin   { background: rgba(245,158,11,0.1); color: #96A480; }

        .view-finance-link {
            font-size: 0.78rem; font-weight: 600;
            color: var(--primary);
            text-decoration: none;
            transition: color 0.2s;
        }
        .view-finance-link:hover { color: #1d4ed8; }

        /* Quick links */
        .quick-grid {
            display: grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: 1rem; margin-bottom: 2rem;
        }
        .quick-card {
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 16px; padding: 1.25rem;
            text-decoration: none; color: inherit;
            display: flex; align-items: center; gap: 0.75rem;
            transition: all 0.2s;
        }
        .quick-card:hover {
            border-color: var(--primary);
            background: rgba(37,99,235,0.04);
            transform: translateY(-2px);
        }
        .quick-icon { font-size: 1.5rem; }
        .quick-label { font-size: 0.85rem; font-weight: 600; color: var(--text-primary); }
        .quick-sub { font-size: 0.72rem; color: var(--text-secondary); margin-top: 0.1rem; }
    </style>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-badge">
            <div class="logo-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <div>
                <div class="logo-text">Admin Panel</div>
                <div class="logo-sub">Kantin Al-Amanah</div>
            </div>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-section-label">Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item active" id="nav-dashboard">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/>
            </svg>
            Dashboard
        </a>

        <div class="nav-section-label">Manajemen</div>
        <a href="{{ route('admin.stores') }}" class="nav-item" id="nav-stores">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/>
            </svg>
            Kelola Toko
        </a>
        <a href="{{ route('admin.finance') }}" class="nav-item" id="nav-finance">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/>
            </svg>
            Laporan Keuangan
        </a>

        <div class="nav-section-label">Akses Cepat</div>
        <a href="{{ route('welcome') }}" class="nav-item" id="nav-home">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                <path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/>
            </svg>
            Beranda Web
        </a>
    </nav>

    <div class="sidebar-footer">
        <div class="admin-user-info">
            <div class="user-avatar">{{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ session('admin_name') }}</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2">
                    <path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/>
                </svg>
                Logout
            </button>
        </form>
    </div>
</aside>

<!-- Main -->
<main class="main">
    <div class="top-bar">
        <div>
            <div class="page-title">Dashboard Admin</div>
            <div class="page-sub">Ringkasan keseluruhan unit usaha</div>
        </div>
        <div class="top-bar-right">
            <span class="date-badge">📅 {{ now()->translatedFormat('d F Y') }}</span>
        </div>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif

        <!-- Stats -->
        <div class="stats-grid">
            <div class="stat-card indigo">
                <div class="stat-icon">🏬</div>
                <div class="stat-value" id="stat-total-stores">{{ $totalStores }}</div>
                <div class="stat-label">Total Toko</div>
            </div>
            <div class="stat-card emerald">
                <div class="stat-icon">✅</div>
                <div class="stat-value" id="stat-open-stores">{{ $openStores }}</div>
                <div class="stat-label">Toko Buka Sekarang</div>
            </div>
            <div class="stat-card amber">
                <div class="stat-icon">🧾</div>
                <div class="stat-value" id="stat-orders-today">{{ $totalOrdersToday }}</div>
                <div class="stat-label">Pesanan Hari Ini</div>
            </div>
            <div class="stat-card red">
                <div class="stat-icon">⏳</div>
                <div class="stat-value" id="stat-pending-orders">{{ $pendingOrders }}</div>
                <div class="stat-label">Pesanan Menunggu</div>
            </div>
            <div class="stat-card purple">
                <div class="stat-icon">💰</div>
                <div class="stat-value" id="stat-revenue-today" style="font-size:1.3rem;">Rp {{ number_format($revenueToday, 0, ',', '.') }}</div>
                <div class="stat-label">Pendapatan Hari Ini</div>
            </div>
            <div class="stat-card teal">
                <div class="stat-icon">📈</div>
                <div class="stat-value" id="stat-revenue-total" style="font-size:1.3rem;">Rp {{ number_format($revenueTotal, 0, ',', '.') }}</div>
                <div class="stat-label">Total Pendapatan</div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="section-title">Kelola Toko</div>
        <div class="quick-grid">
            <a href="{{ route('admin.stores') }}" class="quick-card">
                <div class="quick-icon">🏪</div>
                <div>
                    <div class="quick-label">Kelola Toko</div>
                    <div class="quick-sub">Edit, buka/tutup toko</div>
                </div>
            </a>
            <a href="{{ route('admin.finance') }}" class="quick-card">
                <div class="quick-icon">📊</div>
                <div>
                    <div class="quick-label">Laporan Keuangan</div>
                    <div class="quick-sub">Pendapatan per toko</div>
                </div>
            </a>
            <a href="{{ route('koperasi.index') }}" class="quick-card">
                <div class="quick-icon">🏪</div>
                <div>
                    <div class="quick-label">Lihat Koperasi</div>
                    <div class="quick-sub">Halaman publik koperasi</div>
                </div>
            </a>
            <a href="{{ route('kantin.index') }}" class="quick-card">
                <div class="quick-icon">🍽️</div>
                <div>
                    <div class="quick-label">Lihat Kantin</div>
                    <div class="quick-sub">Daftar warung kantin</div>
                </div>
            </a>
        </div>

        <!-- Revenue per store -->
        <div class="section-title">Pendapatan</div>
        <div class="revenue-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>Toko</th>
                        <th>Unit</th>
                        <th>Status</th>
                        <th>Total Pendapatan</th>
                        <th>Detail</th>
                    </tr>
                </thead>
                <tbody id="revenue-table-body">
                    @foreach($revenuePerStore as $store)
                    <tr>
                        <td>
                            <div class="td-store">
                                <span class="td-emoji">{{ $store->icon_emoji }}</span>
                                <span class="td-name">{{ $store->name }}</span>
                            </div>
                        </td>
                        <td>
                            <span class="td-unit-badge {{ $store->unit }}">{{ ucfirst($store->unit) }}</span>
                        </td>
                        <td>
                            @if($store->is_open)
                                <span style="font-size:0.75rem;color:#34d399;font-weight:600;">● Buka</span>
                            @else
                                <span style="font-size:0.75rem;color:#475569;font-weight:600;">● Tutup</span>
                            @endif
                        </td>
                        <td class="td-revenue">
                            Rp {{ number_format($store->orders_sum_total_price ?? 0, 0, ',', '.') }}
                        </td>
                        <td>
                            <a href="{{ route('admin.finance.store', $store) }}" class="view-finance-link">Lihat Detail →</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</main>

<script>
    function refreshAdminStats() {
        fetch('/admin/api/stats')
            .then(r => r.json())
            .then(data => {
                document.getElementById('stat-total-stores').textContent = data.totalStores;
                document.getElementById('stat-open-stores').textContent = data.openStores;
                document.getElementById('stat-orders-today').textContent = data.totalOrdersToday;
                document.getElementById('stat-pending-orders').textContent = data.pendingOrders;
                document.getElementById('stat-revenue-today').textContent = data.revenueToday;
                document.getElementById('stat-revenue-total').textContent = data.revenueTotal;
                document.getElementById('revenue-table-body').innerHTML = data.storeTableHtml;
            })
            .catch(() => {});
    }

    // Auto refresh every 5 seconds
    setInterval(refreshAdminStats, 5000);
</script>

</body>
</html>
