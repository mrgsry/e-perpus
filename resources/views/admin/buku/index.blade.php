@extends('layouts.admin')
@section('title', 'Manajemen Buku')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Manajemen Buku</h1>
    </div>
</div>
<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h3 class="card-title">Daftar Buku</h3>
                <div>
                    <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#modalTambah">
                        <i class="fas fa-plus me-1"></i> Tambah Buku
                    </button>
                    <a href="{{ route('admin.buku.export-pdf') }}" class="btn btn-danger btn-sm">
                        <i class="fas fa-file-pdf me-1"></i> PDF
                    </a>
                    <a href="{{ route('admin.buku.export-excel') }}" class="btn btn-success btn-sm">
                        <i class="fas fa-file-excel me-1"></i> Excel
                    </a>
                </div>
            </div>
            <div class="card-body">
                <table id="tableBuku" class="table table-bordered table-striped">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Sampul</th>
                            <th>Nama Buku</th>
                            <th>Penerbit</th>
                            <th>Jenis</th>
                            <th>Stok Total</th>
                            <th>Stok Tersedia</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($bukus as $i => $buku)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>
                                @if($buku->sampul_buku)
                                    <img src="{{ asset('storage/'.$buku->sampul_buku) }}"
                                         width="50" height="60" style="object-fit:cover;border-radius:4px">
                                @else
                                    <div style="width:50px;height:60px;background:#e2e8f0;border-radius:4px;display:flex;align-items:center;justify-content:center">
                                        <i class="fas fa-book text-muted"></i>
                                    </div>
                                @endif
                            </td>
                            <td>{{ $buku->nama_buku }}</td>
                            <td>{{ $buku->penerbit }}</td>
                            <td><span class="badge bg-info">{{ $buku->jenis_buku }}</span></td>
                            <td class="text-center">{{ $buku->stok_total }}</td>
                            <td class="text-center">
                                @if($buku->stok_tersedia > 3)
                                    <span class="badge bg-success">{{ $buku->stok_tersedia }}</span>
                                @elseif($buku->stok_tersedia > 0)
                                    <span class="badge bg-warning text-dark">{{ $buku->stok_tersedia }}</span>
                                @else
                                    <span class="badge bg-danger">Habis</span>
                                @endif
                            </td>
                            <td>
                                <button onclick="editBuku({{ $buku->id }}, '{{ addslashes($buku->nama_buku) }}', '{{ addslashes($buku->penerbit) }}', '{{ addslashes($buku->jenis_buku) }}', '{{ addslashes($buku->genre_buku) }}', {{ $buku->stok_total }}, '{{ addslashes($buku->file_ebook ? Storage::url($buku->file_ebook) : '') }}')"
                                        class="btn btn-warning btn-xs">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="hapusBuku({{ $buku->id }})"
                                        class="btn btn-danger btn-xs">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- Modal Tambah Buku --}}
<div class="modal fade" id="modalTambah" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-plus me-2"></i>Tambah Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div id="alertTambah"></div>
                <div class="mb-3">
                    <label class="form-label">Nama Buku</label>
                    <input type="text" id="tambah_nama" class="form-control" placeholder="Judul buku">
                </div>
                <div class="mb-3">
                    <label class="form-label">Penerbit</label>
                    <input type="text" id="tambah_penerbit" class="form-control" placeholder="Nama penerbit">
                </div>
                <div class="mb-3">
                    <label class="form-label">Genre Buku</label>
                    <select id="tambah_genre" class="form-control" onchange="toggleEbookField('tambah')">
                        <option value="">-- Pilih Genre --</option>
                        <option value="Fisik">Buku Fisik</option>
                        <option value="Ebook">E-Book</option>
                        <option value="Hybrid">Fisik + E-Book</option>
                    </select>
                    <small class="text-muted">Pilih jenis format buku</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Buku</label>
                    <select id="tambah_jenis" class="form-control">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="Fiksi">Fiksi</option>
                        <option value="Non-Fiksi">Non-Fiksi</option>
                        <option value="Akademik">Akademik</option>
                        <option value="Ilmu Pengetahuan">Ilmu Pengetahuan</option>
                        <option value="Teknologi">Teknologi</option>
                        <option value="Sejarah">Sejarah</option>
                    </select>
                </div>
                <div class="mb-3" id="tambah_stok_wrapper">
                    <label class="form-label">Stok Buku Fisik</label>
                    <input type="number" id="tambah_stok" class="form-control" placeholder="Jumlah stok fisik" min="0" value="0">
                    <small class="text-muted">Isi 0 jika hanya E-Book</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Sampul Buku</label>
                    <input type="file" id="tambah_sampul" class="form-control" accept="image/*">
                    <small class="text-muted">Format: JPG, PNG. Maks 2MB</small>
                </div>
                <div class="mb-3" id="tambah_ebook_wrapper" style="display:none">
                    <label class="form-label">File PDF E-Book <span class="text-danger">*</span></label>
                    <input type="file" id="tambah_ebook" class="form-control" accept="application/pdf">
                    <small class="text-muted">Format: PDF. Maks 10MB. Wajib untuk E-Book</small>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" onclick="simpanBuku()">
                    <i class="fas fa-save me-1"></i>Simpan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Edit Buku --}}
<div class="modal fade" id="modalEdit" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-edit me-2"></i>Edit Buku</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="hidden" id="edit_id">
                <div class="mb-3">
                    <label class="form-label">Nama Buku</label>
                    <input type="text" id="edit_nama" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Penerbit</label>
                    <input type="text" id="edit_penerbit" class="form-control">
                </div>
                <div class="mb-3">
                    <label class="form-label">Jenis Buku</label>
                    <select id="edit_jenis" class="form-control">
                        <option value="Fiksi">Fiksi</option>
                        <option value="Non-Fiksi">Non-Fiksi</option>
                        <option value="Akademik">Akademik</option>
                        <option value="Ilmu Pengetahuan">Ilmu Pengetahuan</option>
                        <option value="Teknologi">Teknologi</option>
                        <option value="Sejarah">Sejarah</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label class="form-label">Genre Buku</label>
                    <select id="edit_genre" class="form-control" onchange="toggleEbookField('edit')">
                        <option value="Fisik">Buku Fisik</option>
                        <option value="Ebook">E-Book</option>
                        <option value="Hybrid">Fisik + E-Book</option>
                    </select>
                    <small class="text-muted">Pilih jenis format buku</small>
                </div>
                <div class="mb-3" id="edit_stok_wrapper">
                    <label class="form-label">Stok Buku Fisik</label>
                    <input type="number" id="edit_stok" class="form-control" min="0">
                    <small class="text-muted">Isi 0 jika hanya E-Book</small>
                </div>
                <div class="mb-3">
                    <label class="form-label">Ganti Sampul (opsional)</label>
                    <input type="file" id="edit_sampul" class="form-control" accept="image/*">
                </div>
                <div class="mb-3" id="edit_ebook_wrapper" style="display:none">
                    <label class="form-label">Ganti File PDF E-Book (opsional)</label>
                    <input type="file" id="edit_ebook" class="form-control" accept="application/pdf">
                    <small class="text-muted">Format: PDF. Maks 10MB.</small>
                    <div id="current_ebook_file" class="mt-2" style="font-size:13px;display:none">
                        File saat ini: <a href="#" target="_blank" id="current_ebook_link"></a>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-warning" onclick="updateBuku()">
                    <i class="fas fa-save me-1"></i>Update
                </button>
            </div>
        </div>
    </div>
</div>

{{-- Modal Hapus --}}
<div class="modal fade" id="modalHapus" tabindex="-1">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-header bg-danger text-white">
                <h5 class="modal-title">Konfirmasi Hapus</h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body text-center">
                <i class="fas fa-exclamation-triangle fa-3x text-danger mb-3"></i>
                <p>Yakin ingin menghapus buku ini?</p>
                <input type="hidden" id="hapus_id">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-danger" onclick="konfirmasiHapus()">Hapus</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('js')
<script>
$('#tableBuku').DataTable({
    language: { url: '//cdn.datatables.net/plug-ins/1.10.x/i18n/Indonesian.json' }
});

function toggleEbookField(mode) {
    const genre = $('#' + mode + '_genre').val();
    const ebookWrapper = $('#' + mode + '_ebook_wrapper');
    const stokWrapper = $('#' + mode + '_stok_wrapper');
    
    if (genre === 'Ebook' || genre === 'Hybrid') {
        ebookWrapper.show();
    } else {
        ebookWrapper.hide();
        $('#' + mode + '_ebook').val('');
    }
    
    if (genre === 'Ebook') {
        stokWrapper.hide();
        $('#' + mode + '_stok').val(1); // Default stok 1 untuk ebook
    } else {
        stokWrapper.show();
        if ($('#' + mode + '_stok').val() == 1 || $('#' + mode + '_stok').val() == 0) {
            $('#' + mode + '_stok').val(''); // Reset jika sebelumnya ebook
        }
    }
}

function simpanBuku() {
    const genre = $('#tambah_genre').val();
    
    // Validasi genre
    if (!genre) {
        $('#alertTambah').html('<div class="alert alert-danger">Pilih genre buku terlebih dahulu</div>');
        return;
    }
    
    // Validasi ebook file untuk genre Ebook atau Hybrid
    if ((genre === 'Ebook' || genre === 'Hybrid') && !$('#tambah_ebook')[0].files[0]) {
        $('#alertTambah').html('<div class="alert alert-danger">File PDF E-Book wajib diupload untuk genre ' + genre + '</div>');
        return;
    }
    
    // Validasi stok untuk genre Fisik atau Hybrid
    if ((genre === 'Fisik' || genre === 'Hybrid') && $('#tambah_stok').val() < 1) {
        $('#alertTambah').html('<div class="alert alert-danger">Stok buku fisik harus lebih dari 0 untuk genre ' + genre + '</div>');
        return;
    }
    
    let formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('nama_buku', $('#tambah_nama').val());
    formData.append('penerbit', $('#tambah_penerbit').val());
    formData.append('genre_buku', genre);
    formData.append('jenis_buku', $('#tambah_jenis').val());
    
    // Untuk ebook, stok default 1 (unlimited digital copies)
    const stok = genre === 'Ebook' ? 1 : $('#tambah_stok').val();
    formData.append('stok_total', stok);
    
    if ($('#tambah_sampul')[0].files[0]) {
        formData.append('sampul_buku', $('#tambah_sampul')[0].files[0]);
    }
    if ($('#tambah_ebook')[0].files[0]) {
        formData.append('file_ebook', $('#tambah_ebook')[0].files[0]);
    }

    $.ajax({
        url: '{{ route("admin.buku.store") }}',
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(res) {
            if (res.success) location.reload();
        },
        error: function(xhr) {
            let errors = xhr.responseJSON.errors;
            let msg = Object.values(errors).flat().join('<br>');
            $('#alertTambah').html('<div class="alert alert-danger">'+msg+'</div>');
        }
    });
}

function editBuku(id, nama, penerbit, jenis, genre, stok, ebookUrl) {
    $('#edit_id').val(id);
    $('#edit_nama').val(nama);
    $('#edit_penerbit').val(penerbit);
    $('#edit_jenis').val(jenis);
    $('#edit_genre').val(genre);
    $('#edit_stok').val(stok);
    
    // Reset file inputs
    $('#edit_sampul').val('');
    $('#edit_ebook').val('');

    // Update current ebook link
    if (ebookUrl) {
        $('#current_ebook_link').attr('href', ebookUrl).text(ebookUrl.split('/').pop());
        $('#current_ebook_file').show();
    } else {
        $('#current_ebook_file').hide();
    }

    toggleEbookField('edit'); // Apply visibility rules based on genre
    $('#modalEdit').modal('show');
}

function updateBuku() {
    let id = $('#edit_id').val();
    let formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('_method', 'PUT');
    formData.append('nama_buku', $('#edit_nama').val());
    formData.append('penerbit', $('#edit_penerbit').val());
    formData.append('jenis_buku', $('#edit_jenis').val());
    formData.append('genre_buku', $('#edit_genre').val());
    formData.append('stok_total', $('#edit_stok').val());
    if ($('#edit_sampul')[0].files[0]) {
        formData.append('sampul_buku', $('#edit_sampul')[0].files[0]);
    }
    if ($('#edit_ebook')[0].files[0]) {
        formData.append('file_ebook', $('#edit_ebook')[0].files[0]);
    }

    $.ajax({
        url: '/admin/buku/' + id,
        method: 'POST',
        data: formData,
        processData: false,
        contentType: false,
        success: function(res) {
            if (res.success) location.reload();
        }
    });
}

function hapusBuku(id) {
    $('#hapus_id').val(id);
    $('#modalHapus').modal('show');
}

function konfirmasiHapus() {
    let id = $('#hapus_id').val();
    $.ajax({
        url: '/admin/buku/' + id,
        method: 'POST',
        data: { _token: '{{ csrf_token() }}', _method: 'DELETE' },
        success: function(res) {
            if (res.success) {
                $('#modalHapus').modal('hide');
                showNotification('success', res.message);
                setTimeout(() => location.reload(), 1500);
            }
        },
        error: function(xhr) {
            $('#modalHapus').modal('hide');
            const message = xhr.responseJSON?.message || 'Terjadi kesalahan saat menghapus buku';
            showNotification('error', message);
        }
    });
}

function showNotification(type, message) {
    const bgColor = type === 'success' ? '#10b981' : '#ef4444';
    const icon = type === 'success' ? '✓' : '✕';
    
    const notification = $('<div>')
        .css({
            position: 'fixed',
            top: '20px',
            right: '20px',
            background: bgColor,
            color: 'white',
            padding: '16px 24px',
            borderRadius: '12px',
            boxShadow: '0 4px 12px rgba(0,0,0,0.15)',
            zIndex: 9999,
            display: 'flex',
            alignItems: 'center',
            gap: '12px',
            fontSize: '14px',
            fontWeight: '500',
            maxWidth: '400px'
        })
        .html(`<span style="font-size:18px">${icon}</span><span>${message}</span>`);
    
    $('body').append(notification);
    
    setTimeout(() => {
        notification.fadeOut(300, function() {
            $(this).remove();
        });
    }, 4000);
}
</script>
@endpush