<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Akun — Admin K2Hub</title>
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

        /* Form & Cards Layout */
        .layout-grid { display: grid; grid-template-columns: 320px 1fr; gap: 1.5rem; }
        
        .form-card {
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 18px; padding: 1.5rem; height: fit-content;
        }
        .form-title { font-size: 0.95rem; font-weight: 700; color: var(--text-primary); margin-bottom: 1.25rem; }
        .form-group { margin-bottom: 1rem; }
        .form-label { display: block; font-size: 0.72rem; font-weight: 600; color: var(--text-secondary); margin-bottom: 0.35rem; text-transform: uppercase; letter-spacing: 0.04em; }
        .form-input {
            width: 100%; background: #FFFFFF; border: 1px solid var(--border);
            border-radius: 8px; padding: 0.6rem 0.85rem; font-size: 0.85rem; font-family: inherit; color: var(--text-primary); outline: none;
            transition: border-color 0.2s;
        }
        .form-input:focus { border-color: var(--primary); }
        .checkbox-label { display: flex; align-items: center; gap: 0.5rem; font-size: 0.8rem; color: var(--text-secondary); cursor: pointer; }
        .checkbox-input { accent-color: var(--primary); }
        .submit-btn {
            width: 100%; background: var(--primary); border: none;
            border-radius: 10px; padding: 0.7rem; font-size: 0.85rem; font-weight: 700; font-family: inherit;
            color: white; cursor: pointer; transition: all 0.2s; margin-top: 0.5rem;
        }
        .submit-btn:hover { background: #1d4ed8; }

        /* Accounts List */
        .accounts-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(280px, 1fr)); gap: 1.25rem; }
        .account-card {
            background: var(--card-bg); border: 1px solid var(--border);
            border-radius: 18px; padding: 1.5rem; display: flex; flex-direction: column; justify-content: space-between;
            transition: border-color 0.2s;
        }
        .account-card:hover { border-color: var(--primary); }
        
        .ac-header { display: flex; align-items: center; gap: 0.75rem; margin-bottom: 1rem; }
        .ac-avatar { width: 44px; height: 44px; border-radius: 12px; background: rgba(37,99,235,0.1); color: var(--primary); display: flex; align-items: center; justify-content: center; font-size: 1.2rem; font-weight: 700; }
        .ac-name { font-size: 0.95rem; font-weight: 700; color: var(--text-primary); }
        .ac-role { font-size: 0.72rem; color: var(--text-secondary); margin-top: 0.15rem; }
        
        .ac-details { font-size: 0.8rem; color: var(--text-secondary); margin-bottom: 1.25rem; }
        
        .ac-actions { display: flex; gap: 0.5rem; border-top: 1px solid var(--border); padding-top: 1rem; }
        .edit-btn { background: rgba(37,99,235,0.08); border: 1px solid rgba(37,99,235,0.2); border-radius: 8px; padding: 0.4rem 0.75rem; font-size: 0.78rem; font-weight: 600; color: var(--primary); cursor: pointer; transition: all 0.2s; }
        .edit-btn:hover { background: rgba(37,99,235,0.15); }
        
        .delete-form { display: inline; }
        .delete-btn { background: rgba(239,68,68,0.08); border: 1px solid rgba(239,68,68,0.2); border-radius: 8px; padding: 0.4rem 0.75rem; font-size: 0.78rem; font-weight: 600; color: #dc2626; cursor: pointer; transition: all 0.2s; }
        .delete-btn:hover { background: rgba(239,68,68,0.15); }

        .alert { display: flex; align-items: flex-start; gap: 0.6rem; padding: 0.8rem 1rem; border-radius: 12px; font-size: 0.83rem; margin-bottom: 1.5rem; }
        .alert-success { background: rgba(22,163,74,0.1); border: 1px solid rgba(22,163,74,0.2); color: var(--success); }
        .alert-error { background: rgba(239,68,68,0.1); border: 1px solid rgba(239,68,68,0.2); color: #dc2626; }

        /* Modal styling */
        .modal { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.6); backdrop-filter: blur(5px); z-index: 100; align-items: center; justify-content: center; padding: 1.5rem; }
        .modal.open { display: flex; }
        .modal-content { background: #FFFFFF; border: 1px solid var(--border); border-radius: 20px; padding: 2rem; width: 100%; max-width: 400px; }
        .modal-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 1.5rem; color: var(--text-primary); }
        .modal-title { font-size: 1rem; font-weight: 700; color: var(--text-primary); }
        .modal-close { background: none; border: none; color: var(--text-secondary); cursor: pointer; font-size: 1.2rem; }
        .modal-close:hover { color: var(--text-primary); }
    </style>
</head>
<body>

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
        <a href="{{ route('admin.stores') }}" class="nav-item" id="nav-stores">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M3 9l9-7 9 7v11a2 2 0 01-2 2H5a2 2 0 01-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
            Kelola Toko
        </a>
        <a href="{{ route('admin.products') }}" class="nav-item" id="nav-products">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            Kelola Produk
        </a>
        <a href="{{ route('admin.accounts') }}" class="nav-item active" id="nav-accounts">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M17 21v-2a4 4 0 0 0-4-4H5a4 4 0 0 0-4 4v2"/><circle cx="9" cy="7" r="4"/><path d="M23 21v-2a4 4 0 0 0-3-3.87"/><path d="M16 3.13a4 4 0 0 1 0 7.75"/></svg>
            Kelola Akun
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
</aside>

<main class="main">
    <div class="top-bar">
        <div>
            <div class="page-title">Kelola Akun</div>
            <div class="page-sub">Manajemen akun admin & staff unit usaha</div>
        </div>
    </div>

    <div class="content">
        @if(session('success'))
            <div class="alert alert-success">✅ {{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-error">⚠️ {{ session('error') }}</div>
        @endif

        <div class="layout-grid">
            <!-- Add Account Form -->
            <div class="form-card">
                <div class="form-title">➕ Tambah Akun</div>
                <form method="POST" action="{{ route('admin.accounts.create') }}">
                    @csrf
                    <div class="form-group">
                        <label class="form-label">Nama Pengguna</label>
                        <input type="text" name="name" class="form-input" placeholder="Nama lengkap" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Email</label>
                        <input type="email" name="email" class="form-input" placeholder="email@kantin-alamanah.com" required>
                    </div>
                    <div class="form-group">
                        <label class="form-label">Password</label>
                        <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter" required>
                    </div>
                    <div class="form-group">
                        <label class="checkbox-label">
                            <input type="checkbox" name="is_admin" class="checkbox-input" value="1">
                            Jadikan Administrator
                        </label>
                    </div>
                    <button type="submit" class="submit-btn">Buat Akun</button>
                </form>
            </div>

            <!-- Accounts List -->
            <div class="accounts-grid">
                @foreach($users as $user)
                    <div class="account-card">
                        <div>
                            <div class="ac-header">
                                <div class="ac-avatar">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                                <div>
                                    <div class="ac-name">{{ $user->name }}</div>
                                    <div class="ac-role">{{ $user->is_admin ? 'Administrator' : 'Pengelola Kantin/Koperasi' }}</div>
                                </div>
                            </div>
                            <div class="ac-details">
                                📧 {{ $user->email }}
                            </div>
                        </div>

                        <div class="ac-actions">
                            <button class="edit-btn" onclick="openEditModal({{ json_encode($user) }})">✏️ Edit</button>
                            <form method="POST" action="{{ route('admin.accounts.delete', $user) }}" class="delete-form" onsubmit="return confirm('Apakah Anda yakin ingin menghapus akun ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="delete-btn">🗑️ Hapus</button>
                            </form>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</main>

<!-- Edit Modal -->
<div class="modal" id="editModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title">✏️ Edit Akun</div>
            <button class="modal-close" onclick="closeEditModal()">×</button>
        </div>
        <form method="POST" id="editForm" action="">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label class="form-label">Nama Pengguna</label>
                <input type="text" id="edit_name" name="name" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Email</label>
                <input type="email" id="edit_email" name="email" class="form-input" required>
            </div>
            <div class="form-group">
                <label class="form-label">Password Baru (kosongkan jika tidak diubah)</label>
                <input type="password" name="password" class="form-input" placeholder="Minimal 6 karakter">
            </div>
            <div class="form-group">
                <label class="checkbox-label">
                    <input type="checkbox" id="edit_is_admin" name="is_admin" class="checkbox-input" value="1">
                    Jadikan Administrator
                </label>
            </div>
            <button type="submit" class="submit-btn">Simpan Perubahan</button>
        </form>
    </div>
</div>

<script>
    function openEditModal(user) {
        document.getElementById('editForm').action = "/admin/akun/" + user.id;
        document.getElementById('edit_name').value = user.name;
        document.getElementById('edit_email').value = user.email;
        document.getElementById('edit_is_admin').checked = user.is_admin ? true : false;
        document.getElementById('editModal').classList.add('open');
    }

    function closeEditModal() {
        document.getElementById('editModal').classList.remove('open');
    }
</script>

</body>
</html>
