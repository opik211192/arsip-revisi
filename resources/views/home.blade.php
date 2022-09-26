@extends('adminlte::page')

@section('title', 'Home')

@section('content_header')
<div></div>
@stop

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script>
  $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select Permissions"
            });
        });

 $('#data').change(function(){
    var label=$('#data :selected').parent().attr('label');
    var sub = $('#data').find(":selected").val();
    console.log(sub);
    console.log(label);
 })
</script>

@endpush

@section('content')
<div class="card">
  <div class="card-header bg-dark">Dashboard</div>
  <div class="card-body">
    <div class="row">
      <div class="col">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>150</h3>
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
            <h3>44</h3>
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
            <table class="table">
              <thead>
                <tr>
                  <td><strong>Struktural</strong></td>
                  <td></td>
                  <td></td>
                  <td><strong>Jumlah Arsip</strong></td>
                </tr>
              </thead>
              <tbody>
                @foreach ($arsips as $arsip)
                <tr>
                  <td>{{ $arsip->name }}</td>
                  <td>{{ $arsip->struktural }}</td>
                  <td>:</td>
                  <td>
                    <h5>
                      <span class="badge badge-primary right">{{ $arsip->jml }}</span>
                    </h5>
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


  {{-- <div class="form-group col-md-6">
    <label for="data">data</label>
    <select name="data" id="data" class="form-control select2">
      @foreach ($models as $model => $strukturals)
      <optgroup id="opt" label="{{ $model }}">
        @foreach ($strukturals as $s)
        <option value="{{ $s->id }}">{{ $s->name }}</option>
        @endforeach
      </optgroup>
      @endforeach
    </select>
    @error('permissions')
    <div class="text-danger mt-2 d-block">{{ $message }}</div>
    @enderror
  </div>
</div> --}}
@stop