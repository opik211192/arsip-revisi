@extends('adminlte::page')

@section('title', 'Data Arsip')

@section('styles2')
<link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
@endsection


@section('content')
@if (Auth::user()->roles->pluck('name')->contains('super admin') ||
Auth::user()->roles->pluck('name')->contains('admin'))
<div class="card">
    <div class="card-header bg-dark">Data Arsip</div>
    <div class="card-body">
        <table class="table table-bordered table-striped table-sm" id="table-datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No. Berkas</th>
                    <th>Tahun</th>
                    <th>Jenis</th>
                    <th>Lokasi</th>
                    <th>Pencipta Arsip</th>
                    <th>Pengunggah</th>
                    <th>Act</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection


@push('scripts')
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" language="javascript"
    src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript"
    src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
    $(function () {
      
      var table = $('#table-datatable').DataTable({
          processing: true,
          serverSide: true,
          ajax: "{{ route('arsip.data') }}",
          columns: [
              {data: 'id', name: 'id'},
              {data: 'no_berkas', name: 'no_berkas'},
              {data: 'tahun', name: 'tahun'},
              {data: 'jenis.name', name: 'jenis.name'},
              {data: 'lokasi_arsip', name: 'lokasi_arsip'},
              {data: 'struktural_detail.name'},
              {data: 'user.name', name: 'user.name'},
              {
                  data: 'action', 
                  name: 'action', 
                  orderable: false, 
                  searchable: false
              },
          ]
      });    
    });
</script>
@endpush
@else
<div class="card">
    <div class="card-header text-white" style="background-color: slategray">Data Arsip</div>
    <div class="card-body">
        <table class="table table-striped table-sm" id="table-datatable">
            <thead>
                <tr>
                    <th>#</th>
                    <th>No. Berkas</th>
                    <th>Tahun</th>
                    <th>Jenis</th>
                    <th>Lokasi</th>
                    <th>Act</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
@endsection


@push('scripts')
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script type="text/javascript" language="javascript"
    src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script type="text/javascript" language="javascript"
    src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>

<script type="text/javascript">
    $(function () {
  
  var table = $('#table-datatable').DataTable({
      processing: true,
      serverSide: true,
      ajax: "{{ route('arsip.data') }}",
      columns: [
          {data: 'id', name: 'id'},
          {data: 'no_berkas', name: 'no_berkas'},
          {data: 'tahun', name: 'tahun'},
          {data: 'jenis.name', name: 'jenis.name'},
          {data: 'lokasi_arsip', name: 'lokasi_arsip'},
          
          {
              data: 'action', 
              name: 'action', 
              orderable: true, 
              searchable: true
          },
      ]
  });    
});
</script>
@endpush
@endif