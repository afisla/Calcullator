<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Store;
use Illuminate\Http\Request;

class AjaxController extends Controller
{
    /**
     * Status pesanan (polling dari halaman siswa)
     */
    public function orderStatus(string $orderCode)
    {
        $order = Order::where('order_code', $orderCode)->firstOrFail();

        return response()->json([
            'status'           => $order->status,
            'payment_status'   => $order->payment_status,
            'status_label'     => $order->status_label,
            'status_icon'      => $order->status_icon,
            'rejection_reason' => $order->rejection_reason,
            'customer_name'    => $order->customer_name,
            'store_name'       => $order->store->name ?? '',
            'queue_code'       => $order->queue_code,
        ]);
    }

    /**
     * Pesanan aktif warung (polling dari dashboard penjaga)
     */
    public function storePendingOrders(Store $store)
    {
        $orders = Order::with('items')
            ->where('store_id', $store->id)
            ->whereIn('status', ['paid', 'processing', 'ready'])
            ->orderBy('paid_at')
            ->get()
            ->map(fn ($order) => [
                'id'              => $order->id,
                'order_code'      => $order->order_code,
                'queue_code'      => $order->queue_code,
                'customer_name'   => $order->customer_name,
                'customer_class'  => $order->customer_class,
                'customer_phone'  => $order->customer_phone,
                'status'          => $order->status,
                'status_label'    => $order->status_label,
                'status_icon'     => $order->status_icon,
                'total_price'     => $order->total_price,
                'formatted_total' => $order->formatted_total,
                'paid_at'         => $order->paid_at?->diffForHumans() ?? '-',
            ]);

        return response()->json(['orders' => $orders, 'count' => $orders->count()]);
    }

    /**
     * Pesanan aktif untuk dashboard siswa (auto-refresh)
     */
    public function activeOrders(Request $request)
    {
        $search = $request->input('search');

        $query = Order::with('store')
            ->whereIn('status', ['pending', 'paid', 'processing', 'ready']);

        if ($search) {
            $query->where('customer_name', 'like', '%' . $search . '%');
        }

        $orders = $query->orderBy('created_at', 'desc')->get();

        // Build HTML untuk inject ke dashboard
        $html = '';
        foreach ($orders as $order) {
            $badgeMap = [
                'pending'    => 'badge-pending',
                'paid'       => 'badge-paid',
                'processing' => 'badge-processing',
                'ready'      => 'badge-ready',
                'completed'  => 'badge-completed'
            ];
            $badgeClass = $badgeMap[$order->status] ?? 'badge-pending';

            $html .= '<div class="order-status-card animate-in" style="background:white;border-radius:16px;padding:14px;margin-bottom:10px;box-shadow:0 2px 10px rgba(231,100,142,0.08);border:1px solid rgba(231,100,142,0.08);">';
            
            // Header Info
            $html .= '<div style="display:flex;align-items:flex-start;justify-content:space-between;margin-bottom:10px;">';
            $html .= '<div>';
            $html .= '<div style="font-size:22px;font-family:\'Fredoka One\',cursive;color:#BA797D;line-height:1;">' . ($order->queue_code ?? ('No.'.$order->queue_number)) . '</div>';
            $html .= '<div style="font-size:13px;font-weight:700;color:#96A480;">' . e($order->customer_name) . '</div>';
            $html .= '<div style="font-size:11px;color:#94a3b8;font-weight:500;">' . e($order->store->name ?? '') . ' · Kelas ' . e($order->customer_class ?: '-') . '</div>';
            $html .= '</div>';
            $html .= '<div>';
            $html .= '<span class="badge ' . $badgeClass . '">' . $order->status_icon . ' ' . $order->status_label . '</span>';
            $html .= '</div>';
            $html .= '</div>';

            // Progress Tracker
            $html .= '<div style="display:flex;align-items:center;gap:0;margin-bottom:10px;">';
            $steps = [
                ['icon' => '💳', 'label' => 'Bayar'],
                ['icon' => '🍳', 'label' => 'Proses'],
                ['icon' => '🎉', 'label' => 'Siap'],
                ['icon' => '✅', 'label' => 'Selesai'],
            ];
            $statusOrder = ['pending' => 0, 'paid' => 1, 'processing' => 1, 'ready' => 2, 'completed' => 3];
            $currentStep = $statusOrder[$order->status] ?? 0;

            foreach ($steps as $si => $step) {
                if ($si > 0) {
                    $lineBg = $si <= $currentStep ? '#A9D770' : '#DAD6D3';
                    $html .= '<div style="flex:1;height:2px;margin-top:-14px;background:' . $lineBg . ';"></div>';
                }
                $circleBg = $si <= $currentStep ? ($si < $currentStep ? '#A9D770' : '#F9E6A7') : 'white';
                $circleBorder = $si <= $currentStep ? ($si < $currentStep ? '#A9D770' : '#F9E6A7') : '#DAD6D3';
                $circleShadow = $si === $currentStep ? '0 0 0 4px rgba(249,230,167,0.3)' : 'none';
                $labelColor = $si <= $currentStep ? '#96A480' : '#94a3b8';

                $html .= '<div style="display:flex;flex-direction:column;align-items:center;flex-shrink:0;">';
                $html .= '<div style="width:28px;height:28px;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;background:' . $circleBg . ';border:2px solid ' . $circleBorder . ';z-index:1;box-shadow:' . $circleShadow . ';">' . $step['icon'] . '</div>';
                $html .= '<div style="font-size:9px;font-weight:700;color:' . $labelColor . ';margin-top:3px;white-space:nowrap;">' . $step['label'] . '</div>';
                $html .= '</div>';
            }
            $html .= '</div>';

            // Footer
            $html .= '<div style="display:flex;justify-content:space-between;align-items:center;padding-top:8px;border-top:1px solid rgba(231,100,142,0.08);">';
            $html .= '<span style="font-size:13px;font-weight:800;color:#96A480;">' . $order->formatted_total . '</span>';
            $html .= '<a href="/pesanan/' . $order->order_code . '" class="btn btn-sm btn-outline" style="padding:5px 12px;font-size:12px;border-radius:999px;">Detail →</a>';
            $html .= '</div>';
            
            $html .= '</div>';
        }

        if ($orders->isEmpty()) {
            $html = '<div style="text-align:center;padding:32px 16px;background:white;border-radius:16px;border:1px solid rgba(231,100,142,0.08);">'
                . '<span style="font-size:48px;display:block;margin-bottom:12px;">📋</span>'
                . '<div style="font-weight:800;font-size:15px;color:#2D1B3D;margin-bottom:6px;">Belum ada pesanan aktif</div>'
                . '<div style="font-size:13px;color:#94a3b8;font-weight:500;">Pesan sekarang di Koperasi atau Kantin!</div></div>';
        }

        return response()->json(['html' => $html, 'count' => $orders->count()]);
    }

    /**
     * Stats real-time untuk dashboard admin
     */
    public function adminDashboardStats()
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

        $storeTableHtml = '';
        foreach($revenuePerStore as $store) {
            $statusStr = $store->is_open 
                ? '<span style="font-size:0.75rem;color:#34d399;font-weight:600;">● Buka</span>' 
                : '<span style="font-size:0.75rem;color:#475569;font-weight:600;">● Tutup</span>';
            $storeTableHtml .= '<tr>
                <td>
                    <div class="td-store">
                        <span class="td-emoji">' . $store->icon_emoji . '</span>
                        <span class="td-name">' . e($store->name) . '</span>
                    </div>
                </td>
                <td>
                    <span class="td-unit-badge ' . $store->unit . '">' . ucfirst($store->unit) . '</span>
                </td>
                <td>' . $statusStr . '</td>
                <td class="td-revenue">Rp ' . number_format($store->orders_sum_total_price ?? 0, 0, ',', '.') . '</td>
                <td>
                    <a href="/admin/keuangan/' . $store->id . '" class="view-finance-link">Lihat Detail →</a>
                </td>
            </tr>';
        }

        return response()->json([
            'totalStores'      => $totalStores,
            'openStores'       => $openStores,
            'totalOrdersToday' => $totalOrdersToday,
            'pendingOrders'    => $pendingOrders,
            'revenueToday'     => 'Rp ' . number_format($revenueToday, 0, ',', '.'),
            'revenueTotal'     => 'Rp ' . number_format($revenueTotal, 0, ',', '.'),
            'storeTableHtml'   => $storeTableHtml,
        ]);
    }
}
