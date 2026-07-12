@extends('layouts.app')

@section('title', $store->name . ' — K2Hub')

@section('header-left')
    <a href="{{ route('kantin.index') }}" class="btn-back">‹ Kembali</a>
    <div style="display:flex;align-items:center;gap:8px;">
        <div class="header-logo-wrap">{{ $store->icon_emoji ?? '🍽️' }}</div>
        <div>
            <div class="brand-text-k2">{{ $store->name }}</div>
            <div class="header-subtitle">{{ $store->category }}</div>
        </div>
    </div>
    @if($store->is_open)
        <span style="background:rgba(169,215,112,0.25);border:1px solid rgba(169,215,112,0.5);color:#A9D770;font-size:11px;font-weight:800;padding:4px 10px;border-radius:999px;">● Buka</span>
    @else
        <span style="background:rgba(218,214,211,0.25);border:1px solid rgba(218,214,211,0.4);color:rgba(255,255,255,0.6);font-size:11px;font-weight:800;padding:4px 10px;border-radius:999px;">● Tutup</span>
    @endif
@endsection

@section('content')

{{-- Status Toko --}}
@if(!$store->is_open)
<div class="alert alert-warning alert-permanent">⚠️ Warung ini sedang <strong>tutup</strong>. Anda masih bisa melihat menu.</div>
@endif

{{-- Search --}}
<div class="search-wrap">
    <span class="search-icon">🔍</span>
    <input type="text" id="search-input" class="search-input" placeholder="Cari menu..." oninput="filterMenu()">
</div>

{{-- Pesanan Aktif di Warung Ini --}}
@php
    $myOrders = $activeOrders->where('store_id', $store->id)->where('session_token', session('anonymous_token'));
@endphp
@if($myOrders->isNotEmpty())
<div style="margin-bottom:16px;" id="my-orders-section">
    <div class="section-title">⭐ Pesanan Aktif Kamu</div>
    @foreach($myOrders as $order)
    <div style="background:#FFFBEB;border:1.5px solid #f59e0b;border-radius:14px;padding:12px 14px;margin-bottom:8px;display:flex;align-items:center;justify-content:space-between;">
        <div>
            <div style="font-family:'Fredoka One',cursive;font-size:20px;color:#C6345D;">{{ $order->queue_code ?? 'No.'.$order->queue_number }}</div>
            <div style="font-size:12px;font-weight:600;color:#92400e;">{{ $order->status_icon }} {{ $order->status_label }}</div>
        </div>
        <a href="/pesanan/{{ $order->order_code }}" class="btn btn-sm" style="background:#f59e0b;color:white;border-radius:999px;font-size:12px;">Detail →</a>
    </div>
    @endforeach
</div>
@endif

{{-- Info cart beda toko --}}
@php $cart = session('cart'); @endphp
@if($cart && !empty($cart['items']) && $cart['store_id'] != $store->id)
<div class="alert alert-warning">
    🛒 Keranjang berisi pesanan dari <strong>{{ $cart['store_name'] }}</strong>.
    <button onclick="clearCartAndReload()" style="margin-left:8px;background:#E7648E;color:white;border:none;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:700;cursor:pointer;">Kosongkan</button>
</div>
@endif

{{-- Product Grid --}}
<div class="section-title">📋 Menu
    <span class="section-title-sub">{{ $products->count() }} item</span>
</div>

{{-- Filter Kategori --}}
<div style="display:flex;gap:8px;margin-bottom:14px;overflow-x:auto;padding-bottom:4px;scrollbar-width:none;">
    <button class="filter-btn active" onclick="setFilter('all',this)" style="white-space:nowrap;padding:6px 14px;border-radius:999px;font-size:12px;font-weight:700;border:2px solid #96A480;background:#96A480;color:white;cursor:pointer;transition:all 0.2s;">Semua</button>
    <button class="filter-btn" onclick="setFilter('tersedia',this)" style="white-space:nowrap;padding:6px 14px;border-radius:999px;font-size:12px;font-weight:700;border:2px solid #96A480;background:transparent;color:#96A480;cursor:pointer;transition:all 0.2s;">✅ Tersedia</button>
</div>

<div id="product-grid" class="product-grid">
    @forelse($products as $product)
    <div class="product-card"
         data-name="{{ strtolower($product->name) }}"
         data-available="{{ ($product->is_available && $product->stock > 0) ? 'tersedia' : 'habis' }}"
         id="prod-card-{{ $product->id }}">

        {{-- Foto --}}
        <div style="position:relative; width:100%; aspect-ratio:4/3; overflow:hidden; flex-shrink:0;">
            @if($product->photo)
                <img src="{{ asset('storage/'.$product->photo) }}" alt="{{ $product->name }}" class="product-img" loading="lazy" style="width:100%; height:100%; object-fit:cover;">
            @else
                <div class="product-img-placeholder" style="width:100%; height:100%; font-size:40px; display:flex; align-items:center; justify-content:center; background:#F1F5F9;">{{ $store->icon_emoji ?? '🍽️' }}</div>
            @endif
            
            @if(!$product->is_available || $product->stock <= 0)
                <div style="position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.6);color:white;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:16px;z-index:2;letter-spacing:1px;">HABIS</div>
            @endif
        </div>

        <div class="product-info">
            <div class="product-name">{{ $product->name }}</div>
            @if($product->description)
                <div style="font-size:11px;color:#94a3b8;margin-bottom:4px;font-weight:500;">{{ Str::limit($product->description,50) }}</div>
            @endif
            <div class="product-price">{{ $product->formatted_price }}</div>
            <div class="product-stock {{ $product->stock <= 5 ? 'low' : ($product->stock <= 0 ? 'out' : '') }}">
                @if($product->stock <= 0) Habis @else Sisa: {{ $product->stock }} @endif
            </div>

            @if(!$store->is_open)
                <button disabled style="width:100%;padding:8px;background:#f1f5f9;color:#94a3b8;border:none;border-radius:10px;font-size:13px;font-weight:700;margin-top:auto;cursor:not-allowed;">Toko Tutup</button>
            @elseif(!$product->is_available || $product->stock <= 0)
                <button disabled style="width:100%;padding:8px;background:#f1f5f9;color:#94a3b8;border:none;border-radius:10px;font-size:13px;font-weight:700;margin-top:auto;">Habis</button>
            @else
                <div style="margin-top:auto;">
                    <div class="qty-control" id="qty-ctrl-{{ $product->id }}" style="display:none;margin-left:auto;margin-right:auto;">
                        <button class="qty-btn" onclick="changeQty({{ $product->id }}, -1)">−</button>
                        <span class="qty-num" id="qty-{{ $product->id }}">0</span>
                        <button class="qty-btn" onclick="changeQty({{ $product->id }}, 1)">+</button>
                    </div>
                    <button class="btn btn-primary btn-sm btn-block"
                            id="add-btn-{{ $product->id }}"
                            onclick="addToCart({{ $product->id }}, this)"
                            style="margin-top:6px;">
                        + Tambah
                    </button>
                </div>
            @endif
        </div>
    </div>
    @empty
    <div style="grid-column:span 2;">
        <div class="empty-state">
            <span class="empty-icon">🍽️</span>
            <div class="empty-title">Belum ada menu</div>
            <div class="empty-text">Warung ini belum menambahkan menu.</div>
        </div>
    </div>
    @endforelse
</div>

{{-- Floating cart bar --}}
<div id="cart-bar" style="display:none;position:fixed;bottom:0;left:0;right:0;background:#BA797D;padding:12px 20px;z-index:99;max-width:520px;margin:0 auto;border-radius:20px 20px 0 0;box-shadow:0 -4px 20px rgba(186,121,125,0.2);">
    <div style="display:flex;align-items:center;justify-content:space-between;max-width:520px;margin:0 auto;">
        <div>
            <div style="font-size:12px;color:rgba(255,255,255,0.75);font-weight:600;" id="bar-count">0 item</div>
            <div style="font-size:18px;font-weight:900;color:#A9D770;" id="bar-total">Rp 0</div>
        </div>
        <a href="/keranjang" style="background:white;color:#BA797D;padding:10px 20px;border-radius:999px;font-size:14px;font-weight:800;text-decoration:none;box-shadow:0 2px 10px rgba(0,0,0,0.15);">
            Lihat Keranjang 🛒
        </a>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let cartData = {
        @if($cart && $cart['store_id'] == $store->id)
            count: {{ count($cart['items']) }},
            total: {{ $cart['total'] }},
        @else
            count: 0,
            total: 0,
        @endif
    };

    // Restore existing cart UI
    @if($cart && $cart['store_id'] == $store->id)
    @foreach($cart['items'] as $pid => $item)
    (function() {
        const ctrl = document.getElementById('qty-ctrl-{{ $pid }}');
        const btn  = document.getElementById('add-btn-{{ $pid }}');
        const qty  = document.getElementById('qty-{{ $pid }}');
        if (ctrl && btn && qty) {
            ctrl.style.display = 'flex';
            btn.style.display = 'none';
            qty.textContent = {{ $item['qty'] }};
        }
    })();
    @endforeach
    @endif

    updateCartBar();

    function addToCart(productId, btn) {
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner" style="width:14px;height:14px;border-width:2px;"></span>';

        apiPost('/keranjang/tambah', { product_id: productId, qty: 1 })
            .then(data => {
                if (data.error === 'different_store') {
                    if (confirm(`Keranjang berisi pesanan dari warung lain.\nKosongkan dan pesan dari sini?`)) {
                        apiPost('/keranjang/kosongkan').then(() => {
                            btn.disabled = false;
                            btn.textContent = '+ Tambah';
                            addToCart(productId, btn);
                        });
                    } else {
                        btn.disabled = false;
                        btn.textContent = '+ Tambah';
                    }
                    return;
                }
                if (data.error) {
                    showToast(data.error, 'error');
                    btn.disabled = false;
                    btn.textContent = '+ Tambah';
                    return;
                }

                const ctrl = document.getElementById(`qty-ctrl-${productId}`);
                const addBtn = document.getElementById(`add-btn-${productId}`);
                if (ctrl && addBtn) {
                    ctrl.style.display = 'flex';
                    addBtn.style.display = 'none';
                }
                document.getElementById(`qty-${productId}`).textContent = data.qty;
                cartData = { count: data.item_count, total: data.total };
                updateCartBar();
                showToast('Ditambahkan ke keranjang! 🛒');
            });
    }

    function changeQty(productId, delta) {
        const qtyEl = document.getElementById(`qty-${productId}`);
        const current = parseInt(qtyEl.textContent);
        const newQty = current + delta;

        if (newQty < 0) return;

        apiPost('/keranjang/update', { product_id: productId, qty: newQty })
            .then(data => {
                if (data.error) { showToast(data.error, 'error'); return; }

                if (data.empty || newQty === 0) {
                    const ctrl = document.getElementById(`qty-ctrl-${productId}`);
                    const btn  = document.getElementById(`add-btn-${productId}`);
                    if (ctrl && btn) {
                        ctrl.style.display = 'none';
                        btn.style.display = 'block';
                        btn.disabled = false;
                        btn.textContent = '+ Tambah';
                    }
                    cartData = { count: 0, total: 0 };
                } else {
                    qtyEl.textContent = newQty;
                    cartData = { count: data.item_count, total: data.total };
                }
                updateCartBar();
            });
    }

    function updateCartBar() {
        const bar   = document.getElementById('cart-bar');
        const count = document.getElementById('bar-count');
        const total = document.getElementById('bar-total');

        if (cartData.count > 0) {
            bar.style.display = 'block';
            count.textContent = `${cartData.count} item dipilih`;
            total.textContent = 'Rp ' + Number(cartData.total).toLocaleString('id-ID');
            document.body.style.paddingBottom = '80px';
        } else {
            bar.style.display = 'none';
            document.body.style.paddingBottom = '';
        }
    }

    let activeFilter = 'all';

    function setFilter(filter, btn) {
        activeFilter = filter;
        document.querySelectorAll('.filter-btn').forEach(b => {
            b.style.background = 'transparent';
            b.style.color = '#96A480';
        });
        btn.style.background = '#96A480';
        btn.style.color = 'white';
        filterMenu();
    }

    function filterMenu() {
        const q = document.getElementById('search-input').value.toLowerCase();
        document.querySelectorAll('#product-grid .product-card').forEach(card => {
            const nameMatch = (card.dataset.name || '').includes(q);
            const statusMatch = activeFilter === 'all' || card.dataset.available === activeFilter;
            
            if (nameMatch && statusMatch) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function clearCartAndReload() {
        if (confirm('Kosongkan keranjang?')) {
            apiPost('/keranjang/kosongkan').then(() => location.reload());
        }
    }
</script>
@endpush