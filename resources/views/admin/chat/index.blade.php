@extends('layouts.admin')

@section('title', 'Chat Sessions')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Chat Sessions</h1>
            </div>
        </div>
    </div>
</div>

<!-- Active Sessions Monitoring Card -->
<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card card-primary card-outline">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-users"></i> Sesi Akses Aktif
                        </h3>
                        <div class="card-tools">
                            <span class="badge badge-success">{{ $activeSessions->count() }} Aktif</span>
                        </div>
                    </div>
                    <div class="card-body">
                        @if($activeSessions->isEmpty())
                            <div class="text-center text-muted py-3">
                                <i class="fas fa-user-slash fa-3x mb-3"></i>
                                <p>Tidak ada sesi aktif saat ini</p>
                            </div>
                        @else
                            <div class="table-responsive">
                                <table class="table table-hover table-sm">
                                    <thead>
                                        <tr>
                                            <th>User</th>
                                            <th>Status</th>
                                            <th>Sedang Akses</th>
                                            <th>Waktu Terakhir</th>
                                            <th>Aksi</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($activeSessions as $session)
                                        <tr>
                                            <td>
                                                @if($session->mahasiswa_id)
                                                    <div>
                                                        <strong>{{ $session->mahasiswa->nama }}</strong>
                                                        <br>
                                                        <small class="text-muted">
                                                            <i class="fas fa-id-card"></i> {{ $session->mahasiswa->nim }}
                                                        </small>
                                                    </div>
                                                @else
                                                    <span class="badge badge-secondary">
                                                        <i class="fas fa-user-secret"></i> Anonymous
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($session->mahasiswa_id)
                                                    <span class="badge badge-success">
                                                        <i class="fas fa-check-circle"></i> Terverifikasi
                                                    </span>
                                                @else
                                                    <span class="badge badge-warning">
                                                        <i class="fas fa-exclamation-circle"></i> Tidak Terverifikasi
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($session->messages->isNotEmpty())
                                                    <div class="text-truncate" style="max-width: 300px;">
                                                        <i class="fas fa-comment-dots"></i>
                                                        {{ Str::limit($session->messages->first()->message, 50) }}
                                                    </div>
                                                @else
                                                    <span class="text-muted">
                                                        <i class="fas fa-hourglass-start"></i> Memulai chat
                                                    </span>
                                                @endif
                                            </td>
                                            <td>
                                                <small>
                                                    <i class="far fa-clock"></i>
                                                    {{ $session->updated_at->diffForHumans() }}
                                                </small>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.chat.show', $session->session_id) }}" 
                                                   class="btn btn-sm btn-info" 
                                                   title="Lihat Detail">
                                                    <i class="fas fa-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        @endif
                    </div>
                    <div class="card-footer text-muted">
                        <small>
                            <i class="fas fa-info-circle"></i> 
                            Menampilkan sesi dengan aktivitas dalam 5 menit terakhir
                        </small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<div class="content">
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><i class="fas fa-list"></i> Sesi Chat Aktif</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-sm btn-primary" onclick="location.reload()">
                        <i class="fas fa-sync-alt"></i> Refresh
                    </button>
                </div>
            </div>
            <div class="card-body">
                @if($sessions->isEmpty())
                    <div class="alert alert-info">
                        <i class="fas fa-info-circle"></i> Tidak ada sesi chat aktif saat ini.
                    </div>
                @else
                    <div class="row">
                        @foreach($sessions as $session)
                            <div class="col-md-4 mb-3">
                                <div class="card border-left-primary shadow h-100">
                                    <div class="card-body">
                                        <div class="d-flex align-items-center mb-3">
                                            <div class="avatar-circle bg-primary text-white mr-3">
                                                <i class="fas fa-user"></i>
                                            </div>
                                            <div>
                                                <h5 class="mb-0">{{ $session->mahasiswa_nama ?? 'Mahasiswa' }}</h5>
                                                <small class="text-muted">NIM: {{ $session->mahasiswa_nim ?? '-' }}</small>
                                            </div>
                                        </div>
                                        
                                        <div class="mb-2">
                                            <small class="text-muted">Session ID:</small><br>
                                            <code class="text-xs">{{ Str::limit($session->session_id, 20) }}</code>
                                        </div>
                                        
                                        <div class="mb-3">
                                            @if($session->is_connected_to_admin)
                                                <span class="badge badge-success">
                                                    <i class="fas fa-check-circle"></i> Terhubung ke Admin
                                                </span>
                                            @else
                                                <span class="badge badge-warning">
                                                    <i class="fas fa-robot"></i> Bot
                                                </span>
                                            @endif
                                        </div>
                                        
                                        <div class="text-muted small mb-3">
                                            <i class="far fa-clock"></i> {{ $session->created_at->diffForHumans() }}
                                        </div>
                                        
                                        <div class="d-flex gap-2">
                                            <a href="{{ route('admin.chat.show', $session->session_id) }}" 
                                               class="btn btn-primary flex-fill">
                                                <i class="fas fa-comments"></i> Buka
                                            </a>
                                            <button type="button" 
                                                    class="btn btn-outline-danger" 
                                                    onclick="endSession('{{ addslashes($session->session_id) }}', '{{ addslashes($session->mahasiswa_nama ?? 'Mahasiswa') }}')">
                                                <i class="fas fa-times"></i>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>
</div>

<style>
.border-left-primary {
    border-left: 4px solid #2563eb !important;
}

.avatar-circle {
    width: 48px;
    height: 48px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 20px;
}

.card {
    transition: transform 0.2s, box-shadow 0.2s;
}

.card:hover {
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(0,0,0,0.1) !important;
}
</style>

<script>
// Auto-refresh every 10 seconds
setInterval(function() {
    location.reload();
}, 10000);

// End session function
function endSession(sessionId, mahasiswaName) {
    if (!confirm('Apakah Anda yakin ingin mengakhiri sesi chat dengan ' + mahasiswaName + '?')) {
        return;
    }
    
    // Get CSRF token from meta tag or generate from Laravel
    const csrfMeta = document.querySelector('meta[name="csrf-token"]');
    const csrfToken = csrfMeta ? csrfMeta.content : '{{ csrf_token() }}';
    
    fetch('/admin/chat/' + sessionId + '/close', {
        method: 'POST',
        credentials: 'same-origin',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            alert('Sesi chat telah diakhiri.');
            location.reload();
        } else {
            alert('Gagal mengakhiri sesi. Silakan coba lagi.');
        }
    })
    .catch(err => {
        console.error('Error:', err);
        alert('Terjadi kesalahan. Silakan coba lagi.');
    });
}
</script>
@endsection