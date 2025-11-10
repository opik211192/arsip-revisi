@extends('adminlte::page')

@section('title', 'Dashboard')

@push('css')
<style>
  :root {
    --color-primary: #007bff;
    --color-secondary: #343a40;
    --color-light: #f8f9fa;
  }

  body {
    background: linear-gradient(135deg, #f7f9fc, #eef2f7);
  }

  .fade-in {
    animation: fadeIn 0.6s ease-in-out;
  }

  @keyframes fadeIn {
    from {
      opacity: 0;
      transform: translateY(10px);
    }

    to {
      opacity: 1;
      transform: translateY(0);
    }
  }

  .small-box {
    border-radius: 14px !important;
    box-shadow: 0 4px 10px rgba(0, 0, 0, 0.12);
    transition: all 0.25s ease-in-out;
    overflow: hidden;
    position: relative;
  }

  .small-box:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 18px rgba(0, 0, 0, 0.15);
  }

  .small-box .inner {
    padding: 15px 15px 10px;
  }

  .small-box .inner h3 {
    font-size: 1.4rem;
    font-weight: 700;
  }

  .small-box .inner p {
    font-size: 0.85rem;
    margin-top: 4px;
    color: rgba(255, 255, 255, 0.9);
  }

  .small-box .icon {
    position: absolute;
    top: 12px;
    right: 15px;
    font-size: 45px;
    opacity: 0.15;
  }

  .bg-gradient-lightblue {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
  }

  .bg-gradient-dark {
    background: linear-gradient(135deg, #343a40, #1d2124);
  }

  .card {
    border-radius: 14px !important;
    overflow: hidden;
  }

  .card-header {
    font-weight: 600;
    font-size: 1.05rem;
    border-bottom: none;
  }

  .badge-total {
    background: linear-gradient(135deg, #007bff, #00b894);
    font-size: 0.85rem;
    font-weight: 600;
    color: #fff;
    padding: 4px 10px;
    border-radius: 8px;
  }

  .table {
    font-size: 0.95rem;
  }

  .table tr:hover {
    background-color: #f1f5ff;
    transition: 0.3s;
  }

  .empty-text {
    color: #999;
    font-style: italic;
  }

  .chart-container {
    position: relative;
    height: 180px;
    width: 180px;
    margin: 0 auto;
  }

  .user-card {
    background: #fff;
    border-radius: 12px;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    padding: 15px;
  }
</style>
@endpush

@section('content_header')
<div></div>
@stop

@section('content')
<div class="fade-in">
  <div class="card shadow-sm border-0">
    <div class="card-header bg-gradient-dark text-white d-flex align-items-center">
      <i class="fas fa-tachometer-alt me-2"></i>
      <span>Dashboard</span>
    </div>

    <div class="card-body">
      <div class="row g-4 mt-2 align-items-stretch">
        {{-- 🔹 Kiri: Tabel Arsip Unit Kerja --}}
        <div class="col-md-8">
          <div class="card shadow-sm border-0 h-100 fade-in">
            <div class="card-header bg-gradient-dark text-white">
              <h5 class="mb-0"><i class="fas fa-folder-open me-2"></i>Jumlah Arsip per Unit Kerja</h5>
            </div>

            <div class="card-body p-3" style="max-height: 500px; overflow-y: auto;">
              @forelse ($strukturals as $unitKerja => $details)
              @php $totalArsip = collect($details)->sum('jumlah'); @endphp

              <div class="mb-4 border-bottom pb-3">
                <h6 class="fw-bold text-dark d-flex justify-content-between align-items-center mb-2">
                  <span><i class="fas fa-building me-2 text-primary"></i> {{ $unitKerja }}</span>
                  <span class="badge-total">Total: {{ $totalArsip }}</span>
                </h6>

                <div class="table-responsive">
                  <table class="table table-sm align-middle mb-0">
                    <thead>
                      <tr>
                        <th style="width: 5%">No</th>
                        <th>Detail</th>
                        <th class="text-end" style="width: 15%">Jumlah</th>
                        <th class="text-center" style="width: 25%">Update Terakhir</th>
                      </tr>
                    </thead>
                    <tbody>
                      @foreach ($details as $detail)
                      @if ($detail->detail_name)
                      <tr>
                        <td class="text-center">{{ $loop->iteration }}</td>
                        <td class="text-primary fw-semibold">{{ $detail->detail_name }}</td>
                        <td class="text-end fw-bold">{{ $detail->jumlah }}</td>
                        <td class="text-center text-muted">
                          @if ($detail->terakhir_input)
                          {{ \Carbon\Carbon::parse($detail->terakhir_input)->locale('id')->diffForHumans() }}
                          <br>
                          <small class="text-secondary">
                            ({{ \Carbon\Carbon::parse($detail->terakhir_input)->format('d M Y H:i') }})
                          </small>
                          @else
                          <span class="text-danger">Belum ada arsip</span>
                          @endif
                        </td>
                      </tr>
                      @endif
                      @endforeach
                    </tbody>
                  </table>
                </div>
              </div>
              @empty
              <div class="text-center py-5 empty-text">Belum ada data arsip yang ditampilkan.</div>
              @endforelse
            </div>
          </div>
        </div>

        {{-- 🔹 Kanan: Total Arsip + Info Pengguna --}}
        <div class="col-md-4">
          {{-- Total Arsip --}}
          <div class="small-box bg-gradient-lightblue text-white fade-in mb-3">
            <div class="inner text-center">
              <i class="fas fa-archive fa-lg mb-2"></i>
              <h3 class="fw-bold mb-1" style="font-size: 1.3rem;">{{ $allArsip }} Boks</h3>
              <p class="fs-6 mb-1">Total Seluruh Arsip</p>
              <div class="progress mt-2" style="height: 5px;">
                <div class="progress-bar bg-white" style="width: 75%; opacity: 0.6;"></div>
              </div>
            </div>
          </div>

          {{-- Info Pengguna --}}
          <div class="user-card fade-in">
            <div class="text-center mb-2">
              <i class="fas fa-user-circle fa-2x text-primary mb-2"></i>
              <h6 class="fw-bold mb-0">{{ Auth::user()->name }}</h6>
              <small class="text-secondary">
                <strong>{{ Auth::user()->struktural_detail->name ?: '-' }}</strong>
              </small>
            </div>
            <hr>
            {{-- 🔸 Role Admin/Superadmin --}}
            @if (Auth::user()->hasRole('super admin') || Auth::user()->hasRole('admin'))
            {{-- <div class="text-center">
              <h6 class="fw-semibold mb-3 text-secondary">Persentase Arsip</h6>
              <div class="chart-container">
                <canvas id="donutChart"></canvas>
              </div>
            </div> --}}
            {{-- 🔹 Role User --}}
            @else
            <div class="text-center py-3">
              <i class="fas fa-upload fa-lg text-info mb-2"></i>
              <h6>Arsip yang Anda Upload:</h6>
              <h3 class="fw-bold text-primary mt-2">{{ $userArsip ?? 0 }} Boks</h3>
            </div>
            @endif
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@stop

{{-- @push('js')
@if (Auth::user()->hasRole('super admin') || Auth::user()->hasRole('admin'))
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('donutChart');
  new Chart(ctx, {
    type: 'doughnut',
    data: {
      labels: ['Arsip Aktif', 'Arsip Lama', 'Arsip Lainnya'],
      datasets: [{
        data: [60, 25, 15],
        backgroundColor: ['#007bff', '#00b894', '#ff7675'],
        borderWidth: 0
      }]
    },
    options: {
      plugins: {
        legend: {
          display: true,
          position: 'bottom'
        }
      },
      animation: {
        animateRotate: true,
        animateScale: true,
        duration: 1500
      },
      cutout: '70%',
      responsive: true
    }
  });
</script>
@endif
@endpush --}}