<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class StoreAuthMiddleware
{
    /**
     * Protect store dashboard routes with PIN-based session auth.
     * Each store has its own session key: store_auth_{store_id}
     */
    public function handle(Request $request, Closure $next): Response
    {
        $store = $request->route('store');

        // Support both Store model binding and raw ID
        $storeId = is_object($store) ? $store->id : $store;

        // Resolve store ID from order if store ID is not in route parameters
        if (!$storeId) {
            $order = $request->route('order');
            if ($order) {
                $storeId = is_object($order) ? $order->store_id : \App\Models\Order::find($order)?->store_id;
            }
        }

        if (!$storeId) {
            // Check if user has any active store session
            $sessions = session()->all();
            foreach ($sessions as $key => $value) {
                if (str_starts_with($key, 'store_auth_') && $value === true) {
                    return $next($request);
                }
            }
            return abort(403, 'Akses ditolak.');
        }

        if (! session()->has("store_auth_{$storeId}")) {
            return redirect("/warung/{$storeId}/login")
                ->with('info', 'Silakan masukkan PIN toko untuk mengakses dasbor.');
        }

        return $next($request);
    }
}
