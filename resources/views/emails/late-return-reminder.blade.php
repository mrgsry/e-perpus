<x-mail::message>
# Peringatan Keterlambatan Pengembalian Buku

Halo {{ $peminjaman->mahasiswa->nama ?? 'Mahasiswa' }},

Kami mengingatkan bahwa peminjaman buku berikut sudah melewati batas waktu pengembalian:

<x-mail::panel>
**Booking ID:** {{ $peminjaman->booking_id }}  
**Judul Buku:** {{ $peminjaman->buku->nama_buku ?? '-' }}  
**Tanggal Pinjam:** {{ $peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') : '-' }}  
**Batas Pengembalian:** {{ $peminjaman->tanggal_kembali_rencana ? \Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana)->format('d/m/Y') : '-' }}  
**Keterlambatan:** {{ $peminjaman->tanggal_kembali_rencana ? abs((int) now()->diffInDays(\Carbon\Carbon::parse($peminjaman->tanggal_kembali_rencana), false)) : 0 }} hari
</x-mail::panel>

Mohon segera mengembalikan buku ke perpustakaan untuk menghindari denda keterlambatan yang lebih besar.

Terima kasih,<br>
{{ config('app.name', 'SIPUSAKA') }}
</x-mail::message>