@extends('layouts.app')

@section('title', 'Dashboard — K2Hub')

@section('header-left')
    <a href="/" class="btn-back">‹ Kembali</a>
@endsection

@section('content')

{{-- ====== HERO GREETING ====== --}}
<div style="background:#96A480;border-radius:20px;padding:20px;margin-bottom:18px;color:white;text-align:center;box-shadow:0 6px 24px rgba(150,164,128,0.15);border:3px solid #F9E6A7;">
    <div style="margin-bottom:4px;">
        <span class="logo-k2hub-gradient" style="font-size:28px;">K2Hub</span>
    </div>
    <div style="font-size:14px;font-weight:600;opacity:0.88;">Koperasi & Kantin SMP Al Amanah</div>
    <div style="font-size:12px;color:#F9E6A7;font-weight:600;margin-top:4px;">
        Pesan sekarang, ambil nanti!
    </div>
</div>

{{-- ====== PILIHAN UNIT USAHA ====== --}}
<div class="section-title">Pilih Unit Usaha</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">

    {{-- Koperasi --}}
    <a href="/koperasi" style="text-decoration:none;">
        <div style="background:#BA797D;border:2.5px solid #F9E6A7;border-radius:20px;padding:20px 14px;text-align:center;transition:all 0.2s;box-shadow:0 2px 12px rgba(186,121,125,0.1);"
             onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(186,121,125,0.2)'"
             onmouseout="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(186,121,125,0.1)'">
            <div style="font-size:40px;margin-bottom:8px;">🏪</div>
            <div style="font-size:16px;font-weight:800;color:#FFFFFF;margin-bottom:4px;">Koperasi</div>
            <div style="font-size:11px;font-weight:600;color:#F9E6A7;margin-bottom:8px;">Alat tulis & kebutuhan sekolah</div>
            @if($koperasiOpen > 0)
                <div style="display:inline-block;background:#dcfce7;color:#15803d;font-size:10px;font-weight:800;padding:3px 10px;border-radius:999px;">● BUKA</div>
            @else
                <div style="display:inline-block;background:#f1f5f9;color:#94a3b8;font-size:10px;font-weight:800;padding:3px 10px;border-radius:999px;">● TUTUP</div>
            @endif
        </div>
    </a>

    {{-- Kantin --}}
    <a href="/kantin" style="text-decoration:none;">
        <div style="background:#BA797D;border:2.5px solid #F9E6A7;border-radius:20px;padding:20px 14px;text-align:center;transition:all 0.2s;box-shadow:0 2px 12px rgba(186,121,125,0.1);"
             onmouseover="this.style.transform='translateY(-3px)';this.style.boxShadow='0 8px 24px rgba(186,121,125,0.2)'"
             onmouseout="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(186,121,125,0.1)'">
            <div style="font-size:40px;margin-bottom:8px;">🍱</div>
            <div style="font-size:16px;font-weight:800;color:#FFFFFF;margin-bottom:4px;">Kantin</div>
            <div style="font-size:11px;font-weight:600;color:#F9E6A7;margin-bottom:8px;">Makanan & minuman segar</div>
            @if($kantinOpen > 0)
                <div style="display:inline-block;background:#dcfce7;color:#16A34A;font-size:10px;font-weight:800;padding:3px 10px;border-radius:999px;">● {{ $kantinOpen }} Warung Buka</div>
            @else
                <div style="display:inline-block;background:#f1f5f9;color:#94a3b8;font-size:10px;font-weight:800;padding:3px 10px;border-radius:999px;">● Tutup</div>
            @endif
        </div>
    </a>
</div>

{{-- ====== CEK STATUS PESANAN ====== --}}
<div class="section-title">
    🔍 Cek Status Pesanan
    <span class="section-title-sub" id="refresh-timer" style="font-size:11px;color:#c4a8b4;">Auto-refresh 10s</span>
</div>

{{-- Form cari pesanan --}}
<div style="background:white;border-radius:16px;padding:14px;margin-bottom:16px;box-shadow:0 2px 10px rgba(231,100,142,0.10);border:1px solid rgba(231,100,142,0.10);">
    <div style="font-size:13px;font-weight:700;color:#96A480;margin-bottom:10px;">Cari pesanan kamu:</div>
    <div style="display:flex;gap:8px;">
        <input type="text" id="search-order-name" placeholder="Nama pembeli..." class="form-control" style="flex:1;border-radius:999px;font-size:13px;padding:9px 14px;">
        <button onclick="searchOrder()" class="btn btn-primary btn-sm" style="border-radius:999px;white-space:nowrap;padding:9px 16px;">
            🔍 Cari
        </button>
    </div>
</div>

{{-- Daftar pesanan aktif --}}
<div id="active-orders-list">
    @if($activeOrders->isEmpty())
        <div style="text-align:center;padding:32px 16px;background:white;border-radius:16px;border:1px solid rgba(231,100,142,0.08);">
            <span style="font-size:48px;display:block;margin-bottom:12px;">📋</span>
            <div style="font-weight:800;font-size:15px;color:#2D1B3D;margin-bottom:6px;">Belum ada pesanan aktif</div>
            <div style="font-size:13px;color:#94a3b8;font-weight:500;">Pesan sekarang di Koperasi atau Kantin!</div>
        </div>
    @else
        @foreach($activeOrders as $order)
        <div class="order-status-card animate-in" style="background:white;border-radius:16px;padding:14px;margin-bottom:10px;box-shadow:0 2px 10px rgba(231,100,142,0.08);border:1px solid rgba(231,100,142,0.08);">
            <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;">
                <div>
                    <div style="font-size:22px;font-family:'Fredoka One',cursive;color:#BA797D;line-height:1;">
                        {{ $order->queue_code ?? ('No.'.$order->queue_number) }}
                    </div>
                    <div style="font-size:13px;font-weight:700;color:#96A480;">{{ $order->customer_name }}</div>
                    <div style="font-size:11px;color:#94a3b8;font-weight:500;">{{ $order->store?->name ?? 'Toko Terhapus' }} · Kelas {{ $order->customer_class ?: '-' }}</div>
                </div>
                <div>
                    @php
                        $badgeMap = ['pending'=>'badge-pending','paid'=>'badge-paid','processing'=>'badge-processing','ready'=>'badge-ready','completed'=>'badge-completed'];
                        $badgeClass = $badgeMap[$order->status] ?? 'badge-pending';
                    @endphp
                    <span class="badge {{ $badgeClass }}">
                        {{ $order->status_icon }} {{ $order->status_label }}
                    </span>
                </div>
            </div>

            {{-- Progress tracker --}}
            <div style="display:flex;align-items:center;gap:0;margin-bottom:10px;">
                @php
                    $steps = [
                        ['icon'=>'💳','label'=>'Bayar','statuses'=>['pending']],
                        ['icon'=>'🍳','label'=>'Proses','statuses'=>['paid','processing']],
                        ['icon'=>'🎉','label'=>'Siap','statuses'=>['ready']],
                        ['icon'=>'✅','label'=>'Selesai','statuses'=>['completed']],
                    ];
                    $statusOrder = ['pending'=>0,'paid'=>1,'processing'=>1,'ready'=>2,'completed'=>3];
                    $currentStep = $statusOrder[$order->status] ?? 0;
                @endphp
                @foreach($steps as $si => $step)
                    @if($si > 0)
                        <div style="flex:1;height:2px;margin-top:-14px;background:{{ $si <= $currentStep ? '#A9D770' : '#DAD6D3' }};"></div>
                    @endif
                    <div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0;">
                        <div style="width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;background:{{ $si <= $currentStep ? ($si < $currentStep ? '#A9D770' : '#F9E6A7') : 'white' }};border:2px solid {{ $si <= $currentStep ? ($si < $currentStep ? '#A9D770' : '#F9E6A7') : '#DAD6D3' }};z-index:1;box-shadow:{{ $si === $currentStep ? '0 0 0 4px rgba(249,230,167,0.3)' : 'none' }};">
                            {{ $step['icon'] }}
                        </div>
                        <div style="font-size:9px;font-weight:700;color:{{ $si <= $currentStep ? '#96A480' : '#94a3b8' }};margin-top:3px;white-space:nowrap;">{{ $step['label'] }}</div>
                    </div>
                @endforeach
            </div>

            <div style="display:flex;justify-content:space-between;align-items:center;padding-top:8px;border-top:1px solid rgba(231,100,142,0.08);">
                <span style="font-size:13px;font-weight:800;color:#96A480;">{{ $order->formatted_total }}</span>
                <a href="/pesanan/{{ $order->order_code }}" class="btn btn-sm btn-outline" style="padding:5px 12px;font-size:12px;border-radius:999px;">
                    Detail →
                </a>
            </div>
        </div>
        @endforeach
    @endif
</div>

@endsection

@push('scripts')
<script>
    let countdown = 10;

    function refreshOrders() {
        fetch('/api/dashboard/pesanan-aktif')
            .then(r => r.json())
            .then(data => {
                if (data.html !== undefined) {
                    document.getElementById('active-orders-list').innerHTML = data.html;
                    searchOrder(); // Re-apply filter after HTML refresh
                }
            })
            .catch(() => {});
    }

    function updateTimer() {
        countdown--;
        const el = document.getElementById('refresh-timer');
        if (el) el.textContent = `Auto-refresh ${countdown}s`;
        if (countdown <= 0) {
            countdown = 10;
            refreshOrders();
        }
    }

    setInterval(updateTimer, 1000);

    function searchOrder() {
        const query = document.getElementById('search-order-name').value.toLowerCase().trim();
        const cards = document.querySelectorAll('#active-orders-list .order-status-card');
        
        cards.forEach(card => {
            const textContent = card.textContent.toLowerCase();
            if (textContent.includes(query)) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Dynamic filtering as the user types or clears the text box
    document.getElementById('search-order-name')?.addEventListener('input', searchOrder);

    document.getElementById('search-order-name')?.addEventListener('keypress', e => {
        if (e.key === 'Enter') searchOrder();
    });
</script>
@endpush
