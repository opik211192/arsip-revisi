@extends('adminlte::page')

@section('title', 'Edit Arsip')

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
<div class="col-lg-12 col-md-11 mx-auto">
    <div class="card">
        <div class="card-header bg-gradient-dark text-white d-flex justify-content-between align-items-center">
            <span><i class="fas fa-edit"></i> Edit Arsip</span>
        </div>

        <div class="card-body">
            <form id="arsipForm" method="POST">
                @csrf
                @method('PUT')

                {{-- 🔹 Data Arsip --}}
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="jenis_arsip_id">Jenis Arsip</label>
                            <select name="jenis_arsip_id" id="jenis_arsip_id" class="form-control select2" required>
                                @foreach ($jenis_arsip as $item)
                                <option value="{{ $item->id }}" {{ $arsip->jenis_arsip_id == $item->id ? 'selected' : ''
                                    }}>
                                    {{ $item->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mt-2">
                            <label for="jenis_id">Jenis Klasifikasi</label>
                            <select name="jenis_id" id="jenis_id" class="form-control select2" required>
                                @foreach ($jenis as $item)
                                <option value="{{ $item->id }}" {{ $arsip->jenis_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group mt-2">
                            <label for="tahun">Tahun</label>
                            <select name="tahun" id="tahun" class="form-control" required>
                                @for ($i = date('Y'); $i >= 1985; $i--)
                                <option value="{{ $i }}" {{ $arsip->tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group mt-2">
                            <label for="no_berkas">No. Berkas</label>
                            <input type="text" id="no_berkas" name="no_berkas" class="form-control"
                                value="{{ $arsip->no_berkas }}" required>
                        </div>

                        <div class="form-group mt-2">
                            <label for="no_item">No. Item</label>
                            <input type="text" id="no_item" name="no_item" class="form-control"
                                value="{{ $arsip->no_item }}" required>
                        </div>

                        <div class="form-group mt-2">
                            <label for="no_box">No. Box</label>
                            <input type="text" id="no_box" name="no_box" class="form-control"
                                value="{{ $arsip->no_box }}" required>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="id_pencipta_arsip">Pencipta Arsip</label>
                            @if (Auth::user()->hasRole('super admin') || Auth::user()->hasRole('admin'))
                            <select name="id_pencipta_arsip" id="id_pencipta_arsip" class="form-control select2"
                                required>
                                @foreach ($models as $model => $strukturals)
                                <optgroup label="{{ $model }}">
                                    @foreach ($strukturals as $s)
                                    <option value="{{ $s->id }}" {{ $arsip->id_pencipta_arsip == $s->id ? 'selected' :
                                        '' }}>
                                        {{ $s->name }}
                                    </option>
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

                        <div class="form-group mt-2">
                            <label for="lokasi_arsip">Lokasi Arsip</label>
                            <input type="text" id="lokasi_arsip" name="lokasi_arsip" class="form-control"
                                value="{{ $arsip->lokasi_arsip }}" required>
                        </div>

                        <div class="form-group mt-2">
                            <label for="uraian_arsip">Uraian Arsip</label>
                            <textarea id="uraian_arsip" name="uraian_arsip" class="form-control" rows="5"
                                required>{{ $arsip->uraian_arsip }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- simpan user_id tersembunyi --}}
                <input type="hidden" name="user_id" value="{{ $arsip->user_id ?? auth()->id() }}">

                {{-- 🔹 File Lama --}}
                <fieldset class="mt-4 p-3 border rounded-3">
                    <legend class="float-none w-auto px-2 text-primary">
                        <i class="fas fa-file-upload me-1"></i> File Arsip Lama
                    </legend>
                    @if ($arsip->uploads->count() > 0)
                    <table class="table table-bordered align-middle mb-3" id="file-list">
                        <thead class="table-secondary">
                            <tr>
                                <th>No</th>
                                <th>Nama File</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($arsip->uploads as $index => $file)
                            <tr class="file-item" data-id="{{ $file->id }}">
                                <td>{{ $index + 1 }}</td>
                                <td>{{ basename($file->file_path) }}</td>
                                <td class="text-center">
                                    <a href="{{ route('arsip.viewFile', $file->id) }}" target="_blank"
                                        class="btn btn-info btn-sm"><i class="fas fa-eye"></i></a>
                                    <button type="button" class="btn btn-danger btn-sm delete-file"
                                        data-id="{{ $file->id }}"><i class="fas fa-trash"></i></button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @else
                    <p class="text-muted">Belum ada file tersimpan.</p>
                    @endif
                </fieldset>

                {{-- 🔹 Dropzone Upload --}}
                <fieldset class="mt-3 border rounded-3 p-3">
                    <legend class="float-none w-auto px-2 text-success">
                        <i class="fas fa-cloud-upload-alt me-1"></i> Tambah File Baru
                    </legend>
                    <div id="dropzone-area" class="dropzone">
                        <div class="dz-message">Klik atau seret file PDF baru ke sini</div>
                    </div>
                    <small class="text-muted d-block mt-2"><i class="fas fa-info-circle me-1"></i> Format PDF | Maks
                        100MB</small>
                </fieldset>

                <div class="text-end mt-4">
                    <a href="{{ route('arsip.data') }}" class="btn btn-secondary btn-sm"><i
                            class="fas fa-arrow-left"></i> Kembali</a>
                    <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-save"></i> Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/dropzone/5.9.3/min/dropzone.min.js"></script>
<script>
    Dropzone.autoDiscover = false;

$(document).ready(function () {
    $('.select2').select2({ theme: 'classic', width: '100%' });

    let uploadedFiles = [];

    // ✅ Dropzone aktif
    const dz = new Dropzone("#dropzone-area", {
        url: "{{ route('arsip.upload-temp') }}",
        paramName: "file",
        maxFilesize: 100, // MB
        acceptedFiles: ".pdf",
        addRemoveLinks: true,
        dictRemoveFile: "Hapus",
        headers: { "X-CSRF-TOKEN": "{{ csrf_token() }}" },

        success: function (file, response) {
            if (response.success) {
                file.serverPath = response.path;
                uploadedFiles.push(response.path);
                console.log("✅ Upload sukses:", response.filename);
            } else {
                console.warn("Gagal upload:", response);
            }
        },

        removedfile: function (file) {
            if (file.serverPath) {
                $.post("{{ route('arsip.delete-temp') }}", {
                    _token: "{{ csrf_token() }}",
                    path: file.serverPath
                });
            }
            file.previewElement.remove();
        },

        error: function (file, msg) {
            alert("Upload gagal: " + msg);
            this.removeFile(file);
        }
    });

    // 🧾 Submit form
    $('#arsipForm').on('submit', function (e) {
        e.preventDefault();
        const formData = new FormData(this);
        uploadedFiles.forEach((path, i) => formData.append(`temp_files[${i}]`, path));

        const btn = $(this).find('button[type="submit"]');
        btn.prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Menyimpan...');

        $.ajax({
            url: "{{ route('arsip.edit', $arsip->id) }}",
            type: "POST",
            data: formData,
            contentType: false,
            processData: false,
            success: function (res) {
                if (res.success) {
                    window.location.href = res.redirect;
                } else {
                    alert(res.message || "Gagal menyimpan data.");
                    btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
                }
            },
            error: function (xhr) {
                alert("Terjadi kesalahan: " + xhr.statusText);
                btn.prop('disabled', false).html('<i class="fas fa-save"></i> Simpan');
            }
        });
    });

    // 🗑 Hapus file lama
    $(document).on('click', '.delete-file', function () {
        const id = $(this).data('id');
        const row = $(this).closest('tr');
        if (!confirm("Yakin ingin menghapus file ini?")) return;

        $.ajax({
            url: `/arsip/delete-file/${id}`,
            type: 'DELETE',
            data: { _token: "{{ csrf_token() }}" },
            success: res => { if (res.success) row.remove(); else alert('Gagal hapus file'); },
            error: () => alert('Terjadi kesalahan saat menghapus.')
        });
    });
});
</script>
@endpush