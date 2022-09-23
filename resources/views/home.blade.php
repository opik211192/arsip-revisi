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
      <div class="col-lg-3 col-6">
        <div class="small-box bg-info">
          <div class="inner">
            <h3>150</h3>
            <p>New Orders</p>
          </div>
          <div class="icon">
            <i class="ion ion-bag"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-6">
        <div class="small-box bg-success">
          <div class="inner">
            <h3>53<sup style="font-size: 20px">%</sup></h3>
            <p>Bounce Rate</p>
          </div>
          <div class="icon">
            <i class="ion ion-stats-bars"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-6">
        <div class="small-box bg-warning">
          <div class="inner">
            <h3>44</h3>
            <p>User Registrations</p>
          </div>
          <div class="icon">
            <i class="ion ion-person-add"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
        </div>
      </div>

      <div class="col-lg-3 col-6">
        <div class="small-box bg-danger">
          <div class="inner">
            <h3>65</h3>
            <p>Unique Visitors</p>
          </div>
          <div class="icon">
            <i class="ion ion-pie-graph"></i>
          </div>
          <a href="#" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
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