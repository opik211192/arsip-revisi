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

    /* 🔹 Progress Modal */
    .progress-modal .modal-content {
        border-radius: 12px;
        text-align: center;
        padding: 25px;
    }

    .progress {
        height: 8px;
        border-radius: 10px;
    }

    .progress-bar {
        width: 100%;
        animation: progressMove 2s linear infinite;
        background: linear-gradient(90deg, #007bff, #00c0ef, #007bff);
        background-size: 200% 100%;
    }

    @keyframes progressMove {
        0% {
            background-position: 200% 0;
        }

        100% {
            background-position: -200% 0;
        }
    }

    /* 🔹 Override khusus input range dua kolom */
    .form-group .range-inputs {
        display: inline-flex !important;
        align-items: center;
        gap: 4px !important;
        flex: 0 0 auto !important;
    }

    .form-group .range-inputs input[type="number"] {
        width: 80px !important;
        /* panjang input dikontrol di sini */
        padding: 4px 6px !important;
    }

    .form-group .range-inputs span {
        margin: 0 2px;
        color: #555;
        font-weight: 600;
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
                    <a href="{{ route('arsip.data') }}"> <i class="fas fa-archive me-1"></i> Data Arsip</a>
                </li>
                <li class="breadcrumb-item active" aria-current="page"> Tambah Arsip</li>
            </ol>
        </nav>
    </div>
</div>
@stop

@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="col-lg-12 col-md-11 mx-auto">
    <div class="card">
        <div class="card-header bg-gradient-dark text-white">
            <i class="fas fa-folder-open me-2"></i> Tambah Arsip
        </div>

        <div class="card-body">
            {{-- ✅ tambahkan id="arsipForm" --}}
            <form id="arsipForm" action="{{ route('arsip.store') }}" method="post" enctype="multipart/form-data">
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
                            <label for="tahun">Tahun Penciptaan</label>
                            <select name="tahun" id="tahun" class="form-control" required>
                                <option value="" selected disabled>Pilih Tahun</option>
                                @for ($i = date('Y'); $i >= 1985; $i--)
                                <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="no_berkas">No. Berkas</label>
                            <div class="range-inputs">
                                <input type="number" class="form-control" id="no_berkas_awal" placeholder="Dari" min="1"
                                    required>
                                <span>-</span>
                                <input type="number" class="form-control" id="no_berkas_akhir" placeholder="Sampai"
                                    min="1">
                            </div>
                            <input type="hidden" name="no_berkas" id="no_berkas">
                        </div>

                        <div class="form-group">
                            <label for="no_item">No. Item</label>
                            <div class="range-inputs">
                                <input type="number" class="form-control" id="no_item_awal" placeholder="Dari" min="1"
                                    required>
                                <span>-</span>
                                <input type="number" class="form-control" id="no_item_akhir" placeholder="Sampai"
                                    min="1">
                            </div>
                            <input type="hidden" name="no_item" id="no_item">
                        </div>

                        <div class="form-group">
                            <label for="no_box">No. Boks</label>
                            <div class="range-inputs">
                                <input type="number" class="form-control" id="no_box_awal" placeholder="Dari" min="1"
                                    required>
                                <span>-</span>
                                <input type="number" class="form-control" id="no_box_akhir" placeholder="Sampai"
                                    min="1">
                            </div>
                            <input type="hidden" name="no_box" id="no_box">
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
                                            accept=".pdf,.doc,.docx,.xls,.xlsx,.zip,.rar">
                                    </div>
                                </div>
                            </div>

                            <small class="text-muted d-block mt-2">
                                <i class="fas fa-info-circle me-1"></i>
                                Format: <strong>.pdf, .doc, .docx, .xls, .xlsx, .zip, .rar</strong> &nbsp; | &nbsp;
                                Maksimal per file: <strong>50 MB</strong>
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

                <input type="hidden" name="user_id" value="{{ $user->id }}">

                <div class="text-end mt-4">
                    <a href="{{ route('arsip.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    {{-- ✅ tambahkan id="btnSubmit" --}}
                    <button type="submit" id="btnSubmit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- ✅ Modal Progress --}}
<div class="modal fade progress-modal" id="progressModal" tabindex="-1" role="dialog" aria-hidden="true"
    data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content p-4">
            <h5 class="mb-3">
                <i class="fas fa-spinner fa-spin text-primary me-2"></i> Sedang menyimpan data...
            </h5>
            <p class="text-muted mb-3">Harap tidak menutup halaman ini sampai proses selesai.</p>
            <div class="progress">
                <div class="progress-bar progress-bar-striped progress-bar-animated"></div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script>
    $(document).ready(function() {
    $('.select2').select2({ theme: 'classic', width: '100%' });

    // Tampilkan modal progress saat form disubmit
    $('#arsipForm').on('submit', function(e) {
        $('#btnSubmit').prop('disabled', true);
        $('#progressModal').modal('show');
    });
});
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
    const pairs = [
        { awal: 'no_berkas_awal', akhir: 'no_berkas_akhir', target: 'no_berkas' },
        { awal: 'no_item_awal', akhir: 'no_item_akhir', target: 'no_item' },
        { awal: 'no_box_awal', akhir: 'no_box_akhir', target: 'no_box' },
    ];

    pairs.forEach(p => {
        const awal = document.getElementById(p.awal);
        const akhir = document.getElementById(p.akhir);
        const target = document.getElementById(p.target);

        function updateTarget() {
            const val1 = awal.value.trim();
            const val2 = akhir.value.trim();

            // 🔹 Logika baru biar bisa satu input aja
            if (val1 && val2) {
                target.value = `${val1} - ${val2}`; // dua-duanya diisi
            } else if (val1 && !val2) {
                target.value = val1; // cuma awal diisi
            } else if (!val1 && val2) {
                target.value = val2; // cuma akhir diisi
            } else {
                target.value = ''; // dua-duanya kosong
            }
        }

        // Jalankan saat diketik
        awal.addEventListener('input', updateTarget);
        akhir.addEventListener('input', updateTarget);

        // Jalankan saat halaman pertama kali dibuka (jaga-jaga)
        updateTarget();
    });
});
</script>
@endpush