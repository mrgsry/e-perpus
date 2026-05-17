<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MahasiswaApproved extends Notification
{
    use Queueable;

    public $mahasiswa;
    public $referralToken;

    /**
     * Create a new notification instance.
     */
    public function __construct($mahasiswa, $referralToken)
    {
        $this->mahasiswa = $mahasiswa;
        $this->referralToken = $referralToken;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pendaftaran Anda Telah Disetujui!')
            ->greeting('Halo, ' . $this->mahasiswa->nama . '!')
            ->line('Selamat! Pendaftaran Anda sebagai mahasiswa di ' . config('app.name') . ' telah disetujui.')
            ->line('Anda sekarang dapat login menggunakan NIM Anda: ' . $this->mahasiswa->nim)
            ->line('Token Referral Anda adalah: **' . $this->referralToken . '**')
            ->line('Bagikan token ini kepada teman-teman Anda untuk mendapatkan keuntungan menarik.')
            ->action('Login ke Akun Anda', url('/login')) // Anda mungkin perlu menyesuaikan URL login
            ->line('Terima kasih telah bergabung dengan kami!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
