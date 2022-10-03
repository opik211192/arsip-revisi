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
        <div class="card card-info">
          <div class="card-header">Data User</div>
          <div class="card-body">
            <table class="table table-striped">
              <tbody>
                <tr>
                  <td><strong>Nama User</strong></td>
                  <td>:</td>
                  <td>{{ Auth::user()->name; }}</td>
                </tr>
                <tr>
                  <td><strong>Role</strong></td>
                  <td>:</td>
                  <td>{{ implode(', ', Auth::user()->getRoleNames()->toArray()); }}</td>
                </tr>
                <tr>
                  <td><strong>Struktural</strong></td>
                  <td>:</td>
                  <td>{{ Auth::user()->struktural->name; }}</td>
                </tr>
                <tr>
                  <td></td>
                  <td></td>
                  <td>{{ Auth::user()->struktural_detail->name; }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
      <div class="col">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>{{ $allArsip }}</h3>
            <p>Data Arsip Balai</p>
          </div>
          <div class="icon">
            <i class="fas fa-solid fa-book"></i>
          </div>
          <div class="small-box-footer"></div>
        </div>
      </div>
    </div>

    <div class="row mt-2">
      <div class="col-12">
        <div class="row">
          <div class="col-4">
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{ $countArsip[1]->jumlah }}</h3>
                <p>Data Arsip Bagian Umum & Tata Usaha</p>
              </div>
              <div class="icon">
                <i class="fas fa-solid fa-book"></i>
              </div>
              <div class="small-box-footer"></div>
            </div>
          </div>
          <div class="col-4">
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3>{{ $countArsip[2]->jumlah }}</h3>
                <p>Data Arsip Bidang KPISDA</p>
              </div>
              <div class="icon">
                <i class="fas fa-solid fa-book"></i>
              </div>
              <div class="small-box-footer"></div>
            </div>
          </div>
          <div class="col-4">
            <div class="small-box bg-success">
              <div class="inner">
                <h3>{{ $countArsip[3]->jumlah }}</h3>
                <p>Data Arsip Bidang Pelaksanaann</p>
              </div>
              <div class="icon">
                <i class="fas fa-solid fa-book"></i>
              </div>
              <div class="small-box-footer"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-2">
      <div class="col-12">
        <div class="row">
          <div class="col-4">
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{ $countArsip[4]->jumlah }}</h3>
                <p>Data Arsip Bidang OP</p>
              </div>
              <div class="icon">
                <i class="fas fa-solid fa-book"></i>
              </div>
              <div class="small-box-footer"></div>
            </div>
          </div>
          <div class="col-4">
            <div class="small-box bg-danger">
              <div class="inner">
                <h3>{{ $countArsip[5]->jumlah }}</h3>
                <p>Data Arsip Satker BBWS Citanduy</p>
              </div>
              <div class="icon">
                <i class="fas fa-solid fa-book"></i>
              </div>
              <div class="small-box-footer"></div>
            </div>
          </div>
          <div class="col-4">
            <div class="small-box bg-warning">
              <div class="inner">
                <h3>{{ $countArsip[6]->jumlah }}</h3>
                <p>Data Arsip Satker OP SDA Citanduy</p>
              </div>
              <div class="icon">
                <i class="fas fa-solid fa-book"></i>
              </div>
              <div class="small-box-footer"></div>
            </div>
          </div>
        </div>
      </div>
    </div>
    <div class="row mt-2">
      <div class="col-12">
        <div class="row">
          <div class="col-4">
            <div class="small-box bg-primary">
              <div class="inner">
                <h3>{{ $countArsip[7]->jumlah }}</h3>
                <p>Data Arsip SNVT PJSA Citanduy</p>
              </div>
              <div class="icon">
                <i class="fas fa-solid fa-book"></i>
              </div>
              <div class="small-box-footer"></div>
            </div>
          </div>
          <div class="col-4">
            <div class="small-box bg-secondary">
              <div class="inner">
                <h3>{{ $countArsip[8]->jumlah }}</h3>
                <p>Data Arsip SNVT Pembangunan Bendungan</p>
              </div>
              <div class="icon">
                <i class="fas fa-solid fa-book"></i>
              </div>
              <div class="small-box-footer"></div>
            </div>
          </div>
          <div class="col-4">
          </div>
        </div>
      </div>
    </div>

  </div>
  @stop