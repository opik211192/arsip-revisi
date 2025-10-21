@extends('adminlte::page')
@section('title', 'Buat Arsip')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
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

    .form-column:nth-child(2) {
        padding-left: 20px;
    }

    .form-group {
        display: flex;
        align-items: center;
        margin-bottom: 12px;
    }

    .form-group label {
        flex: 0 0 25%;
        text-align: left;
        margin-right: 10px;
    }

    .form-group input,
    .form-group select,
    .form-group textarea {
        flex: 1;
    }

    .select2-container {
        width: 100% !important;
    }

    /* 🔹 Responsif mobile */
    @media (max-width: 768px) {
        .form-section {
            flex-direction: column;
            gap: 20px;
        }

        .form-group {
            flex-direction: column;
            align-items: flex-start;
        }

        .form-group label {
            width: 100%;
            margin-bottom: 6px;
        }

        .form-column:nth-child(2) {
            padding-left: 0;
        }
    }

    fieldset {
        border: 1.5px solid #ccc !important;
        background-color: #fafafa;
    }

    legend {
        font-weight: 600;
        color: #0d6efd;
    }

    fieldset .form-group label {
        flex: 0 0 30%;
        text-align: left;
        margin-right: 10px;
    }
</style>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script>
    $(document).ready(function() {
        $('.select2').select2({
            theme: 'classic',
            width: '100%',
        });
    });
</script>
@endpush

@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="col-lg-12 col-md-11 mx-auto">
    <div class="card">
        <div class="card-header bg-gradient-dark text-white">
            <i class="fas fa-archive"></i> Formulir Arsip
        </div>

        <div class="card-body">
            <form action="{{ route('arsip.store') }}" method="post" enctype="multipart/form-data">
                @csrf

                {{-- 🔹 Form Section: KIRI & KANAN --}}
                <div class="form-section">
                    {{-- 🔸 Kolom Kiri --}}
                    <div class="form-column">
                        <div class="form-group">
                            <label for="jenis_arsip_id">Jenis Arsip</label>
                            <select name="jenis_arsip_id" id="jenis_arsip_id" class="form-control select2" required>
                                <option value="" selected disabled>Pilih Jenis Arsip</option>
                                @foreach ($jenis_arsip as $item)
                                <option value="{{ $item->id }}">{{ $item->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="jenis_id">Jenis Klasifikasi</label>
                            <select name="jenis_id" id="jenis_id" class="form-control select2" required>
                                <option value="" selected disabled>Pilih Klasifikasi</option>
                                @foreach ($jeniss as $jenis)
                                <option value="{{ $jenis->id }}">{{ $jenis->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tahun">Tahun</label>
                            <select name="tahun" id="tahun" class="form-control" required>
                                <option value="" selected disabled>Pilih Tahun</option>
                                @for ($i = date('Y'); $i >= 1985; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="no_berkas">No. Berkas</label>
                            <input type="text" class="form-control" name="no_berkas" id="no_berkas"
                                placeholder="01/A/BKS" required>
                        </div>

                        <div class="form-group">
                            <label for="no_item">No. Item</label>
                            <input type="text" class="form-control" id="no_item" name="no_item" placeholder="01"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="no_box">No. Box</label>
                            <input type="text" class="form-control" id="no_box" name="no_box" placeholder="BOX-12"
                                required>
                        </div>

                        <fieldset class="mt-4 border rounded-3 p-3">
                            <legend class="float-none w-auto px-2 text-primary" style="font-size: 14px;">
                                <i class="fas fa-file-upload me-1"></i> Upload File Arsip
                            </legend>

                            <div id="file-wrapper">
                                <div class=" file-group mb-3">
                                    <label class="pt-2">
                                        <i class="fas fa-paperclip text-secondary me-1"></i> Pilih File
                                    </label>
                                    <div class="w-100 d-flex align-items-center gap-2">
                                        <input type="file" name="file_arsip[]" class="form-control mr-1"
                                            accept=".pdf,.doc,.docx,.xls,.xlsx">
                                        <button type="button" class="btn btn-success btn-sm add-file"
                                            title="Tambah file" hidden>
                                            <i class="fas fa-plus"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Format: <strong>.pdf, .doc, .docx, .xls, .xlsx</strong> &nbsp; | &nbsp;
                                Maksimal per file: <strong>5 MB</strong>
                            </small>
                        </fieldset>
                    </div>

                    {{-- 🔸 Kolom Kanan --}}
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
                            <input type="text" class="form-control" id="lokasi_arsip" name="lokasi_arsip"
                                placeholder="Lokasi penyimpanan..." required>
                        </div>

                        <div class="form-group">
                            <label for="uraian_arsip">Uraian Arsip</label>
                            <textarea name="uraian_arsip" class="form-control" id="uraian_arsip" rows="5"
                                placeholder="Tuliskan uraian arsip..." required></textarea>
                        </div>
                    </div>
                </div>

                {{-- Hidden --}}
                <input type="hidden" name="user_id" value="{{ $user->id }}">

                {{-- Tombol --}}
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
<script>
    // Batas maksimal file
    const maxFiles = 5;

    // Tambah input file
    $(document).on('click', '.add-file', function() {
        const currentFiles = $('#file-wrapper .file-group').length;

        if (currentFiles >= maxFiles) {
            alert(`Maksimal ${maxFiles} file yang boleh diupload.`);
            return;
        }

        let newFileInput = `
        <div class="file-group mb-3">
            <label class="pt-2">
                <i class="fas fa-paperclip text-secondary me-1"></i> File Tambahan
            </label>
            <div class="w-100 d-flex align-items-center gap-2">
                <input type="file" name="file_arsip[]" class="form-control mr-1"
                    accept=".pdf,.doc,.docx,.xls,.xlsx">
                <button type="button" class="btn btn-danger btn-sm remove-file" title="Hapus file">
                    <i class="fas fa-minus"></i>
                </button>
            </div>
        </div>`;
        
        $('#file-wrapper').append(newFileInput);
    });

    // Hapus input file
    $(document).on('click', '.remove-file', function() {
        $(this).closest('.file-group').remove();
    });
</script>

@endpush