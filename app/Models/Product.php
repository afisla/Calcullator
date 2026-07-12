<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable = [
        'store_id', 'name', 'photo', 'price', 'description',
        'is_available', 'stock', 'sort_order',
    ];

    protected $casts = [
        'is_available' => 'boolean',
        'price'        => 'decimal:2',
        'stock'        => 'integer',
    ];

    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class);
    }

    public function stockHistories(): HasMany
    {
        return $this->hasMany(StockHistory::class);
    }

    public function getFormattedPriceAttribute(): string
    {
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

    /**
     * Ambil URL foto produk (foto upload atau placeholder)
     */
    public function getPhotoUrlAttribute(): string
    {
        if ($this->photo && file_exists(public_path('storage/' . $this->photo))) {
            return asset('storage/' . $this->photo);
        }
        return '';
    }

    /**
     * Catat riwayat stok
     */
    public function recordStock(int $qtyChange, string $type = 'out', ?int $orderId = null, ?string $note = null): void
    {
        $before = $this->stock;
        $after  = $before + $qtyChange;

        StockHistory::create([
            'product_id' => $this->id,
            'order_id'   => $orderId,
            'type'       => $type,
            'qty_change' => $qtyChange,
            'qty_before' => $before,
            'qty_after'  => $after,
            'note'       => $note,
        ]);
    }
}
