<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;

class KantinController extends Controller
{
    /**
     * Daftar semua toko kantin
     */
    public function index()
    {
        $stores = Store::where('unit', 'kantin')
            ->orderBy('sort_order')
            ->get();

        return view('kantin.index', compact('stores'));
    }
}
