<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Keuangan K2Hub — {{ $dateFrom }} sd {{ $dateTo }}</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.4; padding: 20px; background: #fff; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
        .header h1 { margin: 0 0 5px; font-size: 24px; text-transform: uppercase; letter-spacing: 0.5px; }
        .header p { margin: 0; font-size: 14px; color: #666; }
        
        .summary-box { display: flex; justify-content: space-between; margin-bottom: 30px; background: #f9f9f9; border: 1px solid #ddd; padding: 15px 20px; border-radius: 6px; }
        .summary-item { font-size: 14px; }
        .summary-item strong { display: block; font-size: 18px; margin-top: 5px; color: #c6345d; }

        table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
        th { background: #f2f2f2; font-size: 12px; font-weight: 700; text-transform: uppercase; border: 1px solid #ddd; padding: 10px 12px; text-align: left; }
        td { font-size: 13px; border: 1px solid #ddd; padding: 10px 12px; }
        tr:nth-child(even) { background: #fafafa; }
        
        .total-row { font-weight: bold; background: #eaeff5 !important; }
        .total-row td { font-size: 14px; }
        
        .footer { text-align: center; font-size: 11px; color: #888; margin-top: 50px; border-top: 1px solid #eee; padding-top: 15px; }
        
        @media print {
            body { padding: 0; }
            .no-print { display: none; }
        }
    </style>
</head>
<body>

<div class="header">
    <h1>Laporan Keuangan K2Hub</h1>
    <p>SMP Al Amanah — Sistem Pemesanan Koperasi & Kantin</p>
    <p style="margin-top: 5px; font-weight: bold;">Periode: {{ \Carbon\Carbon::parse($dateFrom)->translatedFormat('d M Y') }} s/d {{ \Carbon\Carbon::parse($dateTo)->translatedFormat('d M Y') }}</p>
</div>

<div class="summary-box">
    <div class="summary-item">
        Total Pendapatan Unit Usaha:
        <strong>Rp {{ number_format($grandTotal, 0, ',', '.') }}</strong>
    </div>
    <div class="summary-item" style="text-align: right;">
        Tanggal Cetak:
        <strong>{{ now()->translatedFormat('d F Y H:i') }}</strong>
    </div>
</div>

<table>
    <thead>
        <tr>
            <th style="width: 50px;">No</th>
            <th>Nama Toko / Stand</th>
            <th>Unit Usaha</th>
            <th style="text-align: right; width: 120px;">Total Pesanan</th>
            <th style="text-align: right; width: 180px;">Total Pendapatan</th>
        </tr>
    </thead>
    <tbody>
        @php $no = 1; $totalOrders = 0; @endphp
        @foreach($financeData as $data)
            @php $totalOrders += $data['total_orders']; @endphp
            <tr>
                <td>{{ $no++ }}</td>
                <td style="font-weight: bold;">{{ $data['store']->name }}</td>
                <td>{{ ucfirst($data['store']->unit) }}</td>
                <td style="text-align: right;">{{ $data['total_orders'] }}</td>
                <td style="text-align: right; font-weight: bold; color: #333;">Rp {{ number_format($data['total_revenue'], 0, ',', '.') }}</td>
            </tr>
        @endforeach
        <tr class="total-row">
            <td colspan="3" style="text-align: right;">TOTAL KESELURUHAN</td>
            <td style="text-align: right;">{{ $totalOrders }}</td>
            <td style="text-align: right; color: #c6345d;">Rp {{ number_format($grandTotal, 0, ',', '.') }}</td>
        </tr>
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
