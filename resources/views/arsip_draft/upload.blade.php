@extends('adminlte::page')

@section('title', 'Upload File Arsip Draft')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/4.5.2/css/bootstrap.css" rel="stylesheet">
<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap4.min.css" rel="stylesheet">
<style>
    .progress {
        height: 20px;
        margin-top: 10px;
    }

    .progress-bar {
        width: 0%;
        background-color: #28a745;
    }
</style>
@endsection

@section('content')
<div class="card">
    <div class="card-header bg-gradient-dark text-white">
        <i class="fas fa-file-upload"></i> Upload File Arsip Draft
        <a href="{{ route('arsip_draft.index') }}" class="btn btn-secondary btn-sm float-right">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card-body">
        {{-- 🔹 Info Arsip --}}
        <div class="mb-4">
            <table class="table table-sm table-bordered">
                <tr>
                    <th width="25%">Uraian Arsip</th>
                    <td>{{ $arsipDraft->uraian_arsip }}</td>
                </tr>
                <tr>
                    <th>Tahun</th>
                    <td>{{ $arsipDraft->tahun }}</td>
                </tr>
                <tr>
                    <th>Jenis</th>
                    <td>{{ optional($arsipDraft->jenis)->name }}</td>
                </tr>
                <tr>
                    <th>Pencipta Arsip</th>
                    <td>{{ optional($arsipDraft->struktural_detail)->name }}</td>
                </tr>
            </table>
        </div>

        {{-- 🔹 Form Upload --}}
        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <div class="form-row align-items-start mb-3">
                <div class="col-md-4">
                    <label>File Arsip</label>
                    <input type="file" name="file_arsip" id="file_arsip" class="form-control"
                        accept=".pdf,.doc,.docx,.xls,.xlsx" required>
                </div>
                <div class="col-md-2">
                    <label>No Item</label>
                    <input type="text" name="no_item" class="form-control" placeholder="No Item">
                </div>
                <div class="col-md-4">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control" rows="2" placeholder="Keterangan File"></textarea>
                </div>
                <div class="col-md-2 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary btn-sm w-100">
                        <i class="fas fa-upload"></i> Upload
                    </button>
                </div>
            </div>

            {{-- 🔹 Progress Bar --}}
            <div class="progress d-none">
                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar">0%</div>
            </div>
        </form>

        <hr>

        {{-- 🔹 Daftar File --}}
        <h6><i class="fas fa-list"></i> Daftar File yang Telah Diupload</h6>
        <table class="table table-bordered table-striped table-sm mt-2" id="fileList">
            <thead class="table-dark">
                <tr>
                    <th>No Item</th>
                    <th>Nama File</th>
                    <th>Keterangan</th>
                    <th>Diunggah Pada</th>
                    <th width="15%">Aksi</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>
<script>
    $(function () {
    // DataTable init
    let table = $('#fileList').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        order: [[0, 'asc']],
        columns: [
            { data: 'no_item' },
            { data: 'file_name' },
            { data: 'keterangan' },
            { data: 'uploaded_at' },
            { data: 'actions', orderable: false, searchable: false }
        ]
    });

    function loadFiles() {
        $.get("{{ route('arsip_draft.getUploads', $arsipDraft->id) }}", function (res) {
            table.clear();
            if (res.files) {
                res.files.forEach(file => {
                    table.row.add({
                        no_item: file.no_item ?? '-',
                        file_name: file.file_name,
                        keterangan: file.keterangan ?? '-',
                        uploaded_at: file.uploaded_at,
                        actions: `<a href="${file.download_url}" class="btn btn-sm btn-success"><i class="fas fa-download"></i></a>
                                  <button class="btn btn-sm btn-danger delete-upload" data-id="${file.id}"><i class="fas fa-trash"></i></button>`
                    });
                });
            }
            table.draw();
        });
    }

    loadFiles();

    // Upload file ke tmp + progress bar
    $('#uploadForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        const progress = $('.progress');
        const progressBar = $('.progress-bar');

        $.ajax({
            xhr: function () {
                let xhr = new window.XMLHttpRequest();
                xhr.upload.addEventListener("progress", function (e) {
                    if (e.lengthComputable) {
                        let percent = Math.round((e.loaded / e.total) * 100);
                        progress.removeClass('d-none');
                        progressBar.css('width', percent + '%').text(percent + '%');
                    }
                }, false);
                return xhr;
            },
            url: "{{ route('arsip_draft.storeUpload', $arsipDraft->id) }}", // sesuai name prefix
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            beforeSend: function () {
                progress.removeClass('d-none');
                progressBar.css('width', '0%').text('0%');
            },
            success: function (res) {
                if (res.success) {
                    loadFiles();
                    $('#uploadForm')[0].reset();
                    progress.addClass('d-none');
                    $('<div class="alert alert-success mt-2">✅ File sementara berhasil diupload!</div>')
                        .insertBefore('#fileList').delay(2500).fadeOut('slow');
                }
            },
            error: function () {
                alert('Upload gagal.');
            },
            complete: function () {
                progressBar.css('width', '0%').text('0%');
            }
        });
    });

    // Hapus file
    $(document).on('click', '.delete-upload', function () {
        if (!confirm('Yakin ingin menghapus file ini?')) return;
        const id = $(this).data('id');
        $.ajax({
            url: "{{ url('/arsip-draft/upload') }}/" + id,
            type: 'DELETE',
            data: { _token: "{{ csrf_token() }}" },
            success: () => loadFiles(),
            error: () => alert('Gagal menghapus file.')
        });
    });
});
</script>
@endpush