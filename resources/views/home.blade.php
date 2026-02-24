@extends('adminlte::page')

@section('title', 'Dashboard')

@push('css')
<style>
  body {
    background: linear-gradient(135deg, #f7f9fc, #eef2f7);
  }

  .fade-in {
    animation: fadeIn .6s ease-in-out;
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
    border-radius: 14px;
    box-shadow: 0 4px 10px rgba(0, 0, 0, .12);
  }

  .bg-gradient-lightblue {
    background: linear-gradient(135deg, #4facfe, #00f2fe);
  }

  .bg-gradient-dark {
    background: linear-gradient(135deg, #343a40, #1d2124);
  }

  .badge-total {
    background: linear-gradient(135deg, #007bff, #00b894);
    color: #fff;
    padding: 4px 10px;
    border-radius: 8px;
  }
</style>
@endpush

@section('content')
<div class="fade-in">
  <div class="card shadow-sm border-0">
    <div class="card-header bg-gradient-dark text-white">
      <i class="fas fa-tachometer-alt me-2"></i> Dashboard
    </div>

    <div class="card-body">
      <div class="row g-4">

        {{-- =======================
        TABEL (SEMUA ROLE)
        ======================= --}}
        <div class="col-md-8">
          <div class="card h-100">
            <div class="card-header bg-gradient-dark text-white">
              <i class="fas fa-folder-open me-2"></i>
              Jumlah Arsip per Unit Kerja
            </div>

            <div class="card-body" style="max-height:500px;overflow:auto">
              @forelse ($strukturals as $unitKerja => $details)
              @php $total = collect($details)->sum('jumlah'); @endphp

              <div class="mb-4 border-bottom pb-3">
                <h6 class="fw-bold d-flex justify-content-between">
                  <span>
                    <i class="fas fa-building me-2"></i>{{ $unitKerja }}
                  </span>
                  <span class="badge-total">Total: {{ $total }}</span>
                </h6>

                <table class="table table-sm">
                  <thead>
                    <tr>
                      <th>No</th>
                      <th>Detail</th>
                      <th class="text-end">Jumlah</th>
                      <th class="text-center">Update</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($details as $d)
                    @if ($d->detail_name)
                    <tr>
                      <td>{{ $loop->iteration }}</td>
                      <td class="fw-semibold text-primary">{{ $d->detail_name }}</td>
                      <td class="text-end fw-bold">{{ $d->jumlah }}</td>
                      <td class="text-center">
                        @if ($d->terakhir_input)
                        {{ \Carbon\Carbon::parse($d->terakhir_input)->diffForHumans() }}
                        @else
                        <span class="text-danger">Belum ada</span>
                        @endif
                      </td>
                    </tr>
                    @endif
                    @endforeach
                  </tbody>
                </table>
              </div>
              @empty
              <p class="text-center text-muted">Belum ada data</p>
              @endforelse
            </div>
          </div>
        </div>

        {{-- =======================
        PANEL KANAN
        ======================= --}}
        <div class="col-md-4">

          @if (Auth::user()->hasAnyRole(['admin','super admin']))
          <div class="small-box bg-gradient-lightblue text-white mb-3">
            <div class="inner text-center">
              <h3>{{ $allArsip }}</h3>
              <p>Total Seluruh Arsip</p>
            </div>
          </div>
          @else
          <div class="small-box bg-gradient-lightblue text-white mb-3">
            <div class="inner text-center">
              <h3>{{ $userTotalArsip }}</h3>
              <p>Total Arsip Anda</p>
            </div>
          </div>
          @endif

          <div class="card text-center p-3">
            <i class="fas fa-user-circle fa-2x text-primary mb-2"></i>
            <strong>{{ Auth::user()->name }}
              @if(Auth::user()->hasAnyRole(['admin','super admin']))
              ({{ Auth::user()->roles->pluck('name')->implode(', ') }})
              @endif
            </strong><br>
            <small class="text-muted">
              {{ Auth::user()->struktural_detail->name ?? '-' }}
            </small>
          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@stop