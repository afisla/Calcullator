<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Toko — Admin Kantin Al-Amanah</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
        :root {
            --bg: #F8F5F2;
            --sidebar-bg: #96A480;
            --card-bg: #F8F5F2;
            --border: #F9E6A7;
            --primary: #BA797D;
            --success: #96A480;
            --warning: #F9E6A7;
            --text-primary: #BA797D;
            --text-secondary: #96A480;
            --text-muted: #96A480;
        }
        body { font-family: 'Inter', sans-serif; background: var(--bg); color: var(--text-primary); min-height: 100vh; display: flex; }

        /* Sidebar */
        .sidebar { width: 240px; flex-shrink: 0; background: var(--sidebar-bg); border-right: 1px solid var(--border); display: flex; flex-direction: column; height: 100vh; position: sticky; top: 0; overflow-y: auto; }
        .sidebar-logo { padding: 1.5rem 1.25rem; border-bottom: 1px solid rgba(255,255,255,0.1); }
        .logo-badge { display: flex; align-items: center; gap: 0.6rem; }
        .logo-icon { width: 36px; height: 36px; border-radius: 10px; background: rgba(255,255,255,0.1); display: flex; align-items: center; justify-content: center; font-size: 1.1rem; color: #FFFFFF; }
        .logo-text { font-size: 0.9rem; font-weight: 700; color: #FFFFFF; }
        .logo-sub { font-size: 0.7rem; color: rgba(255,255,255,0.6); }
        .sidebar-nav { padding: 1rem 0.75rem; flex: 1; }
        .nav-section-label { font-size: 0.65rem; font-weight: 600; text-transform: uppercase; letter-spacing: 0.1em; color: rgba(255,255,255,0.4); padding: 0.5rem 0.5rem 0.4rem; margin-top: 0.5rem; }
        .nav-item { display: flex; align-items: center; gap: 0.7rem; padding: 0.6rem 0.75rem; border-radius: 10px; text-decoration: none; font-size: 0.85rem; font-weight: 500; color: rgba(255,255,255,0.7); transition: all 0.2s; margin-bottom: 0.15rem; }
        .nav-item:hover { background: rgba(255,255,255,0.08); color: #FFFFFF; }
        .nav-item.active { background: var(--primary); color: #FFFFFF; }
        .nav-item svg { width: 17px; height: 17px; flex-shrink: 0; }
        .sidebar-footer { padding: 1rem 0.75rem; border-top: 1px solid rgba(255,255,255,0.1); }
        .admin-user-info { display: flex; align-items: center; gap: 0.6rem; padding: 0.6rem 0.5rem; margin-bottom: 0.5rem; }
        .user-avatar { width: 32px; height: 32px; border-radius: 50%; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 0.8rem; font-weight: 700; flex-shrink: 0; color: #FFFFFF; }
        .user-name { font-size: 0.8rem; font-weight: 600; color: #FFFFFF; }
        .user-role { font-size: 0.7rem; color: rgba(255,255,255,0.5); }
        .logout-btn { display: flex; align-items: center; gap: 0.6rem; width: 100%; padding: 0.55rem 0.75rem; border-radius: 10px; background: rgba(239,68,68,0.15); border: 1px solid rgba(239,68,68,0.3); color: #fca5a5; font-size: 0.82rem; font-weight: 500; font-family: inherit; cursor: pointer; transition: all 0.2s; }
        .logout-btn:hover { background: rgba(239,68,68,0.25); }
        .logout-btn svg { width: 15px; height: 15px; }

        /* Main */
        .main { flex: 1; overflow-y: auto; }
        .top-bar { display: flex; align-items: center; justify-content: space-between; padding: 1.25rem 2rem; border-bottom: 1px solid var(--border); position: sticky; top: 0; z-index: 20; background: #FFFFFF; }
        .page-title { font-size: 1.15rem; font-weight: 700; color: var(--text-primary); }
        .page-sub { font-size: 0.8rem; color: var(--text-muted); margin-top: 0.1rem; }

        .content { padding: 2rem; }

        .alert { display: flex; align-items: flex-start; gap: 0.6rem; padding: 0.8rem 1rem; border-radius: 12px; font-size: 0.83rem; margin-bottom: 1.5rem; }
        .alert-success { background: rgba(22,163,74,0.1); border: 1px solid rgba(22,163,74,0.2); color: var(--success); }
        .alert-error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #dc2626; }

        /* Store cards */
        .stores-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(320px, 1fr)); gap: 1.25rem; }

        .store-mgmt-card {
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 20px; overflow: hidden;
            transition: border-color 0.2s, box-shadow 0.2s;
        }
        .store-mgmt-card:hover { border-color: var(--primary); box-shadow: 0 4px 12px rgba(0,0,0,0.05); }

        .card-head {
            display: flex; align-items: center; justify-content: space-between;
            padding: 1.25rem 1.25rem 0;
        }
        .store-info { display: flex; align-items: center; gap: 0.75rem; }
        .emoji-big { font-size: 2rem; }
        .store-name-text { font-size: 0.95rem; font-weight: 700; color: var(--text-primary); }
        .store-cat { font-size: 0.75rem; color: var(--text-muted); margin-top: 0.1rem; }
        .unit-badge { font-size: 0.68rem; font-weight: 600; padding: 0.2rem 0.55rem; border-radius: 50px; }
        .unit-badge.koperasi { background: rgba(22,163,74,0.1); color: #BA797D; }
        .unit-badge.kantin { background: rgba(245,158,11,0.1); color: #96A480; }

        .card-actions {
            display: flex; align-items: center; gap: 0.5rem;
            padding: 1rem 1.25rem;
            border-top: 1px solid var(--border);
            margin-top: 1rem;
        }

        /* Toggle switch */
        .toggle-form { display: flex; align-items: center; gap: 0.6rem; }
        .toggle-label { font-size: 0.78rem; color: var(--text-muted); }
        .toggle-switch-btn {
            position: relative; display: inline-block; width: 44px; height: 24px;
            background: none; border: none; cursor: pointer; padding: 0;
        }
        .toggle-track {
            width: 44px; height: 24px; border-radius: 12px;
            transition: background 0.2s;
        }
        .toggle-track.on { background: var(--success); }
        .toggle-track.off { background: #CBD5E1; }
        .toggle-thumb {
            position: absolute; top: 3px; width: 18px; height: 18px;
            border-radius: 50%; background: white;
            transition: left 0.2s;
        }
        .toggle-track.on + .toggle-thumb,
        .toggle-thumb.on { left: 23px; }
        .toggle-track.off + .toggle-thumb,
        .toggle-thumb.off { left: 3px; }

        /* Edit form */
        .edit-section {
            padding: 0 1.25rem 1.25rem;
        }
        .edit-toggle-btn {
            display: flex; align-items: center; gap: 0.4rem;
            background: rgba(37,99,235,0.08); border: 1px solid rgba(37,99,235,0.2);
            border-radius: 8px; padding: 0.4rem 0.75rem;
            font-size: 0.78rem; font-weight: 600; color: var(--primary);
            cursor: pointer; font-family: inherit;
            transition: all 0.2s;
        }
        .edit-toggle-btn:hover { background: rgba(37,99,235,0.15); }

        .edit-form-wrap { display: none; margin-top: 1rem; }
        .edit-form-wrap.open { display: block; }

        .form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 0.75rem; margin-bottom: 0.75rem; }
        .form-group { margin-bottom: 0.75rem; }
        .form-label { display: block; font-size: 0.72rem; font-weight: 600; color: var(--text-muted); margin-bottom: 0.35rem; letter-spacing: 0.04em; text-transform: uppercase; }
        .form-input, .form-select {
            width: 100%; background: #FFFFFF; border: 1px solid var(--border);
            border-radius: 8px; padding: 0.6rem 0.85rem;
            font-size: 0.83rem; font-family: inherit; color: var(--text-primary); outline: none;
            transition: border-color 0.2s;
        }
        .form-input:focus, .form-select:focus { border-color: var(--primary); }
        .form-select option { background: #FFFFFF; color: var(--text-primary); }

        .save-btn {
            background: var(--primary);
            border: none; border-radius: 8px; padding: 0.6rem 1.25rem;
            font-size: 0.83rem; font-weight: 700; font-family: inherit;
            color: white; cursor: pointer; transition: all 0.2s;
        }
        .save-btn:hover { background: var(--success); }

        .cancel-btn {
            background: #CBD5E1;
            border: none; border-radius: 8px; padding: 0.6rem 1.25rem;
            font-size: 0.83rem; font-weight: 700; font-family: inherit;
            color: #475569; cursor: pointer; transition: all 0.2s;
        }
        .cancel-btn:hover { background: #94A3B8; color: #1E293B; }

        .store-status-badge {
            display: inline-flex; align-items: center; gap: 0.35rem;
            font-size: 0.72rem; font-weight: 600;
            padding: 0.2rem 0.6rem; border-radius: 50px;
        }
        .store-status-badge.open { background: rgba(22,163,74,0.1); color: var(--success); }
        .store-status-badge.closed { background: #E2E8F0; color: var(--text-secondary); }

        /* Editing mode classes */
        .stores-grid.editing-mode .store-mgmt-card:not(.currently-editing) {
            display: none;
        }
        .stores-grid.editing-mode {
            grid-template-columns: 1fr;
            max-width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>

<!-- Sidebar -->
<aside class="sidebar">
    <div class="sidebar-logo">
        <div class="logo-badge">
            <div class="logo-icon">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="#FFFFFF" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round">
                    <path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path>
                    <circle cx="12" cy="7" r="4"></circle>
                </svg>
            </div>
            <div>
                <div class="logo-text">Admin Panel</div>
                <div class="logo-sub">Kantin Al-Amanah</div>
            </div>
        </div>
    </div>
    <nav class="sidebar-nav">
        <div class="nav-section-label">Utama</div>
        <a href="{{ route('admin.dashboard') }}" class="nav-item" id="nav-dashboard">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/></svg>
            Dashboard
        </a>
        <div class="nav-section-label">Manajemen</div>
        <a href="{{ route('admin.stores') }}" class="nav-item active" id="nav-stores">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Kelola Toko
        </a>
        <a href="{{ route('admin.finance') }}" class="nav-item" id="nav-finance">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><line x1="12" y1="1" x2="12" y2="23"/><path d="M17 5H9.5a3.5 3.5 0 000 7h5a3.5 3.5 0 010 7H6"/></svg>
            Laporan Keuangan
        </a>
        <div class="nav-section-label">Akses Cepat</div>
        <a href="{{ route('welcome') }}" class="nav-item" id="nav-home">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/></svg>
            Beranda Web
        </a>
    </nav>
    <div class="sidebar-footer">
        <div class="admin-user-info">
            <div class="user-avatar">{{ strtoupper(substr(session('admin_name', 'A'), 0, 1)) }}</div>
            <div>
                <div class="user-name">{{ session('admin_name') }}</div>
                <div class="user-role">Administrator</div>
            </div>
        </div>
        <form method="POST" action="{{ route('admin.logout') }}">
            @csrf
            <button type="submit" class="logout-btn">
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M9 21H5a2 2 0 01-2-2V5a2 2 0 012-2h4"/><polyline points="16 17 21 12 16 7"/><line x1="21" y1="12" x2="9" y2="12"/></svg>
                Logout
            </button>
        </form>
    </div>
</aside>

<!-- Main -->
<main class="main">
    <div class="top-bar">
        <div>
            <div class="page-title">Kelola Toko</div>
            <div class="page-sub">Edit info toko, ubah PIN, buka/tutup toko</div>
        </div>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">⚠️ {{ session('error') }}</div>
        @endif

        <div class="stores-grid">
            @foreach($stores as $store)
            <div class="store-mgmt-card" id="store-card-{{ $store->id }}">
                <div class="card-head">
                    <div class="store-info">
                        <span class="emoji-big">{{ $store->icon_emoji }}</span>
                        <div>
                            <div class="store-name-text">{{ $store->name }}</div>
                            <div class="store-cat">{{ $store->category }}</div>
                        </div>
                    </div>
                    <span class="unit-badge {{ $store->unit }}">{{ ucfirst($store->unit) }}</span>
                </div>

                <div class="card-actions">
                    <!-- Toggle Buka/Tutup -->
                    <form method="POST" action="{{ route('admin.stores.toggle', $store) }}" class="toggle-form">
                        @csrf
                        <span class="toggle-label">{{ $store->is_open ? 'Buka' : 'Tutup' }}</span>
                        <button type="submit" class="toggle-switch-btn" title="Toggle Buka/Tutup">
                            <div class="toggle-track {{ $store->is_open ? 'on' : 'off' }}"></div>
                            <div class="toggle-thumb {{ $store->is_open ? 'on' : 'off' }}"></div>
                        </button>
                    </form>

                    <div style="flex:1;"></div>

                    <button class="edit-toggle-btn" onclick="startEdit({{ $store->id }})">
                        ✏️ Edit
                    </button>
                </div>

                <!-- Edit form -->
                <div class="edit-section">
                    <div class="edit-form-wrap" id="edit-form-{{ $store->id }}">
                        <form method="POST" action="{{ route('admin.stores.update', $store) }}">
                            @csrf
                            @method('PUT')
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Nama Toko</label>
                                    <input type="text" name="name" class="form-input" value="{{ $store->name }}" required>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Kategori</label>
                                    <input type="text" name="category" class="form-input" value="{{ $store->category }}" required>
                                </div>
                            </div>
                            <div class="form-row">
                                <div class="form-group">
                                    <label class="form-label">Unit Usaha</label>
                                    <select name="unit" class="form-select">
                                        <option value="koperasi" {{ $store->unit === 'koperasi' ? 'selected' : '' }}>Koperasi</option>
                                        <option value="kantin" {{ $store->unit === 'kantin' ? 'selected' : '' }}>Kantin</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="form-label">Urutan Tampil</label>
                                    <input type="number" name="sort_order" class="form-input" value="{{ $store->sort_order }}" min="1" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="form-label">Deskripsi</label>
                                <input type="text" name="description" class="form-input" value="{{ $store->description }}">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Reset PIN (kosongkan jika tidak diubah)</label>
                                <input type="text" name="pin" class="form-input" placeholder="PIN baru (4-10 digit)">
                            </div>
                            <div style="display:flex;gap:0.5rem;margin-top:1rem;">
                                <button type="button" class="cancel-btn" onclick="cancelEdit({{ $store->id }})">◀ Kembali</button>
                                <button type="submit" class="save-btn" style="flex:1;">💾 Simpan Perubahan</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</main>

<script>
    function startEdit(storeId) {
        const grid = document.querySelector('.stores-grid');
        grid.classList.add('editing-mode');
        
        document.querySelectorAll('.store-mgmt-card').forEach(card => {
            card.classList.remove('currently-editing');
        });
        const activeCard = document.getElementById('store-card-' + storeId);
        activeCard.classList.add('currently-editing');
        
        document.querySelectorAll('.edit-form-wrap').forEach(wrap => {
            wrap.classList.remove('open');
        });
        const wrap = document.getElementById('edit-form-' + storeId);
        wrap.classList.add('open');
    }

    function cancelEdit(storeId) {
        const grid = document.querySelector('.stores-grid');
        grid.classList.remove('editing-mode');
        
        const activeCard = document.getElementById('store-card-' + storeId);
        activeCard.classList.remove('currently-editing');
        
        const wrap = document.getElementById('edit-form-' + storeId);
        wrap.classList.remove('open');
    }
</script>
</body>
</html>
