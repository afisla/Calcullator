<?php

namespace App\Http\Controllers;

use App\Models\Store;

class KoperasiController extends Controller
{
    /**
     * Halaman produk koperasi (katalog publik)
     */
    public function index()
    {
        $store = Store::where('unit', 'koperasi')->firstOrFail();

        $products = $store->products()
            ->orderBy('sort_order')
            ->get();

        return view('koperasi.index', compact('store', 'products'));
    }
}
