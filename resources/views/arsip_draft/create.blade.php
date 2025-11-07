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

    @media (max-width: 768px) {
        .form-section {
            flex-direction: column;
            gap: 20px;
        }
    }

    .btn-sm i {
        margin-right: 4px;
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
            <i class="fas fa-plus"></i> Formulir Arsip
        </div>

        <div class="card-body">
            <form action="{{ route('arsip_draft.store') }}" method="POST">
                @csrf

                <div class="form-section">
                    {{-- 🔹 Kolom Kiri --}}
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
                            <label for="no_box">No. Box</label>
                            <input type="text" name="no_box" id="no_box" class="form-control" required>
                        </div>

                        <div class="form-group">
                            <label for="no_berkas">No. Berkas</label>
                            <input type="text" name="no_berkas" id="no_berkas" class="form-control" required>
                        </div>
                    </div>

                    {{-- 🔹 Kolom Kanan --}}
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
                            <textarea name="uraian_arsip" id="uraian_arsip" rows="5" class="form-control"
                                required></textarea>
                        </div>
                    </div>
                </div>

                <input type="hidden" name="user_id" value="{{ $user->id }}">

                <div class="text-end mt-4">
                    <a href="{{ route('arsip_draft.index') }}" class="btn btn-secondary btn-sm">
                        <i class="fas fa-arrow-left"></i> Kembali
                    </a>
                    <button type="submit" class="btn btn-primary btn-sm">
                        <i class="fas fa-save"></i> Simpan & Lanjut Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script>
    $(document).ready(function () {
        $('.select2').select2({
            theme: 'classic',
            width: '100%'
        });
    });
</script>
@endpush