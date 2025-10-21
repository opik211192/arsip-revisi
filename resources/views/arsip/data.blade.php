@extends('adminlte::page')

@section('title', 'Data Arsip')

@section('styles2')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css">
<link rel="stylesheet" href="https://cdn.datatables.net/1.12.1/css/dataTables.bootstrap4.min.css">

<style>
    .card {
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
    }

    .card-header {
        font-weight: 600;
        letter-spacing: .5px;
    }

    table.dataTable thead th {
        background-color: #343a40;
        color: #fff;
        text-align: center;
        vertical-align: middle;
    }

    table.dataTable tbody td {
        vertical-align: middle;
    }

    table.dataTable tbody tr:hover {
        background-color: #f8f9fa;
    }

    .badge {
        font-size: 0.85rem;
        padding: .4em .6em;
    }

    .btn-action {
        padding: 3px 8px;
        font-size: 0.8rem;
    }
</style>
@endsection

@section('content')

@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="card">
    <div class="card-header bg-gradient-dark text-white">Data Arsip</div>
    <div class="card-body">
        <table class="table table-bordered table-striped table-sm w-100" id="table-datatable">
            <thead>
                @if (Auth::user()->roles->pluck('name')->contains('super admin') ||
                Auth::user()->roles->pluck('name')->contains('admin'))
                <tr>
                    <th>#</th>
                    <th>Urian Arsip</th>
                    <th>No Berkas</th>
                    <th>No Box</th>
                    <th>Jenis</th>
                    <th>Jenis Arsip</th>
                    <th>Pencipta</th>
                    <th>Dibuat Oleh</th>
                    <th>Diperbarui Oleh</th>
                    <th>Status</th>
                    <th>Terakhir Diperbarui</th>
                    <th>Aksi</th>
                </tr>
                @else
                <tr>
                    <th>#</th>
                    <th>Uraian Arsip</th>
                    <th>No Berkas</th>
                    <th>No Box</th>
                    <th>Jenis</th>
                    <th>Jenis Arsip</th>
                    <th>Status</th>
                    <th>Terakhir Diperbarui</th>
                    <th>Aksi</th>
                </tr>
                @endif
            </thead>
        </table>
    </div>
</div>

{{-- Modal persetujuan hanya muncul untuk admin --}}
@if (Auth::user()->roles->pluck('name')->contains('super admin') ||
Auth::user()->roles->pluck('name')->contains('admin'))
<div class="modal fade" id="modalForm" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form name="frm_edit" id="frm_edit" class="form-horizontal">
            <input type="hidden" name="arsip_id" id="arsip_id">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Form Persetujuan Arsip</h5>
                    <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
                </div>
                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" value="0">
                        <label class="form-check-label">Menunggu Konfirmasi</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" value="1">
                        <label class="form-check-label">Disetujui</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" value="2">
                        <label class="form-check-label">Koreksi</label>
                    </div>
                    <textarea class="form-control mt-2" name="keterangan" cols="10" rows="2"
                        placeholder="Keterangan Koreksi..." required></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="saveBtn">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endif
@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(function () {
    let isAdmin = @json(Auth::user()->roles->pluck('name')->contains('super admin') ||
                        Auth::user()->roles->pluck('name')->contains('admin'));

    $('#table-datatable').DataTable({
        searchDelay: 1000,
        processing: false,
        serverSide: true,
        ajax: "{{ route('arsip.data') }}",
        columns: isAdmin ? [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'uraian_arsip', name: 'uraian_arsip' },
            { data: 'no_berkas', name: 'no_berkas' },
            { data: 'no_box', name: 'no_box' },
            { data: 'jenis', name: 'jenis' },
            { data: 'jenis_arsip', name: 'jenis_arsip' },
            { data: 'pencipta', name: 'pencipta' },
            { data: 'created_by', name: 'created_by' },
            { data: 'updated_by', name: 'updated_by' },
            {
                data: 'status', name: 'status', render: function (data, type, row) {
                    if (row.status == 0)
                        return '<small class="badge badge-warning">Menunggu Konfirmasi</small>';
                    else if (row.status == 1)
                        return '<small class="badge badge-success">Disetujui</small>';
                    else if (row.status == 2)
                        return '<small class="badge badge-danger">Koreksi</small>';
                    else
                        return '<div class="text-center">-</div>';
                }
            },
            { data: 'updated_at', name: 'updated_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ] : [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'uraian_arsip', name: 'uraian_arsip' },
            { data: 'no_berkas', name: 'no_berkas' },
            { data: 'no_box', name: 'no_box' },
            { data: 'jenis', name: 'jenis' },
            { data: 'jenis_arsip', name: 'jenis_arsip' },
            {
                data: 'status', name: 'status', render: function (data, type, row) {
                    if (row.status == 0)
                        return '<small class="badge badge-warning">Menunggu Konfirmasi</small>';
                    else if (row.status == 1)
                        return '<small class="badge badge-success">Disetujui</small>';
                    else if (row.status == 2)
                        return '<small class="badge badge-danger">Koreksi</small>';
                    else
                        return '<div class="text-center">-</div>';
                }
            },
            { data: 'updated_at', name: 'updated_at' },
            { data: 'action', name: 'action', orderable: false, searchable: false },
        ],
        language: {
            search: "Cari:",
            lengthMenu: "Tampilkan _MENU_ data",
            info: "Menampilkan _START_ - _END_ dari _TOTAL_ data",
            paginate: { first: "Awal", last: "Akhir", next: "›", previous: "‹" },
            zeroRecords: "Tidak ada data.",
            infoEmpty: "Tidak ada data tersedia."
        }
    });
    
});
</script>
@endpush