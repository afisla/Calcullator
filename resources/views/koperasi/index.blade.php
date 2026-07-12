@extends('layouts.app')

@section('title', 'Koperasi — K2Hub')

@section('header-left')
    <a href="/dashboard" class="btn-back">‹ Kembali</a>
    <div style="display:flex;align-items:center;gap:8px;">
        <div class="header-logo-wrap" style="width:48px;height:48px;font-size:26px;">🏪</div>
        <div>
            <div class="brand-text-k2" style="font-size:22px;">Koperasi</div>
            <div class="header-subtitle" style="font-size:12px;">{{ $store->name ?? 'Koperasi Sekolah' }}</div>
        </div>
    </div>
@endsection

@section('content')

{{-- Status Toko --}}
@if(isset($store) && !$store->is_open)
<div class="alert alert-warning alert-permanent">
    ⚠️ Koperasi sedang <strong>tutup</strong>. Anda masih bisa melihat produk, namun belum bisa memesan.
</div>
@endif

{{-- Search --}}
<div class="search-wrap">
    <span class="search-icon">🔍</span>
    <input type="text" id="search-input" class="search-input" placeholder="Cari produk koperasi..." oninput="filterProducts()">
</div>

{{-- Filter kategori (jika ada) --}}
<div style="display:flex;gap:8px;margin-bottom:14px;overflow-x:auto;padding-bottom:4px;scrollbar-width:none;">
    <button class="filter-btn active" onclick="setFilter('all',this)" style="white-space:nowrap;padding:6px 14px;border-radius:999px;font-size:12px;font-weight:700;border:2px solid #96A480;background:#96A480;color:white;cursor:pointer;transition:all 0.2s;">Semua</button>
    <button class="filter-btn" onclick="setFilter('tersedia',this)" style="white-space:nowrap;padding:6px 14px;border-radius:999px;font-size:12px;font-weight:700;border:2px solid #96A480;background:transparent;color:#96A480;cursor:pointer;transition:all 0.2s;">✅ Tersedia</button>
</div>

{{-- Info cart --}}
@php $cart = session('cart'); @endphp
@if($cart && !empty($cart['items']) && $cart['store_unit'] !== 'koperasi')
<div class="alert alert-warning">
    🛒 Keranjang berisi pesanan dari <strong>{{ $cart['store_name'] }}</strong>.
    <button onclick="clearCartConfirm()" style="margin-left:8px;background:#E7648E;color:white;border:none;padding:3px 10px;border-radius:999px;font-size:12px;font-weight:700;cursor:pointer;">Kosongkan</button>
</div>
@endif

{{-- Product Grid --}}
<div id="product-grid" class="product-grid">
    @forelse($products as $product)
    <div class="product-card"
         data-name="{{ strtolower($product->name) }}"
         data-available="{{ ($product->is_available && $product->stock > 0) ? 'tersedia' : 'habis' }}">

        {{-- Foto Produk --}}
        <div style="position:relative; width:100%; aspect-ratio:4/3; overflow:hidden; flex-shrink:0;">
            @if($product->photo)
                <img src="{{ asset('storage/'.$product->photo) }}"
                     alt="{{ $product->name }}"
                     class="product-img"
                     loading="lazy"
                     style="width:100%; height:100%; object-fit:cover;">
            @else
                <div class="product-img-placeholder" style="width:100%; height:100%; font-size:40px; display:flex; align-items:center; justify-content:center; background:#F1F5F9;">🛒</div>
            @endif
            
            @if(!$product->is_available || $product->stock <= 0)
                <div style="position:absolute;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.6);color:white;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:16px;z-index:2;letter-spacing:1px;">HABIS</div>
            @endif
        </div>

        <div class="product-info">
            <div class="product-name">{{ $product->name }}</div>
            <div class="product-price">{{ $product->formatted_price }}</div>
            <div class="product-stock {{ $product->stock <= 5 ? 'low' : ($product->stock <= 0 ? 'out' : '') }}">
                Stok: {{ $product->stock }}
            </div>

            @if(isset($store) && !$store->is_open)
                <button disabled style="width:100%;padding:8px;background:#f1f5f9;color:#94a3b8;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:not-allowed;margin-top:auto;">
                    Toko Tutup
                </button>
            @elseif(!$product->is_available || $product->stock <= 0)
                <button disabled style="width:100%;padding:8px;background:#f1f5f9;color:#94a3b8;border:none;border-radius:10px;font-size:13px;font-weight:700;cursor:not-allowed;margin-top:auto;">
                    Habis
                </button>
            @else
                <div style="margin-top:auto;">
                    {{-- Qty Control --}}
                    <div class="qty-control" id="qty-ctrl-{{ $product->id }}" style="display:none;margin-left:auto;margin-right:auto;">
                        <button class="qty-btn" onclick="changeQty({{ $product->id }}, -1)">−</button>
                        <span class="qty-num" id="qty-{{ $product->id }}">0</span>
                        <button class="qty-btn" onclick="changeQty({{ $product->id }}, 1)">+</button>
                    </div>

                    <button class="btn btn-primary btn-sm btn-block"
                            id="add-btn-{{ $product->id }}"
                            onclick="addToCart({{ $product->id }}, this, '{{ addslashes($product->name) }}', '{{ $product->formatted_price }}', {{ $product->stock }}, '{{ $product->photo ? asset('storage/'.$product->photo) : '' }}')"
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
            <span class="empty-icon">🏪</span>
            <div class="empty-title">Belum ada produk</div>
            <div class="empty-text">Produk koperasi belum tersedia saat ini.</div>
        </div>
    </div>
    @endforelse
</div>

{{-- Floating Cart --}}
@php $cartCount = $cart ? count($cart['items']) : 0; @endphp
@if($cartCount > 0)
<a href="/keranjang" class="float-cart">
    🛒
    <span class="float-cart-badge" id="float-badge">{{ $cartCount }}</span>
</a>
@else
<a href="/keranjang" class="float-cart" id="float-cart-btn" style="{{ $cartCount > 0 ? '' : 'display:none' }}">
    🛒
    <span class="float-cart-badge" id="float-badge">0</span>
</a>
@endif

{{-- Styling Opsi Premium (Shopee/Tokopedia Style) --}}
<style>
    .btn-opt {
        background: #F8F5F2;
        border: 2px solid #F9E6A7;
        color: #BA797D;
        padding: 8px 14px;
        font-family: inherit;
        font-size: 13px;
        font-weight: 700;
        border-radius: 10px;
        cursor: pointer;
        transition: all 0.2s;
        min-width: 60px;
        text-align: center;
    }
    .btn-opt:hover {
        background: #fdfaf7;
        border-color: #BA797D;
    }
    .btn-opt.active {
        background: #BA797D;
        border-color: #BA797D;
        color: white;
        box-shadow: 0 4px 10px rgba(186,121,125,0.2);
    }
    .option-group-title {
        font-size: 12px;
        font-weight: 800;
        color: #96A480;
        margin-bottom: 8px;
        text-transform: uppercase;
        letter-spacing: 0.5px;
    }
</style>

{{-- Modal Pilihan Atribut Produk --}}
<div id="options-modal" class="modal-overlay">
    <div class="modal-sheet" style="padding: 20px;">
        <div class="modal-handle"></div>
        
        {{-- Header Modal: Foto, Harga, Stok --}}
        <div style="display:flex;gap:16px;margin-bottom:20px;position:relative;padding-bottom:16px;border-bottom:1px solid rgba(231,100,142,0.08);">
            <div style="width:84px;height:84px;border-radius:14px;overflow:hidden;background:#F8F5F2;flex-shrink:0;border:1.5px solid #F9E6A7;display:flex;align-items:center;justify-content:center;">
                <img id="opt-modal-img" src="" style="width:100%;height:100%;object-fit:cover;display:none;">
                <div id="opt-modal-placeholder" style="font-size:36px;">🛒</div>
            </div>
            <div style="display:flex;flex-direction:column;justify-content:center;min-width:0;padding-right:20px;">
                <h3 id="opt-modal-title" style="font-size:16px;font-weight:800;color:#BA797D;margin-bottom:4px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;">Pilih Opsi</h3>
                <div id="opt-modal-price" style="font-size:18px;font-weight:900;color:#96A480;margin-bottom:2px;">Rp 0</div>
                <div id="opt-modal-stock" style="font-size:12px;color:#94a3b8;font-weight:700;">Stok: -</div>
            </div>
            <button type="button" onclick="closeOptionsModal()" style="position:absolute;top:-4px;right:-4px;background:none;border:none;font-size:24px;color:#c4a8b4;cursor:pointer;line-height:1;font-weight:300;">&times;</button>
        </div>
        
        <form id="options-form" onsubmit="submitOptions(event)">
            <input type="hidden" id="opt-product-id">
            
            {{-- Bagian Opsi Seragam --}}
            <div id="uniform-options" style="display:none;flex-direction:column;gap:16px;margin-bottom:20px;">
                <!-- Opsi Lengan -->
                <div class="option-group">
                    <div class="option-group-title">Jenis Lengan</div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        <button type="button" class="btn-opt active" onclick="selectBoxOption(this, 'opt-lengan', 'Lengan Pendek')">Lengan Pendek</button>
                        <button type="button" class="btn-opt" onclick="selectBoxOption(this, 'opt-lengan', 'Lengan Panjang')">Lengan Panjang</button>
                    </div>
                    <input type="hidden" id="opt-lengan" value="Lengan Pendek">
                </div>

                <!-- Opsi Ukuran Baju -->
                <div class="option-group">
                    <div class="option-group-title">Ukuran Baju</div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        <button type="button" class="btn-opt active" onclick="selectBoxOption(this, 'opt-ukuran-baju', 'S')">S</button>
                        <button type="button" class="btn-opt" onclick="selectBoxOption(this, 'opt-ukuran-baju', 'M')">M</button>
                        <button type="button" class="btn-opt" onclick="selectBoxOption(this, 'opt-ukuran-baju', 'L')">L</button>
                        <button type="button" class="btn-opt" onclick="selectBoxOption(this, 'opt-ukuran-baju', 'XL')">XL</button>
                    </div>
                    <input type="hidden" id="opt-ukuran-baju" value="S">
                </div>

                <!-- Opsi Bawahan -->
                <div class="option-group">
                    <div class="option-group-title">Bawahan</div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        <button type="button" class="btn-opt active" onclick="selectBoxOption(this, 'opt-bawahan', 'Celana')">Celana</button>
                        <button type="button" class="btn-opt" onclick="selectBoxOption(this, 'opt-bawahan', 'Rok')">Rok</button>
                    </div>
                    <input type="hidden" id="opt-bawahan" value="Celana">
                </div>

                <!-- Opsi Ukuran Bawahan -->
                <div class="option-group">
                    <div class="option-group-title">Ukuran Bawahan</div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        <button type="button" class="btn-opt active" onclick="selectBoxOption(this, 'opt-ukuran-bawahan', '28')">28</button>
                        <button type="button" class="btn-opt" onclick="selectBoxOption(this, 'opt-ukuran-bawahan', '30')">30</button>
                        <button type="button" class="btn-opt" onclick="selectBoxOption(this, 'opt-ukuran-bawahan', '32')">32</button>
                        <button type="button" class="btn-opt" onclick="selectBoxOption(this, 'opt-ukuran-bawahan', '33')">33</button>
                        <button type="button" class="btn-opt" onclick="selectBoxOption(this, 'opt-ukuran-bawahan', '36')">36</button>
                        <button type="button" class="btn-opt" onclick="selectBoxOption(this, 'opt-ukuran-bawahan', '38')">38</button>
                    </div>
                    <input type="hidden" id="opt-ukuran-bawahan" value="28">
                </div>
            </div>

            {{-- Bagian Opsi Bed Kelas --}}
            <div id="class-options" style="display:none;flex-direction:column;gap:16px;margin-bottom:20px;">
                <div class="option-group">
                    <div class="option-group-title">Pilih Tingkat Kelas</div>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        <button type="button" class="btn-opt active" onclick="selectBoxOption(this, 'opt-kelas', 'VII')">VII</button>
                        <button type="button" class="btn-opt" onclick="selectBoxOption(this, 'opt-kelas', 'VIII')">VIII</button>
                        <button type="button" class="btn-opt" onclick="selectBoxOption(this, 'opt-kelas', 'IX')">IX</button>
                    </div>
                    <input type="hidden" id="opt-kelas" value="VII">
                </div>
            </div>

            <div style="display:flex;gap:10px;margin-top:10px;">
                <button type="button" class="btn btn-gray" onclick="closeOptionsModal()" style="flex:1;">Batal</button>
                <button type="submit" class="btn btn-primary" style="flex:1;">Tambahkan 🛒</button>
            </div>
        </form>
    </div>
</div>

@endsection

@push('scripts')
<script>
    let cartCounts = {};

    // Load existing cart counts from session
    @if($cart && !empty($cart['items']) && isset($cart['store_unit']) && $cart['store_unit'] === 'koperasi')
        @foreach($cart['items'] as $pid => $item)
        cartCounts[{{ $pid }}] = {{ $item['qty'] }};
        @endforeach
        // Show qty controls for items already in cart
        Object.keys(cartCounts).forEach(pid => {
            const ctrl = document.getElementById(`qty-ctrl-${pid}`);
            const btn  = document.getElementById(`add-btn-${pid}`);
            const qty  = document.getElementById(`qty-${pid}`);
            if (ctrl && btn && qty) {
                ctrl.style.display = 'flex';
                btn.style.display  = 'none';
                qty.textContent    = cartCounts[pid];
            }
        });
    @endif

    let currentBtn = null;

    function openOptionsModal() {
        document.getElementById('options-modal').classList.add('active');
    }

    function closeOptionsModal() {
        document.getElementById('options-modal').classList.remove('active');
        if (currentBtn) {
            currentBtn.disabled = false;
            currentBtn.textContent = '+ Tambah';
        }
    }

    function selectBoxOption(btn, inputId, val) {
        const buttons = btn.parentElement.querySelectorAll('.btn-opt');
        buttons.forEach(b => b.classList.remove('active'));
        btn.classList.add('active');
        document.getElementById(inputId).value = val;
    }

    function resetActiveOptions(section) {
        section.querySelectorAll('.option-group').forEach(group => {
            const buttons = group.querySelectorAll('.btn-opt');
            const hiddenInput = group.querySelector('input[type="hidden"]');
            if (buttons.length > 0 && hiddenInput) {
                buttons.forEach((b, idx) => {
                    if (idx === 0) {
                        b.classList.add('active');
                        hiddenInput.value = b.textContent.trim();
                    } else {
                        b.classList.remove('active');
                    }
                });
            }
        });
    }

    function submitOptions(event) {
        event.preventDefault();
        const productId = document.getElementById('opt-product-id').value;
        const productName = document.getElementById('opt-modal-title').textContent;
        const nameLower = productName.toLowerCase();
        
        let options = {};
        if (nameLower.includes('seragam putih biru') || nameLower.includes('seragam pramuka')) {
            options = {
                baju: document.getElementById('opt-lengan').value,
                ukuran_baju: document.getElementById('opt-ukuran-baju').value,
                bawahan: document.getElementById('opt-bawahan').value,
                ukuran_bawahan: document.getElementById('opt-ukuran-bawahan').value
            };
        } else if (nameLower.includes('bed kelas')) {
            options = {
                kelas: document.getElementById('opt-kelas').value
            };
        }
        
        document.getElementById('options-modal').classList.remove('active');
        if (currentBtn) {
            performAddToCart(productId, currentBtn, options);
        }
    }

    function addToCart(productId, btn, productName, productPrice, productStock, productPhoto) {
        const nameLower = productName.toLowerCase();
        
        if (nameLower.includes('seragam putih biru') || nameLower.includes('seragam pramuka') || nameLower.includes('bed kelas')) {
            currentBtn = btn;
            btn.disabled = true;
            btn.textContent = '...';
            document.getElementById('opt-product-id').value = productId;
            document.getElementById('opt-modal-title').textContent = productName;
            document.getElementById('opt-modal-price').textContent = productPrice;
            document.getElementById('opt-modal-stock').textContent = 'Stok: ' + productStock;
            
            const modalImg = document.getElementById('opt-modal-img');
            const modalPlaceholder = document.getElementById('opt-modal-placeholder');
            if (productPhoto) {
                modalImg.src = productPhoto;
                modalImg.style.display = 'block';
                modalPlaceholder.style.display = 'none';
            } else {
                modalImg.style.display = 'none';
                modalPlaceholder.style.display = 'block';
            }
            
            const uniformSection = document.getElementById('uniform-options');
            const classSection = document.getElementById('class-options');
            
            if (nameLower.includes('seragam putih biru') || nameLower.includes('seragam pramuka')) {
                uniformSection.style.display = 'flex';
                classSection.style.display = 'none';
                resetActiveOptions(uniformSection);
            } else {
                uniformSection.style.display = 'none';
                classSection.style.display = 'flex';
                resetActiveOptions(classSection);
            }
            
            openOptionsModal();
            return;
        }

        performAddToCart(productId, btn, {});
    }

    function performAddToCart(productId, btn, options = {}) {
        btn.disabled = true;
        btn.textContent = '...';

        apiPost('/keranjang/tambah', { product_id: productId, qty: 1, options: options })
            .then(data => {
                if (data.error === 'different_store') {
                    if (confirm(`Keranjang berisi pesanan dari warung lain.\n\nKosongkan dan lanjut?`)) {
                        apiPost('/keranjang/kosongkan').then(() => performAddToCart(productId, btn, options));
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

                // Show qty control only if there are no options
                const cartKey = data.cart_key || productId;
                const ctrl = document.getElementById(`qty-ctrl-${productId}`);
                const addBtn = document.getElementById(`add-btn-${productId}`);
                const hasOptions = Object.keys(options).length > 0;

                if (!hasOptions) {
                    if (ctrl && addBtn) {
                        ctrl.style.display = 'flex';
                        addBtn.style.display = 'none';
                    }
                    cartCounts[cartKey] = data.qty;
                    document.getElementById(`qty-${productId}`).textContent = data.qty;
                } else {
                    btn.disabled = false;
                    btn.textContent = '+ Tambah';
                    cartCounts[cartKey] = data.qty;
                }

                updateFloatCart(data.item_count);
                showToast('Ditambahkan ke keranjang! 🛒', 'success');
            })
            .catch(() => {
                showToast('Gagal menambahkan produk', 'error');
                btn.disabled = false;
                btn.textContent = '+ Tambah';
            });
    }

    function changeQty(productId, delta) {
        const qtyEl = document.getElementById(`qty-${productId}`);
        const current = parseInt(qtyEl.textContent);
        const newQty  = current + delta;

        if (newQty < 0) return;

        apiPost('/keranjang/update', { product_id: productId, qty: newQty })
            .then(data => {
                if (data.error) { showToast(data.error, 'error'); return; }

                if (data.empty || newQty === 0) {
                    // Kembali tampilkan tombol tambah
                    const ctrl   = document.getElementById(`qty-ctrl-${productId}`);
                    const addBtn = document.getElementById(`add-btn-${productId}`);
                    if (ctrl && addBtn) {
                        ctrl.style.display   = 'none';
                        addBtn.style.display = 'block';
                        addBtn.disabled      = false;
                        addBtn.textContent   = '+ Tambah';
                    }
                    delete cartCounts[productId];
                } else {
                    qtyEl.textContent = newQty;
                    cartCounts[productId] = newQty;
                }

                updateFloatCart(data.item_count ?? 0);
            });
    }

    function updateFloatCart(count) {
        const btn   = document.getElementById('float-cart-btn');
        const badge = document.getElementById('float-badge');

        if (count > 0) {
            if (btn) btn.style.display = 'flex';
            if (badge) badge.textContent = count;
        } else {
            if (btn) btn.style.display = 'none';
        }

        // Update header cart badge
        const headerBadge = document.querySelector('.cart-badge');
        if (headerBadge) headerBadge.textContent = count;
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
        filterProducts();
    }

    function filterProducts() {
        const q = document.getElementById('search-input').value.toLowerCase();
        document.querySelectorAll('.product-card').forEach(card => {
            const name = card.dataset.name || '';
            const nameMatch = name.includes(q);
            const statusMatch = activeFilter === 'all' || card.dataset.available === activeFilter;
            
            if (nameMatch && statusMatch) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    function clearCartConfirm() {
        if (confirm('Kosongkan keranjang?')) {
            apiPost('/keranjang/kosongkan').then(() => location.reload());
        }
    }
</script>
@endpush
