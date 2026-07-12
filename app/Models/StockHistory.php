<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StockHistory extends Model
{
    protected $fillable = [
        'product_id', 'order_id', 'type',
        'qty_change', 'qty_before', 'qty_after', 'note',
    ];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function getTypeLabel(): string
    {
        return match ($this->type) {
            'in'         => 'Stok Masuk',
            'out'        => 'Terjual',
            'adjustment' => 'Penyesuaian',
            default      => '-',
        };
    }
}
