@extends('layouts.app')

@section('title', 'Kantin — K2Hub')

@section('header-left')
    <a href="/dashboard" class="btn-back">‹ Kembali</a>
    <div style="display:flex;align-items:center;gap:8px;">
        <div class="header-logo-wrap" style="width:48px;height:48px;font-size:26px;">🍱</div>
        <div>
            <div class="brand-text-k2" style="font-size:22px;">Kantin</div>
            <div class="header-subtitle" style="font-size:12px;">Pilih warung kesukaan kamu</div>
        </div>
    </div>
@endsection

@section('content')

{{-- Search --}}
<div class="search-wrap">
    <span class="search-icon">🔍</span>
    <input type="text" id="search-input" class="search-input" placeholder="Cari warung..." oninput="filterWarung()">
</div>

{{-- Warung List --}}
<div class="section-title">🏪 Warung Kantin</div>

<div id="warung-grid" class="warung-grid">
    @forelse($stores as $store)
    <a href="/toko/{{ $store->id }}" style="text-decoration:none;" class="warung-item" data-name="{{ strtolower($store->name) }}">
        <div style="background:white;border-radius:18px;padding:16px;display:flex;align-items:center;gap:14px;box-shadow:0 2px 12px rgba(231,100,142,0.09);border:1.5px solid rgba(231,100,142,0.09);transition:all 0.2s;"
             onmouseover="this.style.transform='translateX(4px)';this.style.boxShadow='0 4px 20px rgba(231,100,142,0.18)'"
             onmouseout="this.style.transform='';this.style.boxShadow='0 2px 12px rgba(231,100,142,0.09)'">

            {{-- Ikon/Foto Warung --}}
            <div style="width:64px;height:64px;border-radius:16px;overflow:hidden;flex-shrink:0;background:#F1F5F9;display:flex;align-items:center;justify-content:center;font-size:32px;">
                @if($store->photo ?? null)
                    <img src="{{ asset('storage/'.$store->photo) }}" style="width:100%;height:100%;object-fit:cover;" alt="{{ $store->name }}">
                @else
                    {{ $store->icon_emoji ?? '🍽️' }}
                @endif
            </div>

            {{-- Info --}}
            <div style="flex:1;min-width:0;">
                <div style="font-size:15px;font-weight:800;color:#BA797D;margin-bottom:2px;">{{ $store->name }}</div>
                <div style="font-size:12px;color:#96A480;font-weight:500;margin-bottom:6px;">
                    {{ $store->category ?? 'Warung Kantin' }}
                </div>
                @if($store->description)
                <div style="font-size:11px;color:#96A480;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">
                    {{ $store->description }}
                </div>
                @endif
            </div>

            {{-- Status + Arrow --}}
            <div style="display:flex;flex-direction:column;align-items:flex-end;gap:8px;flex-shrink:0;">
                @if($store->is_open)
                    <span class="badge badge-open">● Buka</span>
                @else
                    <span class="badge badge-closed">● Tutup</span>
                @endif
                <span style="font-size:20px;color:#E7648E;">›</span>
            </div>
        </div>
    </a>
    @empty
    <div class="empty-state">
        <span class="empty-icon">🍱</span>
        <div class="empty-title">Belum ada warung</div>
        <div class="empty-text">Warung kantin belum tersedia saat ini.</div>
    </div>
    @endforelse
</div>

@endsection

@push('scripts')
<script>
    function filterWarung() {
        const q = document.getElementById('search-input').value.toLowerCase();
        document.querySelectorAll('.warung-item').forEach(item => {
            item.style.display = (item.dataset.name || '').includes(q) ? '' : 'none';
        });
    }
</script>
@endpush
