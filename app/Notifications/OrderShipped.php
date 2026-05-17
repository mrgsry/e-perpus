<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class OrderShipped extends Notification
{
    use Queueable;

    public function __construct(public array $orderData) {}

    // Channel yang digunakan
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    // Isi email
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pesanan Anda Telah Dikirim! 🚀')
            ->greeting('Halo, ' . $notifiable->name . '!')
            ->line('Pesanan #' . $this->orderData['order_id'] . ' sudah dalam perjalanan.')
            ->action('Lihat Detail Pesanan', url('/orders/' . $this->orderData['order_id']))
            ->line('Terima kasih telah berbelanja!')
            ->salutation('Salam, Tim ' . config('app.name'));
    }
}