<?php
/**
 * K2Hub Static Site Builder
 * Generates static HTML pages with proper data for all views.
 * Uses theme from portal/welcome.blade.php and layouts/app.blade.php.
 */

$outDir = __DIR__ . '/public_html';
if (!is_dir($outDir)) mkdir($outDir, 0755, true);

// Copy Vite build assets
$buildDir = __DIR__ . '/public/build';
if (is_dir($buildDir)) {
    system("cp -rf $buildDir $outDir/");
}

// Copy public assets
foreach (['logo.jpg', 'favicon.ico', 'robots.txt'] as $file) {
    $src = __DIR__ . "/public/$file";
    if (file_exists($src)) copy($src, "$outDir/$file");
}

// Copy CNAME
$cname = __DIR__ . '/CNAME';
if (file_exists($cname)) copy($cname, "$outDir/CNAME");
file_put_contents("$outDir/.nojekyll", '');

$version = time();

// Helper: read built manifest for asset paths
$cssFile = 'build/assets/app-4EUrGnYV.css';
$jsFile  = 'build/assets/app-UyRVujZY.js';

$cssLink  = "<link rel=\"stylesheet\" href=\"$cssFile?v=$version\">";
$jsScript = "<script type=\"module\" src=\"$jsFile?v=$version\"></script>";

/**
 * Render layout header
 */
function pageHeader(array $opts = []): string {
    $title = $opts['title'] ?? 'K2Hub';
    $desc  = $opts['desc'] ?? 'Sistem Pemesanan Koperasi & Kantin SMP Al Amanah';
    $extraCss = $opts['css'] ?? '';
    return <<<HEADER
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="description" content="{$desc}">
    <title>{$title} — K2Hub</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Fredoka+One&family=Nunito:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <style>
        :root {
            --k2-primary: #BA797D; --k2-primary-dark: #96A480; --k2-light-pink: #F9E6A7;
            --k2-green: #96A480; --k2-neutral: #F9E6A7; --k2-white: #F8F5F2;
            --k2-dark: #BA797D; --k2-bg: #F8F5F2; --k2-card-bg: #F8F5F2;
            --r-sm: 10px; --r-md: 16px; --r-lg: 22px; --r-xl: 30px; --r-full: 999px;
        }
        *,*::before,*::after{box-sizing:border-box;margin:0;padding:0}
        html,body{font-family:'Nunito',sans-serif;background:var(--k2-bg);color:var(--k2-dark);min-height:100vh;-webkit-font-smoothing:antialiased}
        a{text-decoration:none;color:inherit}
        .app-wrap{max-width:480px;margin:0 auto;min-height:100vh;position:relative;padding:0 0 80px 0}
        .app-header{position:sticky;top:0;z-index:10;background:var(--k2-bg);border-bottom:2px solid var(--k2-neutral);display:flex;align-items:center;justify-content:space-between;padding:12px 16px}
        .header-left{display:flex;align-items:center;gap:10px}
        .btn-back{display:inline-flex;align-items:center;gap:4px;padding:6px 12px;border:2px solid var(--k2-neutral);border-radius:var(--r-full);font-size:13px;font-weight:700;color:var(--k2-primary-dark);transition:all 0.2s}
        .btn-back:hover{background:var(--k2-neutral)}
        .brand-text-k2{font-family:'Fredoka One',cursive;font-size:22px;background:linear-gradient(90deg,#96A480,#BA797D,#F9E6A7);-webkit-background-clip:text;-webkit-text-fill-color:transparent;-webkit-text-stroke:1px #5c4534;filter:drop-shadow(0 2px 4px rgba(101,86,75,0.25))}
        .header-subtitle{font-size:11px;font-weight:600;color:var(--k2-primary-dark)}
        .header-logo-wrap{width:40px;height:40px;border-radius:12px;background:#F8F5F2;border:2px solid var(--k2-neutral);display:flex;align-items:center;justify-content:center;font-size:22px}
        .cart-badge{background:#E7648E;color:white;font-size:10px;font-weight:800;padding:1px 6px;border-radius:var(--r-full);margin-left:4px}
        .app-content{padding:16px}
        .section-title{font-size:15px;font-weight:800;color:var(--k2-dark);margin-bottom:12px;margin-top:4px}
        .section-title-sub{display:block;font-size:11px;font-weight:600;color:var(--k2-primary-dark);margin-top:2px}
        .btn{display:inline-flex;align-items:center;justify-content:center;gap:6px;padding:10px 20px;border-radius:var(--r-md);font-family:inherit;font-size:14px;font-weight:700;border:none;cursor:pointer;transition:all 0.2s}
        .btn-primary{background:var(--k2-primary);color:white;box-shadow:0 4px 12px rgba(186,121,125,0.25)}
        .btn-primary:hover{opacity:0.9;transform:translateY(-1px)}
        .btn-sm{padding:7px 14px;font-size:13px}
        .btn-block{width:100%;display:flex}
        .btn-outline{background:transparent;border:2px solid var(--k2-primary);color:var(--k2-primary)}
        .badge{display:inline-flex;align-items:center;gap:4px;padding:3px 10px;border-radius:var(--r-full);font-size:11px;font-weight:800}
        .badge-open{background:#dcfce7;color:#15803d}
        .badge-closed{background:#f1f5f9;color:#94a3b8}
        .empty-state{text-align:center;padding:40px 16px}
        .empty-icon{font-size:48px;display:block;margin-bottom:12px}
        .empty-title{font-size:16px;font-weight:800;color:var(--k2-dark);margin-bottom:6px}
        .empty-text{font-size:13px;color:var(--k2-primary-dark);font-weight:500}
        .product-grid{display:grid;grid-template-columns:1fr 1fr;gap:12px}
        .product-card{background:white;border-radius:var(--r-md);overflow:hidden;box-shadow:0 2px 10px rgba(231,100,142,0.08);border:1px solid rgba(231,100,142,0.08);display:flex;flex-direction:column}
        .product-img{width:100%;aspect-ratio:4/3;object-fit:cover}
        .product-img-placeholder{aspect-ratio:4/3;display:flex;align-items:center;justify-content:center;background:#F1F5F9;font-size:40px}
        .product-info{padding:10px 12px 12px;display:flex;flex-direction:column;flex:1}
        .product-name{font-size:13px;font-weight:700;color:var(--k2-dark);margin-bottom:4px}
        .product-price{font-size:15px;font-weight:900;color:var(--k2-primary-dark);margin-bottom:4px}
        .product-stock{font-size:11px;font-weight:600;color:#94a3b8;margin-bottom:6px}
        .product-stock.low{color:#f59e0b}
        .product-stock.out{color:#ef4444}
        .search-wrap{position:relative;margin-bottom:14px}
        .search-icon{position:absolute;left:14px;top:50%;transform:translateY(-50%);font-size:16px;z-index:1}
        .search-input{width:100%;padding:10px 14px 10px 40px;border-radius:var(--r-full);border:2px solid var(--k2-neutral);font-family:inherit;font-size:14px;background:white;outline:none}
        .search-input:focus{border-color:var(--k2-primary)}
        .float-cart{position:fixed;bottom:24px;right:24px;width:56px;height:56px;border-radius:50%;background:var(--k2-primary);color:white;display:flex;align-items:center;justify-content:center;font-size:24px;box-shadow:0 8px 24px rgba(186,121,125,0.35);z-index:20;transition:transform 0.2s}
        .float-cart:hover{transform:scale(1.1)}
        .float-cart-badge{position:absolute;top:-4px;right:-4px;background:#E7648E;color:white;font-size:11px;font-weight:800;padding:2px 6px;border-radius:var(--r-full);min-width:20px;text-align:center}
        .warung-grid{display:flex;flex-direction:column;gap:10px}
        .warung-item{text-decoration:none}
        .qty-control{display:inline-flex;align-items:center;gap:6px;background:#F8F5F2;border:2px solid var(--k2-neutral);border-radius:var(--r-full);padding:4px}
        .qty-btn{width:28px;height:28px;border-radius:50%;border:2px solid var(--k2-primary);background:white;color:var(--k2-primary);font-size:16px;font-weight:700;cursor:pointer;display:flex;align-items:center;justify-content:center}
        .qty-num{font-size:14px;font-weight:800;color:var(--k2-primary);min-width:20px;text-align:center}
        .alert{padding:10px 14px;border-radius:var(--r-md);font-size:13px;margin-bottom:12px;font-weight:500}
        .alert-warning{background:#fffbeb;border:1px solid #fde68a;color:#92400e}
        .modal-overlay{display:none;position:fixed;top:0;left:0;right:0;bottom:0;background:rgba(0,0,0,0.5);z-index:100;align-items:flex-end;justify-content:center}
        .modal-overlay.active{display:flex}
        .modal-sheet{background:white;border-radius:var(--r-xl) var(--r-xl) 0 0;width:100%;max-width:480px;padding:20px;animation:slideUp 0.3s ease}
        @keyframes slideUp{from{transform:translateY(100%)}to{transform:translateY(0)}}
        .modal-handle{width:40px;height:4px;background:#ddd;border-radius:2px;margin:0 auto 16px}
        .order-status-card{background:white;border-radius:var(--r-md);padding:14px;margin-bottom:10px;box-shadow:0 2px 10px rgba(231,100,142,0.08);border:1px solid rgba(231,100,142,0.08)}
        .logo-k2hub-gradient{font-family:'Fredoka One',cursive;background:linear-gradient(90deg,#96A480,#BA797D,#F9E6A7);-webkit-background-clip:text;-webkit-text-fill-color:transparent;-webkit-text-stroke:1.5px #5c4534;filter:drop-shadow(0 4px 6px rgba(101,86,75,0.25))}
        .footer-text{text-align:center;padding:20px 16px;font-size:11px;color:var(--k2-primary-dark);font-weight:500}
        .nav-bottom{position:fixed;bottom:0;left:0;right:0;background:var(--k2-bg);border-top:2px solid var(--k2-neutral);display:flex;justify-content:space-around;padding:8px 0;z-index:10}
        .nav-item{display:flex;flex-direction:column;align-items:center;gap:2px;padding:4px 12px;border-radius:var(--r-md);font-size:10px;font-weight:700;color:var(--k2-primary-dark);transition:all 0.2s}
        .nav-item.active{color:var(--k2-primary)}
        .nav-item span{font-size:20px}
        .welcome-header-bar{position:absolute;top:0;left:0;right:0;padding:16px 20px;display:flex;justify-content:space-between;align-items:center}
        .welcome-footer{position:absolute;bottom:0;left:0;right:0;padding:12px;text-align:center;font-size:11px;color:#96A480;font-weight:500}
        .choice-btn{display:flex;align-items:center;gap:14px;width:100%;padding:16px 20px;border-radius:18px;border:none;cursor:pointer;text-decoration:none;transition:all 0.25s;margin-bottom:12px;text-align:left}
        .choice-btn:hover{transform:scale(1.025)}
        .choice-btn:active{transform:scale(0.97)}
        .choice-btn-student{background:#BA797D}
        .choice-btn-owner{background:#96A480;border:1.5px solid #F9E6A7}
        .choice-icon{width:52px;height:52px;border-radius:14px;display:flex;align-items:center;justify-content:center;font-size:28px;flex-shrink:0}
        .icon-student{background:rgba(255,255,255,0.15)}
        .icon-owner{background:rgba(255,255,255,0.15);border:1px solid #F9E6A7}
        .choice-title{font-size:16px;font-weight:800;margin-bottom:2px;color:#F8F5F2}
        .choice-desc{font-size:12px;font-weight:500;color:#F9E6A7}
        .choice-arrow{margin-left:auto;font-size:18px;color:#F8F5F2}
        .status-chip{background:#F8F5F2;border:1px solid #F9E6A7;color:#BA797D;padding:5px 12px;border-radius:999px;font-size:11px;font-weight:700}
        .bg{position:fixed;inset:0;z-index:-1}
        {$extraCss}
    </style>
</head>
<body>
HEADER;
}

function pageFooter(bool $includeNav = true, array $opts = []): string {
    $addJs = $opts['js'] ?? '';
    $nav = '';
    if ($includeNav) {
        $nav = <<<NAV
    <div class="nav-bottom">
        <a href="/" class="nav-item"><span>🏠</span>Beranda</a>
        <a href="/dashboard" class="nav-item active"><span>📋</span>Dashboard</a>
        <a href="/kantin" class="nav-item"><span>🍱</span>Kantin</a>
        <a href="/koperasi" class="nav-item"><span>🏪</span>Koperasi</a>
        <a href="/cart" class="nav-item"><span>🛒</span>Keranjang</a>
    </div>
NAV;
    }
    return <<<FOOTER
    {$addJs}
</body>
</html>
FOOTER;
}

// ============= PAGE BUILDERS =============

// 1. Index/Welcome
copy(__DIR__ . '/resources/views/portal/welcome.blade.php', "{$outDir}/index.html");
$html = file_get_contents("{$outDir}/index.html");

// Remove Blade directives
$html = preg_replace('/\{\{.*?asset\(\'(.+?)\'\).*?\}\}/', '/$1', $html);
$html = preg_replace('/\{\{.*?route\(\'(.+?)\'\).*?\}\}/', '/$1', $html);
$html = preg_replace('/@if.*?@endif/s', '', $html);
$html = preg_replace('/\{\{ date\(\'Y\'\) \}\}/', '2025', $html);
$html = preg_replace('/@auth.*?@else.*?@endauth/s', '', $html);
$html = preg_replace('/\{\{ config\(\'app\.name\', \'Laravel\'\) \}\}/', 'K2Hub', $html);
$html = preg_replace('/href="(?!http)(?!\/)(?!mailto)/', 'href="/', $html);
$html = preg_replace('/src="(?!http)(?!\/)/', 'src="/', $html);
file_put_contents("{$outDir}/index.html", $html);
echo "  OK: /\n";

// 2. Dashboard
$dashboardHtml = pageHeader(['title' => 'Dashboard', 'desc' => 'Dashboard K2Hub']);
$dashboardHtml .= <<<HTML
<div class="app-wrap">
    <div class="app-header">
        <div class="header-left">
            <a href="/" class="btn-back">‹ Kembali</a>
        </div>
        <div class="brand-text-k2" style="font-size:20px">K2Hub</div>
        <div></div>
    </div>
    <div class="app-content">
        <div style="background:#96A480;border-radius:20px;padding:20px;margin-bottom:18px;color:white;text-align:center;border:3px solid #F9E6A7;">
            <div style="margin-bottom:4px;"><span class="logo-k2hub-gradient" style="font-size:28px;">K2Hub</span></div>
            <div style="font-size:14px;font-weight:600;opacity:0.88;">Koperasi & Kantin SMP Al Amanah</div>
            <div style="font-size:12px;color:#F9E6A7;font-weight:600;margin-top:4px;">Pesan sekarang, ambil nanti!</div>
        </div>
        <div class="section-title">Pilih Unit Usaha</div>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:20px;">
            <a href="/koperasi" style="text-decoration:none;">
                <div style="background:#BA797D;border:2.5px solid #F9E6A7;border-radius:20px;padding:20px 14px;text-align:center;">
                    <div style="font-size:40px;margin-bottom:8px;">🏪</div>
                    <div style="font-size:16px;font-weight:800;color:#FFFFFF;margin-bottom:4px;">Koperasi</div>
                    <div style="font-size:11px;font-weight:600;color:#F9E6A7;margin-bottom:8px;">Alat tulis & kebutuhan sekolah</div>
                    <div style="display:inline-block;background:#dcfce7;color:#15803d;font-size:10px;font-weight:800;padding:3px 10px;border-radius:999px;">● BUKA</div>
                </div>
            </a>
            <a href="/kantin" style="text-decoration:none;">
                <div style="background:#BA797D;border:2.5px solid #F9E6A7;border-radius:20px;padding:20px 14px;text-align:center;">
                    <div style="font-size:40px;margin-bottom:8px;">🍱</div>
                    <div style="font-size:16px;font-weight:800;color:#FFFFFF;margin-bottom:4px;">Kantin</div>
                    <div style="font-size:11px;font-weight:600;color:#F9E6A7;margin-bottom:8px;">Makanan & minuman segar</div>
                    <div style="display:inline-block;background:#dcfce7;color:#16A34A;font-size:10px;font-weight:800;padding:3px 10px;border-radius:999px;">● 2 Warung Buka</div>
                </div>
            </a>
        </div>
        <div class="section-title">🔍 Cek Status Pesanan</div>
        <div style="background:white;border-radius:16px;padding:14px;margin-bottom:16px;border:1px solid rgba(231,100,142,0.10);">
            <div style="font-size:13px;font-weight:700;color:#96A480;margin-bottom:10px;">Cari pesanan kamu:</div>
            <div style="display:flex;gap:8px;">
                <input type="text" placeholder="Nama pembeli..." style="flex:1;border-radius:999px;font-size:13px;padding:9px 14px;border:2px solid #F9E6A7;outline:none;font-family:inherit;">
                <button style="border-radius:999px;padding:9px 16px;background:#BA797D;color:white;border:none;font-weight:700;cursor:pointer;">🔍 Cari</button>
            </div>
        </div>
        <div style="text-align:center;padding:32px 16px;background:white;border-radius:16px;border:1px solid rgba(231,100,142,0.08);">
            <span style="font-size:48px;display:block;margin-bottom:12px;">📋</span>
            <div style="font-weight:800;font-size:15px;color:#2D1B3D;margin-bottom:6px;">Belum ada pesanan aktif</div>
            <div style="font-size:13px;color:#94a3b8;font-weight:500;">Pesan sekarang di Koperasi atau Kantin!</div>
        </div>
    </div>
    <div class="footer-text">© 2025 K2Hub — SMP Al Amanah</div>
    <div class="nav-bottom">
        <a href="/" class="nav-item"><span>🏠</span>Beranda</a>
        <a href="/dashboard" class="nav-item active"><span>📋</span>Dashboard</a>
        <a href="/kantin" class="nav-item"><span>🍱</span>Kantin</a>
        <a href="/koperasi" class="nav-item"><span>🏪</span>Koperasi</a>
        <a href="/cart" class="nav-item"><span>🛒</span>Keranjang</a>
    </div>
</div>
<script>let countdown=10;setInterval(()=>{countdown--;if(countdown<=0){countdown=10}},1000)</script>
</body></html>
HTML;
file_put_contents("{$outDir}/dashboard.html", $dashboardHtml);
echo "  OK: /dashboard\n";

// 3. Kantin
$kantinHtml = pageHeader(['title' => 'Kantin', 'desc' => 'Pilih warung favorit kamu']);
$kantinHtml .= <<<HTML
<div class="app-wrap">
    <div class="app-header">
        <div class="header-left">
            <a href="/dashboard" class="btn-back">‹ Kembali</a>
            <div class="header-logo-wrap" style="width:48px;height:48px;font-size:26px;">🍱</div>
            <div><div class="brand-text-k2" style="font-size:22px;">Kantin</div><div class="header-subtitle">Pilih warung kesukaan kamu</div></div>
        </div>
    </div>
    <div class="app-content">
        <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" class="search-input" placeholder="Cari warung..." oninput="filter(this)">
        </div>
        <div class="section-title">🏪 Warung Kantin</div>
        <div id="warung-list">
            <a href="/toko/kantin-hijau" class="warung-item">
                <div style="background:white;border-radius:18px;padding:16px;display:flex;align-items:center;gap:14px;border:1.5px solid rgba(231,100,142,0.09);">
                    <div style="width:64px;height:64px;border-radius:16px;overflow:hidden;flex-shrink:0;background:#F1F5F9;display:flex;align-items:center;justify-content:center;font-size:32px;">🍜</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:15px;font-weight:800;color:#BA797D;">Kantin Hijau</div>
                        <div style="font-size:12px;color:#96A480;font-weight:500;">Warung Kantin</div>
                        <div style="font-size:11px;color:#96A480;">Menyediakan makanan sehat & bergizi</div>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;flex-shrink:0;">
                        <span class="badge badge-open">● Buka</span>
                        <span style="font-size:20px;color:#E7648E;">›</span>
                    </div>
                </div>
            </a>
            <a href="/toko/kantin-biru" class="warung-item">
                <div style="background:white;border-radius:18px;padding:16px;display:flex;align-items:center;gap:14px;border:1.5px solid rgba(231,100,142,0.09);">
                    <div style="width:64px;height:64px;border-radius:16px;overflow:hidden;flex-shrink:0;background:#F1F5F9;display:flex;align-items:center;justify-content:center;font-size:32px;">🥤</div>
                    <div style="flex:1;min-width:0;">
                        <div style="font-size:15px;font-weight:800;color:#BA797D;">Kantin Biru</div>
                        <div style="font-size:12px;color:#96A480;font-weight:500;">Warung Kantin</div>
                        <div style="font-size:11px;color:#96A480;">Cemilan dan minuman segar</div>
                    </div>
                    <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;flex-shrink:0;">
                        <span class="badge badge-open">● Buka</span>
                        <span style="font-size:20px;color:#E7648E;">›</span>
                    </div>
                </div>
            </a>
        </div>
    </div>
    <div class="nav-bottom">
        <a href="/" class="nav-item"><span>🏠</span>Beranda</a>
        <a href="/dashboard" class="nav-item"><span>📋</span>Dashboard</a>
        <a href="/kantin" class="nav-item active"><span>🍱</span>Kantin</a>
        <a href="/koperasi" class="nav-item"><span>🏪</span>Koperasi</a>
        <a href="/cart" class="nav-item"><span>🛒</span>Keranjang</a>
    </div>
</div>
<script>function filter(el){const q=el.value.toLowerCase();document.querySelectorAll('.warung-item').forEach(i=>{i.style.display=i.textContent.toLowerCase().includes(q)?'':'none'})}</script>
HTML;
mkdir("{$outDir}/kantin", 0755, true);
file_put_contents("{$outDir}/kantin/index.html", $kantinHtml);
echo "  OK: /kantin\n";

// 4. Koperasi
$koperasiHtml = pageHeader(['title' => 'Koperasi', 'desc' => 'Belanja kebutuhan sekolah']);
$koperasiHtml .= <<<HTML
<div class="app-wrap">
    <div class="app-header">
        <div class="header-left">
            <a href="/dashboard" class="btn-back">‹ Kembali</a>
            <div class="header-logo-wrap" style="width:48px;height:48px;font-size:26px;">🏪</div>
            <div><div class="brand-text-k2" style="font-size:22px;">Koperasi</div><div class="header-subtitle">Koperasi Sekolah</div></div>
        </div>
        <a href="/cart" style="position:relative;"><span style="font-size:22px;">🛒</span><span class="cart-badge">3</span></a>
    </div>
    <div class="app-content">
        <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" class="search-input" placeholder="Cari produk koperasi..." oninput="filterProducts()" id="search-input">
        </div>
        <div style="display:flex;gap:8px;margin-bottom:14px;">
            <button class="filter-btn active" onclick="setFilter('all',this)" style="padding:6px 14px;border-radius:999px;font-size:12px;font-weight:700;border:2px solid #96A480;background:#96A480;color:white;cursor:pointer;">Semua</button>
            <button class="filter-btn" onclick="setFilter('tersedia',this)" style="padding:6px 14px;border-radius:999px;font-size:12px;font-weight:700;border:2px solid #96A480;background:transparent;color:#96A480;cursor:pointer;">✅ Tersedia</button>
        </div>
        <div id="product-grid" class="product-grid">
            <div class="product-card" data-name="buku tulis 42 lembar" data-available="tersedia">
                <div class="product-img-placeholder">📓</div>
                <div class="product-info">
                    <div class="product-name">Buku Tulis 42 Lembar</div>
                    <div class="product-price">Rp5.000</div>
                    <div class="product-stock">Stok: 100</div>
                    <button class="btn btn-primary btn-sm btn-block" style="margin-top:auto;">+ Tambah</button>
                </div>
            </div>
            <div class="product-card" data-name="pulpen standard" data-available="tersedia">
                <div class="product-img-placeholder">🖊️</div>
                <div class="product-info">
                    <div class="product-name">Pulpen Standard</div>
                    <div class="product-price">Rp3.000</div>
                    <div class="product-stock">Stok: 80</div>
                    <button class="btn btn-primary btn-sm btn-block" style="margin-top:auto;">+ Tambah</button>
                </div>
            </div>
            <div class="product-card" data-name="pensil 2b" data-available="tersedia">
                <div class="product-img-placeholder">✏️</div>
                <div class="product-info">
                    <div class="product-name">Pensil 2B</div>
                    <div class="product-price">Rp2.500</div>
                    <div class="product-stock">Stok: 60</div>
                    <button class="btn btn-primary btn-sm btn-block" style="margin-top:auto;">+ Tambah</button>
                </div>
            </div>
            <div class="product-card" data-name="penghapus" data-available="tersedia">
                <div class="product-img-placeholder">🧹</div>
                <div class="product-info">
                    <div class="product-name">Penghapus</div>
                    <div class="product-price">Rp2.000</div>
                    <div class="product-stock">Stok: 45</div>
                    <button class="btn btn-primary btn-sm btn-block" style="margin-top:auto;">+ Tambah</button>
                </div>
            </div>
            <div class="product-card" data-name="seragam putih biru" data-available="tersedia">
                <div class="product-img-placeholder">👔</div>
                <div class="product-info">
                    <div class="product-name">Seragam Putih Biru</div>
                    <div class="product-price">Rp85.000</div>
                    <div class="product-stock">Stok: 25</div>
                    <button class="btn btn-primary btn-sm btn-block" style="margin-top:auto;">+ Tambah</button>
                </div>
            </div>
            <div class="product-card" data-name="buku gambar" data-available="tersedia">
                <div class="product-img-placeholder">🎨</div>
                <div class="product-info">
                    <div class="product-name">Buku Gambar</div>
                    <div class="product-price">Rp4.000</div>
                    <div class="product-stock">Stok: 35</div>
                    <button class="btn btn-primary btn-sm btn-block" style="margin-top:auto;">+ Tambah</button>
                </div>
            </div>
        </div>
        <a href="/cart" class="float-cart">🛒<span class="float-cart-badge">3</span></a>
    </div>
    <div class="nav-bottom">
        <a href="/" class="nav-item"><span>🏠</span>Beranda</a>
        <a href="/dashboard" class="nav-item"><span>📋</span>Dashboard</a>
        <a href="/kantin" class="nav-item"><span>🍱</span>Kantin</a>
        <a href="/koperasi" class="nav-item active"><span>🏪</span>Koperasi</a>
        <a href="/cart" class="nav-item"><span>🛒</span>Keranjang</a>
    </div>
</div>
<script>
function setFilter(f,btn){window._filter=f;document.querySelectorAll('.filter-btn').forEach(b=>{b.style.background='transparent';b.style.color='#96A480'});btn.style.background='#96A480';btn.style.color='white';filterProducts()}
function filterProducts(){const q=document.getElementById('search-input').value.toLowerCase();const f=window._filter||'all';document.querySelectorAll('.product-card').forEach(c=>{const n=(c.dataset.name||'').includes(q);const s=f==='all'||c.dataset.available===f;c.style.display=n&&s?'':'none'})}
</script>
HTML;
mkdir("{$outDir}/koperasi", 0755, true);
file_put_contents("{$outDir}/koperasi/index.html", $koperasiHtml);
echo "  OK: /koperasi\n";

// 5. Cart
$cartHtml = pageHeader(['title' => 'Keranjang', 'desc' => 'Keranjang belanja K2Hub']);
$cartHtml .= <<<HTML
<div class="app-wrap">
    <div class="app-header">
        <div class="header-left">
            <a href="javascript:history.back()" class="btn-back">‹ Kembali</a>
            <div><div class="brand-text-k2" style="font-size:20px;">Keranjang</div></div>
        </div>
        <button onclick="if(confirm('Kosongkan keranjang?'))location.reload()" style="background:none;border:none;font-size:13px;font-weight:700;color:#ef4444;cursor:pointer;">Kosongkan</button>
    </div>
    <div class="app-content">
        <div style="background:white;border-radius:16px;margin-bottom:12px;border:1px solid rgba(231,100,142,0.08);">
            <div style="padding:12px 14px;border-bottom:1px solid #f0f0f0;display:flex;align-items:center;gap:10px;">
                <div style="width:40px;height:40px;border-radius:10px;background:#F1F5F9;display:flex;align-items:center;justify-content:center;font-size:20px;">🍜</div>
                <div style="flex:1;"><div style="font-weight:700;font-size:13px;color:#BA797D;">Kantin Hijau</div><div style="font-size:11px;color:#96A480;">Nasi Goreng</div></div>
                <div class="qty-control"><button class="qty-btn">−</button><span class="qty-num">2</span><button class="qty-btn">+</button></div>
                <div style="font-weight:800;font-size:14px;color:#96A480;min-width:60px;text-align:right;">Rp14.000</div>
            </div>
            <div style="padding:12px 14px;display:flex;align-items:center;gap:10px;">
                <div style="width:40px;height:40px;border-radius:10px;background:#F1F5F9;display:flex;align-items:center;justify-content:center;font-size:20px;">🥤</div>
                <div style="flex:1;"><div style="font-weight:700;font-size:13px;color:#BA797D;">Kantin Hijau</div><div style="font-size:11px;color:#96A480;">Es Teh</div></div>
                <div class="qty-control"><button class="qty-btn">−</button><span class="qty-num">1</span><button class="qty-btn">+</button></div>
                <div style="font-weight:800;font-size:14px;color:#96A480;min-width:60px;text-align:right;">Rp2.000</div>
            </div>
        </div>
        <div style="background:white;border-radius:16px;padding:14px;border:1px solid rgba(231,100,142,0.08);">
            <div style="display:flex;justify-content:space-between;margin-bottom:10px;">
                <span style="font-weight:600;color:#96A480;">Subtotal</span>
                <span style="font-weight:800;font-size:16px;">Rp16.000</span>
            </div>
            <button class="btn btn-primary btn-block" onclick="alert('Checkout: Rp16.000\\n\\nFitur ini tersedia setelah login.')">Checkout Sekarang ›</button>
        </div>
    </div>
    <div class="nav-bottom">
        <a href="/" class="nav-item"><span>🏠</span>Beranda</a>
        <a href="/dashboard" class="nav-item"><span>📋</span>Dashboard</a>
        <a href="/kantin" class="nav-item"><span>🍱</span>Kantin</a>
        <a href="/koperasi" class="nav-item"><span>🏪</span>Koperasi</a>
        <a href="/cart" class="nav-item active"><span>🛒</span>Keranjang</a>
    </div>
</div>
HTML;
file_put_contents("{$outDir}/cart.html", $cartHtml);
echo "  OK: /cart\n";

// 6. Store detail
$storeHtml = pageHeader(['title' => 'Kantin Hijau', 'desc' => 'Menu Kantin Hijau']);
$storeHtml .= <<<HTML
<div class="app-wrap">
    <div class="app-header">
        <div class="header-left">
            <a href="/kantin" class="btn-back">‹ Kembali</a>
            <div class="header-logo-wrap" style="width:48px;height:48px;font-size:26px;">🍜</div>
            <div><div class="brand-text-k2" style="font-size:22px;">Kantin Hijau</div><div class="header-subtitle">Warung Kantin · Buka</div></div>
        </div>
        <a href="/cart"><span style="font-size:22px;">🛒</span><span class="cart-badge">3</span></a>
    </div>
    <div class="app-content">
        <div class="search-wrap">
            <span class="search-icon">🔍</span>
            <input type="text" class="search-input" placeholder="Cari menu..." oninput="filterProducts()" id="search-input">
        </div>
        <div class="product-grid">
            <div class="product-card" data-name="nasi goreng">
                <div class="product-img-placeholder">🍚</div>
                <div class="product-info">
                    <div class="product-name">Nasi Goreng</div>
                    <div class="product-price">Rp7.000</div>
                    <div class="product-stock">Stok: 20</div>
                    <button class="btn btn-primary btn-sm btn-block">+ Tambah</button>
                </div>
            </div>
            <div class="product-card" data-name="mie ayam">
                <div class="product-img-placeholder">🍜</div>
                <div class="product-info">
                    <div class="product-name">Mie Ayam</div>
                    <div class="product-price">Rp6.000</div>
                    <div class="product-stock">Stok: 15</div>
                    <button class="btn btn-primary btn-sm btn-block">+ Tambah</button>
                </div>
            </div>
            <div class="product-card" data-name="es teh">
                <div class="product-img-placeholder">🥤</div>
                <div class="product-info">
                    <div class="product-name">Es Teh</div>
                    <div class="product-price">Rp2.000</div>
                    <div class="product-stock">Stok: 50</div>
                    <button class="btn btn-primary btn-sm btn-block">+ Tambah</button>
                </div>
            </div>
            <div class="product-card" data-name="air mineral">
                <div class="product-img-placeholder">💧</div>
                <div class="product-info">
                    <div class="product-name">Air Mineral</div>
                    <div class="product-price">Rp3.000</div>
                    <div class="product-stock">Stok: 30</div>
                    <button class="btn btn-primary btn-sm btn-block">+ Tambah</button>
                </div>
            </div>
        </div>
        <a href="/cart" class="float-cart">🛒<span class="float-cart-badge">3</span></a>
    </div>
    <div class="nav-bottom">
        <a href="/" class="nav-item"><span>🏠</span>Beranda</a>
        <a href="/dashboard" class="nav-item"><span>📋</span>Dashboard</a>
        <a href="/kantin" class="nav-item active"><span>🍱</span>Kantin</a>
        <a href="/koperasi" class="nav-item"><span>🏪</span>Koperasi</a>
        <a href="/cart" class="nav-item"><span>🛒</span>Keranjang</a>
    </div>
</div>
<script>
function filterProducts(){const q=document.getElementById('search-input').value.toLowerCase();document.querySelectorAll('.product-card').forEach(c=>{c.style.display=(c.dataset.name||'').includes(q)?'':'none'})}
</script>
HTML;
mkdir("{$outDir}/toko", 0755, true);
file_put_contents("{$outDir}/toko/kantin-hijau.html", $storeHtml);
echo "  OK: /toko/kantin-hijau\n";

// 7. Portal Pemilik
$portalHtml = pageHeader(['title' => 'Merchant Portal', 'desc' => 'Login dashboard pengelola']);
$portalHtml .= <<<HTML
<div class="welcome-bg" style="min-height:100vh;background:#F8F5F2;display:flex;flex-direction:column;align-items:center;padding:24px 20px;position:relative;">
    <div class="welcome-header">
        <a href="/" style="display:inline-flex;align-items:center;gap:8px;border:2.5px solid #F9E6A7;padding:8px 16px;border-radius:50px;color:#96A480;text-decoration:none;font-size:13px;font-weight:600;">
            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
            Kembali
        </a>
        <span class="brand" style="font-family:'Fredoka One',cursive;font-size:24px;background:linear-gradient(90deg,#96A480,#BA797D,#F9E6A7);-webkit-background-clip:text;-webkit-text-fill-color:transparent;-webkit-text-stroke:1px #5c4534;">K2Hub</span>
    </div>
    <div style="text-align:center;margin:40px 0;animation:popIn 0.7s cubic-bezier(0.34,1.56,0.64,1) forwards;">
        <span style="display:inline-block;background:rgba(150,164,128,0.12);color:#96A480;border:1px solid rgba(150,164,128,0.2);padding:4px 12px;border-radius:50px;font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:0.08em;margin-bottom:12px;">Merchant Portal</span>
        <h1 style="font-size:24px;font-weight:800;margin-bottom:6px;color:#BA797D;">Pilih Unit Usaha Anda</h1>
        <p style="font-size:14px;color:#96A480;">Akses dasbor pengelolaan untuk Koperasi atau Kantin</p>
    </div>
    <div style="display:grid;grid-template-columns:1fr;gap:16px;width:100%;max-width:400px;animation:fade-up 0.5s ease 0.2s both;">
        <a href="/warung/koperasi-sejahtera/login" style="background:#F8F5F2;border:2px solid #F9E6A7;border-radius:32px;padding:24px;text-decoration:none;transition:transform 0.3s;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                <div style="width:56px;height:56px;border-radius:16px;background:rgba(150,164,128,0.12);border:1px solid rgba(150,164,128,0.25);display:flex;align-items:center;justify-content:center;font-size:28px;">🏪</div>
                <span style="font-size:11px;font-weight:700;padding:3px 8px;border-radius:50px;background:rgba(150,164,128,0.12);color:#96A480;">KOPERASI</span>
            </div>
            <h2 style="font-size:18px;font-weight:800;color:#BA797D;margin-bottom:6px;">Koperasi Sekolah</h2>
            <p style="font-size:13px;color:#96A480;margin-bottom:16px;">Kelola persediaan barang, stok, dan pesanan</p>
            <div style="display:flex;justify-content:space-between;align-items:center;border-top:2px solid #F9E6A7;padding-top:14px;">
                <span style="font-size:14px;font-weight:600;color:#96A480;">Login Koperasi</span>
                <span style="font-size:18px;color:#BA797D;">→</span>
            </div>
        </a>
        <a href="/portal-pemilik/daftar" style="background:#F8F5F2;border:2px solid #F9E6A7;border-radius:32px;padding:24px;text-decoration:none;transition:transform 0.3s;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                <div style="width:56px;height:56px;border-radius:16px;background:rgba(245,158,11,0.12);border:1px solid rgba(245,158,11,0.25);display:flex;align-items:center;justify-content:center;font-size:28px;">🍽️</div>
                <span style="font-size:11px;font-weight:700;padding:3px 8px;border-radius:50px;background:rgba(245,158,11,0.12);color:#BA797D;">KANTIN</span>
            </div>
            <h2 style="font-size:18px;font-weight:800;color:#BA797D;margin-bottom:6px;">Kantin Sekolah</h2>
            <p style="font-size:13px;color:#96A480;margin-bottom:16px;">Pilih warung makan untuk kelola pesanan</p>
            <div style="display:flex;justify-content:space-between;align-items:center;border-top:2px solid #F9E6A7;padding-top:14px;">
                <span style="font-size:14px;font-weight:600;color:#96A480;">Pilih Warung Kantin</span>
                <span style="font-size:18px;color:#BA797D;">→</span>
            </div>
        </a>
        <a href="/admin/login" style="background:#F8F5F2;border:2px solid #F9E6A7;border-radius:32px;padding:24px;text-decoration:none;transition:transform 0.3s;">
            <div style="display:flex;align-items:center;gap:12px;margin-bottom:12px;">
                <div style="width:56px;height:56px;border-radius:16px;background:rgba(186,121,125,0.12);border:1px solid rgba(186,121,125,0.25);display:flex;align-items:center;justify-content:center;">
                    <svg width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="#BA797D" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                </div>
                <span style="font-size:11px;font-weight:700;padding:3px 8px;border-radius:50px;background:rgba(186,121,125,0.12);color:#BA797D;">ADMIN</span>
            </div>
            <h2 style="font-size:18px;font-weight:800;color:#BA797D;margin-bottom:6px;">Admin Sekolah</h2>
            <p style="font-size:13px;color:#96A480;margin-bottom:16px;">Manajemen toko, akun, dan laporan keuangan</p>
            <div style="display:flex;justify-content:space-between;align-items:center;border-top:2px solid #F9E6A7;padding-top:14px;">
                <span style="font-size:14px;font-weight:600;color:#96A480;">Login Admin Panel</span>
                <span style="font-size:18px;color:#BA797D;">→</span>
            </div>
        </a>
    </div>
    <div class="welcome-footer" style="position:absolute;bottom:12px;color:#96A480;font-weight:500;">© 2025 K2Hub — SMP Al Amanah</div>
</div>
<style>
@keyframes popIn{from{transform:scale(0.6);opacity:0}to{transform:scale(1);opacity:1}}
@keyframes fade-up{from{opacity:0;transform:translateY(20px)}to{opacity:1;transform:translateY(0)}}
</style>
HTML;
file_put_contents("{$outDir}/portal-pemilik.html", $portalHtml);
echo "  OK: /portal-pemilik\n";

// 8. Owner list (daftar warung)
$ownerListHtml = pageHeader(['title' => 'Daftar Unit Usaha', 'desc' => 'Pilih unit usaha']);
$ownerListHtml .= <<<HTML
<div style="min-height:100vh;background:#F8F5F2;display:flex;flex-direction:column;align-items:center;padding:24px 20px;position:relative;">
    <div style="width:100%;max-width:600px;">
        <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:24px;">
            <a href="/portal-pemilik" style="display:inline-flex;align-items:center;gap:8px;border:2.5px solid #F9E6A7;padding:8px 16px;border-radius:50px;color:#96A480;text-decoration:none;font-size:13px;font-weight:600;">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5"><path d="M19 12H5M12 19l-7-7 7-7"/></svg>
                Kembali
            </a>
            <span style="font-family:'Fredoka One',cursive;font-size:22px;background:linear-gradient(90deg,#96A480,#BA797D,#F9E6A7);-webkit-background-clip:text;-webkit-text-fill-color:transparent;-webkit-text-stroke:1px #5c4534;">K2Hub</span>
        </div>
        <div style="text-align:center;margin-bottom:32px;">
            <h1 style="font-size:22px;font-weight:800;color:#BA797D;">Daftar Warung Kantin</h1>
            <p style="font-size:13px;color:#96A480;">Pilih warung Anda untuk login</p>
        </div>
        <div style="display:grid;grid-template-columns:1fr;gap:16px;">
            <a href="/warung/kantin-hijau/login" style="background:#F8F5F2;border:2.5px solid #F9E6A7;border-radius:20px;padding:20px;text-decoration:none;display:flex;align-items:center;gap:16px;">
                <div style="font-size:36px;width:56px;height:56px;border-radius:14px;border:2.5px solid #F9E6A7;display:flex;align-items:center;justify-content:center;">🍜</div>
                <div style="flex:1;">
                    <div style="font-size:16px;font-weight:700;color:#BA797D;">Kantin Hijau</div>
                    <div style="font-size:12px;color:#96A480;">kantin · Buka</div>
                </div>
                <span style="font-size:10px;font-weight:700;padding:3px 8px;border-radius:50px;background:rgba(150,164,128,0.12);color:#96A480;">LOGIN →</span>
            </a>
            <a href="/warung/kantin-biru/login" style="background:#F8F5F2;border:2.5px solid #F9E6A7;border-radius:20px;padding:20px;text-decoration:none;display:flex;align-items:center;gap:16px;">
                <div style="font-size:36px;width:56px;height:56px;border-radius:14px;border:2.5px solid #F9E6A7;display:flex;align-items:center;justify-content:center;">🥤</div>
                <div style="flex:1;">
                    <div style="font-size:16px;font-weight:700;color:#BA797D;">Kantin Biru</div>
                    <div style="font-size:12px;color:#96A480;">kantin · Buka</div>
                </div>
                <span style="font-size:10px;font-weight:700;padding:3px 8px;border-radius:50px;background:rgba(150,164,128,0.12);color:#96A480;">LOGIN →</span>
            </a>
        </div>
    </div>
    <div style="position:absolute;bottom:12px;text-align:center;font-size:11px;color:#96A480;font-weight:500;">© 2025 K2Hub — SMP Al Amanah</div>
</div>
HTML;
mkdir("{$outDir}/portal-pemilik", 0755, true);
file_put_contents("{$outDir}/portal-pemilik/daftar.html", $ownerListHtml);
file_put_contents("{$outDir}/portal-pemilik/daftar-kantin.html", $ownerListHtml);
echo "  OK: /portal-pemilik/daftar\n";

// 9. Admin login
$adminLoginHtml = pageHeader(['title' => 'Login Admin', 'desc' => 'Admin Panel K2Hub']);
$adminLoginHtml .= <<<HTML
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;background:#F8F5F2;">
    <div style="width:100%;max-width:400px;background:#F8F5F2;border:1px solid #F9E6A7;border-radius:28px;padding:40px 32px;">
        <div style="text-align:center;margin-bottom:28px;">
            <div style="width:72px;height:72px;border-radius:20px;background:rgba(186,121,125,0.15);border:1px solid rgba(186,121,125,0.3);display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                <svg width="34" height="34" viewBox="0 0 24 24" fill="none" stroke="#BA797D" stroke-width="2.5"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
            </div>
            <h1 style="font-size:22px;font-weight:800;color:#BA797D;margin-bottom:4px;">Admin Panel</h1>
            <p style="font-size:13px;color:#96A480;">Kantin Al-Amanah — Manajemen Unit Usaha</p>
        </div>
        <form onsubmit="event.preventDefault();alert('Login Admin: admin@kantin-alamanah.com / password')">
            <div style="margin-bottom:16px;">
                <label style="font-size:11px;font-weight:700;color:#96A480;margin-bottom:6px;display:block;letter-spacing:0.04em;">Email Admin</label>
                <input type="email" value="admin@kantin-alamanah.com" style="width:100%;padding:12px 16px;border-radius:12px;border:1px solid #F9E6A7;font-family:inherit;font-size:14px;background:#F8F5F2;color:#BA797D;outline:none;">
            </div>
            <div style="margin-bottom:16px;">
                <label style="font-size:11px;font-weight:700;color:#96A480;margin-bottom:6px;display:block;letter-spacing:0.04em;">Password</label>
                <input type="password" value="password" style="width:100%;padding:12px 16px;border-radius:12px;border:1px solid #F9E6A7;font-family:inherit;font-size:14px;background:#F8F5F2;color:#BA797D;outline:none;">
            </div>
            <button type="submit" style="width:100%;padding:14px;background:#BA797D;color:white;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;">Masuk ke Admin Panel</button>
        </form>
        <a href="/" style="display:block;text-align:center;margin-top:20px;font-size:12px;color:#96A480;text-decoration:none;">← Kembali ke Beranda</a>
    </div>
</div>
HTML;
mkdir("{$outDir}/admin", 0755, true);
file_put_contents("{$outDir}/admin/login.html", $adminLoginHtml);
echo "  OK: /admin/login\n";

// 10. Warung login (store login)
$warungLoginHtml = pageHeader(['title' => 'Login Warung', 'desc' => 'Login dashboard warung']);
$warungLoginHtml .= <<<HTML
<div style="min-height:100vh;display:flex;align-items:center;justify-content:center;padding:24px;background:#F8F5F2;">
    <div style="width:100%;max-width:400px;background:#F8F5F2;border:1px solid #F9E6A7;border-radius:28px;padding:32px 24px;">
        <div style="text-align:center;margin-bottom:24px;">
            <div style="width:64px;height:64px;border-radius:16px;background:rgba(186,121,125,0.15);border:1px solid rgba(186,121,125,0.3);display:flex;align-items:center;justify-content:center;margin:0 auto 12px;font-size:32px;">🍜</div>
            <h1 style="font-size:20px;font-weight:800;color:#BA797D;">Login Warung</h1>
            <p style="font-size:13px;color:#96A480;">Kantin Hijau</p>
        </div>
        <form onsubmit="event.preventDefault();alert('Masukkan PIN 4 digit untuk login')">
            <div style="margin-bottom:16px;">
                <label style="font-size:11px;font-weight:700;color:#96A480;margin-bottom:6px;display:block;">PIN Pemilik</label>
                <input type="password" placeholder="••••" maxlength="4" style="width:100%;padding:12px 16px;border-radius:12px;border:1px solid #F9E6A7;font-family:inherit;font-size:18px;background:#F8F5F2;color:#BA797D;outline:none;text-align:center;letter-spacing:8px;">
            </div>
            <button type="submit" style="width:100%;padding:14px;background:#BA797D;color:white;border:none;border-radius:12px;font-size:15px;font-weight:700;cursor:pointer;">Login</button>
        </form>
        <a href="/portal-pemilik/daftar" style="display:block;text-align:center;margin-top:16px;font-size:12px;color:#96A480;text-decoration:none;">← Pilih warung lain</a>
    </div>
</div>
HTML;
mkdir("{$outDir}/warung", 0755, true);
mkdir("{$outDir}/warung/kantin-hijau", 0755, true);
file_put_contents("{$outDir}/warung/kantin-hijau/login.html", $warungLoginHtml);
echo "  OK: /warung/kantin-hijau/login\n";

// 11. Warung dashboard
$warungDashboardHtml = pageHeader(['title' => 'Dashboard Warung', 'desc' => 'Dashboard warung K2Hub']);
$warungDashboardHtml .= <<<HTML
<div class="app-wrap">
    <div class="app-header">
        <div class="header-left">
            <a href="javascript:history.back()" class="btn-back">‹ Kembali</a>
            <div class="header-logo-wrap" style="font-size:22px;">🍜</div>
            <div><div class="brand-text-k2" style="font-size:18px;">Kantin Hijau</div><div class="header-subtitle">Buka · 🕐 06:30 - 16:00</div></div>
        </div>
    </div>
    <div class="app-content">
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
            <div style="background:white;border-radius:16px;padding:14px;text-align:center;border:1px solid rgba(231,100,142,0.08);">
                <div style="font-size:28px;">📋</div>
                <div style="font-size:24px;font-weight:900;color:#BA797D;">1</div>
                <div style="font-size:11px;color:#96A480;font-weight:600;">Pesanan Baru</div>
            </div>
            <div style="background:white;border-radius:16px;padding:14px;text-align:center;border:1px solid rgba(231,100,142,0.08);">
                <div style="font-size:28px;">✅</div>
                <div style="font-size:24px;font-weight:900;color:#96A480;">45</div>
                <div style="font-size:11px;color:#96A480;font-weight:600;">Selesai Hari Ini</div>
            </div>
        </div>
        <div class="section-title">Pesanan Baru</div>
        <div class="order-status-card">
            <div style="display:flex;justify-content:space-between;align-items:start;margin-bottom:10px;">
                <div>
                    <div style="font-size:20px;font-family:'Fredoka One',cursive;color:#BA797D;">No. A001</div>
                    <div style="font-size:12px;color:#96A480;font-weight:600;">Budi Santoso · Kelas VII-A</div>
                </div>
                <span class="badge badge-open" style="background:#F9E6A7;color:#92400e;">⏳ Baru</span>
            </div>
            <div style="font-size:12px;margin-bottom:8px;color:#2D1B3D;">
                <div>🍚 Nasi Goreng × 2</div>
                <div>🥤 Es Teh × 1</div>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid #f0f0f0;padding-top:10px;">
                <span style="font-weight:800;color:#96A480;font-size:14px;">Rp16.000</span>
                <div style="display:flex;gap:8px;">
                    <button style="padding:6px 14px;border-radius:999px;border:2px solid #BA797D;background:transparent;color:#BA797D;font-size:12px;font-weight:700;cursor:pointer;">Tolak</button>
                    <button style="padding:6px 14px;border-radius:999px;background:#96A480;color:white;border:none;font-size:12px;font-weight:700;cursor:pointer;">Proses</button>
                </div>
            </div>
        </div>
        <div class="empty-state">
            <span class="empty-icon">📋</span>
            <div class="empty-title">Tidak ada pesanan lain</div>
        </div>
    </div>
</div>
HTML;
file_put_contents("{$outDir}/warung/kantin-hijau/dashboard.html", $warungDashboardHtml);
echo "  OK: /warung/kantin-hijau/dashboard\n";

// 12. Order status (pesanan)
$orderStatusHtml = pageHeader(['title' => 'Status Pesanan', 'desc' => 'Status pesanan K2Hub']);
$orderStatusHtml .= <<<HTML
<div class="app-wrap">
    <div class="app-header">
        <div class="header-left">
            <a href="/dashboard" class="btn-back">‹ Kembali</a>
            <div><div class="brand-text-k2" style="font-size:20px;">Status Pesanan</div></div>
        </div>
    </div>
    <div class="app-content">
        <div style="background:white;border-radius:16px;padding:16px;margin-bottom:16px;border:1px solid rgba(231,100,142,0.08);text-align:center;">
            <div style="font-size:36px;margin-bottom:8px;">🎉</div>
            <div style="font-size:18px;font-family:'Fredoka One',cursive;color:#BA797D;margin-bottom:4px;">No. A001</div>
            <div style="font-size:12px;color:#96A480;font-weight:600;">Kantin Hijau · Budi Santoso</div>
        </div>
        <div style="background:white;border-radius:16px;padding:16px;margin-bottom:16px;border:1px solid rgba(231,100,142,0.08);">
            <div class="section-title" style="margin-top:0;">Progress Pesanan</div>
            <div style="display:flex;align-items:center;gap:0;margin-bottom:16px;">
                <div style="display:flex;flex-direction:column;align-items:center;">
                    <div style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;background:#A9D770;border:2px solid #A9D770;">💳</div>
                    <div style="font-size:9px;font-weight:700;color:#96A480;margin-top:3px;">Bayar</div>
                </div>
                <div style="flex:1;height:2px;background:#A9D770;"></div>
                <div style="display:flex;flex-direction:column;align-items:center;">
                    <div style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;background:#A9D770;border:2px solid #A9D770;">🍳</div>
                    <div style="font-size:9px;font-weight:700;color:#96A480;margin-top:3px;">Proses</div>
                </div>
                <div style="flex:1;height:2px;background:#f0f0f0;"></div>
                <div style="display:flex;flex-direction:column;align-items:center;">
                    <div style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;background:white;border:2px solid #DAD6D3;">🎉</div>
                    <div style="font-size:9px;font-weight:700;color:#94a3b8;margin-top:3px;">Siap</div>
                </div>
                <div style="flex:1;height:2px;background:#f0f0f0;"></div>
                <div style="display:flex;flex-direction:column;align-items:center;">
                    <div style="width:32px;height:32px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:14px;background:white;border:2px solid #DAD6D3;">✅</div>
                    <div style="font-size:9px;font-weight:700;color:#94a3b8;margin-top:3px;">Selesai</div>
                </div>
            </div>
            <div style="display:flex;justify-content:space-between;align-items:center;border-top:1px solid #f0f0f0;padding-top:10px;">
                <span style="font-size:12px;color:#96A480;">Total</span>
                <span style="font-size:18px;font-weight:900;color:#96A480;">Rp16.000</span>
            </div>
        </div>
        <div style="background:white;border-radius:16px;padding:16px;border:1px solid rgba(231,100,142,0.08);">
            <div class="section-title" style="margin-top:0;">Detail Pesanan</div>
            <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #f0f0f0;">
                <span style="font-size:12px;color:#BA797D;">🍚 Nasi Goreng × 2</span>
                <span style="font-size:12px;font-weight:700;">Rp14.000</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:6px 0;border-bottom:1px solid #f0f0f0;">
                <span style="font-size:12px;color:#BA797D;">🥤 Es Teh × 1</span>
                <span style="font-size:12px;font-weight:700;">Rp2.000</span>
            </div>
            <div style="display:flex;justify-content:space-between;padding:6px 0;font-weight:800;color:#96A480;">
                <span>Total</span>
                <span>Rp16.000</span>
            </div>
        </div>
    </div>
    <div class="nav-bottom">
        <a href="/" class="nav-item"><span>🏠</span>Beranda</a>
        <a href="/dashboard" class="nav-item"><span>📋</span>Dashboard</a>
        <a href="/kantin" class="nav-item"><span>🍱</span>Kantin</a>
        <a href="/koperasi" class="nav-item"><span>🏪</span>Koperasi</a>
        <a href="/cart" class="nav-item"><span>🛒</span>Keranjang</a>
    </div>
</div>
HTML;
mkdir("{$outDir}/pesanan", 0755, true);
file_put_contents("{$outDir}/pesanan/K2H-20250712-001.html", $orderStatusHtml);
echo "  OK: /pesanan/{K2H-20250712-001}\n";

echo "\n✅ Build complete! Output: {$outDir}\n";
