@extends('layouts.admin')
@section('title', 'Manajemen Mahasiswa')

@push('styles')
<style>
.content-header {
    position: sticky;
    top: 0;
    z-index: 1020;
    background: #fff;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.08);
}

.mahasiswa-summary-card {
    border: 0;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
    overflow: hidden;
}

.mahasiswa-summary-card.summary-card .card-body {
    min-height: 118px;
    display: flex;
    align-items: center;
    justify-content: space-between;
}

.mahasiswa-summary-card .summary-value {
    font-size: 1.65rem;
    font-weight: 700;
    line-height: 1;
}

.mahasiswa-summary-card .summary-icon {
    font-size: 2.5rem;
    opacity: .25;
}

.mahasiswa-summary-card .summary-note {
    display: block;
    margin-top: 9px;
    font-size: .75rem;
    opacity: .82;
}

.summary-chart {
    height: 240px;
    position: relative;
}
</style>
@endpush

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <h1 class="m-0">Manajemen Mahasiswa</h1>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        <div class="row mb-4">
            <div class="col-lg-5 mb-3">
                <div class="card mahasiswa-summary-card h-100">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold">Status Akun Mahasiswa</h3>
                    </div>
                    <div class="card-body">
                        <div class="summary-chart"><canvas id="statusMahasiswaChart"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 mb-3">
                <div class="card mahasiswa-summary-card h-100">
                    <div class="card-header border-0">
                        <h3 class="card-title font-weight-bold">Mahasiswa per Jurusan</h3>
                    </div>
                    <div class="card-body">
                        <div class="summary-chart"><canvas id="jurusanMahasiswaChart"></canvas></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 mb-3">
                <div class="card mahasiswa-summary-card summary-card bg-warning text-white h-100">
                    <div class="card-body">
                        <div>
                            <small>Menunggu Persetujuan</small>
                            <div class="summary-value mt-2">{{ $pendingMahasiswa }}</div>
                            <span class="summary-note">Perlu ditinjau admin</span>
                        </div>
                        <i class="fas fa-user-clock summary-icon"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Daftar Mahasiswa</h3>
            </div>
            <div class="card-body">
                <div class="table-responsive-sm">
                    <table class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama</th>
                                <th>NIM</th>
                                <th>Jurusan</th>
                                <th>No. Telepon</th>
                                <th>Email</th>
                                <th>Status</th>
                                <th>Token Referral</th>
                                <th>Aksi</th>
                                <th>Update Request</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($mahasiswas as $i => $mahasiswa)
                            <tr>
                                <td>{{ $i + 1 }}</td>
                                <td>{{ $mahasiswa->nama }}</td>
                                <td>{{ $mahasiswa->nim }}</td>
                                <td>{{ $mahasiswa->jurusan }}</td>
                                <td>{{ $mahasiswa->no_telepon ?? '-' }}</td>
                                <td>{{ $mahasiswa->email }}</td>
                                <td>
                                    @if($mahasiswa->status === 'pending')
                                    <span class="badge badge-warning">Pending</span>
                                    @elseif($mahasiswa->status === 'approved')
                                    <span class="badge badge-success">Approved</span>
                                    @else
                                    <span class="badge badge-danger">Rejected</span>
                                    @endif
                                </td>
                                <td>
                                    @if($mahasiswa->referral_token)
                                    <code class="bg-light p-1">{{ $mahasiswa->referral_token }}</code>
                                    @else
                                    <span class="text-muted">-</span>
                                    @endif
                                </td>
                                <td>
                                    @if ($mahasiswa->status === 'pending')
                                    <button type="button" class="btn btn-success btn-xs btn-action"
                                        data-url="{{ route('admin.mahasiswa.approve', $mahasiswa->id) }}"
                                        data-action="Approve" data-nama="{{ $mahasiswa->nama }}">
                                        <i class="fas fa-check"></i>
                                    </button>
                                    <button type="button" class="btn btn-danger btn-xs btn-action"
                                        data-url="{{ route('admin.mahasiswa.reject', $mahasiswa->id) }}"
                                        data-action="Reject" data-nama="{{ $mahasiswa->nama }}">
                                        <i class="fas fa-times"></i>
                                    </button>
                                    @endif
                                    <button type="button" class="btn btn-outline-primary btn-xs btn-edit"
                                        data-url="{{ route('admin.mahasiswa.update', $mahasiswa->id) }}"
                                        data-nama="{{ $mahasiswa->nama }}" data-nim="{{ $mahasiswa->nim }}"
                                        data-jurusan="{{ $mahasiswa->jurusan }}"
                                        data-telepon="{{ $mahasiswa->no_telepon }}"
                                        data-email="{{ $mahasiswa->email }}">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    @if($mahasiswa->status === 'approved' && $mahasiswa->email)
                                    <button type="button" class="btn btn-outline-warning btn-xs btn-resend-email"
                                        data-url="{{ route('admin.mahasiswa.resend-email', $mahasiswa->id) }}"
                                        data-nama="{{ $mahasiswa->nama }}" data-email="{{ $mahasiswa->email }}"
                                        title="Kirim Ulang Email Informasi Akun">
                                        <i class="fas fa-envelope"></i>
                                    </button>
                                    @endif
                                    <button type="button" class="btn btn-outline-info btn-xs btn-action"
                                        data-url="{{ route('admin.mahasiswa.approve', $mahasiswa->id) }}"
                                        data-action="Generate Token" data-nama="{{ $mahasiswa->nama }}">
                                        <i class="fas fa-key"></i>
                                    </button>
                                    <button type="button" class="btn btn-outline-danger btn-xs btn-delete"
                                        data-url="{{ route('admin.mahasiswa.destroy', $mahasiswa->id) }}"
                                        data-nama="{{ $mahasiswa->nama }}">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </td>
                                <td>
                                    @if($mahasiswa->pending_updates)
                                    <button type="button" class="btn btn-sm btn-outline-warning btn-update-request"
                                        data-url="{{ route('admin.mahasiswa.process-update', $mahasiswa->id) }}"
                                        data-nama="{{ $mahasiswa->nama }}"
                                        data-updates="{{ json_encode($mahasiswa->pending_updates) }}">
                                        <i class="fas fa-exclamation-triangle"></i> Update
                                    </button>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <form id="editForm">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Mahasiswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label>Nama</label>
                        <input type="text" name="nama" id="editNama" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>NIM</label>
                        <input type="text" name="nim" id="editNim" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label>Jurusan</label>
                        <select name="jurusan" id="editJurusan" class="form-control" required>
                            <option value="Teknik Informatika (TI)">Teknik Informatika (TI)</option>
                            <option value="Sistem Informatika (SI)">Sistem Informatika (SI)</option>
                            <option value="Desain Komunikasi Visual (DKV)">Desain Komunikasi Visual (DKV)</option>
                            <option value="Teknik Sipil (TS)">Teknik Sipil (TS)</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label>No. Telepon</label>
                        <input type="text" name="no_telepon" id="editTelepon" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label>Email</label>
                        <input type="email" name="email" id="editEmail" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Confirmation Modal -->
<div class="modal fade" id="confirmModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmTitle">Konfirmasi</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body" id="confirmMessage">
                Apakah Anda yakin?
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="confirmBtn">Lanjutkan</button>
            </div>
        </div>
    </div>
</div>

<!-- Update Request Modal -->
<div class="modal fade" id="updateRequestModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">
                    <i class="fas fa-exclamation-triangle text-warning"></i> Permintaan Update Data
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning">
                    <strong>Mahasiswa:</strong> <span id="updateRequestNama"></span>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>Field</th>
                                <th>Nilai Baru</th>
                            </tr>
                        </thead>
                        <tbody id="updateRequestTableBody"></tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                <button type="button" class="btn btn-success" id="approveUpdateBtn">Approve</button>
                <button type="button" class="btn btn-danger" id="rejectUpdateBtn">Reject</button>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.7/dist/chart.umd.min.js"></script>
<div id="mahasiswaChartData" data-status="{{ $statusChartData }}" data-jurusan-labels="{{ $jurusanChartLabels }}"
    data-jurusan-values="{{ $jurusanChartData }}"></div>
<script>
const mahasiswaChartData = document.getElementById('mahasiswaChartData').dataset;
const statusChartData = JSON.parse(mahasiswaChartData.status);
const jurusanChartLabels = JSON.parse(mahasiswaChartData.jurusanLabels);
const jurusanChartData = JSON.parse(mahasiswaChartData.jurusanValues);
const chartDefaults = {
    responsive: true,
    maintainAspectRatio: false,
    plugins: {
        legend: {
            position: 'bottom'
        }
    }
};
new Chart(document.getElementById('statusMahasiswaChart'), {
    type: 'doughnut',
    data: {
        labels: ['Approved', 'Pending', 'Rejected'],
        datasets: [{
            data: statusChartData,
            backgroundColor: ['#28a745', '#ffc107', '#dc3545'],
            borderWidth: 0
        }]
    },
    options: {
        ...chartDefaults,
        cutout: '62%'
    }
});
new Chart(document.getElementById('jurusanMahasiswaChart'), {
    type: 'doughnut',
    data: {
        labels: jurusanChartLabels,
        datasets: [{
            data: jurusanChartData,
            backgroundColor: ['#007bff', '#17a2b8', '#6f42c1', '#fd7e14', '#20c997', '#e83e8c'],
            borderWidth: 0
        }]
    },
    options: {
        ...chartDefaults,
        cutout: '62%'
    }
});
// Initialize Bootstrap modal
const confirmModal = new bootstrap.Modal(document.getElementById('confirmModal'));
const editModal = new bootstrap.Modal(document.getElementById('editModal'));
let currentAction = null;
let currentUrl = null;
let currentNama = null;

// Handle Approve/Reject buttons
document.querySelectorAll('.btn-action').forEach(button => {
    button.addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        const action = this.getAttribute('data-action');
        const nama = this.getAttribute('data-nama');

        currentAction = action;
        currentUrl = url;
        currentNama = nama;

        // Set modal content
        document.getElementById('confirmTitle').textContent = 'Konfirmasi ' + action;
        document.getElementById('confirmMessage').textContent =
            'Apakah Anda yakin ingin ' + action.toLowerCase() + ' mahasiswa "' + nama + '"?';
        document.getElementById('confirmBtn').className = 'btn ' +
            (action === 'Approve' ? 'btn-success' : 'btn-danger');
        document.getElementById('confirmBtn').textContent = action;

        // Show modal
        confirmModal.show();
    });
});

// Handle Delete button
document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        const nama = this.getAttribute('data-nama');

        currentAction = 'Delete';
        currentUrl = url;
        currentNama = nama;

        // Set modal content
        document.getElementById('confirmTitle').textContent = 'Konfirmasi Hapus';
        document.getElementById('confirmMessage').textContent =
            'Apakah Anda yakin ingin menghapus mahasiswa "' + nama + '"?\n\n' +
            'Data yang dihapus tidak dapat dikembalikan.';
        document.getElementById('confirmBtn').className = 'btn btn-danger';
        document.getElementById('confirmBtn').textContent = 'Hapus';

        // Show modal
        confirmModal.show();
    });
});

// Handle Edit button
document.querySelectorAll('.btn-edit').forEach(button => {
    button.addEventListener('click', function() {
        const url = this.getAttribute('data-url');

        document.getElementById('editNama').value = this.getAttribute('data-nama');
        document.getElementById('editNim').value = this.getAttribute('data-nim');
        document.getElementById('editJurusan').value = this.getAttribute('data-jurusan');
        document.getElementById('editTelepon').value = this.getAttribute('data-telepon');
        document.getElementById('editEmail').value = this.getAttribute('data-email');

        document.getElementById('editForm').setAttribute('action', url);

        editModal.show();
    });
});

// Handle Edit form submission
document.getElementById('editForm').addEventListener('submit', function(e) {
    e.preventDefault();

    fetch(this.getAttribute('action'), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                _method: 'PUT',
                nama: document.getElementById('editNama').value,
                nim: document.getElementById('editNim').value,
                jurusan: document.getElementById('editJurusan').value,
                no_telepon: document.getElementById('editTelepon').value,
                email: document.getElementById('editEmail').value
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                window.location.reload();
            } else {
                alert('Gagal update data');
            }
        });
});

// Handle Resend Email button
document.querySelectorAll('.btn-resend-email').forEach(button => {
    button.addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        const nama = this.getAttribute('data-nama');
        const email = this.getAttribute('data-email');

        currentAction = 'Resend Email';
        currentUrl = url;
        currentNama = nama;

        // Set modal content
        document.getElementById('confirmTitle').textContent = 'Konfirmasi Kirim Ulang Email';
        document.getElementById('confirmMessage').innerHTML =
            'Apakah Anda yakin ingin mengirim ulang email informasi akun ke:<br><br>' +
            '<strong>' + nama + '</strong><br>' +
            '<code>' + email + '</code><br><br>' +
            'Email akan berisi informasi NIM dan Token Referral.';
        document.getElementById('confirmBtn').className = 'btn btn-warning';
        document.getElementById('confirmBtn').textContent = 'Kirim Email';

        // Show modal
        confirmModal.show();
    });
});

// Handle confirm button click
document.getElementById('confirmBtn').addEventListener('click', function() {
    const method = currentAction === 'Delete' ? 'DELETE' : 'POST';

    fetch(currentUrl, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            confirmModal.hide();

            if (data.success) {
                // Show success message
                let successMessage = currentAction === 'Approve' ?
                    'Mahasiswa "' + currentNama + '" berhasil disetujui.' :
                    currentAction === 'Reject' ?
                    'Mahasiswa "' + currentNama + '" berhasil ditolak.' :
                    currentAction === 'Generate Token' ?
                    'Token berhasil di generate ulang untuk "' + currentNama + '".' :
                    currentAction === 'Resend Email' ?
                    'Email informasi akun berhasil dikirim ulang ke "' + currentNama + '".' :
                    'Mahasiswa "' + currentNama + '" berhasil dihapus.';

                // Add token info if approve action
                let tokenInfo = '';
                if ((currentAction === 'Approve' || currentAction === 'Generate Token') && data
                    .referral_token) {
                    tokenInfo = `
                    <div class="alert alert-info mt-3 mb-0" style="text-align: left;">
                        <strong><i class="fas fa-key me-2"></i>Token Referral:</strong>
                        <div class="mt-2">
                            <code style="font-size: 1.2rem; background: #fff; padding: 8px 16px; border-radius: 6px; display: inline-block; letter-spacing: 2px;">${data.referral_token}</code>
                        </div>
                        <small class="d-block mt-2 text-muted">
                            <i class="fas fa-info-circle me-1"></i>
                            Token ini diperlukan mahasiswa untuk akses ebook dan peminjaman buku.
                        </small>
                    </div>
                `;
                }

                // Create and show success modal
                const successModal = new bootstrap.Modal(document.createElement('div'));
                const modalHtml = `
                <div class="modal fade" tabindex="-1">
                    <div class="modal-dialog modal-dialog-centered">
                        <div class="modal-content">
                            <div class="modal-header bg-success text-white">
                                <h5 class="modal-title">Berhasil</h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
                            </div>
                            <div class="modal-body">
                                <div class="text-center">
                                    <i class="fas fa-check-circle text-success mb-3" style="font-size: 3rem;"></i>
                                    <p>${successMessage}</p>
                                </div>
                                ${tokenInfo}
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-success" data-bs-dismiss="modal" onclick="window.location.reload()">OK</button>
                            </div>
                        </div>
                    </div>
                </div>
            `;

                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = modalHtml;
                document.body.appendChild(tempDiv.firstChild);

                const newModal = new bootstrap.Modal(tempDiv.firstChild);
                newModal.show();

                // Auto reload after modal is hidden
                tempDiv.firstChild.addEventListener('hidden.bs.modal', function() {
                    window.location.reload();
                });
            } else {
                // Show error message
                alert('Gagal melakukan aksi: ' + (data.message || 'Unknown error'));
            }
        })
        .catch(error => {
            confirmModal.hide();
            console.error('Error:', error);
            alert('Terjadi kesalahan saat memproses permintaan.');
        });
});

// Update Request Modal handling
const updateRequestModal = new bootstrap.Modal(document.getElementById('updateRequestModal'));
let currentUpdateRequestUrl = null;

document.querySelectorAll('.btn-update-request').forEach(button => {
    button.addEventListener('click', function() {
        const url = this.getAttribute('data-url');
        const nama = this.getAttribute('data-nama');
        const updates = JSON.parse(this.getAttribute('data-updates'));

        currentUpdateRequestUrl = url;
        document.getElementById('updateRequestNama').textContent = nama;

        const tbody = document.getElementById('updateRequestTableBody');
        tbody.innerHTML = '';
        for (const [key, value] of Object.entries(updates)) {
            const row = document.createElement('tr');
            row.innerHTML = `<td>${formatFieldName(key)}</td><td>${value || '-'}</td>`;
            tbody.appendChild(row);
        }

        updateRequestModal.show();
    });
});

function formatFieldName(field) {
    const map = {
        nama: 'Nama',
        email: 'Email',
        jurusan: 'Jurusan',
        no_telepon: 'No. Telepon'
    };
    return map[field] || field;
}

document.getElementById('approveUpdateBtn').addEventListener('click', function() {
    if (!currentUpdateRequestUrl) return;
    fetch(currentUpdateRequestUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'approve'
            })
        })
        .then(r => r.json())
        .then(data => {
            updateRequestModal.hide();
            if (data.success) {
                alert('Update data berhasil disetujui.');
                location.reload();
            } else {
                alert(data.message || 'Gagal menyetujui update.');
            }
        })
        .catch(() => {
            alert('Error processing request.');
        });
});

document.getElementById('rejectUpdateBtn').addEventListener('click', function() {
    if (!currentUpdateRequestUrl) return;
    fetch(currentUpdateRequestUrl, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json',
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({
                action: 'reject'
            })
        })
        .then(r => r.json())
        .then(data => {
            updateRequestModal.hide();
            if (data.success) {
                alert('Update data ditolak.');
                location.reload();
            } else {
                alert(data.message || 'Gagal menolak update.');
            }
        })
        .catch(() => {
            alert('Error processing request.');
        });
});
</script>
@endpush