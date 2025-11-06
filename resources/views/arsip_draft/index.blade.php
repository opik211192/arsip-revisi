@extends('adminlte::page')

@section('title', 'Data Arsip Draft')

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
    <div class="card-header bg-gradient-dark text-white">
        <span><i class="fas fa-archive"></i> Data Arsip Draft</span>
        <a href="{{ route('arsip_draft.create') }}" class="btn btn-primary btn-sm float-right">
            <i class="fas fa-plus"></i> Tambah Arsip
        </a>
    </div>
    <div class="card-body">
        <table class="table table-bordered table-striped table-sm w-100" id="table-datatable">
            <thead>
                @if (Auth::user()->hasRole('super admin') || Auth::user()->hasRole('admin'))
                <tr>
                    <th>#</th>
                    <th>Uraian Arsip</th>
                    <th>Tahun</th>
                    <th>No Box</th>
                    <th>No Berkas</th>
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
                    <th>Tahun</th>
                    <th>No Box</th>
                    <th>No Berkas</th>
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

@endsection

@push('scripts')
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.12.1/js/dataTables.bootstrap4.min.js"></script>

<script>
    $(function () {
    let isAdmin = @json(Auth::user()->hasRole('super admin') || Auth::user()->hasRole('admin'));

    $('#table-datatable').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ route('arsip_draft.index') }}", // ✅ sesuai controller index()
        columns: isAdmin ? [
            { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
            { data: 'uraian_arsip', name: 'uraian_arsip' },
            { data: 'tahun', name: 'tahun' },
            { data: 'no_box', name: 'no_box' },
            { data: 'no_berkas', name: 'no_berkas' },
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
            { data: 'tahun', name: 'tahun' },
            { data: 'no_box', name: 'no_box' },
            { data: 'no_berkas', name: 'no_berkas' },
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
            paginate: {
                first: "Awal",
                last: "Akhir",
                next: "›",
                previous: "‹"
            },
            zeroRecords: "Tidak ada data.",
            infoEmpty: "Tidak ada data tersedia."
        }
    });
});

    // === Hapus Arsip Draft ===
    $(document).on('click', '.btn-delete', function (e) {
        e.preventDefault();

        const id = $(this).data('id');
        const row = $(this).closest('tr');
        const uraian = row.find('td:eq(1)').text().trim();
        const tahun = row.find('td:eq(2)').text().trim();

        if (!confirm(`🗑️ Yakin ingin menghapus arsip:\n"${uraian} ${tahun}" ?\nSemua file terkait juga akan dihapus!`)) {
            return;
        }

        $.ajax({
            url: `/arsip-draft/${id}`,
            type: 'DELETE',
            data: { _token: "{{ csrf_token() }}" },
            beforeSend: function () {
                row.css('opacity', '0.5');
            },
            success: function (res) {
                if (res.success) {
                    $('#table-datatable').DataTable().ajax.reload(null, false);
                } else {
                    alert('Gagal: ' + res.message);
                }
            },
            error: function (xhr) {
                alert('❌ Terjadi kesalahan: ' + (xhr.responseJSON?.message ?? 'Server error'));
            },
            complete: function () {
                row.css('opacity', '1');
            }
        });
    });



</script>
@endpush