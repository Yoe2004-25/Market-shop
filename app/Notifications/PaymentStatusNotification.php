<?php

namespace App\Notifications;

use App\Models\Payments;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PaymentStatusNotification extends Notification
{
    use Queueable;

    public function __construct(
        public Payments $payment,
        public string $status   // success | failed | pending
    ) {}

    /**
     * قنوات الإرسال: mail + database.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * إيميل الإشعار.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line("Your payment for order #{$this->payment->order_id} has been processed.")
            ->line("Amount: " . number_format($this->payment->amount, 2) . " EGP")
            ->line("Method: " . strtoupper($this->payment->payment_method))
            ->line("Transaction: {$this->payment->transaction_id}");

        if ($this->status === 'success') {
            $mail->subject('✅ Payment Successful')
                 ->line('Your payment was completed successfully.');
        } elseif ($this->status === 'failed') {
            $mail->subject('❌ Payment Failed')
                 ->line('Unfortunately, your payment could not be processed.')
                 ->line('Please try again or contact support.');
        } else {
            $mail->subject('⏳ Payment Pending')
                 ->line('Your payment is pending and will be processed soon.');
        }

        return $mail->action('View Payment', url("/payments/{$this->payment->id}"))
                    ->line('Thank you for using our service!');
    }

    /**
     * الإشعار في قاعدة البيانات.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'payment_id'     => $this->payment->id,
            'order_id'       => $this->payment->order_id,
            'amount'         => $this->payment->amount,
            'payment_method' => $this->payment->payment_method,
            'status'         => $this->status,
            'transaction_id' => $this->payment->transaction_id,
            'message'        => $this->getStatusMessage(),
        ];
    }

    private function getStatusMessage(): string
    {
        return match ($this->status) {
            'success' => 'Your payment was completed successfully.',
            'failed'  => 'Your payment failed. Please try again.',
            default   => 'Your payment is pending.',
        };
    }
}