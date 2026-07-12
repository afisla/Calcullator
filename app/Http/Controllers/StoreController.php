<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Request;

class StoreController extends Controller
{
    /**
     * Form login PIN toko
     */
    public function loginForm(Store $store)
    {
        // Sudah login? Langsung ke dashboard
        if (session()->has("store_auth_{$store->id}")) {
            return redirect("/warung/{$store->id}");
        }

        return view('store.login', compact('store'));
    }

    /**
     * Proses login PIN toko
     */
    public function login(Request $request, Store $store)
    {
        $request->validate([
            'pin' => 'required|string|min:4|max:10',
        ]);

        if (! $store->checkPin($request->pin)) {
            return back()->withErrors(['pin' => 'PIN salah! Silakan coba lagi.'])->withInput();
        }

        // Simpan auth session untuk toko ini (12 jam)
        session(["store_auth_{$store->id}" => true]);
        session()->put("store_name_{$store->id}", $store->name);

        return redirect("/warung/{$store->id}")
            ->with('success', "Selamat datang di Dashboard '{$store->name}'! 🎉");
    }

    /**
     * Dashboard dasbor warung (order board)
     */
    public function dashboard(Store $store)
    {
        $activeOrders = Order::with('items')
            ->where('store_id', $store->id)
            ->whereIn('status', ['paid', 'processing', 'ready'])
            ->orderBy('paid_at')
            ->get();

        $completedToday = Order::where('store_id', $store->id)
            ->where('status', 'completed')
            ->whereDate('updated_at', today())
            ->count();

        $revenueToday = Order::where('store_id', $store->id)
            ->whereIn('status', ['paid', 'processing', 'ready', 'completed'])
            ->whereDate('paid_at', today())
            ->sum('total_price');

        $orderHistory = Order::with('items')
            ->where('store_id', $store->id)
            ->whereIn('status', ['completed', 'rejected'])
            ->orderBy('updated_at', 'desc')
            ->take(50)
            ->get();

        $revenueTotal = Order::where('store_id', $store->id)
            ->whereIn('status', ['paid', 'processing', 'ready', 'completed'])
            ->sum('total_price');

        $itemSales = \App\Models\OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.store_id', $store->id)
            ->whereIn('orders.status', ['paid', 'processing', 'ready', 'completed'])
            ->select('order_items.product_name', \DB::raw('SUM(order_items.quantity) as total_qty'), \DB::raw('SUM(order_items.subtotal) as total_revenue'))
            ->groupBy('order_items.product_name')
            ->orderBy('total_qty', 'desc')
            ->get();

        return view('store.dashboard', compact(
            'store', 'activeOrders', 'completedToday', 'revenueToday', 'orderHistory', 'revenueTotal', 'itemSales'
        ));
    }

    /**
     * Ubah status buka/tutup toko
     */
    public function toggleStatus(Store $store)
    {
        $store->update(['is_open' => ! $store->is_open]);
        
        $status = $store->is_open ? 'Buka' : 'Tutup';
        return back()->with('success', "Toko sudah {$status}! ");
    }

    /**
     * Keluar dari dashboard toko
     */
    public function logout(Store $store)
    {
        session()->forget("store_auth_{$store->id}");

        return redirect()->route('portal.owner')
            ->with('success', 'Anda telah keluar dari dashboard toko. 👋');
    }
}
