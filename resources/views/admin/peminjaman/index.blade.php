@extends('layouts.admin')
@section('title', 'Peminjaman')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Manajemen Peminjaman</h1>
    </div>
</div>
<div class="content">
    <div class="container-fluid">

        {{-- Tab Navigation --}}
        <ul class="nav nav-tabs mb-3" id="tabPeminjaman">
            <li class="nav-item">
                <a class="nav-link active" data-bs-toggle="tab" href="#tabPending">
                    Pending
                    @if($pending->count() > 0)
                        <span class="badge bg-warning text-dark ms-1">{{ $pending->count() }}</span>
                    @endif
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-bs-toggle="tab" href="#tabAktif">
                    Dipinjam
                    <span class="badge bg-primary ms-1">{{ $aktif->count() }}</span>
                </a>
            </li>
        </ul>

        <div class="tab-content">

            {{-- Tab Pending --}}
            <div class="tab-pane fade show active" id="tabPending">
                <div class="card">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h3 class="card-title">Request Peminjaman Pending</h3>
                        <div>
                            <a href="{{ route('admin.peminjaman.export-pdf') }}" class="btn btn-danger btn-sm">
                                <i class="fas fa-file-pdf"></i> PDF
                            </a>
                            <a href="{{ route('admin.peminjaman.export-excel') }}" class="btn btn-success btn-sm">
                                <i class="fas fa-file-excel"></i> Excel
                            </a>
                        </div>
                    </div>
                    <div class="card-body">
                        <table id="tablePending" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Booking ID</th>
                                    <th>Mahasiswa</th>
                                    <th>NIM</th>
                                    <th>Buku</th>
                                    <th>Tgl Request</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($pending as $i => $p)
                                <tr>
                                    <td>{{ $i+1 }}</td>
                                    <td><code>{{ $p->booking_id }}</code></td>
                                    <td>{{ $p->mahasiswa->nama ?? '-' }}</td>
                                    <td>{{ $p->mahasiswa->nim ?? '-' }}</td>
                                    <td>{{ $p->buku->nama_buku ?? '-' }}</td>
                                    <td>{{ $p->created_at->format('d/m/Y H:i') }}</td>
                                    <td>
                                        <button onclick="approvePinjam({{ $p->id }}, '{{ $p->booking_id }}', '{{ $p->mahasiswa->nama ?? '' }}', '{{ addslashes($p->buku->nama_buku ?? '') }}')"
                                                class="btn btn-success btn-xs">
                                            <i class="fas fa-check"></i> Approve
                                        </button>
                                        <button onclick="tolakPinjam({{ $p->id }})"
                                                class="btn btn-danger btn-xs">
                                            <i class="fas fa-times"></i> Tolak
                                        </button>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="7" class="text-center text-muted py-4">
                                        <i class="fas fa-inbox fa-2x mb-2 d-block"></i>
                                        Tidak ada request pending
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Tab Aktif --}}
            <div class="tab-pane fade" id="tabAktif">
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Buku Sedang Dipinjam</h3>
                    </div>
                    <div class="card-body">
                        <table id="tableAktif" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Booking ID</th>
                                    <th>Mahasiswa</th>
                                    <th>Buku</th>
                                    <th>Tgl Pinjam</th>
                                    <th>Batas Kembali</th>
                                    <th>Sisa Hari</th>
                                    <th>QR</th>
                                    <th>Aksi</th> {{-- New column for actions --}}
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($aktif as $i => $p)
                                @php
                                    $batas = \Carbon\Carbon::parse($p->tanggal_kembali_rencana);
                                    $sisaHari = (int) now()->diffInDays($batas, false);
                                @endphp
                                <tr class="{{ $sisaHari < 0 ? 'table-danger' : ($sisaHari <= 2 ? 'table-warning' : '') }}">
                                    <td>{{ $i+1 }}</td>
                                    <td><code>{{ $p->booking_id }}</code></td>
                                    <td>{{ $p->mahasiswa->nama ?? '-' }}</td>
                                    <td>{{ $p->buku->nama_buku ?? '-' }}</td>
                                    <td>{{ $p->tanggal_pinjam ? \Carbon\Carbon::parse($p->tanggal_pinjam)->format('d/m/Y') : '-' }}</td>
                                    <td>{{ $batas->format('d/m/Y') }}</td>
                                    <td>
                                        @if($sisaHari < 0)
                                            <span class="badge bg-danger">Terlambat {{ abs($sisaHari) }} hari</span>
                                        @elseif($sisaHari == 0)
                                            <span class="badge bg-warning text-dark">Hari ini</span>
                                        @else
                                            <span class="badge bg-success">{{ $sisaHari }} hari lagi</span>
                                        @endif
                                    </td>
                                    <td>
                                      {{-- Di tabAktif, bagian kolom QR --}}
@if($p->qr_code_path)
    {{-- Pastikan asset path benar --}}
    <button onclick="lihatQR('{{ asset('storage/'.$p->qr_code_path) }}', '{{ $p->booking_id }}')" class="btn btn-info btn-xs">
        <i class="fas fa-qrcode"></i> QR
    </button>
@else
    <span class="text-muted">-</span>
@endif
                                    </td>
                                    <td>
                                        @if($sisaHari < 0) {{-- Only show email button if late --}}
                                            <button onclick="sendReminderEmail({{ $p->id }})" class="btn btn-warning btn-xs">
                                                <i class="fas fa-envelope"></i> Email
                                            </button>
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="9" class="text-center text-muted py-4"> {{-- Updated colspan --}}
                                        <i class="fas fa-book fa-2x mb-2 d-block"></i>
                                        Tidak ada buku yang sedang dipinjam
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Modal Approve --}}
<div class="modal fade" id="modalApprove" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header bg-success text-white">
                <h5 class="modal-title"><i class="fas fa-check-circle me-2"></i>Approve Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="approve_id">
                <div class="table-responsive">
                    <table class="table table-borderless">
                        <tr>
                            <td width="40%"><strong>Booking ID</strong></td>
                            <td><code id="approve_booking_id"></code></td>
                        </tr>
                        <tr>
                            <td><strong>Mahasiswa</strong></td>
                            <td id="approve_mahasiswa"></td>
                        </tr>
                        <tr>
                            <td><strong>Buku</strong></td>
                            <td id="approve_buku"></td>
                        </tr>
                        <tr>
                            <td><strong>Tgl Pinjam</strong></td>
                            <td>{{ now()->format('d/m/Y') }} (hari ini)</td>
                        </tr>
                        <tr>
                            <td><strong>Batas Kembali</strong></td>
                            <td>{{ now()->addDays(7)->format('d/m/Y') }} (+7 hari)</td>
                        </tr>
                    </table>
                </div>
                <div class="alert alert-info">
                    <i class="fas fa-info-circle me-2"></i>
                    Setelah diapprove, QR Code akan otomatis dibuat untuk mahasiswa.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" onclick="konfirmasiApprove()">
                    <i class="fas fa-check me-1"></i>Ya, Approve
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tolak --}}
<div class="modal fade" id="modalTolak" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Tolak Peminjaman</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-times-circle fa-3x text-danger mb-3"></i>
                <p>Yakin ingin menolak peminjaman ini?</p>
                <input type="hidden" id="tolak_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="konfirmasiTolak()">Tolak</button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Lihat QR --}}
<div class="modal fade" id="modalQR" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-qrcode me-2"></i>QR Code Peminjaman</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <p class="text-muted mb-2" id="qr_booking_label"></p>
                <img id="qr_image" src="" alt="QR Code"
                     style="width:220px;height:220px;border:1px solid #e2e8f0;border-radius:8px;padding:8px">
            </div>
            <div class="modal-footer justify-content-center">
                <a id="qr_download" href="" download class="btn btn-primary">
                    <i class="fas fa-download me-1"></i>Download QR
                </a>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$(document).ready(function() {
    // Inisialisasi DataTable untuk tab aktif pertama
    if ($('#tabPending').hasClass('active')) {
        $('#tablePending').DataTable();
    }

    // Inisialisasi DataTable saat tab diklik
    $('a[data-toggle="tab"]').on('shown.bs.tab', function (e) {
        var target = $(e.target).attr("href");
        if (target === '#tabPending' && !$.fn.DataTable.isDataTable('#tablePending')) {
            $('#tablePending').DataTable();
        } else if (target === '#tabAktif' && !$.fn.DataTable.isDataTable('#tableAktif')) {
            $('#tableAktif').DataTable();
        }
    });

});

function sendReminderEmail(id) {
    if (confirm('Apakah Anda yakin ingin mengirim email peringatan keterlambatan kepada mahasiswa ini?')) {
        $.ajax({
            url: '/admin/peminjaman/' + id + '/send-reminder-email',
            method: 'POST',
            data: { _token: '{{ csrf_token() }}' },
            success: function(res) {
                if (res.success) {
                    alert(res.message);
                } else {
                    alert('Gagal mengirim email: ' + res.message);
                }
            },
            error: function(xhr) {
                let message = xhr.responseJSON && xhr.responseJSON.message
                    ? xhr.responseJSON.message
                    : 'Terjadi kesalahan saat mengirim email.';
                alert(message);
            }
        });
    }
}

function approvePinjam(id, bookingId, mahasiswa, buku) {
    $('#approve_id').val(id);
    $('#approve_booking_id').text(bookingId);
    $('#approve_mahasiswa').text(mahasiswa);
    $('#approve_buku').text(buku);
    $('#modalApprove').modal('show');
}

function konfirmasiApprove() {
    let id = $('#approve_id').val();
    $.ajax({
        url: '/admin/peminjaman/' + id + '/approve',
        method: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(res) {
            if (res.success) {
                $('#modalApprove').modal('hide');
                alert(res.message);
                location.reload();
            }
        },
        error: function(xhr) {
            alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
        }
    });
}

function tolakPinjam(id) {
    $('#tolak_id').val(id);
    $('#modalTolak').modal('show');
}

function konfirmasiTolak() {
    let id = $('#tolak_id').val();
    $.ajax({
        url: '/admin/peminjaman/' + id + '/tolak',
        method: 'POST',
        data: { _token: '{{ csrf_token() }}' },
        success: function(res) {
            if (res.success) location.reload();
        }
    });
}

function lihatQR(qrUrl, bookingId) {
    $('#qr_image').attr('src', qrUrl);
    $('#qr_download').attr('href', qrUrl);
    $('#qr_booking_label').text('Booking ID: ' + bookingId);
    $('#modalQR').modal('show');
}
</script>
@endpush