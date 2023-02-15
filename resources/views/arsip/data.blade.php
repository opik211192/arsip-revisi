@extends('adminlte::page')

@section('title', 'Data Arsip')

@section('styles2')
<link rel="stylesheet" type="text/css"
    href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">
<script type="text/javascript" language="javascript" src="https://code.jquery.com/jquery-3.5.1.js"></script>
@endsection

@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

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
                    <th>Tgl. Unggah</th>
                    <th>Status</th>
                    <th>Act</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form name="frm_edit" id="frm_edit" class="form-horizontal">
            <input type="hidden" name="arsip_id" id="arsip_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Form Persetujuan Arsip</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status" value="0">
                        <label for="status1" class="form-check-label">Menunggu Konfirmasi</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status" value="1">
                        <label for="status2" class="form-check-label">Disetujui</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status" value="2">
                        <label for="status3" class="form-check-label">Koreksi</label>
                    </div>
                    <div>
                        <textarea class="form-control mt-2" name="keterangan" id="keterangan" cols="10" rows="2"
                            placeholder="Keterangan Koreksi..." required></textarea>
                    </div>
                    <div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary" id="saveBtn">Save changes
                    </div>
                </div>
        </form>
    </div>
</div>
@endsection


@push('scripts')
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
              {data: 'created_at', name: 'created_at'},
              {data: 'status', 
                'render' : function (data, type, row) {  
                    if (row.status == 0) {
                        return `<small class="badge badge-warning">Menunggu Konfirmasi</small>`
                    }else if(row.status == 1){
                        return `<small class="badge badge-success">Disetujui</small>`
                    }else if(row.status == 2){
                        return `<small class="badge badge-danger">Koreksi</small>`
                    }else{
                        return `<div class="text-center">-</div>`
                    }
                   
                   
                   
              }},
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

{{-- <script>
    $('body').on('click', '#approval', function () {
        var id = $(this).data('id');
        //fetch detail post with ajax
        console.log();
         $.ajax({
            url: `approval/${id}`,
            type: "GET",
            cache: false,
            success:function(response){
                
                //$('input[name=status]:checked').val(response.status);
                $('input[name^="status"][value="'+response.status+'"').prop('checked',true);


                $('#modalForm').modal('show');
                console.log(response);
            }
         });
     });
</script> --}}
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
                    <th>Status</th>
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
          {data: 'status', 
            'render' : function (data, type, row) {  
                if (row.status == 0) {
                    return `<small class="badge badge-warning">Menunggu Konfirmasi</small>`
                }else if(row.status == 1){
                    return `<small class="badge badge-success">Disetujui</small>`
                }else if(row.status == 2){
                    return `<small class="badge badge-danger">Koreksi</small>`
                }else{
                    return `<div class="text-center">-</div>`
                }
            }},
          
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