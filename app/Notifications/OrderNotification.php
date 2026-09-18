<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Orders; 
class OrderNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */

    protected Orders $order ; 
    public function __construct(Orders $order)
    {
        $this->order = $order ; 
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail','sms'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
       return (new MailMessage)
            ->subject('your order is confirmed' . $this->order->id)
            ->greeting('welcome' . $notifiable->name)
            ->line('total order ' . $this->order->grand_total.'pound to')
            ->action('show order', url('/orders/' . $this->order->id))
            ->line('have a nice day');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'order_id' => $this->order->id,
            'grand_total' => $this->order->grand_total,
            'status' => $this->order->status,
        ];
    }
}