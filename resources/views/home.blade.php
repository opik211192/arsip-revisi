@extends('adminlte::page')

@section('title', 'Dashboard')

@push('css')
<style>
  :root {
    --color-primary: #007bff;
    --color-secondary: #343a40;
    --color-light: #f8f9fa;
  }

  /* Efek hover list */
  .list-item-hover:hover {
    background-color: var(--color-light) !important;
    transition: background 0.25s ease-in-out;
  }

  /* Scrollbar */
  .card-body::-webkit-scrollbar {
    width: 6px;
  }

  .card-body::-webkit-scrollbar-thumb {
    background-color: rgba(0, 0, 0, 0.2);
    border-radius: 4px;
  }

  .card-body::-webkit-scrollbar-thumb:hover {
    background-color: rgba(0, 0, 0, 0.4);
  }

  /* Badge gradasi */
  .badge-gradient {
    background: linear-gradient(135deg, #007bff, #00b894);
    font-size: 0.85rem;
    font-weight: 600;
    color: #fff;
    padding: 4px 10px;
    border-radius: 8px;
    box-shadow: 0 1px 4px rgba(0, 0, 0, 0.15);
  }

  /* Small box modern */
  .small-box {
    border-radius: 10px !important;
    box-shadow: 0 3px 10px rgba(0, 0, 0, 0.12);
    transition: all 0.2s ease-in-out;
    overflow: hidden;
  }

  .small-box:hover {
    transform: translateY(-3px);
    box-shadow: 0 5px 14px rgba(0, 0, 0, 0.2);
  }

  .small-box .inner {
    padding: 20px;
  }

  .small-box .inner h3 {
    font-size: 2.2rem;
    font-weight: 700;
  }

  .small-box .inner p {
    font-size: 1rem;
    margin-top: 4px;
    color: rgba(255, 255, 255, 0.9);
  }

  .small-box .icon {
    top: 10px;
    right: 15px;
    opacity: 0.3;
  }

  /* Tabel user */
  .table-user td {
    vertical-align: middle;
    padding: 8px 10px;
  }

  .table-user strong {
    color: var(--color-secondary);
  }

  /* Card header style */
  .card-header {
    font-weight: 600;
    font-size: 1.05rem;
    border-bottom: none;
  }

  /* Gradient untuk header */
  .bg-gradient-primary {
    background: linear-gradient(135deg, #007bff, #0062cc);
  }

  /* Tampilan struktur list */
  .struktur-title {
    color: var(--color-secondary);
    font-weight: 600;
    border-left: 4px solid var(--color-primary);
    padding-left: 8px;
    margin-bottom: 6px;
  }
</style>
@endpush

@section('content_header')
<div></div>
@stop

@section('content')
<div class="card shadow-sm border-0">
  <div class="card-header bg-gradient-dark text-white">
    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
  </div>

  <div class="card-body">
    <div class="row g-4 mt-2">
      {{-- 🔹 Jumlah Arsip per Unit Kerja --}}
      <div class="col-md-8">
        <div class="card shadow-sm border-0 h-100">
          <div class="card-header bg-gradient-dark text-white d-flex align-items-center">

            <h5 class="mb-0">Jumlah Arsip Unit Kerja</h5>
          </div>

          <div class="card-body p-3" style="max-height: 450px; overflow-y: auto;">
            @foreach ($strukturals as $unitKerja => $details)
            @php
            $totalArsip = collect($details)->sum('jumlah');
            @endphp

            <div class="mb-4 border-bottom pb-3">
              <h6 class="fw-bold text-dark d-flex justify-content-between align-items-center mb-2">
                <span><i class="fas fa-folder me-1"></i> {{ $unitKerja }}</span>
                <span class="badge bg-gradient-lightblue">Total: {{ $totalArsip }}</span>
              </h6>

              <div class="table-responsive">
                <table class="table table-sm align-middle mb-0">
                  <thead class="table-light">
                    <tr>
                      <th style="width: 5%">No.</th>
                      <th>Detail</th>
                      <th class="text-end" style="width: 15%">Jumlah</th>
                      <th class="text-center" style="width: 25%">Update Terakhir</th>
                    </tr>
                  </thead>
                  <tbody>
                    @foreach ($details as $index => $detail)
                    @if ($detail->detail_name)
                    <tr>
                      <td class="text-center">{{ $loop->iteration }}</td>
                      <td class="text-primary">{{ $detail->detail_name }}</td>
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
            @endforeach
          </div>
        </div>
      </div>

      {{-- 🔹 Total Arsip Keseluruhan --}}
      <div class="col-md-4">
        <div class="small-box bg-gradient-lightblue">
          <div class="inner text-center">
            <h3 class="">{{ $allArsip }}</h3>
            <p>Total Seluruh Arsip</p>
          </div>
          <div class="icon">
            <i class="fas fa-book"></i>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

@stop