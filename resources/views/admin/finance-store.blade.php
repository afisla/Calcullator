<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Keuangan {{ $store->name }} — Admin</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #F8F5F2;
            --card-bg: #F8F5F2;
            --border: #F9E6A7;
            --primary: #BA797D;
            --success: #96A480;
            --warning: #F9E6A7;
            --text-primary: #BA797D;
            --text-secondary: #96A480;
            --text-muted: #96A480;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-primary); min-height: 100vh; }

        nav {
            display: flex; align-items: center; gap: 1rem;
            padding: 1.1rem 2rem; border-bottom: 1px solid var(--border);
            background: #FFFFFF;
            position: sticky; top: 0; z-index: 20;
        }
        .back-btn {
            display: flex; align-items: center; gap: 0.4rem;
            background: rgba(37,99,235,0.08); border: 1px solid rgba(37,99,235,0.2);
            border-radius: 10px; padding: 0.45rem 0.9rem;
            color: var(--primary); text-decoration: none;
            font-size: 0.82rem; font-weight: 500; transition: all 0.2s;
        }
        .back-btn:hover { background: rgba(37,99,235,0.15); }
        .nav-title { font-size: 1rem; font-weight: 700; color: var(--text-primary); }
        .nav-emoji { font-size: 1.3rem; }

        .content { max-width: 1000px; margin: 0 auto; padding: 2rem 1.5rem; }

        /* Filter */
        .filter-bar {
            display: flex; align-items: flex-end; gap: 1rem;
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 16px; padding: 1.25rem 1.5rem; margin-bottom: 2rem;
        }
        .filter-group { flex: 1; }
        .filter-label { display: block; font-size: 0.72rem; font-weight: 600; color: var(--text-secondary); text-transform: uppercase; letter-spacing: 0.06em; margin-bottom: 0.4rem; }
        .filter-input { width: 100%; background: #FFFFFF; border: 1px solid var(--border); border-radius: 10px; padding: 0.6rem 0.9rem; font-size: 0.85rem; font-family: inherit; color: var(--text-primary); outline: none; }
        .filter-input:focus { border-color: var(--primary); }
        .filter-btn { background: var(--primary); border: none; border-radius: 10px; padding: 0.65rem 1.5rem; font-size: 0.85rem; font-weight: 700; font-family: inherit; color: white; cursor: pointer; white-space: nowrap; }
        .filter-btn:hover { background: #1d4ed8; }

        /* Stats */
        .stats-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 1rem; margin-bottom: 2rem; }
        .stat-card { background: var(--card-bg); border: 1px solid var(--border); border-radius: 16px; padding: 1.25rem; }
        .stat-label { font-size: 0.75rem; color: var(--text-secondary); margin-bottom: 0.35rem; }
        .stat-value { font-size: 1.5rem; font-weight: 800; color: var(--primary); }

        /* Item sales table */
        .section-title { font-size: 1rem; font-weight: 700; margin-bottom: 1rem; margin-top: 2rem; color: var(--text-primary); }
        .table-wrap { background: var(--card-bg); border: 1px solid var(--border); border-radius: 16px; overflow: hidden; margin-bottom: 2rem; }
        table { width: 100%; border-collapse: collapse; }
        thead tr { background: #F8FAFC; border-bottom: 1px solid var(--border); }
        th { padding: 0.8rem 1.25rem; font-size: 0.72rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.07em; color: var(--text-secondary); text-align: left; }
        td { padding: 0.8rem 1.25rem; font-size: 0.84rem; color: var(--text-secondary); border-bottom: 1px solid var(--border); }
        tbody tr:last-child td { border-bottom: none; }
        tbody tr:hover td { background: #F8FAFC; }
        .font-bold { font-weight: 700; color: var(--text-primary); }
        .text-amber { color: var(--text-primary); font-weight: 700; }

        .empty-state { text-align: center; padding: 3rem; color: var(--text-secondary); }
    </style>
</head>
<body>
<nav>
    <a href="{{ route('admin.finance', ['from' => $dateFrom, 'to' => $dateTo]) }}" class="back-btn">
        ← Kembali
    </a>
    <span class="nav-emoji">{{ $store->icon_emoji }}</span>
    <span class="nav-title">{{ $store->name }}</span>
</nav>

<div class="content">
    <form method="GET" action="{{ route('admin.finance.store', $store) }}">
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

    <div class="stats-row">
        <div class="stat-card">
            <div class="stat-label">Total Pendapatan</div>
            <div class="stat-value" style="font-size:1.25rem;">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Total Pesanan</div>
            <div class="stat-value">{{ $totalOrders }}</div>
        </div>
        <div class="stat-card">
            <div class="stat-label">Rata-rata Nilai Pesanan</div>
            <div class="stat-value" style="font-size:1.2rem;">Rp {{ $totalOrders > 0 ? number_format($totalRevenue / $totalOrders, 0, ',', '.') : 0 }}</div>
        </div>
    </div>

    <!-- Item Sales -->
    <div class="section-title">📦 Produk Terjual</div>
    <div class="table-wrap">
        @if($itemSales->isEmpty())
            <div class="empty-state">Belum ada transaksi pada periode ini.</div>
        @else
        <table>
            <thead><tr>
                <th>Produk</th>
                <th>Qty Terjual</th>
                <th>Total Pendapatan</th>
            </tr></thead>
            <tbody>
                @foreach($itemSales as $item)
                <tr>
                    <td class="font-bold">{{ $item->product_name }}</td>
                    <td>{{ $item->total_qty }} pcs</td>
                    <td class="text-amber">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>

    <!-- Order History -->
    <div class="section-title">🧾 Riwayat Transaksi</div>
    <div class="table-wrap">
        @if($orders->isEmpty())
            <div class="empty-state">Belum ada transaksi pada periode ini.</div>
        @else
        <table>
            <thead><tr>
                <th>Kode Pesanan</th>
                <th>Tanggal</th>
                <th>Total</th>
                <th>Status</th>
            </tr></thead>
            <tbody>
                @foreach($orders as $order)
                <tr>
                    <td class="font-bold">{{ $order->order_code }}</td>
                    <td>{{ $order->paid_at ? \Carbon\Carbon::parse($order->paid_at)->format('d/m/Y H:i') : '-' }}</td>
                    <td class="text-amber">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
                    <td>
                        @if($order->status === 'paid')
                            <span style="font-size:0.75rem;font-weight:600;padding:0.15rem 0.5rem;border-radius:50px;background:rgba(71,85,105,0.12);color:#475569;">
                                Paid
                            </span>
                        @elseif($order->status === 'processing')
                            <span style="font-size:0.75rem;font-weight:600;padding:0.15rem 0.5rem;border-radius:50px;background:rgba(23,59,100,0.12);color:#173B64;">
                                Processing
                            </span>
                        @elseif($order->status === 'completed')
                            <span style="font-size:0.75rem;font-weight:600;padding:0.15rem 0.5rem;border-radius:50px;background:rgba(16,185,129,0.12);color:#34d399;">
                                Completed
                            </span>
                        @else
                            <span style="font-size:0.75rem;font-weight:600;padding:0.15rem 0.5rem;border-radius:50px;background:rgba(71,85,105,0.1);color:#64748b;">
                                {{ ucfirst($order->status) }}
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @endif
    </div>
</div>
</body>
</html>
