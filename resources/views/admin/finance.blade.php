<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan — Admin Kantin Al-Amanah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
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
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-primary); min-height: 100vh; display: flex; }

        /* Sidebar */
        .sidebar { width: 240px; flex-shrink: 0; background: var(--sidebar-bg); border-right: 1px solid var(--border); display: flex; flex-direction: column; height: 100vh; position: sticky; top: 0; overflow-y: auto; }
        .sidebar-logo { padding: 1.5rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .logo-badge { display: flex; align-items: center; gap: 0.6rem; }
        .logo-icon { width: 36px; height: 36px; border-radius: 10px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: #FFFFFF; }
        .logo-text { font-size: 0.9rem; font-weight: 700; color: #FFFFFF; }
        .logo-sub { font-size: 0.7rem; color: rgba(255,255,255,0.6); }
        .sidebar-nav { padding: 1rem 0.75rem; flex: 1; }
        .nav-section-label { font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.4); padding: 0.5rem 0.5rem 0.4rem; margin-top: 0.5rem; }
        .nav-item { display: flex; align-items: center; gap: 0.7rem; padding: 0.6rem 0.75rem; border-radius: 10px; text-decoration: none; font-size: 0.85rem; font-weight: 500; color: rgba(255,255,255,0.7); transition: all 0.2s; margin-bottom: 0.15rem; }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: #FFFFFF; }
        .nav-item.active { background: var(--primary); color: #FFFFFF; }
        .nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }
        .sidebar-footer { padding: 1rem 0.75rem; border-top: 1px solid rgba(255,255,255,0.1); }
        .admin-user-info { display: flex; align-items: center; gap: 0.6rem; padding: 0.6rem 0.5rem; margin-bottom: 0.5rem; }
        .user-avatar { width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; flex-shrink: 0; color: #FFFFFF; }
        .user-name { font-size: 0.8rem; font-weight: 600; color: #FFFFFF; }
        .user-role { font-size: 0.7rem; color: rgba(255,255,255,0.5); }
        .logout-btn { display: flex; align-items: center; gap: 0.6rem; width: 100%; padding: 0.55rem 0.75rem; border-radius: 10px; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; font-size: 0.82rem; font-weight: 500; font-family: inherit; cursor: pointer; transition: all 0.2s; }
        .logout-btn:hover { background: rgba(239,68,68,0.25); }
        .logout-btn svg { width: 15px; height: 15px; }

        /* Main */
        .main { flex: 1; overflow-y: auto; }
        .top-bar { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 2rem; border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 20; background: #FFFFFF; }
        .page-title { font-size: 1.15rem; font-weight: 700; color: var(--text-primary); }
        .page-sub { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.1rem; }

        .content { padding: 2rem; }

        /* Date filter */
        .filter-bar {
            display: flex; align-items: flex-end; gap: 1rem;
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 16px; padding: 1.25rem 1.5rem;
            margin-bottom: 2rem;
        }
        .filter-group { flex: 1; }
        .filter-label { font-size: 0.72rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.4rem; display: block; }
        .filter-input {
            background: #FFFFFF; border: 1px solid var(--border);
            border-radius: 10px; padding: 0.6rem 0.9rem;
            font-size: 0.85rem; font-family: inherit; color: var(--text-primary);
            outline: none; transition: border-color 0.2s; width: 100%;
        }
        .filter-input:focus { border-color: var(--primary); }
        .filter-btn {
            background: var(--primary);
            border: none; border-radius: 10px; padding: 0.65rem 1.5rem;
            font-size: 0.85rem; font-weight: 700; font-family: inherit;
            color: white; cursor: pointer; transition: all 0.2s;
            white-space: nowrap;
        }
        .filter-btn:hover { background: #1d4ed8; }

        /* Grand total */
        .grand-total-card {
            background: #EFF6FF;
            border: 1px solid #BFDBFE;
            border-radius: 18px; padding: 1.5rem 2rem;
            display: flex; align-items: center; justify-content: space-between;
            margin-bottom: 2rem;
        }
        .grand-label { font-size: 0.85rem; color: var(--text-secondary); margin-bottom: 0.3rem; }
        .grand-amount { font-size: 2.2rem; font-weight: 800; color: var(--primary); }
        .grand-icon { font-size: 3rem; opacity: 0.7; }

        /* Store finance cards */
        .finance-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(280px, 1fr));
            gap: 1.25rem;
            margin-bottom: 2rem;
        }
        .finance-card {
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 18px; padding: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
        }
        .finance-card:hover { transform: translateY(-4px); border-color: var(--primary); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }
        .fc-header { display: flex; align-items: flex-start; justify-content: space-between; margin-bottom: 1rem; }
        .fc-emoji { font-size: 2rem; }
        .fc-unit { font-size: 0.68rem; font-weight: 600; padding: 0.18rem 0.5rem; border-radius: 50px; }
        .fc-unit.koperasi { background: rgba(22,163,74,0.1); color: var(--primary); }
        .fc-unit.kantin   { background: rgba(245,158,11,0.1); color: var(--success); }
        .fc-name { font-size: 0.95rem; font-weight: 700; color: var(--text-primary); margin-bottom: 0.15rem; }
        .fc-category { font-size: 0.72rem; color: var(--text-secondary); margin-bottom: 1rem; }
        .fc-stats { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 1rem; }
        .fc-stat { background: #F8FAFC; border: 1px solid var(--border); border-radius: 10px; padding: 0.65rem; }
        .fc-stat-val { font-size: 1rem; font-weight: 700; color: var(--text-primary); }
        .fc-stat-lbl { font-size: 0.68rem; color: var(--text-secondary); margin-top: 0.15rem; }
        .fc-revenue { font-size: 1.4rem; font-weight: 800; color: var(--primary); margin-bottom: 0.9rem; }
        .fc-link {
            display: flex; align-items: center; justify-content: space-between;
            text-decoration: none;
            padding: 0.6rem 0.85rem;
            background: rgba(37,99,235,0.08); border: 1px solid rgba(37,99,235,0.18);
            border-radius: 10px; color: var(--primary);
            font-size: 0.8rem; font-weight: 600;
            transition: all 0.2s;
        }
        .fc-link:hover { background: rgba(150,164,128,0.15); color: var(--success); }

        /* Top products */
        .section-title { font-size: 1.05rem; font-weight: 700; margin-bottom: 1.1rem; color: var(--text-primary); }
        .products-table-wrap {
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 18px; overflow: hidden;
        }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #F8FAFC; border-bottom: 1px solid var(--border); }
        th { padding: 0.85rem 1.25rem; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-secondary); text-align: left; }
        td { padding: 0.85rem 1.25rem; font-size: 0.85rem; color: var(--text-secondary); border-bottom: 1px solid var(--border); }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #F8FAFC; }
        .rank-badge { display: inline-flex; align-items: center; justify-content: center; width: 26px; height: 26px; border-radius: 50%; font-size: 0.75rem; font-weight: 700; }
        .rank-badge.gold   { background: rgba(245,158,11,0.2); color: var(--warning); }
        .rank-badge.silver { background: #E2E8F0; color: var(--text-secondary); }
        .rank-badge.bronze { background: rgba(239,68,68,0.1); color: #ef4444; }
        .rank-badge.other  { background: #F1F5F9; color: var(--text-secondary); }
        .product-name-td { font-weight: 600; color: var(--text-primary); }
        .revenue-td { font-weight: 700; color: var(--text-primary); }
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
        <a href="{{ route('admin.dashboard') }}" class="nav-item" id="nav-dashboard">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Dashboard
        </a>
        <div class="nav-section-label">Manajemen</div>
        <a href="{{ route('admin.stores') }}" class="nav-item" id="nav-stores">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Kelola Toko
        </a>
        <a href="{{ route('admin.finance') }}" class="nav-item active" id="nav-finance">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
            Laporan Keuangan
        </a>
        <div class="nav-section-label">Akses Cepat</div>
        <a href="{{ route('welcome') }}" class="nav-item" id="nav-home">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
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
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Logout
            </button>
        </form>
    </div>
</aside>

<!-- Main -->
<main class="main">
    <div class="top-bar">
        <div>
            <div class="page-title">Laporan Keuangan</div>
            <div class="page-sub">Pendapatan semua toko periode {{ \Carbon\Carbon::parse($dateFrom)->translatedFormat('d M Y') }} — {{ \Carbon\Carbon::parse($dateTo)->translatedFormat('d M Y') }}</div>
        </div>
    </div>

    <div class="content">
        <!-- Filter -->
        <form method="GET" action="{{ route('admin.finance') }}">
            <div class="filter-bar">
                <div class="filter-group">
                    <label class="filter-label" for="from">Dari Tanggal</label>
                    <input type="date" id="from" name="from" class="filter-input" value="{{ $dateFrom }}">
                </div>
                <div class="filter-group">
                    <label class="filter-label" for="to">Sampai Tanggal</label>
                    <input type="date" id="to" name="to" class="filter-input" value="{{ $dateTo }}">
                </div>
                <button type="submit" class="filter-btn">🔍 Filter</button>
            </div>
        </form>

        <!-- Grand Total -->
        <div class="grand-total-card">
            <div>
                <div class="grand-label">Total Pendapatan Semua Unit Usaha</div>
                <div class="grand-amount">Rp {{ number_format($grandTotal, 0, ',', '.') }}</div>
            </div>
            <div class="grand-icon">💰</div>
        </div>

        <!-- Per Store Finance -->
        <div class="section-title">📊 Pendapatan Per Toko</div>
        <div class="finance-grid">
            @foreach($financeData as $data)
            <div class="finance-card">
                <div class="fc-header">
                    <span class="fc-emoji">{{ $data['store']->icon_emoji }}</span>
                    <span class="fc-unit {{ $data['store']->unit }}">{{ ucfirst($data['store']->unit) }}</span>
                </div>
                <div class="fc-name">{{ $data['store']->name }}</div>
                <div class="fc-category">{{ $data['store']->category }}</div>
                <div class="fc-revenue">Rp {{ number_format($data['total_revenue'], 0, ',', '.') }}</div>
                <div class="fc-stats">
                    <div class="fc-stat">
                        <div class="fc-stat-val">{{ $data['total_orders'] }}</div>
                        <div class="fc-stat-lbl">Total Pesanan</div>
                    </div>
                    <div class="fc-stat">
                        <div class="fc-stat-val">Rp {{ number_format($data['avg_order'], 0, ',', '.') }}</div>
                        <div class="fc-stat-lbl">Rata-rata Pesanan</div>
                    </div>
                </div>
                <a href="{{ route('admin.finance.store', [$data['store'], 'from' => $dateFrom, 'to' => $dateTo]) }}" class="fc-link">
                    Lihat Detail Transaksi
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M5 12h14M12 5l7 7-7 7"/></svg>
                </a>
            </div>
            @endforeach
        </div>

        <!-- Top Products -->
        @if($topProducts->isNotEmpty())
        <div class="section-title">🏆 Produk Terlaris (Semua Toko)</div>
        <div class="products-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nama Produk</th>
                        <th>Total Terjual</th>
                        <th>Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $i => $product)
                    <tr>
                        <td>
                            @php
                                $rankClass = match($i) { 0 => 'gold', 1 => 'silver', 2 => 'bronze', default => 'other' };
                                $rankEmoji = match($i) { 0 => '🥇', 1 => '🥈', 2 => '🥉', default => ($i+1) };
                            @endphp
                            <span class="rank-badge {{ $rankClass }}">{{ $rankEmoji }}</span>
                        </td>
                        <td class="product-name-td">{{ $product->product_name }}</td>
                        <td>{{ $product->total_qty }} pcs</td>
                        <td class="revenue-td">Rp {{ number_format($product->total_revenue, 0, ',', '.') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        @endif
    </div>
</main>

</body>
</html>
