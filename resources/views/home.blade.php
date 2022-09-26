@extends('adminlte::page')

@section('title', 'Home')

@section('content_header')
<div></div>
@stop

@section('content')
<div class="card">
  <div class="card-header bg-dark">Dashboard</div>
  <div class="card-body">
    <div class="row">
      <div class="col">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ $allArsip }}</h3>
            <p>Data Arsip</p>
          </div>
          <div class="icon">
            <i class="fas fa-solid fa-book"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
      <div class="col">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>{{ $allUser }}</h3>
            <p>Data User</p>
          </div>
          <div class="icon">
            <i class="fas fa-solid fa-user"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>
    </div>
    <div class="row mt-2">
      <div class="col-md-12">
        <div class="card card-primary">
          <div class="card-header">Data Arsip Detail</div>
          <div class="card-body">
            <table class="table table-sm">
              <thead>
                <tr>
                  <td><strong>No</strong></td>
                  <td><strong>Struktural</strong></td>
                  <td></td>
                  <td><strong>Jumlah Arsip</strong></td>
                </tr>
              </thead>
              <tbody>
                @foreach ($arsips as $index => $arsip)
                <tr>
                  <td>{{ $arsips->firstItem() + $loop->iteration - 1 }}</td>
                  <td><strong>{{ $arsip->struktural_detail }}</strong> ({{ $arsip->struktural }})</td>
                  <td>:</td>
                  <td>
                    <h5>
                      @if ($arsip->jml == 0)
                      <span class="badge badge-danger right">{{ $arsip->jml }}</span>
                      @else
                      <span class="badge badge-info right">{{ $arsip->jml }}</span>
                      @endif
                    </h5>
                  </td>
                </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <div class="row justify-content-center">
            {{ $arsips->links() }}
          </div>
        </div>
      </div>
    </div>
  </div>
  @stop