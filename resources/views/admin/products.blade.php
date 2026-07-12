<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Produk — Admin K2Hub</title>
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

        /* Filter */
        .filter-bar {
            display: flex; align-items: flex-end; gap: 1rem;
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 16px; padding: 1.25rem 1.5rem; margin-bottom: 2rem;
        }
        .filter-group { flex: 1; }
        .filter-label { display: block; font-size: 0.72rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.4rem; }
        .filter-select { width: 100%; background: #FFFFFF; border: 1px solid var(--border); border-radius: 10px; padding: 0.6rem 0.9rem; font-size: 0.85rem; font-family: inherit; color: var(--text-primary); outline: none; }
        .filter-select:focus { border-color: var(--primary); }
        .filter-select option { background: #FFFFFF; color: var(--text-primary); }
        .filter-btn { background: var(--primary); border: none; border-radius: 10px; padding: 0.65rem 1.5rem; font-size: 0.85rem; font-weight: 700; font-family: inherit; color: white; cursor: pointer; white-space: nowrap; transition: all 0.2s; }
        .filter-btn:hover { background: #1d4ed8; }

        /* Products Table */
        .table-wrap { background: var(--card-bg); border: 1px solid var(--border); border-radius: 18px; overflow: hidden; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #F8FAFC; border-bottom: 1px solid var(--border); }
        th { padding: 0.85rem 1.25rem; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.08em; color: var(--text-secondary); text-align: left; }
        td { padding: 0.85rem 1.25rem; font-size: 0.85rem; color: var(--text-secondary); border-bottom: 1px solid var(--border); }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #F8FAFC; }
        
        .product-photo { width: 44px; height: 44px; border-radius: 8px; object-fit: cover; background: #F1F5F9; border: 1px solid var(--border); }
        .product-info { display: flex; align-items: center; gap: 0.85rem; }
        .product-name-text { font-weight: 600; color: var(--text-primary); }
        .product-desc { font-size: 0.75rem; color: var(--text-secondary); margin-top: 0.15rem; }
        .price-td { font-weight: 700; color: var(--primary); }
        
        .badge { font-size: 0.68rem; font-weight: 600; padding: 0.2rem 0.55rem; border-radius: 50px; }
        .badge.open { background: rgba(22,163,74,0.1); color: var(--success); }
        .badge.closed { background: #E2E8F0; color: var(--text-secondary); }
        
        .empty-state { text-align: center; padding: 4rem 2rem; color: var(--text-secondary); }
    </style>
</head>
<body>

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
        <a href="{{ route('admin.products') }}" class="nav-item active" id="nav-products">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            Kelola Produk
        </a>
        <a href="{{ route('admin.accounts') }}" class="nav-item" id="nav-accounts">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Kelola Akun
        </a>
        <a href="{{ route('admin.finance') }}" class="nav-item" id="nav-finance">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
            Laporan Keuangan
        </a>
        <div class="nav-section-label">Akses Cepat</div>
        <a href="{{ route('welcome') }}" class="nav-item" id="nav-home">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
            Beranda Web
        </a>
    </nav>
</aside>

<main class="main">
    <div class="top-bar">
        <div>
            <div class="page-title">Kelola Produk</div>
            <div class="page-sub">Daftar produk semua koperasi dan kantin</div>
        </div>
    </div>

    <div class="content">
        <form method="GET" action="{{ route('admin.products') }}">
            <div class="filter-bar">
                <div class="filter-group">
                    <label class="filter-label">Filter Berdasarkan Toko</label>
                    <select name="store_id" class="filter-select">
                        <option value="">Semua Toko</option>
                        @foreach($stores as $st)
                            <option value="{{ $st->id }}" {{ $storeId == $st->id ? 'selected' : '' }}>{{ $st->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="filter-btn">🔍 Filter</button>
            </div>
        </form>

        <div class="table-wrap">
            @if($products->isEmpty())
                <div class="empty-state">Belum ada produk yang terdaftar.</div>
            @else
                <table>
                    <thead>
                        <tr>
                            <th>Foto</th>
                            <th>Info Produk</th>
                            <th>Toko / Merchant</th>
                            <th>Harga</th>
                            <th>Stok</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($products as $product)
                            <tr>
                                <td>
                                    @if($product->photo_url)
                                        <img src="{{ $product->photo_url }}" class="product-photo" alt="{{ $product->name }}">
                                    @else
                                        <div class="product-photo" style="display:flex;align-items:center;justify-content:center;font-size:1.2rem;">🍔</div>
                                    @endif
                                </td>
                                <td>
                                    <div class="product-name-text">{{ $product->name }}</div>
                                    <div class="product-desc">{{ $product->description ?? 'Tidak ada deskripsi.' }}</div>
                                </td>
                                <td>
                                    <span class="product-name-text">{{ $product->store->name }}</span>
                                </td>
                                <td class="price-td">
                                    {{ $product->formatted_price }}
                                </td>
                                <td>
                                    {{ $product->stock }} pcs
                                </td>
                                <td>
                                    <span class="badge {{ $product->is_available ? 'open' : 'closed' }}">
                                        {{ $product->is_available ? 'Tersedia' : 'Habis' }}
                                    </span>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </div>
</main>

</body>
</html>
