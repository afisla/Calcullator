<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Payment;
use App\Models\Product;
use App\Models\Store;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CartController extends Controller
{
    /**
     * Tambah produk ke keranjang (AJAX)
     */
    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'qty'        => 'sometimes|integer|min:1|max:20',
            'options'    => 'sometimes|array',
        ]);

        $product = Product::with('store')->findOrFail($request->product_id);

        if (! $product->is_available) {
            return response()->json(['error' => 'Produk tidak tersedia'], 422);
        }

        if (! $product->store->is_open) {
            return response()->json(['error' => 'Toko sedang tutup, tidak dapat menambah pesanan.'], 422);
        }

        if ($product->stock <= 0) {
            return response()->json(['error' => 'Stok habis'], 422);
        }

        $cart = session('cart');

        // Cek apakah cart dari toko berbeda
        if ($cart && $cart['store_id'] != $product->store_id) {
            return response()->json([
                'error'      => 'different_store',
                'message'    => 'Keranjang Anda berisi pesanan dari ' . $cart['store_name'] . '. Kosongkan keranjang dulu?',
                'store_name' => $cart['store_name'],
            ], 409);
        }

        if (! $cart) {
            $cart = [
                'store_id'   => $product->store_id,
                'store_unit' => $product->store->unit,
                'store_name' => $product->store->name,
                'items'      => [],
                'total'      => 0,
            ];
        }

        $pid = $product->id;
        $options = $request->input('options', []);
        $cartKey = $pid;
        if (!empty($options)) {
            $cartKey = $pid . '-' . md5(json_encode($options));
        }
        $qty = $request->qty ?? 1;

        // Validasi stok tidak melebihi yang ada
        $currentQty = isset($cart['items'][$cartKey]) ? $cart['items'][$cartKey]['qty'] : 0;
        if ($currentQty + $qty > $product->stock) {
            return response()->json([
                'error' => "Stok tidak mencukupi! Sisa stok: {$product->stock}",
            ], 422);
        }

        if (isset($cart['items'][$cartKey])) {
            $cart['items'][$cartKey]['qty'] += $qty;
        } else {
            $cart['items'][$cartKey] = [
                'product_id' => $product->id,
                'name'       => $product->name,
                'photo'      => $product->photo_url,
                'price'      => (float) $product->price,
                'qty'        => $qty,
                'stock'      => $product->stock,
                'options'    => $options,
            ];
        }

        $cart['items'][$cartKey]['subtotal'] = $cart['items'][$cartKey]['price'] * $cart['items'][$cartKey]['qty'];
        $cart['total'] = array_sum(array_column($cart['items'], 'subtotal'));

        session(['cart' => $cart]);

        return response()->json([
            'success'    => true,
            'item_count' => count($cart['items']),
            'total'      => $cart['total'],
            'qty'        => $cart['items'][$cartKey]['qty'],
            'cart_key'   => $cartKey,
        ]);
    }

    /**
     * Update jumlah item (AJAX)
     */
    public function update(Request $request)
    {
        $request->validate([
            'product_id' => 'required|string',
            'qty'        => 'required|integer|min:0',
        ]);

        $cart = session('cart');
        if (! $cart) {
            return response()->json(['success' => false], 404);
        }

        $pid = $request->product_id;

        if (isset($cart['items'][$pid])) {
            $realProductId = $cart['items'][$pid]['product_id'] ?? $pid;
            $product = Product::with('store')->find($realProductId);
            if ($product && !$product->store->is_open) {
                return response()->json(['error' => 'Toko sedang tutup, tidak dapat mengubah pesanan.'], 422);
            }
        }

        if ($request->qty <= 0) {
            unset($cart['items'][$pid]);
        } else {
            if (isset($cart['items'][$pid])) {
                // Cek stok produk
                $realProductId = $cart['items'][$pid]['product_id'] ?? $pid;
                $product = Product::find($realProductId);
                if ($product && $request->qty > $product->stock) {
                    return response()->json([
                        'error' => "Stok tidak mencukupi! Sisa stok: {$product->stock}",
                    ], 422);
                }
                $cart['items'][$pid]['qty']      = $request->qty;
                $cart['items'][$pid]['subtotal'] = $cart['items'][$pid]['price'] * $request->qty;
            }
        }

        if (empty($cart['items'])) {
            session()->forget('cart');
            return response()->json(['success' => true, 'empty' => true]);
        }

        $cart['total'] = array_sum(array_column($cart['items'], 'subtotal'));
        session(['cart' => $cart]);

        return response()->json([
            'success'    => true,
            'item_count' => count($cart['items']),
            'total'      => $cart['total'],
        ]);
    }

    /**
     * Halaman keranjang belanja
     */
    public function index()
    {
        $cart = session('cart');
        if (! $cart || empty($cart['items'])) {
            return redirect('/dashboard')->with('info', 'Keranjang Anda masih kosong.');
        }

        $store = Store::find($cart['store_id']);
        return view('student.cart', compact('cart', 'store'));
    }

    /**
     * Kosongkan keranjang
     */
    public function clear()
    {
        session()->forget('cart');
        return response()->json(['success' => true]);
    }

    /**
     * Checkout: buat pesanan di database
     */
    public function checkout(Request $request)
    {
        $cart = session('cart');

        if (! $cart || empty($cart['items'])) {
            return redirect('/dashboard')->with('error', 'Keranjang kosong!');
        }

        $request->validate([
            'customer_name'  => 'required|string|max:100',
            'customer_class' => 'required|string|max:20',
            'customer_phone' => 'required|string|max:20',
        ]);

        $store = Store::findOrFail($cart['store_id']);

        if (! $store->is_open) {
            return back()->with('error', 'Maaf, toko ini sedang tutup. Silakan pilih toko lain.');
        }

        // 1. Verifikasi stok semua produk
        foreach ($cart['items'] as $item) {
            $product = Product::findOrFail($item['product_id']);
            if ($product->stock < $item['qty']) {
                return back()->with('error', "Stok '{$product->name}' tidak mencukupi! Sisa: {$product->stock}.");
            }
        }

        // 2. Generate nomor antrian
        [$queueNumber, $queueCode] = Order::generateQueueCode($store);

        $sessionToken = session('anonymous_token') ?? Str::random(32);
        if (! session('anonymous_token')) {
            session(['anonymous_token' => $sessionToken]);
        }

        $orderCode = Order::generateCode();

        // 3. Buat pesanan
        $order = Order::create([
            'store_id'       => $store->id,
            'session_token'  => $sessionToken,
            'order_code'     => $orderCode,
            'queue_number'   => $queueNumber,
            'queue_code'     => $queueCode,
            'customer_name'  => trim($request->customer_name),
            'customer_class' => trim($request->customer_class ?? ''),
            'customer_phone' => trim($request->customer_phone ?? ''),
            'status'         => 'pending',
            'payment_status' => 'pending',
            'total_price'    => $cart['total'],
        ]);

        // 4. Buat order items
        foreach ($cart['items'] as $item) {
            $finalName = $item['name'];
            if (!empty($item['options'])) {
                $optionStrings = [];
                foreach ($item['options'] as $key => $val) {
                    $label = str_replace('_', ' ', $key);
                    $optionStrings[] = ucfirst($label) . ': ' . $val;
                }
                $finalName .= ' (' . implode(', ', $optionStrings) . ')';
            }

            OrderItem::create([
                'order_id'     => $order->id,
                'product_id'   => $item['product_id'],
                'product_name' => $finalName,
                'price'        => $item['price'],
                'quantity'     => $item['qty'],
                'subtotal'     => $item['subtotal'],
            ]);
        }

        // 5. Buat record payment
        Payment::create([
            'order_id' => $order->id,
            'status'   => 'pending',
            'amount'   => $cart['total'],
        ]);

        session()->forget('cart');

        return redirect("/bayar/{$order->order_code}");
    }
}
