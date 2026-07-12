<?php

namespace App\Http\Controllers;

use App\Models\Store;

class DashboardController extends Controller
{
    /**
     * Halaman utama: 2 pilihan unit usaha (Koperasi & Kantin)
     */
    public function index()
    {
        $koperasiStore = Store::where('unit', 'koperasi')->first();
        $koperasiCount = Store::where('unit', 'koperasi')->count();
        $kantinCount   = Store::where('unit', 'kantin')->count();

        $koperasiOpen = Store::where('unit', 'koperasi')->where('is_open', true)->count();
        $kantinOpen   = Store::where('unit', 'kantin')->where('is_open', true)->count();

        $activeOrders = \App\Models\Order::with('store')
            ->whereIn('status', ['pending', 'paid', 'processing', 'ready'])
            ->orderBy('created_at', 'desc')
            ->get();

        return view('dashboard.index', compact(
            'koperasiStore', 'koperasiCount', 'kantinCount', 'koperasiOpen', 'kantinOpen', 'activeOrders'
        ));
    }
}
