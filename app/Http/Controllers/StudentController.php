<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;

class StudentController extends Controller
{
    /**
     * Halaman menu produk warung yang dipilih
     */
    public function show(Store $store)
    {
        $products = $store->products()
            ->orderBy('sort_order')
            ->get();

        $cart = session('cart');

        $activeOrders = Order::with('store')
            ->where('session_token', session('anonymous_token'))
            ->whereIn('status', ['pending', 'paid', 'processing', 'ready'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('student.store', compact('store', 'products', 'cart', 'activeOrders'));
    }

    /**
     * Halaman status pesanan
     */
    public function orderStatus(string $orderCode)
    {
        $order = Order::with(['store', 'items'])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        // Siswa atau penjaga bisa lihat
        $isBuyer = ($order->session_token === session('anonymous_token'));
        $isStore  = session()->has("store_auth_{$order->store_id}");
        $isAdmin  = session()->has('admin_authenticated');

        if (! $isBuyer && ! $isStore && ! $isAdmin) {
            // Jika tidak ada yang cocok, tetap tampilkan (antrian publik)
            // Anda bisa ubah jadi abort(403) jika ingin lebih ketat
        }

        return view('student.order-status', compact('order'));
    }
}
