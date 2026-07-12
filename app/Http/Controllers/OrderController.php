<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Product;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    /**
     * Terima & mulai proses pesanan
     */
    public function process(Order $order)
    {
        $order->update(['status' => 'processing']);
        return response()->json(['success' => true, 'status' => 'processing']);
    }

    /**
     * Tandai pesanan siap diambil
     */
    public function ready(Order $order)
    {
        $order->update(['status' => 'ready']);
        return response()->json(['success' => true, 'status' => 'ready']);
    }

    /**
     * Tandai pesanan sudah diambil / selesai
     */
    public function complete(Order $order)
    {
        $order->update(['status' => 'completed']);
        return response()->json(['success' => true, 'status' => 'completed']);
    }

    /**
     * Tolak pesanan dengan alasan
     */
    public function reject(Request $request, Order $order)
    {
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // Kembalikan stok produk jika pesanan ditolak
        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->recordStock($item->quantity, 'in', $order->id, 'Pesanan ditolak: ' . $request->reason);
                $product->increment('stock', $item->quantity);
            }
        }

        $order->update([
            'status'           => 'rejected',
            'rejection_reason' => $request->reason,
        ]);

        return response()->json(['success' => true, 'status' => 'rejected']);
    }
}
