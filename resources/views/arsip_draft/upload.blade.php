@extends('adminlte::page')

@section('title', 'Upload File Arsip')

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

    /* --- Uploaded File Box --- */
    .uploaded-file {
        display: flex;
        align-items: center;
        gap: 8px;
        background: #f8f9fa;
        border: 1px solid #ddd;
        border-radius: 5px;
        padding: 8px 10px;
        height: 38px;
        /* sama tinggi dengan input bootstrap */
        overflow: hidden;
    }

    .uploaded-file span {
        flex: 1;
        font-size: 14px;
        color: #333;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
        /* 🔹 potong teks kalau kepanjangan */
    }

    .uploaded-file i.fas.fa-file-alt {
        color: #007bff;
        font-size: 16px;
    }

    .remove-upload {
        cursor: pointer;
        color: #dc3545;
        font-size: 18px;
        transition: color 0.2s ease;
    }

    .remove-upload:hover {
        color: #a71d2a;
    }

    /* --- Biar tombol simpan sejajar --- */
    #uploadForm .form-row>[class*='col-'] {
        display: flex;
        flex-direction: column;
        justify-content: flex-end;
    }

    /* 🔹 Biar teks info tampil rapi di desktop */
    .info-format {
        margin-left: 5px;
        display: block;
        margin-top: -5px;
        font-size: 13px;
        color: #6c757d;
    }

    /* 🔹 Tampilkan teks info tepat di bawah input file pada layar kecil */

    /* --- Responsive tweak --- */
    @media (max-width: 768px) {
        .uploaded-file {
            height: auto;
            flex-wrap: wrap;
        }

        .uploaded-file span {
            white-space: normal;
        }

        .info-format {
            margin-top: 10px;
            margin-left: 0;
            font-size: 12px;
            display: block;
        }
    }

    /* 🔹 Modern info box style */
    .info-section {
        background: #ffffff;
        border: 1px solid #e3e6f0;
        border-left: 4px solid #4e73df;
        /* garis aksen kiri */
        transition: all 0.3s ease;
    }

    table th {
        font-weight: 600;
        font-size: 14px;
        padding: 6px 8px;
    }

    table td {
        font-size: 14px;
        padding: 6px 8px;
    }

    .fw-semibold {
        font-weight: 600 !important;
    }

    .text-muted {
        color: #6c757d !important;
    }

    @media (max-width: 768px) {
        .info-section {
            border-left-width: 3px;
            padding: 1rem;
        }

        table th,
        table td {
            font-size: 13px;
        }
    }

    div.dataTables_processing {
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        background: rgba(255, 255, 255, 0.9);
        border: none;
        padding: 15px 30px;
        font-size: 15px;
        font-weight: 500;
        color: #007bff;
        border-radius: 8px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    }
</style>
@endsection

@section('content_header')
<div class="content-header-custom">
    <div class="d-flex flex-column align-items-start">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb custom-breadcrumb">
                <li class="breadcrumb-item">
                    <a href="{{ route('home') }}">
                        <i class="fas fa-home me-1"></i> Dashboard
                    </a>
                </li>
                <li class="breadcrumb-item">
                    <a href="{{ route('arsip_draft.index') }}"> <i class="fas fa-archive me-1"></i> Data Arsip</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"> Upload Arsip</li>
            </ol>
        </nav>
    </div>
</div>
@stop

@section('content')
<div class="card">
    <div class="card-header bg-gradient-dark text-white">
        <i class="fas fa-file-upload"></i> Upload File Arsip
        <a href="{{ route('arsip_draft.index') }}" class="btn btn-secondary btn-sm float-right">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    <div class="card-body">
        {{-- 🔹 Info Arsip --}}
        <div class="mb-4">
            <div class="info-section p-3 rounded shadow-sm">
                <div class="row">
                    {{-- Kolom Kiri --}}
                    <div class="col-md-6 mb-3 mb-md-0">
                        <table class="table table-borderless table-sm mb-0 align-middle">
                            <tr>
                                <th class="fw-semibold text-dark">Pencipta Arsip</th>
                                <td>{{ optional($arsipDraft->struktural_detail)->name }}</td>
                            </tr>
                            <tr>
                                <th width="40%" class="fw-semibold text-dark">Uraian Arsip</th>
                                <td class="">{{ $arsipDraft->uraian_arsip }}</td>
                            </tr>
                            <tr>
                                <th class="fw-semibold text-dark">Jenis Klasifikasi</th>
                                <td>{{ optional($arsipDraft->jenis)->name }}</td>
                            </tr>
                            <tr>
                                <th width="40%" class="fw-semibold text-dark">No. Boks</th>
                                <td>{{ $arsipDraft->no_box }}</td>
                            </tr>
                            <tr>
                                <th class="fw-semibold text-dark">No. Berkas</th>
                                <td>{{ $arsipDraft->no_berkas }}</td>
                            </tr>
                            <tr>
                                <th class="fw-semibold text-dark">Tahun</th>
                                <td>{{ $arsipDraft->tahun }}</td>
                            </tr>
                        </table>
                    </div>

                    {{-- Kolom Kanan --}}
                    <div class="col-md-6">
                        <table class="table table-borderless table-sm mb-0 align-middle">

                        </table>
                    </div>
                </div>
            </div>
        </div>
        <hr>

        {{-- 🔹 Form Upload --}}
        <form id="uploadForm" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="tmp_path" id="tmp_path">
            <div class="form-row align-items-end mb-3">
                <div class="col-md-4">
                    <label>File Arsip</label>
                    <div id="fileInputWrapper">
                        <input type="file" name="file_arsip" id="file_arsip" class="form-control form-control-sm"
                            accept="application/pdf">
                    </div>
                </div>

                <div class="col-md-2">
                    <label>No Item</label>
                    <input type="text" name="no_item" class="form-control form-control-sm" placeholder="No Item">
                </div>

                <div class="col-md-4">
                    <label>Keterangan</label>
                    <textarea name="keterangan" class="form-control form-control-sm" rows="1"
                        placeholder="Keterangan File"></textarea>
                </div>

                <div class="col-md-2">
                    <button type="submit" class="btn btn-info btn-sm w-100 ">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </div>

            {{-- 🔹 Info Format File --}}
            <div class="form-row">
                <div class="col-md-12">
                    <small class="text-muted info-format">
                        <i class="fas fa-info-circle"></i> File yang diupload harus berformat <strong>.pdf</strong>
                    </small>
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
<script src="https://cdnjs.cloudflare.com/ajax/libs/resumable.js/1.1.0/resumable.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap4.min.js"></script>

<script>
    // Fungsi kecil untuk aktifkan spinner di tombol
    function showButtonSpinner(button) {
        const icon = $(button).find("i");
        icon.data("old-class", icon.attr("class"));
        icon.attr("class", "fas fa-spinner fa-spin");
        $(button).prop("disabled", true);
    }

    // Fungsi untuk matikan spinner dan kembalikan ikon semula
    function hideButtonSpinner(button) {
        const icon = $(button).find("i");
        const oldClass = icon.data("old-class");
        if (oldClass) icon.attr("class", oldClass);
        $(button).prop("disabled", false);
    }

    $(function () {
    // === DataTable init ===
    let table = $('#fileList').DataTable({
        paging: true,
        searching: true,
        ordering: true,
        order: [[0, 'asc']],
        processing: true, // 🔹 aktifkan spinner bawaan DataTables
        language: {
        processing: '<i class="fas fa-spinner fa-spin text-primary"></i> Memuat data...',
        emptyTable: "Belum ada data arsip yang diupload."
        },
        columnDefs: [
            { width: "70px", targets: 0 },   // 🔹 kolom "No Item"
            { width: "30%", targets: 1 },    // Nama File (opsional)
            { width: "25%", targets: 2 },    // Keterangan (opsional)
            { width: "20%", targets: 3 },    // Diunggah Pada (opsional)
            { width: "15%", targets: 4 }     // Aksi
        ],
         columnDefs: [
            { width: "70px", targets: 0, className: "text-center" },
            { width: "30%", targets: 1 },
            { width: "25%", targets: 2 },
            { width: "20%", targets: 3, className: "text-center" },
            { width: "15%", targets: 4, className: "text-center" }
        ],
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
                        actions: `
                        <div class="d-flex justify-content-center gap-2">
                            <a href="${file.download_url}" class="btn btn-outline-success btn-sm mr-1 btn-download" title="Download File">
                                <i class="fas fa-download"></i>
                            </a>
                            <button class="btn btn-outline-danger btn-sm delete-upload" data-id="${file.id}" title="Hapus File">
                                <i class="fas fa-trash"></i>
                            </button>
                        </div>
                        `
                    });
                });
            }
            table.draw();
            table.processing(false);
        });
    }

    loadFiles();

    // === Upload logic ===
    let tmpPath = null;
    const fileInput = $('#file_arsip');
    const uploadButton = $('#uploadForm button[type="submit"]');
    uploadButton.prop('disabled', true);

    let resumable = new Resumable({
        target: "{{ route('arsip_draft.uploadTmp') }}",
        query: { _token: "{{ csrf_token() }}" },
        chunkSize: 5 * 1024 * 1024,
        simultaneousUploads: 1,
        testChunks: false,
        throttleProgressCallbacks: 1,
    });

    resumable.assignBrowse(fileInput[0]);

    resumable.on('fileAdded', function (file) {
        const fileExt = file.fileName.split('.').pop().toLowerCase();

        // ✅ Cek apakah file PDF
        if (fileExt !== 'pdf' || file.file.type !== 'application/pdf') {
            alert('❌ Hanya file PDF yang diperbolehkan!');
            resumable.removeFile(file); // batalkan upload
            return;
        }

        // kalau valid PDF, lanjut upload
        $('.progress').removeClass('d-none');
        fileInput.prop('disabled', true);
        uploadButton.prop('disabled', true);
        resumable.upload();
    });


    resumable.on('fileProgress', function (file) {
        let percent = Math.floor(file.progress() * 100);
        $('.progress-bar').css('width', percent + '%').text(percent + '%');
    });

    resumable.on('fileSuccess', function (file, response) {
        let res = JSON.parse(response);
        if (res.success) {
            tmpPath = res.tmp_path;
            $('#tmp_path').val(tmpPath);

            // tampilkan nama file + tombol X
            $('#fileInputWrapper').html(`
                <div class="uploaded-file">
                    <i class="fas fa-file-alt text-primary"></i>
                    <span>${res.file_name}</span>
                    <i class="fas fa-times remove-upload" title="Hapus file"></i>
                </div>
            `);

            uploadButton.prop('disabled', false);
            $('.progress').addClass('d-none');
        }
    });

    resumable.on('fileError', function (file, response) {
        alert('Upload gagal: ' + response);
        fileInput.prop('disabled', false);
        uploadButton.prop('disabled', true);
        $('.progress').addClass('d-none');
    });

    // ❌ Hapus file sementara
    $(document).on('click', '.remove-upload', function () {
        const tmp_path = $('#tmp_path').val();
        if (!tmp_path) return;

        if (!confirm('Hapus file sementara ini?')) return;

        $.post("{{ route('arsip_draft.deleteTmp') }}", { _token: "{{ csrf_token() }}", tmp_path: tmp_path }, function (res) {
            if (res.success) {
                $('#tmp_path').val('');
                tmpPath = null;
                $('#fileInputWrapper').html(`
                    <input type="file" name="file_arsip" id="file_arsip" class="form-control form-control-sm"
                        accept=".pdf,application/pdf">
                `);

                // 🔹 Re-init resumable baru biar event-nya aktif lagi
                resumable = new Resumable({
                    target: "{{ route('arsip_draft.uploadTmp') }}",
                    query: { _token: "{{ csrf_token() }}" },
                    chunkSize: 5 * 1024 * 1024,
                    simultaneousUploads: 1,
                    testChunks: false,
                    throttleProgressCallbacks: 1,
                });

                resumable.assignBrowse($('#file_arsip')[0]);
                bindResumableEvents();

                uploadButton.prop('disabled', true);
            }
        });
    });

    function bindResumableEvents() {
        resumable.on('fileAdded', function (file) {
            const fileExt = file.fileName.split('.').pop().toLowerCase();

            // ✅ Cek apakah file PDF
            if (fileExt !== 'pdf' || file.file.type !== 'application/pdf') {
                alert('❌ Hanya file PDF yang diperbolehkan!');
                resumable.removeFile(file); // batalkan upload
                return;
            }

            // Kalau valid PDF, lanjut upload
            $('.progress').removeClass('d-none');
            fileInput.prop('disabled', true);
            uploadButton.prop('disabled', true);
            resumable.upload();
        });

        resumable.on('fileProgress', function (file) {
            let percent = Math.floor(file.progress() * 100);
            $('.progress-bar').css('width', percent + '%').text(percent + '%');
        });

        resumable.on('fileSuccess', function (file, response) {
            let res = JSON.parse(response);
            if (res.success) {
                tmpPath = res.tmp_path;
                $('#tmp_path').val(tmpPath);

                $('#fileInputWrapper').html(`
                    <div class="uploaded-file">
                        <i class="fas fa-file-alt text-primary"></i>
                        <span>${res.file_name}</span>
                        <i class="fas fa-times remove-upload" title="Hapus file"></i>
                    </div>
                `);

                uploadButton.prop('disabled', false);
                $('.progress').addClass('d-none');
                // $('<div class="alert alert-success mt-2">✅ File berhasil diupload!</div>')
                //     .insertBefore('#fileList').delay(3000).fadeOut('slow');
            }
        });

        resumable.on('fileError', function (file, response) {
            alert('Upload gagal: ' + response);
            fileInput.prop('disabled', false);
            uploadButton.prop('disabled', true);
            $('.progress').addClass('d-none');
        });
    }



    // Simpan permanen ke database
    $('#uploadForm').on('submit', function (e) {
        e.preventDefault();

        const tmp_path = $('#tmp_path').val();
        const no_item = $('input[name="no_item"]').val().trim();
        const keterangan = $('textarea[name="keterangan"]').val().trim();
        showButtonSpinner(uploadButton);
        // 🔸 Validasi file upload
        if (!tmp_path) {
            alert('⚠️ Silakan pilih dan upload file terlebih dahulu.');
            return;
        }

        // 🔸 Validasi No Item
        if (!no_item) {
            $('input[name="no_item"]').addClass('is-invalid').focus();

            return;
        } else {
            $('input[name="no_item"]').removeClass('is-invalid');
        }

        // 🔸 Validasi Keterangan
        if (!keterangan) {
            $('textarea[name="keterangan"]').addClass('is-invalid').focus();
            return;
        } else {
            $('textarea[name="keterangan"]').removeClass('is-invalid');
        }

        // 🔹 Disable tombol biar gak double submit
        uploadButton.prop('disabled', true);

        const data = {
            _token: "{{ csrf_token() }}",
            tmp_path: tmp_path,
            no_item: no_item,
            keterangan: keterangan,
        };

        $.post("{{ route('arsip_draft.storeUpload', $arsipDraft->id) }}", data, function (res) {
            if (res.success) {
                hideButtonSpinner(uploadButton);
                // Reset form
                $('#uploadForm')[0].reset();
                $('#tmp_path').val('');
                tmpPath = null;

                // Ganti input file baru
                $('#fileInputWrapper').html(`
                    <input type="file" name="file_arsip" id="file_arsip" 
                        class="form-control form-control-sm" accept=".pdf">`);

                // Re-init resumable baru
                const newFileInput = $('#file_arsip');
                resumable = new Resumable({
                    target: "{{ route('arsip_draft.uploadTmp') }}",
                    query: { _token: "{{ csrf_token() }}" },
                    chunkSize: 5 * 1024 * 1024,
                    simultaneousUploads: 1,
                    testChunks: false,
                    throttleProgressCallbacks: 1,
                });

                resumable.assignBrowse(newFileInput[0]);
                bindResumableEvents();

                // Refresh tabel file
                loadFiles();

                uploadButton.prop('disabled', true);

                $('<div class="alert alert-success mt-2">✅ File berhasil disimpan!</div>')
                    .insertBefore('#fileList')
                    .delay(3000).fadeOut('slow');
            }
        }).fail(() => {
            hideButtonSpinner(uploadButton);
            alert('❌ Gagal menyimpan file.');
            uploadButton.prop('disabled', false);
        });
    });


    // 🗑️ Delete file dari DataTable
     $(document).on('click', '.delete-upload', function () {
        const btn = $(this);
        const id = $(this).data('id');
        if (!confirm('Yakin ingin menghapus file ini?')) return;

        $.ajax({
            url: "{{ route('arsip_draft.deleteUpload', '') }}/" + id,
            type: 'DELETE',
            data: { _token: "{{ csrf_token() }}" },
            success: function (res) {
                // $('<div class="alert alert-success mt-2">🗑️ File berhasil dihapus.</div>')
                //     .insertBefore('#fileList')
                //     .delay(2500)
                //     .fadeOut('slow');
                loadFiles();
            },
            error: function (xhr) {
                alert('Gagal menghapus file. ' + (xhr.responseJSON?.message ?? ''));
            }
        });
    });

    $(document).on('click', '.btn-download', function (e) {
        const btn = $(this);
        showButtonSpinner(btn);

        // kasih delay kecil biar spinner terlihat sebelum download dimulai
        setTimeout(() => {
            window.location.href = btn.attr('href');
            hideButtonSpinner(btn);
        }, 600);

        e.preventDefault(); // cegah langsung reload page
    });


});
</script>
@endpush