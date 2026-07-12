<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    /**
     * Halaman pembayaran
     */
    public function show(string $orderCode)
    {
        $order = Order::with(['store', 'items', 'payment'])
            ->where('order_code', $orderCode)
            ->firstOrFail();

        // Jika sudah bayar, langsung ke halaman status
        if (in_array($order->payment_status, ['paid', 'failed'])) {
            return redirect("/pesanan/{$orderCode}");
        }

        // Mode pembayaran default
        $paymentMode = config('app.payment_mode', 'simulation');

        // Create or get payment record
        $payment = Payment::firstOrCreate(
            ['order_id' => $order->id],
            [
                'amount' => $order->total_price,
                'status' => 'pending',
            ]
        );
        $order->setRelation('payment', $payment);

        if (!$payment->snap_token) {
            $serverKey = config('services.midtrans.server_key');
            if ($serverKey) {
                $isProduction = config('services.midtrans.is_production', false);
                $endpoint = $isProduction 
                    ? 'https://app.real.midtrans.com/snap/v1/transactions' 
                    : 'https://app.sandbox.midtrans.com/snap/v1/transactions';

                $midtransOrderId = $order->order_code . '-' . time();

                $payload = [
                    'transaction_details' => [
                        'order_id'     => $midtransOrderId,
                        'gross_amount' => (int) $order->total_price,
                    ],
                    'customer_details' => [
                        'first_name' => $order->customer_name,
                        'phone'      => $order->customer_phone,
                    ],
                    'item_details' => $order->items->map(function ($item) {
                        return [
                            'id'       => $item->product_id,
                            'price'    => (int) $item->price,
                            'quantity' => (int) $item->quantity,
                            'name'     => substr($item->product_name, 0, 50),
                        ];
                    })->toArray(),
                ];

                try {
                    $response = \Illuminate\Support\Facades\Http::withHeaders([
                        'Accept'        => 'application/json',
                        'Content-Type'  => 'application/json',
                        'Authorization' => 'Basic ' . base64_encode($serverKey . ':'),
                    ])->post($endpoint, $payload);

                    if ($response->successful()) {
                        $resData = $response->json();
                        $payment->update([
                            'snap_token'        => $resData['token'],
                            'midtrans_order_id' => $midtransOrderId,
                        ]);
                        $paymentMode = 'midtrans';
                    }
                } catch (\Exception $e) {
                    // Fallback to simulation
                }
            }
        } else {
            $paymentMode = 'midtrans';
        }

        return view('student.payment', compact('order', 'paymentMode'));
    }

    /**
     * Simulasi pembayaran berhasil
     */
    public function simulate(string $orderCode)
    {
        $order = Order::with('payment')
            ->where('order_code', $orderCode)
            ->where('payment_status', 'pending')
            ->firstOrFail();

        // Update order
        $order->update([
            'status'         => 'paid',
            'payment_status' => 'paid',
            'paid_at'        => now(),
        ]);

        // Update payment record
        if ($order->payment) {
            $order->payment->update([
                'status'       => 'paid',
                'payment_type' => 'simulation',
                'paid_at'      => now(),
            ]);
        }

        // Kurangi stok produk setelah bayar
        foreach ($order->items as $item) {
            $product = $item->product;
            if ($product) {
                $product->recordStock(-$item->quantity, 'out', $order->id, 'Terjual via pesanan ' . $order->queue_code);
                $product->decrement('stock', $item->quantity);
            }
        }

        return response()->json([
            'success'  => true,
            'redirect' => "/pesanan/{$orderCode}",
            'message'  => "Pembayaran berhasil! Nomor antrian Anda: {$order->queue_code}",
        ]);
    }

    /**
     * Callback Midtrans (untuk integrasi nyata nanti)
     */
    public function midtransCallback(Request $request)
    {
        $payload = $request->all();

        // Verifikasi signature key Midtrans
        $serverKey    = config('services.midtrans.server_key');
        $orderId      = $payload['order_id'] ?? '';
        $statusCode   = $payload['status_code'] ?? '';
        $grossAmount  = $payload['gross_amount'] ?? '';
        $signatureKey = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        if ($signatureKey !== ($payload['signature_key'] ?? '')) {
            return response()->json(['message' => 'Invalid signature'], 403);
        }

        $payment = Payment::where('midtrans_order_id', $orderId)->first();
        if (! $payment) {
            return response()->json(['message' => 'Order not found'], 404);
        }

        $order         = $payment->order;
        $transactionStatus = $payload['transaction_status'] ?? '';
        $fraudStatus   = $payload['fraud_status'] ?? '';

        if ($transactionStatus === 'capture' || $transactionStatus === 'settlement') {
            if ($fraudStatus === 'accept' || empty($fraudStatus)) {
                $payment->update([
                    'status'       => 'paid',
                    'payment_type' => $payload['payment_type'] ?? null,
                    'raw_response' => json_encode($payload),
                    'paid_at'      => now(),
                ]);
                $order->update([
                    'status'         => 'paid',
                    'payment_status' => 'paid',
                    'paid_at'        => now(),
                ]);
                // Kurangi stok
                foreach ($order->items as $item) {
                    $product = $item->product;
                    if ($product) {
                        $product->recordStock(-$item->quantity, 'out', $order->id);
                        $product->decrement('stock', $item->quantity);
                    }
                }
            }
        } elseif ($transactionStatus === 'pending') {
            $payment->update(['status' => 'pending', 'raw_response' => json_encode($payload)]);
        } elseif (in_array($transactionStatus, ['deny', 'expire', 'cancel', 'failure'])) {
            $payStatus = $transactionStatus === 'expire' ? 'expired' : 'failed';
            $payment->update(['status' => $payStatus, 'raw_response' => json_encode($payload)]);
            $order->update(['payment_status' => $payStatus]);
        }

        return response()->json(['message' => 'OK']);
    }
}
