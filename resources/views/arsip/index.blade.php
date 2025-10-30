@extends('adminlte::page')
@section('title', 'Buat Arsip')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
<link href="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.css" rel="stylesheet" />

<style>
    label {
        font-weight: 600;
        color: #333;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        font-weight: bold;
        font-size: 16px;
    }

    .form-section {
        display: flex;
        gap: 40px;
        flex-wrap: wrap;
        justify-content: space-between;
    }

    .form-column {
        flex: 1;
        min-width: 400px;
    }

    .select2-container {
        width: 100% !important;
    }

    fieldset {
        border: 1.5px solid #ccc !important;
        background-color: #fafafa;
    }

    legend {
        font-weight: 600;
        color: #0d6efd;
    }

    /* Dropzone Styling */
    .dropzone {
        border: 2px dashed #0d6efd;
        border-radius: 10px;
        background: #f8f9fa;
        padding: 25px;
        text-align: center;
        cursor: pointer;
    }

    .dropzone .dz-message {
        font-size: 16px;
        color: #666;
    }

    .dz-preview .dz-progress {
        height: 10px !important;
        border-radius: 5px;
        overflow: hidden;
    }

    .dz-preview .dz-progress .dz-upload {
        background: linear-gradient(90deg, #0d6efd, #6ea8fe);
    }

    @media (max-width: 768px) {
        .form-section {
            flex-direction: column;
            gap: 20px;
        }
    }

    .form-section {
        display: flex;
        gap: 60px;
        /* sedikit lebih besar agar ada ruang di tengah */
        flex-wrap: wrap;
        justify-content: center;
        /* tengah-tengah di container */
    }

    .form-column {
        flex: 1;
        min-width: 460px;
        /* lebih lebar agar simetris antar kolom */
    }

    /* ==============================
    📐 Layout Label & Input
    ============================== */
    .form-group {
        display: flex;
        align-items: center;
        margin-bottom: 14px;
        gap: 14px;
    }

    .form-group label {
        flex: 0 0 30%;
        /* sama untuk kiri dan kanan */
        text-align: right;
        font-weight: 600;
        color: #333;
        margin-bottom: 0;
        padding-right: 12px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        flex: 1;
        width: 100%;
        max-width: 100%;
    }

    textarea.form-control {
        resize: vertical;
    }

    /* 🌈 Tambahan opsional: form terlihat lebih rata */
    .form-column:first-child .form-group label {
        padding-right: 18px;
    }

    .form-column:last-child .form-group label {
        padding-right: 14px;
    }

    /* ==============================
    📱 Responsif
    ============================== */

    /* 📏 Tablet (≤ 992px) -> Masih 2 kolom tapi lebih rapat */
    @media (max-width: 992px) {
        .form-section {
            gap: 30px;
            justify-content: space-between;
        }

        .form-column {
            flex: 1 1 100%;
            min-width: 100%;
        }

        .form-group {
            gap: 10px;
        }

        .form-group label {
            flex: 0 0 35%;
            text-align: left;
            padding-right: 0;
        }
    }

    /* 📱 HP (≤ 768px) -> Satu kolom vertikal */
    @media (max-width: 768px) {
        .form-section {
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            flex-direction: column;
            align-items: stretch;
        }

        .form-group label {
            text-align: left;
            width: 100%;
            margin-bottom: 6px;
            flex: unset;
            padding-right: 0;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            max-width: 100%;
        }
    }
</style>
@endsection

@section('content')
@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="col-lg-12 col-md-11 mx-auto">
    <div class="card">
        <div class="card-header bg-gradient-dark text-white">
            <i class="fas fa-archive"></i> Formulir Arsip
        </div>

        <div class="card-body">
            <form id="arsipForm" action="{{ route('arsip.store') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="form-section">
                    <div class="form-column">
                        <div class="form-group">
                            <label for="jenis_arsip_id">Jenis Arsip</label>
                            <select name="jenis_arsip_id" id="jenis_arsip_id" class="form-control select2" required>
                                <option value="" disabled selected>Pilih Jenis Arsip</option>
                                @foreach ($jenis_arsip as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="jenis_id">Jenis Klasifikasi</label>
                            <select name="jenis_id" id="jenis_id" class="form-control select2" required>
                                <option value="" disabled selected>Pilih Klasifikasi</option>
                                @foreach ($jeniss as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tahun">Tahun</label>
                            <select name="tahun" id="tahun" class="form-control" required>
                                <option value="" disabled selected>Pilih Tahun</option>
                                @for ($i = date('Y'); $i >= 1985; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="no_berkas">No. Berkas</label>
                            <input type="text" name="no_berkas" id="no_berkas" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="no_item">No. Item</label>
                            <input type="text" name="no_item" id="no_item" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="no_box">No. Box</label>
                            <input type="text" name="no_box" id="no_box" class="form-control" required>
                        </div>
                    </div>

                    <div class="form-column">
                        <div class="form-group">
                            <label for="id_pencipta_arsip">Pencipta Arsip</label>
                            @if (Auth::user()->hasRole('super admin') || Auth::user()->hasRole('admin'))
                            <select name="id_pencipta_arsip" id="id_pencipta_arsip" class="form-control select2"
                                required>
                                <option value="" disabled selected>-- Pilih Pencipta Arsip --</option>
                                @foreach ($models as $model => $strukturals)
                                <optgroup label="{{ $model }}">
                                    @foreach ($strukturals as $s)
                                    <option value="{{ $s->id }}">{{ $s->name }}</option>
                                    @endforeach
                                </optgroup>
                                @endforeach
                            </select>
                            @else
                            <input type="text" class="form-control" value="{{ $user->struktural_detail->name }}"
                                readonly>
                            <input type="hidden" name="id_pencipta_arsip" value="{{ $user->struktural_detail->id }}">
                            @endif
                        </div>

                        <div class="form-group">
                            <label for="lokasi_arsip">Lokasi Arsip</label>
                            <input type="text" name="lokasi_arsip" id="lokasi_arsip" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="uraian_arsip">Uraian Arsip</label>
                            <textarea name="uraian_arsip" id="uraian_arsip" class="form-control" rows="5"
                                required></textarea>
                        </div>
                    </div>
                </div>

                {{-- Dropzone upload --}}
                <fieldset class="mt-4 border rounded-3 p-3 upload-fieldset">
                    <legend class="float-none w-auto px-2 text-primary" style="font-size: 14px;">
                        <i class="fas fa-file-upload me-1"></i> Upload File Arsip
                    </legend>

                    <div id="dropzone-area" class="dropzone">
                        <div class="dz-message">Seret atau klik untuk upload file PDF (bisa lebih dari satu)</div>
                    </div>

                    <small class="text-muted d-block mt-2">
                        <i class="fas fa-info-circle me-1"></i>
                        Format: <strong>PDF</strong> | Ukuran maks: <strong>100MB / file</strong>
                    </small>
                </fieldset>

                <input type="hidden" name="user_id" value="{{ $user->id }}">

                <div class="text-end mt-4">
                    <a href="{{ route('arsip.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/dropzone.min.js"></script>

<script>
    $(document).ready(function () {
        $('.select2').select2({ theme: 'classic', width: '100%' });
    });

    Dropzone.autoDiscover = false;
    const uploadedFiles = [];

    const dz = new Dropzone("#dropzone-area", {
        url: "{{ route('arsip.upload-temp') }}",
        paramName: "file",
        maxFilesize: 100, // 100MB per file
        acceptedFiles: ".pdf",
        parallelUploads: 3,
        addRemoveLinks: true,
        dictRemoveFile: "Hapus",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },

        init: function () {
            this.on("success", function (file, response) {
                if (response.success) {
                    file.serverPath = response.path; // simpan path yang dikirim dari backend
                    uploadedFiles.push(response.path);
                }
            });
            this.on("removedfile", function (file) {
                if (file.serverPath) {
                    const removedPath = file.serverPath;
                    const i = uploadedFiles.indexOf(removedPath);
                    if (i !== -1) uploadedFiles.splice(i, 1);

                    // 🔥 Hapus di server
                    $.ajax({
                        url: "{{ route('arsip.delete-temp') }}",
                        type: "POST",
                        data: { path: removedPath, _token: "{{ csrf_token() }}" },
                        success: res => console.log(res.message),
                        error: err => console.warn("Gagal menghapus file di server.", err)
                    });
                }
            });
        }
    });

    // Submit form + temp file path
    $('#arsipForm').on('submit', function (e) {
        e.preventDefault();

        if (uploadedFiles.length === 0) {
            alert("Silakan upload minimal 1 file PDF.");
            return;
        }

        const formData = new FormData(this);
        uploadedFiles.forEach((f, i) => formData.append(`temp_files[${i}]`, f));

        const btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: res => {
                if (res.success) window.location.href = res.redirect;
            },
            error: err => {
                alert("Gagal menyimpan data arsip.");
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
            }
        });
    });
</script>
@endpush