@extends('layouts.app')

@section('title', 'Status Pesanan — K2Hub')

@section('header-left')
    <a href="/dashboard" class="btn-back">‹ Beranda</a>
    <div style="display:flex;align-items:center;gap:8px;">
        <div class="header-logo-wrap">📋</div>
        <div>
            <div class="brand-text-k2">Status Pesanan</div>
            <div class="header-subtitle">{{ $order->queue_code ?? $order->order_code }}</div>
        </div>
    </div>
@endsection

@section('content')

{{-- Nomor Antrian --}}
<div id="queue-card" style="background:{{ ['pending'=>'#BA797D','paid'=>'#96A480','processing'=>'#96A480','ready'=>'#96A480','completed'=>'#96A480','rejected'=>'#BA797D'][$order->status] ?? '#96A480' }};border-radius:22px;padding:24px;text-align:center;margin-bottom:18px;box-shadow:0 6px 24px rgba(0,0,0,0.05);color:white;transition:all 0.5s;">
    <div style="font-size:12px;font-weight:700;opacity:0.80;text-transform:uppercase;letter-spacing:1px;margin-bottom:6px;color:white;">Nomor Antrian</div>
    <div id="queue-number" style="font-family:'Fredoka One',cursive;font-size:56px;line-height:1;margin-bottom:6px;color:#F9E6A7;transition:all 0.5s;">
        {{ $order->queue_code ?? ('No. '.$order->queue_number) }}
    </div>
    <div style="font-size:14px;opacity:0.85;font-weight:600;color:white;">{{ $order->store->name }}</div>
    <div style="font-size:12px;opacity:0.65;margin-top:2px;color:white;">
        {{ $order->customer_name }} · Kelas {{ $order->customer_class ?: '-' }}
        @if($order->customer_phone) · {{ $order->customer_phone }}@endif
    </div>
</div>

{{-- Progress Tracker --}}
<div class="card" style="margin-bottom:14px;">
    <div class="card-body">
        <div id="status-label" style="text-align:center;margin-bottom:16px;">
            <div style="font-size:32px;margin-bottom:6px;" id="status-icon">{{ $order->status_icon }}</div>
            <div style="font-size:18px;font-weight:900;color:#BA797D;" id="status-text">{{ $order->status_label }}</div>
            <div style="font-size:12px;color:#94a3b8;margin-top:3px;font-weight:500;" id="status-sub">
                @if($order->status === 'pending') Selesaikan pembayaran untuk melanjutkan
                @elseif($order->status === 'paid') Menunggu penjaga memproses pesanan
                @elseif($order->status === 'processing') Sabar ya, pesanan sedang diproses 🍳
                @elseif($order->status === 'ready') Silakan ambil pesanan kamu! 🎉
                @elseif($order->status === 'completed') Pesanan sudah diambil. Terima kasih!
                @else Pesanan ditolak
                @endif
            </div>
        </div>

        {{-- Tracker Steps --}}
        <div style="display:flex;align-items:center;gap:0;padding:0 8px;">
            @php
                $steps = [
                    ['icon'=>'💳','label'=>'Bayar'],
                    ['icon'=>'🍳','label'=>'Proses'],
                    ['icon'=>'🎉','label'=>'Siap'],
                    ['icon'=>'✅','label'=>'Selesai'],
                ];
                $statusOrder = ['pending'=>0,'paid'=>1,'processing'=>1,'ready'=>2,'completed'=>3,'rejected'=>-1];
                $current = $statusOrder[$order->status] ?? 0;
            @endphp
            @foreach($steps as $si => $step)
                @if($si > 0)
                    <div style="flex:1;height:3px;margin-top:-14px;background:{{ $si <= $current ? '#A9D770' : '#DAD6D3' }};transition:background 0.5s;"></div>
                @endif
                <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0;">
                    <div style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;z-index:1;transition:all 0.5s;
                        background:{{ $si < $current ? '#A9D770' : ($si === $current ? '#F9E6A7' : 'white') }};
                        border:2px solid {{ $si <= $current ? ($si < $current ? '#A9D770' : '#F9E6A7') : '#DAD6D3' }};
                        box-shadow:{{ $si === $current ? '0 0 0 5px rgba(249,230,167,0.3)' : 'none' }};">
                        {{ $step['icon'] }}
                    </div>
                    <div style="font-size:10px;font-weight:700;margin-top:4px;color:#94a3b8;">{{ $step['label'] }}</div>
                </div>
            @endforeach
        </div>
    </div>
</div>

{{-- Detail Pesanan --}}
<div class="card" style="margin-bottom:14px;">
    <div class="card-header">📝 Detail Pesanan</div>
    <div class="card-body" style="padding:0;">
        @foreach($order->items as $item)
        <div style="display:flex;justify-content:space-between;padding:12px 16px;border-bottom:1px solid rgba(231,100,142,0.07);">
            <div>
                <div style="font-size:14px;font-weight:700;color:#BA797D;">{{ $item->product_name }}</div>
                <div style="font-size:12px;color:#94a3b8;">× {{ $item->quantity }}</div>
            </div>
            <div style="font-size:14px;font-weight:800;color:#96A480;">Rp {{ number_format($item->subtotal,0,',','.') }}</div>
        </div>
        @endforeach
        <div style="display:flex;justify-content:space-between;padding:14px 16px;background:rgba(231,100,142,0.04);">
            <div style="font-weight:800;color:#BA797D;">Total</div>
            <div style="font-size:18px;font-weight:900;color:#96A480;">{{ $order->formatted_total }}</div>
        </div>
    </div>
</div>

{{-- Status Pembayaran --}}
<div style="background:white;border-radius:14px;padding:14px 16px;margin-bottom:14px;box-shadow:0 2px 10px rgba(231,100,142,0.06);border:1px solid rgba(231,100,142,0.08);display:flex;justify-content:space-between;align-items:center;">
    <div>
        <div style="font-size:12px;color:#94a3b8;font-weight:600;">Status Pembayaran</div>
        <div id="payment-status-label" style="font-size:14px;font-weight:800;color:#BA797D;">
            {{ $order->payment_status_label }}
        </div>
    </div>
    @if($order->payment_status === 'pending')
        <a href="/bayar/{{ $order->order_code }}" class="btn btn-primary btn-sm" style="border-radius:999px;">
            💳 Bayar
        </a>
    @elseif($order->payment_status === 'paid')
        <span class="badge badge-ready">✅ Lunas</span>
    @else
        <span class="badge badge-rejected">❌ {{ $order->payment_status }}</span>
    @endif
</div>

{{-- Refresh info --}}
<div style="text-align:center;margin-top:8px;">
    <span style="font-size:12px;color:#c4a8b4;font-weight:500;" id="auto-refresh-label">Auto-refresh setiap 5 detik</span>
</div>

@endsection

@push('scripts')
<script>
    const ORDER_CODE  = '{{ $order->order_code }}';
    let currentStatus = '{{ $order->status }}';
    let countdown     = 5;

    const statusConfig = {
        pending:    { icon:'⏳', text:'Menunggu Pembayaran', sub:'Selesaikan pembayaran untuk melanjutkan', color:'#BA797D' },
        paid:       { icon:'💳', text:'Belum Diproses',      sub:'Menunggu penjaga memproses pesanan',        color:'#96A480' },
        processing: { icon:'🍳', text:'Sedang Diproses',     sub:'Sabar ya, pesanan sedang diproses 🍳',      color:'#96A480' },
        ready:      { icon:'🎉', text:'Siap Diambil!',       sub:'Silakan ambil pesanan kamu sekarang! 🎉',   color:'#96A480' },
        completed:  { icon:'✅', text:'Selesai',             sub:'Pesanan sudah diambil. Terima kasih!',      color:'#96A480' },
        rejected:   { icon:'❌', text:'Ditolak',             sub:'Pesanan ditolak oleh penjual.',             color:'#BA797D' },
    };

    function updateUI(status) {
        const cfg = statusConfig[status];
        if (!cfg) return;

        document.getElementById('status-icon').textContent = cfg.icon;
        document.getElementById('status-text').textContent = cfg.text;
        document.getElementById('status-sub').textContent  = cfg.sub;

        const card = document.getElementById('queue-card');
        if (card) card.style.background = cfg.color;

        // Pulse animasi jika ready
        if (status === 'ready') {
            const qNum = document.getElementById('queue-number');
            if (qNum) {
                qNum.style.color = '#96A480';
                qNum.style.animation = 'pulse 1s ease infinite';
            }
        }
    }

    async function pollStatus() {
        try {
            const resp = await fetch(`/api/pesanan/${ORDER_CODE}/status`);
            const data = await resp.json();

            if (data.status !== currentStatus) {
                currentStatus = data.status;
                updateUI(data.status);

                if (data.status === 'ready') {
                    // Notif suara
                    try {
                        const ctx = new AudioContext();
                        const osc = ctx.createOscillator();
                        const gain = ctx.createGain();
                        osc.connect(gain); gain.connect(ctx.destination);
                        osc.frequency.value = 880;
                        gain.gain.setValueAtTime(0.3, ctx.currentTime);
                        gain.gain.exponentialRampToValueAtTime(0.001, ctx.currentTime + 0.6);
                        osc.start(); osc.stop(ctx.currentTime + 0.6);
                    } catch(e) {}
                    showToast('Pesanan kamu siap diambil! 🎉', 'success', 5000);
                }
            }
        } catch(e) {}
    }

    // Auto refresh countdown
    setInterval(() => {
        countdown--;
        const el = document.getElementById('auto-refresh-label');
        if (el) el.textContent = `Refresh dalam ${countdown} detik`;
        if (countdown <= 0) {
            countdown = 5;
            pollStatus();
            if (el) el.textContent = 'Auto-refresh setiap 5 detik';
        }
    }, 1000);

    // Pulse animation keyframe
    const style = document.createElement('style');
    style.textContent = '@keyframes pulse { 0%,100%{transform:scale(1)} 50%{transform:scale(1.05)} }';
    document.head.appendChild(style);
</script>
@endpush
