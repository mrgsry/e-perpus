<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class PeminjamanApproved extends Notification
{
    use Queueable;

    public $pinjaman;

    /**
     * Create a new notification instance.
     */
    public function __construct($pinjaman)
    {
        $this->pinjaman = $pinjaman;
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
        $qrUrl = asset('storage/' . $this->pinjaman->qr_code_path);
        $bookingId = $this->pinjaman->booking_id;
        $mahasiswa = $this->pinjaman->mahasiswa;
        $buku = $this->pinjaman->buku;

        return (new MailMessage)
            ->subject('Peminjaman Buku Anda Telah Disetujui!')
            ->greeting('Halo, ' . $mahasiswa->nama . '!')
            ->line('Selamat! Peminjaman buku "' . $buku->nama_buku . '" Anda telah disetujui oleh admin.')
            ->line('Detail Peminjaman:')
            ->line('- Booking ID: **' . $bookingId . '**')
            ->line('- Tanggal Pinjam: ' . $this->pinjaman->tanggal_pinjam->format('d/m/Y'))
            ->line('- Batas Kembali: ' . $this->pinjaman->tanggal_kembali_rencana->format('d/m/Y'))
            ->line('Silakan tunjukkan QR Code berikut saat mengambil buku di perpustakaan:')
            ->line('QR Code tersedia di halaman "Cek Status Peminjaman" setelah login.')
            ->action('Cek Status Peminjaman', url('/cek-status/'))
            ->line('Terima kasih telah meminjam buku di SiPusaka!');
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