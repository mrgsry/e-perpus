@extends('layouts.publik')

@section('title', 'Konfirmasi Peminjaman')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            {{-- <div class="card"> --}}
                <div class="card-header bg-success text-white">
                    <h4 class="mb-0"><i class="fas fa-check-circle"></i> Peminjaman Berhasil Diajukan</h4>
                </div>
                <div class="card-body">
                    <div class="alert alert-success">
                        <h5>Selamat!</h5>
                        <p>Permintaan peminjaman buku Anda telah berhasil diajukan. Simpan Booking ID di bawah ini untuk cek status peminjaman.</p>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <h6>Detail Peminjaman:</h6>
                            <table class="table table-sm">
                                <tr>
                                    <td><strong>Booking ID</strong></td>
                                    <td>:</td>
                                    <td><code>{{ $peminjaman->booking_id }}</code></td>
                                </tr>
                                <tr>
                                    <td><strong>Mahasiswa</strong></td>
                                    <td>:</td>
                                    <td>{{ $peminjaman->mahasiswa->nama }} ({{ $peminjaman->mahasiswa->nim }})</td>
                                </tr>
                                <tr>
                                    <td><strong>Buku</strong></td>
                                    <td>:</td>
                                    <td>{{ $peminjaman->buku->nama_buku }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Tanggal Pinjam</strong></td>
                                    <td>:</td>
                                    <td>{{ $peminjaman->tanggal_pinjam ? \Carbon\Carbon::parse($peminjaman->tanggal_pinjam)->format('d/m/Y') : 'Belum disetujui' }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Status</strong></td>
                                    <td>:</td>
                                    <td>
                                        @if($peminjaman->status == 'pending')
                                            <span class="badge badge-warning">Menunggu Approval</span>
                                        @elseif($peminjaman->status == 'dipinjam')
                                            <span class="badge badge-success">Dipinjam</span>
                                        @else
                                            <span class="badge badge-secondary">{{ $peminjaman->status }}</span>
                                        @endif
                                    </td>
                                </tr>
                            </table>
                        </div>
                        <div class="col-md-6">
                            @if($peminjaman->qr_code_path)
                                <h6>QR Code Peminjaman:</h6>
                                <div class="text-center">
                                    <img src="{{ asset('storage/' . $peminjaman->qr_code_path) }}" alt="QR Code" class="img-fluid" style="max-width: 200px;">
                                    <br>
                                    <a href="{{ asset('storage/' . $peminjaman->qr_code_path) }}" download class="btn btn-sm btn-primary mt-2">
                                        <i class="fas fa-download"></i> Download QR Code
                                    </a>
                                </div>
                            @else
                                <div class="alert alert-info">
                                    <i class="fas fa-info-circle"></i> QR Code akan tersedia setelah peminjaman disetujui admin.
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="mt-4">
                        <a href="{{ route('publik.cek-status') }}" class="btn btn-primary">
                            <i class="fas fa-search"></i> Cek Status Peminjaman
                        </a>
                        <a href="/" class="btn btn-secondary">
                            <i class="fas fa-home"></i> Kembali ke Katalog
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection