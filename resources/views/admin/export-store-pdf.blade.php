<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Detail {{ $store->name }} — {{ $dateFrom }} sd {{ $dateTo }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.4; padding: 20px; background: #fff; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .header h1 { margin: 0 0 5px; font-size: 24px; text-transform: uppercase; letter-spacing: 0.5px; }
        .header p { margin: 0; font-size: 14px; color: #666; }
        
        .summary-box { display: flex; justify-content: space-between; margin-bottom: 30px; background: #f9f9f9; border: 1px solid #ddd; padding: 15px 20px; border-radius: 6px; }
        .summary-item { font-size: 14px; }
        .summary-item strong { display: block; font-size: 18px; margin-top: 5px; color: #c6345d; }

        .section-title { font-size: 14px; font-weight: bold; text-transform: uppercase; margin-top: 25px; margin-bottom: 10px; border-bottom: 1px solid #ddd; padding-bottom: 5px; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f2f2f2; font-size: 11px; font-weight: 700; text-transform: uppercase; border: 1px solid #ddd; padding: 8px 10px; text-align: left; }
        td { font-size: 12px; border: 1px solid #ddd; padding: 8px 10px; }
        tr:nth-child(even) { background: #fafafa; }
        
        .footer { text-align: center; font-size: 11px; color: #888; margin-top: 50px; border-top: 1px solid #eee; padding-top: 15px; }
        
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Laporan Keuangan Toko: {{ $store->name }}</h1>
    <p>SMP Al Amanah — Unit Usaha: {{ ucfirst($store->unit) }} ({{ $store->category }})</p>
    <p style="margin-top: 5px; font-weight: bold;">Periode: {{ \Carbon\Carbon::parse($dateFrom)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($dateTo)->translatedFormat('d M Y') }}</p>
</div>

<div class="summary-box">
    <div class="summary-item">
        Total Pendapatan Toko:
        <strong>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</strong>
    </div>
    <div class="summary-item">
        Total Pesanan Selesai:
        <strong>{{ $totalOrders }} pesanan</strong>
    </div>
    <div class="summary-item" style="text-align: right;">
        Tanggal Cetak:
        <strong>{{ now()->translatedFormat('d F Y H:i') }}</strong>
    </div>
</div>

<div class="section-title">Produk Terjual</div>
<table>
    <thead>
        <tr>
            <th style="width: 50px;">No</th>
            <th>Nama Produk</th>
            <th style="text-align: right; width: 120px;">Qty Terjual</th>
            <th style="text-align: right; width: 180px;">Total Pendapatan</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @forelse($itemSales as $item)
            <tr>
                <td>{{ $no++ }}</td>
                <td style="font-weight: bold;">{{ $item->product_name }}</td>
                <td style="text-align: right;">{{ $item->total_qty }} pcs</td>
                <td style="text-align: right; font-weight: bold;">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="4" style="text-align: center; color: #888;">Tidak ada produk terjual pada periode ini.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="section-title">Daftar Transaksi</div>
<table>
    <thead>
        <tr>
            <th style="width: 50px;">No</th>
            <th>Kode Pesanan</th>
            <th>Tanggal Lunas</th>
            <th>Status</th>
            <th style="text-align: right; width: 150px;">Total Belanja</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; @endphp
        @forelse($orders as $order)
            <tr>
                <td>{{ $no++ }}</td>
                <td style="font-weight: bold;">{{ $order->order_code }}</td>
                <td>{{ $order->paid_at ? \Carbon\Carbon::parse($order->paid_at)->format('d/m/Y H:i') : '-' }}</td>
                <td>{{ ucfirst($order->status) }}</td>
                <td style="text-align: right; font-weight: bold;">Rp {{ number_format($order->total_price, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="5" style="text-align: center; color: #888;">Tidak ada riwayat transaksi pada periode ini.</td>
            </tr>
        @endforelse
    </tbody>
</table>

<div class="footer">
    Dokumen ini dicetak secara otomatis oleh sistem K2Hub SMP Al Amanah.
</div>

<script>
    window.onload = function() {
        window.print();
    }
</script>
</body>
</html>
