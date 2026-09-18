<?php

namespace App\Notifications;

use App\Models\Coupons;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CouponUsedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Coupons $coupon,
        public User $user,
        public float $discount
    ) {}

    /**
     * قنوات الإرسال.
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * إيميل.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Coupon Used: ' . $this->coupon->code)
            ->greeting('Hello ' . $notifiable->name . '!')
            ->line("The coupon **{$this->coupon->code}** has been used.")
            ->line("User: {$this->user->name} ({$this->user->email})")
            ->line("Discount applied: **{$this->discount} EGP**")
            ->action('View Coupon', url("/coupons/{$this->coupon->id}"))
            ->line('Thank you for using our platform!');
    }

    /**
     * الإشعار في قاعدة البيانات.
     */
    public function toArray(object $notifiable): array
    {
        return [
            'coupon_id'   => $this->coupon->id,
            'coupon_code' => $this->coupon->code,
            'user_id'     => $this->user->id,
            'user_name'   => $this->user->name,
            'discount'    => $this->discount,
            'used_at'     => now()->toDateTimeString(),
        ];
    }
}