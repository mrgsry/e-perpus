@extends('layouts.admin')
@section('title', 'Dashboard')

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-6">
            <div class="col-sm-6">
                <h1 class="m-0 text-dark">Dashboard</h1>
            </div>
            <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active">Dashboard</li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="content">
    <div class="container-fluid">
        {{-- Stats Cards --}}
        <div class="row">
            <div class="col-lg-3 col-6">
                <div class="small-box bg-gradient-primary shadow">
                    <div class="inner">
                        <h3>{{ $totalBuku }}</h3>
                        <p>Total Buku</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-book"></i>
                    </div>
<a href="{{ route('admin.buku.index') }}" class="small-box-footer">
    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
</a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-gradient-success shadow">
                    <div class="inner">
                        <h3>{{ $totalMahasiswa }}</h3>
                        <p>Total Mahasiswa</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-user-graduate"></i>
                    </div>
                    <a href="{{ route('admin.mahasiswa.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-gradient-info shadow">
                    <div class="inner">
                        <h3>{{ $pendingRequest }}</h3>
                        <p>Request Pending</p>
                    </div>
                    <div class="icon">
                        <i class="fas fa-clock"></i>
                    </div>
                    <a href="{{ route('admin.peminjaman.index') }}" class="small-box-footer">
                        Lihat Detail <i class="fas fa-arrow-circle-right"></i>
                    </a>
                </div>
            </div>

            <div class="col-lg-3 col-6">
                <div class="small-box bg-gradient-danger shadow">
                    <div class="inner">
<h3>{{ $stokKritis->count() }}</h3>
<p>Telat Pengembalian</p>
</div>
<div class="icon">
    <i class="fas fa-exclamation-triangle"></i>
</div>
<a href="#" data-bs-toggle="modal" data-bs-target="#lateReturnModal" class="small-box-footer">
    Lihat Detail <i class="fas fa-arrow-circle-right"></i>
</a>
                </div>
</div>
</div>

<!-- Modal Late Returns -->
<div class="modal fade" id="lateReturnModal" tabindex="-1" aria-labelledby="lateReturnModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="lateReturnModalLabel">Mahasiswa dengan Pengembalian Telat</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        @if($stokKritis->isEmpty())
          <p>Tidak ada pengembalian telat.</p>
        @else
          <table class="table table-sm table-striped">
            <thead>
              <tr>
                <th>#</th>
                <th>Mahasiswa</th>
                <th>Buku</th>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali Rencana</th>
                <th>Tanggal Kembali Aktual</th>
              </tr>
            </thead>
            <tbody>
              @foreach($stokKritis as $index => $pinjam)
                <tr>
                  <td>{{ $index + 1 }}</td>
                  <td>{{ $pinjam->mahasiswa->nama ?? '-' }} ({{ $pinjam->mahasiswa->nim ?? '-' }})</td>
                  <td>{{ $pinjam->buku->nama_buku ?? '-' }}</td>
                  <td>{{ $pinjam->tanggal_pinjam ? \Carbon\Carbon::parse($pinjam->tanggal_pinjam)->format('d M Y') : '-' }}</td>
                  <td>{{ $pinjam->tanggal_kembali_rencana ? \Carbon\Carbon::parse($pinjam->tanggal_kembali_rencana)->format('d M Y') : '-' }}</td>
                  <td>{{ $pinjam->tanggal_kembali_aktual ? \Carbon\Carbon::parse($pinjam->tanggal_kembali_aktual)->format('d M Y') : '-' }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        @endif
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>

<div class="row">
            {{-- Donut Chart Peminjaman per Jurusan --}}
            <div class="col-md-4">
                <div class="card card-outline card-primary shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-pie mr-2"></i>Peminjaman per Jurusan
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height: 250px;">
                            <canvas id="jurusanChart" data-json='{!! json_encode($peminjamanPerJurusan) !!}'></canvas>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Tabel Peminjaman Terbaru --}}
            <div class="col-md-8">
                <div class="card card-outline card-success shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-clipboard-list mr-2"></i>Peminjaman Terbaru
                        </h3>
                        <div class="card-tools">
                            <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-tool" title="Lihat Semua">
                                <i class="fas fa-external-link-alt"></i>
                            </a>
                        </div>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-sm table-hover table-striped m-0">
                                <thead class="thead-dark">
                                    <tr>
                                        <th>Booking ID</th>
                                        <th>Mahasiswa</th>
                                        <th>Buku</th>
                                        <th>Status</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($peminjamanTerbaru as $p)
                                    <tr>
                                        <td><span class="badge badge-light">{{ $p->booking_id }}</span></td>
                                        <td>{{ $p->mahasiswa->nama ?? '-' }}</td>
                                        <td>{{ Str::limit($p->buku->nama_buku ?? '-', 30) }}</td>
                                        <td>
                                            @if($p->status == 'pending')
                                                <span class="badge badge-warning text-dark">Pending</span>
                                            @elseif($p->status == 'dipinjam')
                                                <span class="badge badge-primary">Dipinjam</span>
                                            @elseif($p->status == 'dikembalikan')
                                                <span class="badge badge-success">Dikembalikan</span>
                                            @else
                                                <span class="badge badge-danger">Terlambat</span>
                                            @endif
                                        </td>
                                    </tr>
                                    @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted py-3">
                                            Belum ada data peminjaman
                                        </td>
                                    </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="card-footer text-right">
                        <a href="{{ route('admin.peminjaman.index') }}" class="btn btn-sm btn-primary">
                            <i class="fas fa-list mr-1"></i> Lihat Semua Peminjaman
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Bar Chart Buku Dipinjam dan Dikembalikan --}}
            <div class="col-md-12">
                <div class="card card-outline card-warning shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-chart-bar mr-2"></i>Buku Dipinjam & Dikembalikan <small class="text-muted">(14 hari terakhir)</small>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height: 320px;">
                            <canvas id="borrowReturnChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            {{-- Area Chart Pendaftaran Mahasiswa per Hari --}}
            <div class="col-md-12">
                <div class="card card-outline card-info shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title">
                            <i class="fas fa-user-plus mr-2"></i>Pendaftaran Mahasiswa per Hari <small class="text-muted">(30 hari terakhir)</small>
                        </h3>
                    </div>
                    <div class="card-body">
                        <div class="chart-container" style="position: relative; height: 280px;">
                            <canvas id="dailyChart"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Data dari controller
    const jurusanData = JSON.parse(document.getElementById('jurusanChart').dataset.json);
    
    // Prepare data for chart
    const labels = jurusanData.map(item => item.jurusan);
    const data = jurusanData.map(item => item.total);
    
    // Generate colors
    const colors = [
        '#007bff', // blue
        '#28a745', // green
        '#ffc107', // yellow
        '#dc3545', // red
        '#17a2b8', // cyan
        '#6f42c1', // purple
        '#fd7e14', // orange
        '#20c997', // teal
    ];
    
    // Create donut chart
    const ctx = document.getElementById('jurusanChart').getContext('2d');
    new Chart(ctx, {
        type: 'doughnut',
        data: {
            labels: labels,
            datasets: [{
                data: data,
                backgroundColor: colors.slice(0, labels.length),
                borderWidth: 2,
                borderColor: '#fff'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom',
                    labels: {
                        padding: 15,
                        font: {
                            size: 11
                        },
                        generateLabels: function(chart) {
                            const data = chart.data;
                            if (data.labels.length && data.datasets.length) {
                                return data.labels.map((label, i) => {
                                    const value = data.datasets[0].data[i];
                                    return {
                                        text: `${label}: ${value}`,
                                        fillStyle: data.datasets[0].backgroundColor[i],
                                        hidden: false,
                                        index: i
                                    };
                                });
                            }
                            return [];
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const label = context.label || '';
                            const value = context.parsed || 0;
                            const total = context.dataset.data.reduce((a, b) => a + b, 0);
                            const percentage = ((value / total) * 100).toFixed(1);
                            return `${label}: ${value} (${percentage}%)`;
                        }
                    }
                }
            }
        }
    });
    
    // Data pendaftaran mahasiswa per hari
    const dailyLabels = @json($pendaftaranLabels);
    const dailyCounts = @json($pendaftaranData);
    
    // Create area chart
    const dailyCtx = document.getElementById('dailyChart').getContext('2d');
    new Chart(dailyCtx, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Pendaftaran Mahasiswa',
                data: dailyCounts,
                backgroundColor: 'rgba(54, 162, 235, 0.2)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 2,
                pointBackgroundColor: '#fff',
                pointBorderColor: 'rgba(54, 162, 235, 1)',
                pointRadius: 5,
                pointHoverRadius: 7,
                fill: true,
                tension: 0.4
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12
                        }
                    }
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return 'Pendaftaran: ' + context.parsed.y + ' mahasiswa';
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0
                    }
                }
            }
        }
    });

    // Bar Chart - Buku Dipinjam & Dikembalikan
    const chartLabels = @json($chartLabels);
    const chartDataPinjam = @json($chartDataPinjam);
    const chartDataKembali = @json($chartDataKembali);

    const borrowReturnCtx = document.getElementById('borrowReturnChart').getContext('2d');
    new Chart(borrowReturnCtx, {
        type: 'bar',
        data: {
            labels: chartLabels,
            datasets: [
                {
                    label: 'Buku Dipinjam',
                    data: chartDataPinjam,
                    backgroundColor: 'rgba(54, 162, 235, 0.8)',
                    borderColor: 'rgba(54, 162, 235, 1)',
                    borderWidth: 1,
                    borderRadius: 5,
                },
                {
                    label: 'Buku Dikembalikan',
                    data: chartDataKembali,
                    backgroundColor: 'rgba(40, 167, 69, 0.8)',
                    borderColor: 'rgba(40, 167, 69, 1)',
                    borderWidth: 1,
                    borderRadius: 5,
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'top',
                    labels: {
                        padding: 15,
                        font: {
                            size: 12,
                            weight: 'bold'
                        },
                        usePointStyle: true,
                        pointStyle: 'rectRounded'
                    }
                },
                tooltip: {
                    backgroundColor: 'rgba(0, 0, 0, 0.8)',
                    padding: 12,
                    titleFont: {
                        size: 13,
                        weight: 'bold'
                    },
                    bodyFont: {
                        size: 12
                    },
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.parsed.y + ' buku';
                        }
                    }
                }
            },
            scales: {
                x: {
                    grid: {
                        display: false
                    },
                    ticks: {
                        font: {
                            size: 11
                        }
                    }
                },
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1,
                        precision: 0,
                        font: {
                            size: 11
                        }
                    },
                    grid: {
                        color: 'rgba(0, 0, 0, 0.05)'
                    }
                }
            }
        }
    });
});
</script>
@endpush