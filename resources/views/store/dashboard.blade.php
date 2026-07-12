<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="theme-color" content="#E7648E">
    <title>Dashboard {{ $store->name }} — K2Hub</title>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@400;500;600;700;800;900&display=swap" rel="stylesheet">

    <style>
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        html{overflow-y:scroll;scrollbar-gutter:stable;}
        html,body{font-family:'Nunito',sans-serif;background:#F8F5F2;color:#BA797D;min-height:100vh;-webkit-font-smoothing:antialiased;}

        :root {
            --k2-primary:#BA797D;--k2-dark:#BA797D;--k2-light:#F9E6A7;
            --k2-green:#96A480;--k2-neutral:#F9E6A7;--k2-bg:#F8F5F2;
        }

        /* Header */
        .dsh-header{background:var(--k2-primary);padding:0;position:sticky;top:0;z-index:100;}
        .dsh-header-inner{display:flex;align-items:center;justify-content:space-between;padding:12px 16px;max-width:1200px;margin:0 auto;}
        .dsh-brand{
            font-family:'Fredoka One',cursive;
            font-size:22px;
            text-decoration:none;
            background: linear-gradient(90deg, #96A480, #BA797D, #F9E6A7);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            -webkit-text-stroke: 1px #5c4534;
            display: inline-block;
            filter: drop-shadow(0 2px 4px rgba(101, 86, 75, 0.25));
        }

        /* Layout */
        .dsh-wrap{max-width:1200px;margin:0 auto;padding:16px;}

        /* Stats */
        .stats-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:10px;margin-bottom:20px;}
        .stat-card{background:var(--k2-bg);border-radius:14px;padding:14px 8px;text-align:center;border:1px solid var(--k2-neutral);display:flex;flex-direction:column;justify-content:center;align-items:center;min-height:85px;}
        .stat-num{font-family:'Fredoka One',cursive;font-size:18px;color:var(--k2-primary);line-height:1;}
        .stat-lbl{font-size:10px;font-weight:700;color:var(--k2-green);margin-top:4px;text-transform:uppercase;letter-spacing:0.3px;}

        /* Tabs */
        .tab-nav{display:flex;gap:4px;background:var(--k2-neutral);border-radius:12px;padding:4px;margin-bottom:16px;}
        .tab-btn{flex:1;padding:8px 10px;border:none;border-radius:9px;font-family:'Nunito',sans-serif;font-size:13px;font-weight:700;cursor:pointer;transition:all 0.2s;background:transparent;color:var(--k2-green);}
        .tab-btn.active{background:var(--k2-primary);color:var(--k2-bg);}
        .tab-panel{display:none;}
        .tab-panel.active{display:block;}

        /* Order card */
        .order-card{background:var(--k2-bg);border-radius:14px;padding:14px;margin-bottom:10px;border:1.5px solid var(--k2-neutral);transition:all 0.2s;}
        .order-card:hover{transform: translateY(-1px);}
        .order-queue{font-family:'Fredoka One',cursive;font-size:24px;color:var(--k2-primary);line-height:1;}
        .order-name{font-size:14px;font-weight:800;color:var(--k2-primary);}
        .order-meta{font-size:12px;color:var(--k2-green);font-weight:500;}
        .order-items{font-size:12px;color:var(--k2-primary);font-weight:600;margin:6px 0;}
        .order-total{font-size:15px;font-weight:900;color:var(--k2-primary);}

        /* Status action buttons */
        .action-btns{display:flex;gap:6px;flex-wrap:wrap;margin-top:8px;}
        .act-btn{padding:6px 12px;border:none;border-radius:999px;font-size:12px;font-weight:700;cursor:pointer;font-family:'Nunito',sans-serif;transition:all 0.2s;}
        .act-btn:hover{opacity:0.85;transform:scale(1.03);}
        .act-process{background:var(--k2-light);color:var(--k2-primary);}
        .act-ready{background:var(--k2-green);color:var(--k2-bg);}
        .act-done{background:var(--k2-green);color:var(--k2-bg);}
        .act-reject{background:var(--k2-primary);color:var(--k2-bg);}

        /* Product management */
        .prod-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:10px;margin-bottom:14px;}
        .prod-card{background:var(--k2-bg);border-radius:12px;overflow:hidden;border:1px solid var(--k2-neutral);}
        .prod-img{width:100%;aspect-ratio:1;object-fit:cover;background:#F1F5F9;}
        .prod-ph{width:100%;aspect-ratio:1;display:flex;align-items:center;justify-content:center;font-size:36px;background:#F1F5F9;}
        .prod-body{padding:10px;}
        .prod-name{font-size:12px;font-weight:700;color:var(--k2-primary);margin-bottom:2px;}
        .prod-price{font-size:14px;font-weight:900;color:#96A480;margin-bottom:4px;}
        .prod-stock{font-size:11px;font-weight:600;color:var(--k2-green);}
        .prod-actions{display:flex;gap:4px;margin-top:6px;}
        .prod-act-btn{flex:1;padding:5px 6px;border:none;border-radius:6px;font-size:10px;font-weight:700;cursor:pointer;font-family:'Nunito',sans-serif;}

        /* Forms */
        .form-group{margin-bottom:12px;}
        .form-label{display:block;font-size:12px;font-weight:700;color:#2D1B3D;margin-bottom:4px;}
        .form-control{width:100%;padding:10px 12px;border:2px solid #F7C4D5;border-radius:10px;font-family:'Nunito',sans-serif;font-size:14px;color:#2D1B3D;background:white;outline:none;transition:border-color 0.2s;}
        .form-control:focus{border-color:#E7648E;box-shadow:0 0 0 3px rgba(231,100,142,0.12);}
        .form-control-sm{padding:7px 10px;font-size:13px;border-radius:8px;}

        /* Btn */
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:5px;padding:10px 16px;border-radius:10px;font-size:13px;font-weight:700;font-family:'Nunito',sans-serif;cursor:pointer;border:none;transition:all 0.2s;text-decoration:none;white-space:nowrap;}
        .btn:active{transform:scale(0.97);}
        .btn-primary{background:#2563EB;color:white;}
        .btn-green{background:var(--k2-green);color:white;}
        .btn-danger{background:#fee2e2;color:#dc2626;}
        .btn-gray{background:#f1f5f9;color:#64748b;}
        .btn-sm{padding:6px 12px;font-size:12px;border-radius:8px;}
        .btn-block{width:100%;}

        /* Badge */
        .badge{display:inline-flex;align-items:center;padding:3px 10px;border-radius:999px;font-size:11px;font-weight:800;}
        .badge-pending{background:#f1f5f9;color:#64748b;}
        .badge-paid{background:#dbeafe;color:#1d4ed8;}
        .badge-processing{background:#ffedd5;color:#c2410c;}
        .badge-ready{background:#dcfce7;color:#15803d;}
        .badge-completed{background:#ede9fe;color:#7c3aed;}
        .badge-rejected{background:#fee2e2;color:#dc2626;}

        /* Alert */
        .alert{padding:10px 14px;border-radius:10px;font-size:13px;font-weight:600;margin-bottom:10px;display:flex;align-items:center;gap:8px;}
        .alert-success{background:#f0fdf4;color:#166534;border:1px solid #bbf7d0;}
        .alert-error{background:#fff0f5;color:#9b1c4a;border:1px solid #fda4c4;}

        /* Toast */
        #toast-container{position:fixed;bottom:20px;left:50%;transform:translateX(-50%);z-index:9999;width:calc(100% - 32px);max-width:480px;}
        .toast{background:#2D1B3D;color:white;padding:12px 16px;border-radius:10px;font-size:13px;font-weight:600;margin-top:8px;display:flex;align-items:center;gap:8px;animation:toastIn 0.35s ease;box-shadow:0 4px 16px rgba(0,0,0,0.20);}
        .toast-success{background:#15803d;}
        .toast-error{background:#be123c;}
        @keyframes toastIn{from{transform:translateY(20px);opacity:0}to{transform:translateY(0);opacity:1}}

        /* Modal */
        .modal{position:fixed;inset:0;background:rgba(45,27,61,0.60);backdrop-filter:blur(6px);z-index:200;display:flex;align-items:flex-end;justify-content:center;opacity:0;pointer-events:none;transition:opacity 0.3s;}
        .modal.open{opacity:1;pointer-events:all;}
        .modal-sheet{background:white;border-radius:20px 20px 0 0;padding:20px;width:100%;max-width:520px;max-height:90vh;overflow-y:auto;transform:translateY(100%);transition:transform 0.35s ease;}
        .modal.open .modal-sheet{transform:translateY(0);}
        .modal-handle{width:40px;height:4px;background:#DAD6D3;border-radius:999px;margin:0 auto 16px;}
        .modal-title{font-size:17px;font-weight:800;color:#2D1B3D;margin-bottom:4px;}
        .modal-sub{font-size:12px;color:#94a3b8;margin-bottom:16px;}

        @media(min-width:640px){
            .stats-grid{grid-template-columns:repeat(4,1fr);}
            .prod-grid{grid-template-columns:repeat(auto-fill,minmax(180px,1fr));}
        }

        /* Spinner */
        .spinner{width:16px;height:16px;border:2px solid rgba(255,255,255,0.3);border-top-color:white;border-radius:50%;animation:spin 0.7s linear infinite;}
        @keyframes spin{to{transform:rotate(360deg)}}

        .empty-state{text-align:center;padding:40px 20px;}
        .empty-icon{font-size:52px;display:block;margin-bottom:12px;}
    </style>
</head>
<body>

<!-- Header -->
<header class="dsh-header">
    <div style="height:3px;background:#F9E6A7;"></div>
    <div class="dsh-header-inner">
        <a href="/" class="dsh-brand">K2Hub</a>
        <div style="display:flex;align-items:center;gap:8px;">
            <form method="POST" action="{{ route('store.toggle_status', $store) }}" style="display:inline;">
                @csrf
                <button type="submit" style="background:{{ $store->is_open ? 'rgba(169,215,112,0.2)' : 'rgba(239,68,68,0.2)' }};border:1px solid {{ $store->is_open ? '#A9D770' : '#ef4444' }};color:{{ $store->is_open ? '#e2ffd5' : '#ffcfcf' }};padding:7px 12px;border-radius:8px;font-family:'Nunito',sans-serif;font-size:12px;font-weight:800;cursor:pointer;transition:all 0.2s;" title="Klik untuk mengubah status toko">
                    {{ $store->is_open ? '🟢 Toko Buka' : '🔴 Toko Tutup' }}
                </button>
            </form>
            <form method="POST" action="{{ route('store.logout', $store) }}" style="display:inline;">
                @csrf
                <button type="submit" style="background:rgba(255,255,255,0.18);border:1px solid rgba(255,255,255,0.30);color:white;padding:7px 12px;border-radius:8px;font-family:'Nunito',sans-serif;font-size:12px;font-weight:700;cursor:pointer;">
                    🔒 Keluar
                </button>
            </form>
        </div>
    </div>
</header>

<div class="dsh-wrap">

    {{-- Flash via Toast to avoid layout shifts --}}
    @if(session('success'))
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                showToast({!! json_encode(session('success')) !!}, 'success');
            });
        </script>
    @endif
    @if(session('error'))
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                showToast({!! json_encode(session('error')) !!}, 'error');
            });
        </script>
    @endif
    @if($errors->any())
        <script>
            window.addEventListener('DOMContentLoaded', () => {
                @foreach($errors->all() as $error)
                    showToast({!! json_encode($error) !!}, 'error');
                @endforeach
            });
        </script>
    @endif

    {{-- Stats --}}
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-num">{{ $activeOrders->count() }}</div>
            <div class="stat-lbl">Antrian Aktif</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">{{ $completedToday }}</div>
            <div class="stat-lbl">Selesai Hari Ini</div>
        </div>
        <div class="stat-card">
            <div class="stat-num">Rp {{ number_format($revenueToday,0,',','.') }}</div>
            <div class="stat-lbl">Pemasukan Hari Ini</div>
        </div>
    </div>

    {{-- Tabs --}}
    <div class="tab-nav">
        <button class="tab-btn active" onclick="switchTab('orders',this)">📋 Pesanan ({{ $activeOrders->count() }})</button>
        <button class="tab-btn" onclick="switchTab('products',this)">📦 Produk</button>
        <button class="tab-btn" onclick="switchTab('history',this)">📊 Riwayat</button>
    </div>

    {{-- === TAB: PESANAN AKTIF === --}}
    <div id="tab-orders" class="tab-panel active">
        @if($activeOrders->isEmpty())
        <div class="empty-state">
            <span class="empty-icon">📭</span>
            <div style="font-size:16px;font-weight:800;color:#2D1B3D;margin-bottom:6px;">Belum ada pesanan</div>
            <div style="font-size:13px;color:#94a3b8;">Pesanan yang sudah dibayar akan muncul di sini.</div>
        </div>
        @else
        <div id="orders-list">
            @foreach($activeOrders as $order)
            <div class="order-card" id="order-card-{{ $order->id }}">
                <div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:8px;">
                    <div>
                        <div class="order-queue">{{ $order->queue_code ?? 'No.'.$order->queue_number }}</div>
                        <div class="order-name">{{ $order->customer_name }}</div>
                        <div class="order-meta">Kelas {{ $order->customer_class ?: '-' }}
                            @if($order->customer_phone) · {{ $order->customer_phone }} @endif
                        </div>
                    </div>
                    <div>
                        @php $bc=['paid'=>'badge-paid','processing'=>'badge-processing','ready'=>'badge-ready']; @endphp
                        <span class="badge {{ $bc[$order->status] ?? 'badge-pending' }}" id="badge-{{ $order->id }}">
                            {{ $order->status_icon }} {{ $order->status_label }}
                        </span>
                    </div>
                </div>

                <div style="background:#FFF5F8;border-radius:10px;padding:10px;margin-bottom:8px;">
                    @foreach($order->items as $item)
                    <div style="display:flex;justify-content:space-between;font-size:13px;font-weight:600;margin-bottom:2px;">
                        <span>{{ $item->product_name }} × {{ $item->quantity }}</span>
                        <span style="color:#C6345D;">Rp {{ number_format($item->subtotal,0,',','.') }}</span>
                    </div>
                    @endforeach
                    <div style="border-top:1px solid rgba(231,100,142,0.15);margin-top:6px;padding-top:6px;display:flex;justify-content:space-between;">
                        <span style="font-size:13px;font-weight:700;color:#94a3b8;">Total</span>
                        <span class="order-total">{{ $order->formatted_total }}</span>
                    </div>
                </div>

                <div class="action-btns" id="actions-{{ $order->id }}">
                    @if($order->status === 'paid')
                        <button class="act-btn act-process" onclick="updateOrder({{ $order->id }}, 'proses')">🍳 Proses</button>
                    @elseif($order->status === 'processing')
                        <button class="act-btn act-ready" onclick="updateOrder({{ $order->id }}, 'siap')">🎉 Siap Ambil</button>
                    @elseif($order->status === 'ready')
                        <button class="act-btn act-done" onclick="updateOrder({{ $order->id }}, 'selesai')">✅ Sudah Diambil</button>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- === TAB: PRODUK === --}}
    <div id="tab-products" class="tab-panel">

        <button class="btn btn-green btn-sm" onclick="openAddProductModal()" style="margin-bottom:14px;">
            ➕ Tambah Produk
        </button>

        @php $products = $store->products()->orderBy('sort_order')->get(); @endphp

        @if($products->isEmpty())
        <div class="empty-state">
            <span class="empty-icon">📦</span>
            <div style="font-size:16px;font-weight:800;color:#2D1B3D;margin-bottom:6px;">Belum ada produk</div>
            <div style="font-size:13px;color:#94a3b8;margin-bottom:12px;">Tambahkan produk untuk mulai berjualan.</div>
        </div>
        @else
        <div class="prod-grid">
            @foreach($products as $product)
            <div class="prod-card" id="pc-{{ $product->id }}">
                @if($product->photo)
                    <img src="{{ asset('storage/'.$product->photo) }}" alt="{{ $product->name }}" class="prod-img" loading="lazy">
                @else
                    <div class="prod-ph">{{ $store->icon_emoji ?? '🛍️' }}</div>
                @endif
                <div class="prod-body">
                    <div class="prod-name">{{ $product->name }}</div>
                    <div class="prod-price">{{ $product->formatted_price }}</div>
                    <div class="prod-stock">Stok: {{ $product->stock }}</div>
                    <div style="margin-top:4px;">
                        @if($product->is_available)
                            <span style="font-size:10px;font-weight:700;background:#dcfce7;color:#15803d;padding:2px 7px;border-radius:999px;">● Tersedia</span>
                        @else
                            <span style="font-size:10px;font-weight:700;background:#fee2e2;color:#dc2626;padding:2px 7px;border-radius:999px;">● Tidak Tersedia</span>
                        @endif
                    </div>
                    <div class="prod-actions">
                        <button class="prod-act-btn" onclick="openEditProductModal({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->price }}, {{ $product->stock }}, {{ $product->is_available ? 1 : 0 }}, '{{ addslashes($product->description ?? '') }}')"
                                style="background:#dbeafe;color:#1d4ed8;">✏️ Edit</button>
                        <button class="prod-act-btn" onclick="openStockModal({{ $product->id }}, '{{ addslashes($product->name) }}', {{ $product->stock }})"
                                style="background:#dcfce7;color:#15803d;">📦 Stok</button>
                        <button class="prod-act-btn" onclick="deleteProduct({{ $product->id }}, '{{ addslashes($product->name) }}')"
                                style="background:#fee2e2;color:#dc2626;">🗑</button>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
        @endif
    </div>

    {{-- === TAB: RIWAYAT === --}}
    <div id="tab-history" class="tab-panel">
        <div style="background:white;border-radius:14px;overflow:hidden;box-shadow:0 2px 10px rgba(231,100,142,0.06);border:1px solid rgba(231,100,142,0.08);">
            <div style="padding:12px 16px;background:rgba(231,100,142,0.06);font-weight:800;font-size:14px;color:#BA797D;border-bottom:1px solid rgba(231,100,142,0.10);">
                📊 Riwayat Pesanan
            </div>
            @forelse($orderHistory->take(20) as $order)
            <div style="display:flex;align-items:center;gap:12px;padding:12px 16px;border-bottom:1px solid rgba(231,100,142,0.06);">
                <div style="font-family:'Fredoka One',cursive;font-size:18px;color:#BA797D;min-width:60px;">
                    {{ $order->queue_code ?? 'No.'.$order->queue_number }}
                </div>
                <div style="flex:1;min-width:0;">
                    <div style="font-size:13px;font-weight:700;color:#96A480;">{{ $order->customer_name }}</div>
                    <div style="font-size:11px;color:#94a3b8;">{{ $order->updated_at->format('d M · H:i') }}</div>
                </div>
                <div style="text-align:right;">
                    <div style="font-size:13px;font-weight:800;color:#96A480;">{{ $order->formatted_total }}</div>
                    <span class="badge {{ $order->status === 'completed' ? 'badge-completed' : 'badge-rejected' }}" style="font-size:10px;">
                        {{ $order->status_icon }} {{ $order->status_label }}
                    </span>
                </div>
            </div>
            @empty
            <div class="empty-state">
                <span class="empty-icon">📊</span>
                <div style="font-size:14px;color:#94a3b8;">Belum ada riwayat pesanan.</div>
            </div>
            @endforelse
        </div>
    </div>

</div><!-- end dsh-wrap -->

<!-- ===== MODAL: Tambah Produk ===== -->
<div class="modal" id="modal-add-product">
    <div class="modal-sheet">
        <div class="modal-handle"></div>
        <div class="modal-title">➕ Tambah Produk Baru</div>
        <div class="modal-sub">Isi detail produk yang ingin ditambahkan.</div>

        <form method="POST" action="{{ route('product.store', $store) }}" enctype="multipart/form-data" onsubmit="handleFormSubmit(this)">
            @csrf
            <div class="form-group">
                <label class="form-label">Nama Produk *</label>
                <input type="text" name="name" class="form-control" placeholder="Misal: Pulpen Pilot" required>
            </div>
            <div class="form-group">
                <label class="form-label">Harga (Rp) *</label>
                <input type="number" name="price" class="form-control" placeholder="5000" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Stok Awal *</label>
                <input type="number" name="stock" class="form-control" placeholder="50" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" class="form-control" rows="2" placeholder="Deskripsi singkat..."></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Foto Produk</label>
                <input type="file" name="photo" accept="image/*" class="form-control" id="photo-input-add" onchange="previewPhoto(this,'preview-add')">
                <img id="preview-add" style="width:80px;height:80px;object-fit:cover;border-radius:10px;margin-top:8px;display:none;">
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                <input type="checkbox" name="is_available" value="1" id="avail-add" checked style="width:18px;height:18px;">
                <label for="avail-add" class="form-label" style="margin:0;">Tersedia untuk dipesan</label>
            </div>
            <div style="display:flex;gap:8px;margin-top:8px;">
                <button type="button" onclick="closeModal('modal-add-product')" class="btn btn-gray" style="flex:1;">Batal</button>
                <button type="submit" class="btn btn-green" style="flex:2;">💾 Simpan Produk</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL: Edit Produk ===== -->
<div class="modal" id="modal-edit-product">
    <div class="modal-sheet">
        <div class="modal-handle"></div>
        <div class="modal-title">✏️ Edit Produk</div>
        <div class="modal-sub">Ubah detail produk.</div>

        <form id="edit-product-form" method="POST" enctype="multipart/form-data" onsubmit="handleFormSubmit(this)">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Produk *</label>
                <input type="text" name="name" id="edit-name" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">Harga (Rp) *</label>
                <input type="number" name="price" id="edit-price" class="form-control" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Deskripsi</label>
                <textarea name="description" id="edit-desc" class="form-control" rows="2"></textarea>
            </div>
            <div class="form-group">
                <label class="form-label">Ganti Foto (kosongkan jika tidak diubah)</label>
                <input type="file" name="photo" accept="image/*" class="form-control" onchange="previewPhoto(this,'preview-edit')">
                <img id="preview-edit" style="width:80px;height:80px;object-fit:cover;border-radius:10px;margin-top:8px;display:none;">
            </div>
            <div class="form-group" style="display:flex;align-items:center;gap:10px;">
                <input type="checkbox" name="is_available" value="1" id="edit-avail" style="width:18px;height:18px;">
                <label for="edit-avail" class="form-label" style="margin:0;">Tersedia untuk dipesan</label>
            </div>
            <div style="display:flex;gap:8px;margin-top:8px;">
                <button type="button" onclick="closeModal('modal-edit-product')" class="btn btn-gray" style="flex:1;">Batal</button>
                <button type="submit" class="btn btn-green" style="flex:2;">💾 Simpan Perubahan</button>
            </div>
        </form>
    </div>
</div>

<!-- ===== MODAL: Update Stok ===== -->
<div class="modal" id="modal-stock">
    <div class="modal-sheet">
        <div class="modal-handle"></div>
        <div class="modal-title">📦 Update Stok</div>
        <div class="modal-sub" id="stock-modal-sub">Stok produk saat ini: 0</div>

        <form id="stock-form" method="POST" onsubmit="handleFormSubmit(this)">
            @csrf
            <div class="form-group">
                <label class="form-label">Jumlah Stok Baru *</label>
                <input type="number" name="stock" id="stock-input" class="form-control" min="0" required>
            </div>
            <div class="form-group">
                <label class="form-label">Catatan</label>
                <input type="text" name="note" class="form-control" placeholder="Contoh: Restock dari supplier">
            </div>
            <div style="display:flex;gap:8px;margin-top:8px;">
                <button type="button" onclick="closeModal('modal-stock')" class="btn btn-gray" style="flex:1;">Batal</button>
                <button type="submit" class="btn btn-green" style="flex:2;">✅ Simpan Stok</button>
            </div>
        </form>
    </div>
</div>


<!-- Toast -->
<div id="toast-container"></div>

<script>
    const CSRF = document.querySelector('meta[name="csrf-token"]').content;
    const STORE_ID = {{ $store->id }};

    // ===== TABS =====
    function switchTab(id, btn) {
        document.querySelectorAll('.tab-panel').forEach(p => p.classList.remove('active'));
        document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
        document.getElementById('tab-' + id).classList.add('active');
        if (btn) {
            btn.classList.add('active');
        }
        localStorage.setItem('store_active_tab_' + STORE_ID, id);
    }

    // Restore active tab on load
    window.addEventListener('DOMContentLoaded', () => {
        const savedTab = localStorage.getItem('store_active_tab_' + STORE_ID);
        if (savedTab) {
            const panels = ['orders', 'products', 'history'];
            if (panels.includes(savedTab)) {
                // Find the button based on panel id
                let btn = null;
                if (savedTab === 'orders') btn = document.querySelector('[onclick*="orders"]');
                else if (savedTab === 'products') btn = document.querySelector('[onclick*="products"]');
                else if (savedTab === 'history') btn = document.querySelector('[onclick*="history"]');
                
                if (btn) {
                    switchTab(savedTab, btn);
                }
            }
        }
    });

    // ===== ORDER STATUS =====
    async function updateOrder(orderId, action) {
        const btn = event.target;
        btn.disabled = true;
        btn.innerHTML = '<span class="spinner"></span>';

        try {
            const resp = await fetch(`/warung/pesanan/${orderId}/${action}`, {
                method: 'POST',
                headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
            });
            const data = await resp.json();

            if (data.success) {
                showToast('Status pesanan berhasil diperbarui!', 'success');
                // Refresh badge and actions
                setTimeout(() => location.reload(), 800);
            }
        } catch(e) {
            showToast('Gagal memperbarui status.', 'error');
            btn.disabled = false;
        }
    }


    // ===== PRODUCT MODALS =====
    function openAddProductModal() { openModal('modal-add-product'); }

    function openEditProductModal(id, name, price, stock, avail, desc) {
        document.getElementById('edit-name').value  = name;
        document.getElementById('edit-price').value = price;
        document.getElementById('edit-desc').value  = desc;
        document.getElementById('edit-avail').checked = avail == 1;
        document.getElementById('edit-product-form').action = `/warung/{{ $store->id }}/produk/${id}`;
        openModal('modal-edit-product');
    }

    function openStockModal(id, name, stock) {
        document.getElementById('stock-modal-sub').textContent = `${name} — Stok saat ini: ${stock}`;
        document.getElementById('stock-input').value = stock;
        document.getElementById('stock-form').action = `/warung/{{ $store->id }}/produk/${id}/stok`;
        openModal('modal-stock');
    }

    async function deleteProduct(id, name) {
        if (!confirm(`Hapus produk "${name}"?`)) return;

        const resp = await fetch(`/warung/{{ $store->id }}/produk/${id}`, {
            method: 'DELETE',
            headers: { 'X-CSRF-TOKEN': CSRF, 'Accept': 'application/json' }
        });
        const data = await resp.json();
        if (resp.ok) {
            document.getElementById(`pc-${id}`)?.remove();
            showToast(`"${name}" berhasil dihapus.`, 'success');
        } else {
            showToast(data.error || 'Gagal menghapus produk.', 'error');
        }
    }

    // ===== PHOTO PREVIEW =====
    function previewPhoto(input, previewId) {
        const preview = document.getElementById(previewId);
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            reader.onload = e => {
                preview.src = e.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    // ===== MODAL HELPERS =====
    function openModal(id) { document.getElementById(id).classList.add('open'); }
    function closeModal(id) { document.getElementById(id).classList.remove('open'); }

    // Close modal on overlay click
    document.querySelectorAll('.modal').forEach(m => {
        m.addEventListener('click', e => { if (e.target === m) m.classList.remove('open'); });
    });

    // Form submit state
    function handleFormSubmit(form) {
        const btn = form.querySelector('button[type="submit"]');
        if (btn) {
            btn.disabled = true;
            btn.innerHTML = '<span class="spinner"></span> Menyimpan...';
        }
    }

    // ===== TOAST =====
    function showToast(msg, type = 'success') {
        const c = document.getElementById('toast-container');
        const t = document.createElement('div');
        t.className = `toast toast-${type}`;
        const icons = { success: '✅', error: '❌', info: 'ℹ️' };
        t.innerHTML = `<span>${icons[type]||'📢'}</span><span>${msg}</span>`;
        c.appendChild(t);
        setTimeout(() => { t.style.opacity = '0'; t.style.transition = 'opacity 0.4s'; setTimeout(() => t.remove(), 400); }, 3000);
    }

    // ===== AUTO REFRESH ORDERS =====
    setInterval(() => {
        if (document.getElementById('tab-orders').classList.contains('active')) {
            // Soft reload: fetch updated order list
            fetch(`/api/warung/{{ $store->id }}/pesanan`)
                .then(r => r.json())
                .then(data => {
                    // Update badge if there are new orders
                    const tabBtn = document.querySelector('[onclick*="orders"]');
                    if (tabBtn && data.count !== undefined) {
                        tabBtn.textContent = `📋 Pesanan (${data.count})`;
                    }
                }).catch(() => {});
        }
    }, 15000);
</script>
</body>
</html>
