@extends('layouts.front')

@section('adminlte')
<link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}">
@endsection

@section('content')


<?php 

$random = array('bg-primary', 'bg-dark', 'bg-warning', 'bg-danger', 'bg-secondary');
$data_random = array_rand($random);
//echo $random[$data_random];
 ?>

<div class="container">
  <div class="row justify-content-center">
    <div class="col-md-10">
      <div class="card">
        <div class="card-header bg-secondary">{{ __('Home') }}</div>

        <div class="card-body mt-2">
          @if (session('status'))
          <div class="alert alert-success" role="alert">
            {{ session('status') }}
          </div>
          @endif

          {{-- {{ __('You are logged in!') }} --}}
          <div class="row ml-2">
            {{-- <table class="table table-hover">
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Struktural</th>
                <th>Jumlah File</th>
              </tr>
              @foreach ($users as $index => $user)
              <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->struktur->struktural_detail->name }}</td>
                <td>{{ $user->arsips_count }}</td>
              </tr>
              @endforeach
            </table>


            <br> --}}
            @foreach ($users as $index => $user)
            <div class="col-md-4">
              <div class="card bg-light mr-2 border border-primary" style="max-width: 18rem;">
                <div class="card-header bg-primary"><b>{{ $user->struktur->struktural_detail->name }}</b></div>
                <div class="card-body">
                  <h5 class="card-title">
                    <h1 class="text-center"><b>{{ $user->arsips_count }}</b></h1>
                  </h5>
                </div>
                <div class="card-footer bg-transparent border-primary">
                  <div class="icon">
                    <i class="icon fa fa-book"></i>
                    <b>Data Arsip</b>
                  </div>
                </div>
              </div>

            </div>
            @endforeach



            {{-- <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-primary">
                <div class="inner">
                  <h3>150</h3>

                  <p>New Orders</p>
                </div>
                <div class="icon">
                  <i class="icon fa fa-book"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-success">
                <div class="inner">
                  <h3>150</h3>

                  <p>New Orders</p>
                </div>
                <div class="icon">
                  <i class="icon fa fa-book"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div>

            <div class="col-lg-4 col-6">
              <!-- small box -->
              <div class="small-box bg-warning">
                <div class="inner">
                  <h3>150</h3>

                  <p>New Orders</p>
                </div>
                <div class="icon">
                  <i class="icon fa fa-book"></i>
                </div>
                <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
              </div>
            </div> --}}


          </div>

        </div>
      </div>
    </div>
  </div>
</div>
@endsection