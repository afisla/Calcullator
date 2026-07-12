@extends('layouts.app')

@section('title', 'Pembayaran — K2Hub')

@section('header-left')
    <a href="/keranjang" class="btn-back">‹ Kembali</a>
    <div style="display:flex;align-items:center;gap:8px;">
        <div class="header-logo-wrap">💳</div>
        <div>
            <div class="brand-text-k2">Pembayaran</div>
            <div class="header-subtitle">Order #{{ $order->order_code }}</div>
        </div>
    </div>
@endsection

@section('content')

{{-- Nomor Antrian --}}
<div style="background:#96A480;border-radius:20px;padding:24px;text-align:center;margin-bottom:18px;box-shadow:0 6px 24px rgba(150,164,128,0.2);color:white;">
    <div style="font-size:13px;font-weight:700;opacity:0.85;margin-bottom:4px;">Nomor Antrian Kamu</div>
    <div style="font-family:'Fredoka One',cursive;font-size:52px;line-height:1;margin-bottom:4px;color:#F9E6A7;">
        {{ $order->queue_code ?? ('No. '.$order->queue_number) }}
    </div>
    <div style="font-size:13px;opacity:0.80;">{{ $order->store->name }}</div>
    <div style="font-size:12px;opacity:0.65;margin-top:4px;">{{ $order->customer_name }} · Kelas {{ $order->customer_class ?: '-' }}</div>
</div>

{{-- Ringkasan Pesanan --}}
<div class="card" style="margin-bottom:14px;">
    <div class="card-header">📋 Ringkasan Pesanan</div>
    <div class="card-body" style="padding:0;">
        @foreach($order->items as $item)
        <div style="display:flex;justify-content:space-between;padding:12px 16px;border-bottom:1px solid rgba(231,100,142,0.07);">
            <div>
                <div style="font-size:14px;font-weight:700;color:#BA797D;">{{ $item->product_name }}</div>
                <div style="font-size:12px;color:#94a3b8;">× {{ $item->quantity }}</div>
            </div>
            <div style="font-size:14px;font-weight:800;color:#96A480;">
                Rp {{ number_format($item->subtotal,0,',','.') }}
            </div>
        </div>
        @endforeach
        <div style="display:flex;justify-content:space-between;padding:14px 16px;background:#F8FAFC;">
            <div style="font-size:15px;font-weight:800;color:#BA797D;">Total</div>
            <div style="font-size:18px;font-weight:900;color:#96A480;">{{ $order->formatted_total }}</div>
        </div>
    </div>
</div>

{{-- Status Pembayaran --}}
<div id="payment-section">
    @if($order->payment_status === 'pending')

    @if(($paymentMode ?? 'simulation') === 'midtrans' && $order->payment && $order->payment->snap_token)
    <div class="card" style="margin-bottom:14px;">
        <div class="card-header">💳 Pembayaran Online (Midtrans)</div>
        <div class="card-body" style="text-align:center;">
            <div style="font-size:48px;margin-bottom:8px;">📱</div>
            <div style="font-size:14px;font-weight:700;color:#BA797D;margin-bottom:12px;">Selesaikan Pembayaran via Midtrans</div>
            <div style="margin-bottom:18px;font-size:18px;font-weight:900;color:#96A480;">{{ $order->formatted_total }}</div>
            <button id="pay-midtrans-btn" class="btn btn-primary btn-lg btn-block">
                🚀 Bayar Sekarang
            </button>
        </div>
    </div>
    @elseif(($paymentMode ?? 'simulation') === 'simulation')
    <div class="card" style="margin-bottom:14px;">
        <div class="card-header">💳 Simulasi Pembayaran</div>
        <div class="card-body">
            <div style="background:#FFF8F0;border-radius:12px;padding:14px;margin-bottom:14px;border:1px solid #fde68a;text-align:center;">
                <div style="font-size:32px;margin-bottom:8px;">📱</div>
                <div style="font-size:13px;font-weight:700;color:#92400e;margin-bottom:4px;">Mode Simulasi Aktif</div>
                <div style="font-size:12px;color:#92400e;font-weight:500;">
                    Klik tombol di bawah untuk mensimulasikan pembayaran berhasil.
                    <br>Pada sistem nyata, ini akan diarahkan ke Midtrans.
                </div>
            </div>

            {{-- QRIS Placeholder --}}
            <div style="text-align:center;padding:20px;background:white;border:2px dashed #F7C4D5;border-radius:16px;margin-bottom:14px;">
                <div style="font-size:64px;margin-bottom:8px;">📷</div>
                <div style="font-family:'Fredoka One',cursive;font-size:22px;color:#E7648E;margin-bottom:4px;">QRIS Sekolah</div>
                <div style="font-size:12px;color:#94a3b8;font-weight:500;">Scan dengan aplikasi mobile banking / dompet digital</div>
                <div style="margin-top:12px;font-size:18px;font-weight:900;color:#96A480;">{{ $order->formatted_total }}</div>
            </div>

            <button id="pay-btn" class="btn btn-primary btn-lg btn-block" onclick="simulatePay()">
                ✅ Simulasikan Pembayaran Berhasil
            </button>
        </div>
    </div>
    @endif

    @elseif($order->payment_status === 'paid')
    <div class="alert alert-success" style="font-size:15px;font-weight:700;">
        ✅ Pembayaran berhasil! Pesanan sedang diproses.
    </div>
    <a href="/pesanan/{{ $order->order_code }}" class="btn btn-primary btn-lg btn-block">
        🔍 Lihat Status Pesanan
    </a>
    @elseif(in_array($order->payment_status, ['failed','expired']))
    <div class="alert alert-error" style="font-size:15px;font-weight:700;">
        ❌ Pembayaran gagal atau kedaluwarsa.
    </div>
    <a href="/dashboard" class="btn btn-primary btn-lg btn-block">← Kembali ke Beranda</a>
    @endif
</div>

{{-- Cek Status Link --}}
<div style="text-align:center;margin-top:14px;">
    <a href="/pesanan/{{ $order->order_code }}" style="font-size:13px;color:#E7648E;font-weight:600;text-decoration:none;">
        🔍 Cek status pesanan →
    </a>
</div>

@endsection

@push('scripts')
<script>
    async function simulatePay() {
        const btn = document.getElementById('pay-btn');
        if (!btn) return;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner" style="width:16px;height:16px;border-width:2px;margin-right:6px;display:inline-block;"></span> Memproses...';

        try {
            const data = await apiPost('/bayar/{{ $order->order_code }}/simulasi');
            if (data.success) {
                showToast('Pembayaran berhasil! 🎉', 'success');
                setTimeout(() => {
                    window.location = data.redirect;
                }, 1200);
            } else {
                showToast('Gagal memproses pembayaran.', 'error');
                btn.disabled = false;
                btn.textContent = '✅ Simulasikan Pembayaran Berhasil';
            }
        } catch (e) {
            showToast('Terjadi error, coba lagi.', 'error');
            btn.disabled = false;
            btn.textContent = '✅ Simulasikan Pembayaran Berhasil';
        }
    }
</script>

@if(($paymentMode ?? 'simulation') === 'midtrans' && $order->payment && $order->payment->snap_token)
    @if(config('services.midtrans.is_production'))
        <script src="https://app.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @else
        <script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ config('services.midtrans.client_key') }}"></script>
    @endif
    <script>
        function triggerSnapPayment() {
            snap.pay('{{ $order->payment->snap_token }}', {
                onSuccess: function (result) {
                    showToast('Pembayaran berhasil! 🎉', 'success');
                    // Update database locally since localhost webhook callback is unreachable by Midtrans
                    apiPost('/bayar/{{ $order->order_code }}/simulasi')
                        .then(() => {
                            setTimeout(() => {
                                window.location = "/pesanan/{{ $order->order_code }}";
                            }, 1200);
                        })
                        .catch(() => {
                            window.location = "/pesanan/{{ $order->order_code }}";
                        });
                },
                onPending: function (result) {
                    showToast('Menunggu pembayaran...', 'info');
                    setTimeout(() => {
                        window.location = "/pesanan/{{ $order->order_code }}";
                    }, 1500);
                },
                onError: function (result) {
                    showToast('Pembayaran gagal.', 'error');
                },
                onClose: function () {
                    showToast('Pembayaran dibatalkan.', 'warning');
                }
            });
        }

        // Auto trigger Snap popup on page load
        window.addEventListener('DOMContentLoaded', () => {
            setTimeout(triggerSnapPayment, 500);
        });

        document.getElementById('pay-midtrans-btn')?.addEventListener('click', triggerSnapPayment);
    </script>
@endif
@endpush
