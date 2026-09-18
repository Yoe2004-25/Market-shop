<?php

namespace App\Services;

use App\Models\Orders;
use App\Models\Payments;
use App\Notifications\PaymentStatusNotification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PaymentService
{
    public function createPayment(Orders $order, string $method): Payments
    {
        return DB::transaction(function () use ($order, $method) {
            $payment = Payments::create([
                'order_id'=> $order->id,
                'payment_method'=> $method,
                'transaction_id'=> $this->generateTransactionId(),
                'amount'=> $order->grand_total,
                'status'=>'pending',
            ]);
            $this->notifyCustomer($payment, 'pending');

            if ($method === 'visa') {
                $this->processVisaPayment($payment);
            }

            return $payment;
        });
    }

    public function processVisaPayment(Payments $payment): Payments
    {
        $success = rand(1, 10) <= 9;

        if ($success) {
            $payment->update(['status' => 'completed']);
            $this->markOrderPaid($payment->order);

            $this->notifyCustomer($payment, 'success');
        } else {
            $payment->update(['status' => 'failed']);
            $this->notifyCustomer($payment, 'failed');
        }

        return $payment->fresh();
    }

    public function confirmCashPayment(Payments $payment): Payments
    {
        if ($payment->payment_method !== 'cash') {
            throw new \Exception('This payment is not cash.');
        }

        $payment->update(['status' => 'completed']);
        $this->markOrderPaid($payment->order);

        // ✅ إشعار: success
        $this->notifyCustomer($payment, 'success');

        return $payment->fresh();
    }

    public function refund(Payments $payment): Payments
    {
        $payment->update(['status' => 'refunded']);
        return $payment->fresh();
    }

    private function markOrderPaid(Orders $order): void
    {
        $order->update([
            'payment_status'=>'success',
            'status'=>'success',
        ]);
    }

    private function generateTransactionId(): string
    {
        return 'TXN-' . strtoupper(Str::random(12));
    }

    
    private function notifyCustomer(Payments $payment, string $status): void
    {
        $user = $payment->order?->user;

        if ($user) {
            $user->notify(new PaymentStatusNotification($payment, $status));
        }
    }
}