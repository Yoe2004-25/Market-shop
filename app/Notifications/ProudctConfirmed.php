<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Orders;
use App\Models\User; 
use App\Models\Products; 

class ProudctConfirmed extends Notification
{
    use Queueable;

    /** 
      'categery_id', 
         'brand_id',
            'name', 
            'slug',
            'description', 
            'price',
            'discount', 
            'stock',
            'sku',
            'image', 
            'status',
     * Create a new notification instance.
     */


    protected $product ; 
    protected $user ; 

    protected $order ; 
    public function __construct(Products $product , User $user , Orders $order)
    {
        $this->order = $order ; 
        $this->user = $user ; 
        $this->product = $product ; 
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail' , 'sms'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
       return (new MailMessage)
            ->subject('your product is confirmed' . $this->product->id)
            ->greeting('welcome that is confimation about the product is done ' . $notifiable->name)
            ->line('total price ' . $this->product->price.'pound to')
            ->action('show products', url('/products/' . $this->product->id))
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
            'category_id' => $this->product->category_id,
            'brand_id' => $this->product->brand_id,
            'name' => $this->product->name,
            'slug' => $this->product->slug,
            'description' => $this->product->description,
            'price' => $this->product->price,
        ];
    }
}