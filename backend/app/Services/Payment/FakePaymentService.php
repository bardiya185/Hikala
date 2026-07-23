<?php

namespace App\Services\Payment;

use App\Models\Order;
use Illuminate\Support\Str;

/**
 * 💳 Fake Payment Gateway (for testing)
 * 
 * In production, replace with real gateway (Zarinpal, IDPay, etc.)
 */
class FakePaymentService
{
    /**
     * 🚀 Initiate payment (returns fake payment URL)
     */
    public function initiate(Order $order): array
    {
        // در واقعیت اینجا به درگاه وصل می‌شیم
        $transactionId = 'TXN-' . strtoupper(Str::random(12));
        
        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'payment_url' => url("/api/payment/fake/{$order->id}/{$transactionId}"),
            'message' => 'Redirect user to payment_url',
        ];
    }

    /**
     * ✅ Verify payment (fake - always successful)
     */
    public function verify(Order $order, string $transactionId): array
    {
        // در واقعیت اینجا از درگاه verify می‌گیریم
        return [
            'success' => true,
            'transaction_id' => $transactionId,
            'paid_at' => now(),
            'amount' => $order->total_amount,
        ];
    }
}