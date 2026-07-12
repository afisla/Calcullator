<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Store;
use App\Models\StockHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    /**
     * Simpan produk baru
     */
    public function store(Request $request, Store $store)
    {
        $request->validate([
            'name'         => 'required|string|max:150',
            'price'        => 'required|numeric|min:0|max:99999999',
            'stock'        => 'required|integer|min:0|max:999999',
            'description'  => 'nullable|string|max:500',
            'is_available' => 'sometimes|boolean',
            'photo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $photoPath = null;
        if ($request->hasFile('photo')) {
            $photoPath = $request->file('photo')->store("products/{$store->id}", 'public');
        }

        $product = Product::create([
            'store_id'     => $store->id,
            'name'         => $request->name,
            'photo'        => $photoPath,
            'price'        => $request->price,
            'description'  => $request->description,
            'stock'        => $request->stock,
            'is_available' => $request->has('is_available') ? true : false,
            'sort_order'   => Product::where('store_id', $store->id)->max('sort_order') + 1,
        ]);

        // Catat stok awal
        if ($product->stock > 0) {
            StockHistory::create([
                'product_id' => $product->id,
                'type'       => 'in',
                'qty_change' => $product->stock,
                'qty_before' => 0,
                'qty_after'  => $product->stock,
                'note'       => 'Stok awal',
            ]);
        }

        return back()->with('success', "Produk '{$product->name}' berhasil ditambahkan! 🎉");
    }

    /**
     * Update produk
     */
    public function update(Request $request, Store $store, Product $product)
    {
        abort_if($product->store_id !== $store->id, 403);

        $request->validate([
            'name'         => 'required|string|max:150',
            'price'        => 'required|numeric|min:0|max:99999999',
            'description'  => 'nullable|string|max:500',
            'is_available' => 'sometimes|boolean',
            'photo'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $data = [
            'name'         => $request->name,
            'price'        => $request->price,
            'description'  => $request->description,
            'is_available' => $request->boolean('is_available', false),
        ];

        if ($request->hasFile('photo')) {
            // Hapus foto lama
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }
            $data['photo'] = $request->file('photo')->store("products/{$store->id}", 'public');
        }

        $product->update($data);

        return back()->with('success', "Produk '{$product->name}' berhasil diperbarui!");
    }

    /**
     * Hapus produk
     */
    public function destroy(Store $store, Product $product)
    {
        abort_if($product->store_id !== $store->id, 403);

        try {
            if ($product->photo) {
                Storage::disk('public')->delete($product->photo);
            }

            $name = $product->name;
            $product->delete();

            if (request()->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => "Produk '{$name}' berhasil dihapus."
                ]);
            }

            return back()->with('success', "Produk '{$name}' berhasil dihapus.");
        } catch (\Exception $e) {
            if (request()->wantsJson()) {
                return response()->json([
                    'error' => 'Produk ini tidak bisa dihapus karena sudah memiliki riwayat transaksi/pesanan. Silakan nonaktifkan saja ketersediaan produk jika tidak ingin dijual lagi.'
                ], 422);
            }

            return back()->with('error', 'Produk ini tidak bisa dihapus karena sudah memiliki riwayat transaksi/pesanan. Silakan nonaktifkan produk saja.');
        }
    }

    /**
     * Update stok produk
     */
    public function updateStock(Request $request, Store $store, Product $product)
    {
        abort_if($product->store_id !== $store->id, 403);

        $request->validate([
            'stock'  => 'required|integer|min:0',
            'note'   => 'nullable|string|max:200',
        ]);

        $oldStock = $product->stock;
        $newStock = $request->stock;
        $change   = $newStock - $oldStock;

        $product->update(['stock' => $newStock]);

        StockHistory::create([
            'product_id' => $product->id,
            'type'       => $change >= 0 ? 'in' : 'adjustment',
            'qty_change' => $change,
            'qty_before' => $oldStock,
            'qty_after'  => $newStock,
            'note'       => $request->note ?? 'Penyesuaian manual',
        ]);

        return back()->with('success', "Stok '{$product->name}' diperbarui: {$oldStock} → {$newStock}");
    }

    /**
     * Toggle ketersediaan produk (AJAX)
     */
    public function toggleAvailability(Store $store, Product $product)
    {
        abort_if($product->store_id !== $store->id, 403);

        $product->update(['is_available' => ! $product->is_available]);

        return response()->json([
            'success'      => true,
            'is_available' => $product->is_available,
            'label'        => $product->is_available ? 'Tersedia' : 'Tidak Tersedia',
        ]);
    }
}
