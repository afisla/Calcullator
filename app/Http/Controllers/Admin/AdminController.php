<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Store;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    /**
     * Dashboard admin — ringkasan keseluruhan
     */
    public function dashboard()
    {
        $totalStores     = Store::count();
        $openStores      = Store::where('is_open', true)->count();
        $totalOrdersToday = Order::whereDate('created_at', today())->count();
        $revenueToday    = Order::whereIn('status', ['paid', 'processing', 'ready', 'completed'])
            ->whereDate('paid_at', today())->sum('total_price');
        $revenueTotal    = Order::whereIn('status', ['paid', 'processing', 'ready', 'completed'])
            ->sum('total_price');
        $pendingOrders   = Order::where('status', 'paid')->count();

        $revenuePerStore = Store::withSum(
            ['orders' => fn($q) => $q->whereIn('status', ['paid', 'processing', 'ready', 'completed'])],
            'total_price'
        )->orderBy('sort_order')->get();

        return view('admin.dashboard', compact(
            'totalStores', 'openStores', 'totalOrdersToday',
            'revenueToday', 'revenueTotal', 'pendingOrders', 'revenuePerStore'
        ));
    }

    /**
     * Kelola toko — CRUD
     */
    public function stores()
    {
        $stores = Store::orderBy('sort_order')->get();
        return view('admin.stores', compact('stores'));
    }

    /**
     * Update data toko
     */
    public function updateStore(Request $request, Store $store)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'category'    => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'unit'        => 'required|in:koperasi,kantin',
            'sort_order'  => 'required|integer|min:1',
            'is_open'     => 'sometimes|boolean',
        ]);

        if ($request->filled('pin')) {
            $request->validate(['pin' => 'string|min:4|max:10']);
            $validated['pin'] = Hash::make($request->pin);
        }

        $store->update($validated);

        return back()->with('success', "Toko '{$store->name}' berhasil diperbarui.");
    }

    /**
     * Toggle status buka/tutup toko
     */
    public function toggleStore(Store $store)
    {
        $store->update(['is_open' => ! $store->is_open]);

        $status = $store->is_open ? 'dibuka' : 'ditutup';
        return back()->with('success', "Toko '{$store->name}' berhasil {$status}.");
    }

    /**
     * Laporan keuangan semua toko
     */
    public function finance(Request $request)
    {
        $dateFrom = $request->input('from', today()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->input('to', today()->format('Y-m-d'));

        $financeData = Store::orderBy('sort_order')->get()->map(function ($store) use ($dateFrom, $dateTo) {
            $query = Order::where('store_id', $store->id)
                ->whereIn('status', ['paid', 'processing', 'ready', 'completed'])
                ->whereBetween('paid_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);

            return [
                'store'         => $store,
                'total_revenue' => $query->sum('total_price'),
                'total_orders'  => $query->count(),
                'avg_order'     => $query->avg('total_price') ?? 0,
            ];
        });

        $grandTotal = $financeData->sum('total_revenue');

        $topProducts = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->whereIn('orders.status', ['paid', 'processing', 'ready', 'completed'])
            ->whereBetween('orders.paid_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('order_items.product_name')
            ->orderBy('total_qty', 'desc')
            ->take(10)
            ->get();

        return view('admin.finance', compact(
            'financeData', 'grandTotal', 'dateFrom', 'dateTo', 'topProducts'
        ));
    }

    /**
     * Detail keuangan per toko
     */
    public function financeStore(Request $request, Store $store)
    {
        $dateFrom = $request->input('from', today()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->input('to', today()->format('Y-m-d'));

        $orders = Order::with('items')
            ->where('store_id', $store->id)
            ->whereIn('status', ['paid', 'processing', 'ready', 'completed'])
            ->whereBetween('paid_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->orderBy('paid_at', 'desc')
            ->get();

        $totalRevenue = $orders->sum('total_price');
        $totalOrders  = $orders->count();

        $itemSales = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.store_id', $store->id)
            ->whereIn('orders.status', ['paid', 'processing', 'ready', 'completed'])
            ->whereBetween('orders.paid_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('order_items.product_name')
            ->orderBy('total_qty', 'desc')
            ->get();

        return view('admin.finance-store', compact(
            'store', 'orders', 'totalRevenue', 'totalOrders', 'itemSales', 'dateFrom', 'dateTo'
        ));
    }

    /**
     * Buat toko baru
     */
    public function createStore(Request $request)
    {
        $validated = $request->validate([
            'name'        => 'required|string|max:100',
            'category'    => 'required|string|max:100',
            'description' => 'nullable|string|max:500',
            'unit'        => 'required|in:koperasi,kantin',
            'sort_order'  => 'required|integer|min:1',
            'pin'         => 'required|string|min:4|max:10',
            'icon_emoji'  => 'nullable|string|max:5',
        ]);

        $validated['pin'] = Hash::make($request->pin);
        $validated['is_open'] = false;
        if (empty($validated['icon_emoji'])) {
            $validated['icon_emoji'] = $request->unit === 'koperasi' ? '🏪' : '🍳';
        }

        $store = Store::create($validated);

        return back()->with('success', "Toko '{$store->name}' berhasil dibuat.");
    }

    /**
     * Hapus toko
     */
    public function deleteStore(Store $store)
    {
        $name = $store->name;
        $store->delete();

        return back()->with('success', "Toko '{$name}' berhasil dihapus.");
    }

    /**
     * Kelola semua produk
     */
    public function products(Request $request)
    {
        $storeId = $request->input('store_id');
        $stores  = Store::orderBy('sort_order')->get();
        $query   = Product::with('store');
        if ($storeId) {
            $query->where('store_id', $storeId);
        }
        $products = $query->orderBy('name')->get();

        return view('admin.products', compact('products', 'stores', 'storeId'));
    }

    /**
     * Kelola semua akun pengelola
     */
    public function accounts()
    {
        $users = User::orderBy('name')->get();
        return view('admin.accounts', compact('users'));
    }

    /**
     * Buat akun baru
     */
    public function createAccount(Request $request)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
            'is_admin' => 'sometimes|boolean',
        ]);

        $validated['password'] = Hash::make($request->password);
        $validated['is_admin'] = $request->boolean('is_admin', false);

        User::create($validated);

        return back()->with('success', "Akun untuk '{$validated['name']}' berhasil dibuat.");
    }

    /**
     * Update akun
     */
    public function updateAccount(Request $request, User $user)
    {
        $validated = $request->validate([
            'name'     => 'required|string|max:100',
            'email'    => 'required|email|unique:users,email,' . $user->id,
            'is_admin' => 'sometimes|boolean',
        ]);

        if ($request->filled('password')) {
            $request->validate(['password' => 'string|min:6']);
            $validated['password'] = Hash::make($request->password);
        }

        $validated['is_admin'] = $request->boolean('is_admin', false);

        $user->update($validated);

        return back()->with('success', "Akun '{$user->name}' berhasil diperbarui.");
    }

    /**
     * Hapus akun
     */
    public function deleteAccount(User $user)
    {
        if ($user->id === auth()->id() || $user->email === 'admin@kantin-alamanah.com') {
            return back()->with('error', 'Anda tidak dapat menghapus akun Anda sendiri atau akun admin utama.');
        }

        $name = $user->name;
        $user->delete();

        return back()->with('success', "Akun '{$name}' berhasil dihapus.");
    }

    /**
     * Laporan PDF (print-friendly)
     */
    public function exportPdf(Request $request)
    {
        $dateFrom = $request->input('from', today()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->input('to', today()->format('Y-m-d'));

        $financeData = Store::orderBy('sort_order')->get()->map(function ($store) use ($dateFrom, $dateTo) {
            $query = Order::where('store_id', $store->id)
                ->whereIn('status', ['paid', 'processing', 'ready', 'completed'])
                ->whereBetween('paid_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);

            return [
                'store'         => $store,
                'total_revenue' => $query->sum('total_price'),
                'total_orders'  => $query->count(),
            ];
        });

        $grandTotal = $financeData->sum('total_revenue');

        return view('admin.export-pdf', compact('financeData', 'grandTotal', 'dateFrom', 'dateTo'));
    }

    /**
     * Laporan Excel (CSV stream)
     */
    public function exportExcel(Request $request)
    {
        $dateFrom = $request->input('from', today()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->input('to', today()->format('Y-m-d'));

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=Laporan_Keuangan_K2Hub_{$dateFrom}_sd_{$dateTo}.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($dateFrom, $dateTo) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ['LAPORAN KEUANGAN K2HUB - SMP AL AMANAH']);
            fputcsv($file, ["Periode: {$dateFrom} s/d {$dateTo}"]);
            fputcsv($file, []);
            fputcsv($file, ['No', 'Nama Toko', 'Unit Usaha', 'Total Transaksi', 'Total Pendapatan']);

            $financeData = Store::orderBy('sort_order')->get();
            $no = 1;
            $grandTotal = 0;
            $grandOrders = 0;

            foreach ($financeData as $store) {
                $query = Order::where('store_id', $store->id)
                    ->whereIn('status', ['paid', 'processing', 'ready', 'completed'])
                    ->whereBetween('paid_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59']);

                $rev = $query->sum('total_price');
                $ord = $query->count();
                $grandTotal += $rev;
                $grandOrders += $ord;

                fputcsv($file, [
                    $no++,
                    $store->name,
                    ucfirst($store->unit),
                    $ord,
                    'Rp ' . number_format($rev, 0, ',', '.')
                ]);
            }

            fputcsv($file, []);
            fputcsv($file, ['', 'TOTAL KESELURUHAN', '', $grandOrders, 'Rp ' . number_format($grandTotal, 0, ',', '.')]);
            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }

    /**
     * Laporan detail toko PDF (print-friendly)
     */
    public function exportStorePdf(Request $request, Store $store)
    {
        $dateFrom = $request->input('from', today()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->input('to', today()->format('Y-m-d'));

        $orders = Order::with('items')
            ->where('store_id', $store->id)
            ->whereIn('status', ['paid', 'processing', 'ready', 'completed'])
            ->whereBetween('paid_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->orderBy('paid_at', 'desc')
            ->get();

        $totalRevenue = $orders->sum('total_price');
        $totalOrders  = $orders->count();

        $itemSales = OrderItem::query()
            ->join('orders', 'order_items.order_id', '=', 'orders.id')
            ->where('orders.store_id', $store->id)
            ->whereIn('orders.status', ['paid', 'processing', 'ready', 'completed'])
            ->whereBetween('orders.paid_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
            ->select(
                'order_items.product_name',
                DB::raw('SUM(order_items.quantity) as total_qty'),
                DB::raw('SUM(order_items.subtotal) as total_revenue')
            )
            ->groupBy('order_items.product_name')
            ->orderBy('total_qty', 'desc')
            ->get();

        return view('admin.export-store-pdf', compact(
            'store', 'orders', 'totalRevenue', 'totalOrders', 'itemSales', 'dateFrom', 'dateTo'
        ));
    }

    /**
     * Laporan detail toko Excel (CSV stream)
     */
    public function exportStoreExcel(Request $request, Store $store)
    {
        $dateFrom = $request->input('from', today()->startOfMonth()->format('Y-m-d'));
        $dateTo   = $request->input('to', today()->format('Y-m-d'));

        $headers = [
            "Content-type"        => "text/csv; charset=UTF-8",
            "Content-Disposition" => "attachment; filename=Laporan_Keuangan_{$store->name}_{$dateFrom}_sd_{$dateTo}.csv",
            "Pragma"              => "no-cache",
            "Cache-Control"       => "must-revalidate, post-check=0, pre-check=0",
            "Expires"             => "0"
        ];

        $callback = function() use ($store, $dateFrom, $dateTo) {
            $file = fopen('php://output', 'w');
            fprintf($file, chr(0xEF).chr(0xBB).chr(0xBF));
            
            fputcsv($file, ["LAPORAN DETAIL KEUANGAN: {$store->name}"]);
            fputcsv($file, ["Periode: {$dateFrom} s/d {$dateTo}"]);
            fputcsv($file, []);
            
            fputcsv($file, ['PRODUK TERJUAL']);
            fputcsv($file, ['No', 'Nama Produk', 'Jumlah Terjual', 'Total Pendapatan']);

            $itemSales = OrderItem::query()
                ->join('orders', 'order_items.order_id', '=', 'orders.id')
                ->where('orders.store_id', $store->id)
                ->whereIn('orders.status', ['paid', 'processing', 'ready', 'completed'])
                ->whereBetween('orders.paid_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->select(
                    'order_items.product_name',
                    DB::raw('SUM(order_items.quantity) as total_qty'),
                    DB::raw('SUM(order_items.subtotal) as total_revenue')
                )
                ->groupBy('order_items.product_name')
                ->orderBy('total_qty', 'desc')
                ->get();

            $no = 1;
            foreach ($itemSales as $item) {
                fputcsv($file, [
                    $no++,
                    $item->product_name,
                    $item->total_qty . ' pcs',
                    'Rp ' . number_format($item->total_revenue, 0, ',', '.')
                ]);
            }

            fputcsv($file, []);
            fputcsv($file, ['RIWAYAT TRANSAKSI']);
            fputcsv($file, ['No', 'Kode Pesanan', 'Tanggal', 'Status', 'Total Belanja']);

            $orders = Order::where('store_id', $store->id)
                ->whereIn('status', ['paid', 'processing', 'ready', 'completed'])
                ->whereBetween('paid_at', [$dateFrom . ' 00:00:00', $dateTo . ' 23:59:59'])
                ->orderBy('paid_at', 'desc')
                ->get();

            $no = 1;
            foreach ($orders as $order) {
                fputcsv($file, [
                    $no++,
                    $order->order_code,
                    $order->paid_at ? \Carbon\Carbon::parse($order->paid_at)->format('d/m/Y H:i') : '-',
                    ucfirst($order->status),
                    'Rp ' . number_format($order->total_price, 0, ',', '.')
                ]);
            }

            fclose($file);
        };

        return response()->stream($callback, 200, $headers);
    }
}
