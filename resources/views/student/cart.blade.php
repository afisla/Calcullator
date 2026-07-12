@extends('layouts.app')

@section('title', 'Keranjang — K2Hub')

@section('header-left')
    <a href="javascript:history.back()" class="btn-back">‹ Kembali</a>
    <div style="display:flex;align-items:center;gap:8px;">
        <div class="header-logo-wrap">🛒</div>
        <div>
            <div class="brand-text-k2">Keranjang</div>
            <div class="header-subtitle">{{ $store->name ?? '' }}</div>
        </div>
    </div>
@endsection

@section('content')

{{-- Cart Items --}}
@if(!empty($cart['items']))

<div style="margin-bottom:16px;">
    <div class="section-title">🛒 Item Pesanan</div>
    <div style="background:white;border-radius:16px;overflow:hidden;box-shadow:0 2px 12px rgba(231,100,142,0.08);border:1px solid rgba(231,100,142,0.08);">
        @foreach($cart['items'] as $pid => $item)
        <div id="cart-item-{{ $pid }}" style="display:flex;align-items:center;gap:12px;padding:14px 16px;border-bottom:1px solid rgba(231,100,142,0.07);">
            {{-- Foto --}}
            <div style="width:52px;height:52px;border-radius:12px;overflow:hidden;flex-shrink:0;background:#F1F5F9;display:flex;align-items:center;justify-content:center;font-size:24px;">
                @if($item['photo'] ?? null)
                    <img src="{{ $item['photo'] }}" style="width:100%;height:100%;object-fit:cover;" alt="{{ $item['name'] }}">
                @else
                    🛍️
                @endif
            </div>
            {{-- Info --}}
            <div style="flex:1;min-width:0;">
                <div style="font-size:14px;font-weight:700;color:#BA797D;margin-bottom:2px;">{{ $item['name'] }}</div>
                @if(!empty($item['options']))
                    <div style="font-size:11px;color:#94a3b8;margin-bottom:4px;font-weight:500;line-height:1.3;">
                        @foreach($item['options'] as $key => $val)
                            <span style="background:#F3F4F6;padding:2px 6px;border-radius:4px;display:inline-block;margin-top:2px;">
                                <strong>{{ ucfirst(str_replace('_', ' ', $key)) }}:</strong> {{ $val }}
                            </span>
                        @endforeach
                    </div>
                @endif
                <div style="font-size:13px;font-weight:800;color:#96A480;">Rp {{ number_format($item['price'],0,',','.') }}</div>
            </div>
            {{-- Qty Control --}}
            <div class="qty-control" style="border-color:rgba(231,100,142,0.30);">
                <button class="qty-btn" onclick="updateQty('{{ $pid }}', -1)">−</button>
                <span class="qty-num" id="cqty-{{ $pid }}">{{ $item['qty'] }}</span>
                <button class="qty-btn" onclick="updateQty('{{ $pid }}', 1)">+</button>
            </div>
            {{-- Subtotal --}}
            <div style="font-size:13px;font-weight:800;color:#96A480;min-width:60px;text-align:right;" id="csub-{{ $pid }}">
                Rp {{ number_format($item['subtotal'],0,',','.') }}
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- Total --}}
<div style="background:#F8FAFC;border:1.5px solid #E5E7EB;border-radius:16px;padding:14px 16px;margin-bottom:20px;display:flex;justify-content:space-between;align-items:center;">
    <div>
        <div style="font-size:12px;font-weight:600;color:#94a3b8;">Total Pembayaran</div>
        <div style="font-size:22px;font-weight:900;color:#96A480;" id="cart-total-display">
            Rp {{ number_format($cart['total'],0,',','.') }}
        </div>
    </div>
    <div style="font-size:24px;">💰</div>
</div>

{{-- Checkout Form --}}
<div style="background:white;border-radius:16px;padding:18px;box-shadow:0 2px 12px rgba(231,100,142,0.08);border:1px solid rgba(231,100,142,0.08);">
    <div class="section-title" style="margin-bottom:16px;">📝 Data Pemesan</div>

    <form action="/checkout" method="POST" id="checkout-form">
        @csrf

        <div class="form-group">
            <label class="form-label">👤 Nama Lengkap <span style="color:#E7648E;">*</span></label>
            <input type="text" name="customer_name" class="form-control {{ $errors->has('customer_name') ? 'is-invalid' : '' }}"
                   placeholder="Contoh: Andi Pratama"
                   value="{{ old('customer_name') }}" required>
            @error('customer_name')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">🏫 Kelas <span style="color:#E7648E;">*</span></label>
            <input type="text" name="customer_class" class="form-control {{ $errors->has('customer_class') ? 'is-invalid' : '' }}"
                   placeholder="Contoh: 7A, 8B, 9C"
                   value="{{ old('customer_class') }}" required>
            @error('customer_class')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label class="form-label">📱 Nomor HP <span style="color:#E7648E;">*</span></label>
            <input type="tel" name="customer_phone" class="form-control {{ $errors->has('customer_phone') ? 'is-invalid' : '' }}"
                   placeholder="Contoh: 081234567890"
                   value="{{ old('customer_phone') }}" required>
            @error('customer_phone')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="divider"></div>

        {{-- Info Pembayaran --}}
        <div style="background:#FEF3C7;border-radius:12px;padding:12px;margin-bottom:16px;border:1px solid #F59E0B;">
            <div style="font-size:12px;font-weight:700;color:#92400e;margin-bottom:6px;">💳 Info Pembayaran</div>
            <div style="font-size:12px;color:#92400e;line-height:1.6;font-weight:500;">
                Setelah checkout, Anda akan diarahkan ke halaman pembayaran.<br>
                Nomor antrian akan diberikan setelah pembayaran berhasil.
            </div>
        </div>

        <button type="submit" class="btn btn-primary btn-lg btn-block" id="checkout-btn">
            🛒 Checkout Sekarang
        </button>
        <button type="button" onclick="clearCart()" class="btn btn-gray btn-block" style="margin-top:8px;">
            🗑️ Kosongkan Keranjang
        </button>
    </form>
</div>

@else
<div class="empty-state">
    <span class="empty-icon">🛒</span>
    <div class="empty-title">Keranjang Kosong</div>
    <div class="empty-text">Belum ada item di keranjang kamu.</div>
    <a href="/dashboard" class="btn btn-primary">← Mulai Belanja</a>
</div>
@endif

@endsection

@push('scripts')
<script>
    let cartTotal = {{ $cart['total'] ?? 0 }};
    const prices = {};

    @if(!empty($cart['items']))
    @foreach($cart['items'] as $pid => $item)
    prices['{{ $pid }}'] = {{ $item['price'] }};
    @endforeach
    @endif

    function updateQty(pid, delta) {
        const qtyEl = document.getElementById(`cqty-${pid}`);
        const current = parseInt(qtyEl.textContent);
        const newQty = current + delta;

        if (newQty < 0) return;

        apiPost('/keranjang/update', { product_id: pid, qty: newQty })
            .then(data => {
                if (data.error) { showToast(data.error, 'error'); return; }

                if (data.empty || newQty === 0) {
                    const item = document.getElementById(`cart-item-${pid}`);
                    if (item) {
                        item.style.opacity = '0';
                        item.style.transform = 'translateX(-20px)';
                        item.style.transition = 'all 0.3s';
                        setTimeout(() => {
                            item.remove();
                            // If empty, reload
                            if (!document.querySelector('[id^="cart-item-"]')) {
                                location.reload();
                            }
                        }, 300);
                    }
                    cartTotal = 0;
                } else {
                    qtyEl.textContent = newQty;
                    const subEl = document.getElementById(`csub-${pid}`);
                    const sub = prices[pid] * newQty;
                    if (subEl) subEl.textContent = 'Rp ' + sub.toLocaleString('id-ID');
                    cartTotal = data.total;
                }

                const totalEl = document.getElementById('cart-total-display');
                if (totalEl) totalEl.textContent = 'Rp ' + Number(cartTotal).toLocaleString('id-ID');
            });
    }

    function clearCart() {
        if (!confirm('Kosongkan semua keranjang?')) return;
        apiPost('/keranjang/kosongkan').then(() => {
            window.location = '/dashboard';
        });
    }

    document.getElementById('checkout-form')?.addEventListener('submit', function(e) {
        const btn = document.getElementById('checkout-btn');
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner" style="width:16px;height:16px;border-width:2px;"></span> Memproses...';
    });
</script>
@endpush
