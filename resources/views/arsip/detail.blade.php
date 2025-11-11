@extends('adminlte::page')

@section('title', 'Detail Arsip')

@push('css')
<style>
    .card {
        border-radius: 12px;
        box-shadow: 0 3px 10px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        font-weight: 600;
        font-size: 1.1rem;
    }

    #t {
        font-weight: bold;
        width: 200px;
    }

    .table td {
        vertical-align: middle;
    }

    .badge-status {
        font-size: 0.85rem;
        padding: 6px 10px;
        border-radius: 8px;
    }

    .badge-warning {
        background-color: #ffc107 !important;
        color: #222;
    }

    .badge-success {
        background-color: #28a745 !important;
    }

    .badge-danger {
        background-color: #dc3545 !important;
    }

    .file-box {
        background: #f8f9fa;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        padding: 10px 15px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        font-size: 0.95rem;
    }

    .file-box i {
        color: #007bff;
    }

    .file-box span {
        flex-grow: 1;
        margin-left: 10px;
    }

    .btn {
        border-radius: 6px;
    }

    .spinner-border {
        width: 1rem;
        height: 1rem;
        border-width: 2px;
    }
</style>
@endpush

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
                <li class="breadcrumb-item active" aria-current="page"> Detail Arsip</li>
            </ol>
        </nav>
    </div>
</div>
@stop

@section('content')
<div class="container-fluid fade-in">
    <div class="col-12">
        <div class="card mb-4 border-0">
            <div class="card-header bg-gradient-dark text-white">
                <i class="fas fa-folder-open me-2"></i> Detail Arsip
            </div>
            <div class="card-body">
                <table class="table table-borderless table-sm align-middle">
                    <tbody>
                        <tr>
                            <td id="t">Jenis Arsip</td>
                            <td>:</td>
                            <td>{{ $data->jenis_arsip->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td id="t">No. Berkas</td>
                            <td>:</td>
                            <td>{{ $data->no_berkas }}</td>
                        </tr>
                        <tr>
                            <td id="t">No. Item</td>
                            <td>:</td>
                            <td>{{ $data->no_item }}</td>
                        </tr>
                        <tr>
                            <td id="t">No. Boks</td>
                            <td>:</td>
                            <td>{{ $data->no_box }}</td>
                        </tr>
                        <tr>
                            <td id="t">Jenis Klasifikasi</td>
                            <td>:</td>
                            <td>{{ $data->jenis->name ?? '-' }}</td>
                        </tr>
                        <tr>
                            <td id="t">Lokasi Arsip</td>
                            <td>:</td>
                            <td>{{ $data->lokasi_arsip }}</td>
                        </tr>
                        <tr>
                            <td id="t">Tahun Arsip</td>
                            <td>:</td>
                            <td>{{ $data->tahun }}</td>
                        </tr>
                        <tr>
                            <td id="t">Pencipa Arsip</td>
                            <td>:</td>
                            <td>{{ $strukturInfo['struktural_detail'] }}</td>
                        </tr>
                        <tr>
                            <td id="t">Uraian Arsip</td>
                            <td>:</td>
                            <td>{{ $data->uraian_arsip }}</td>
                        </tr>
                        <tr>
                            <td id="t">File Arsip</td>
                            <td>:</td>
                            <td>
                                <div class="file-box">
                                    <i class="fas fa-file-alt fa-lg"></i>
                                    <span>{{ basename($data->file_arsip) }}</span>
                                    <a href="{{ route('arsip.download', $data) }}" class="btn btn-success btn-sm"
                                        id="btnDownload">
                                        <i class="fas fa-download me-1 text-white"></i> Unduh
                                    </a>
                                </div>
                            </td>
                        </tr>

                        @if (Auth::user()->hasRole('super admin') || Auth::user()->hasRole('admin'))
                        <tr>
                            <td id="t">User Pengunggah</td>
                            <td>:</td>
                            <td>
                                {{ $data->user->name ?? '-' }}
                                <small class="text-muted">({{ implode(', ', $data->user->getRoleNames()->toArray())
                                    }})</small>
                            </td>
                        </tr>
                        <tr>
                            <td id="t">Status</td>
                            <td>:</td>
                            <td>
                                @if ($data->status == 0)
                                <span class="badge badge-warning badge-status">Menunggu Konfirmasi</span>
                                @elseif ($data->status == 1)
                                <span class="badge badge-success badge-status">Disetujui</span>
                                @elseif ($data->status == 2)
                                <span class="badge badge-danger badge-status">Koreksi</span>
                                @endif
                            </td>
                        </tr>
                        @endif

                        @if (!empty($data->keterangan))
                        <tr>
                            <td id="t">Keterangan</td>
                            <td>:</td>
                            <td class="text-danger"><i>{{ $data->keterangan }}</i></td>
                        </tr>
                        @endif
                    </tbody>
                </table>

                <div class="mt-4">
                    @if (Auth::user()->hasRole('super admin') || Auth::user()->hasRole('admin'))
                    <button type="button" class="btn btn-secondary btn-sm" data-toggle="modal"
                        data-target="#approvalModal">
                        <i class="fas fa-check-circle me-1"></i> Approval
                    </button>
                    @endif
                    <a href="{{ route('arsip.data') }}" class="btn btn-primary btn-sm">
                        <i class="fas fa-arrow-left me-1"></i> Kembali
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- ==============================
MODAL APPROVAL
============================== --}}
<div class="modal fade" id="approvalModal" tabindex="-1" role="dialog" aria-labelledby="approvalModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="frm_edit" action="{{ route('arsip.approval', $data) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="approvalModalLabel">Form Persetujuan Arsip</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="status" id="status1" value="0" {{
                            (old('status') ?? $data->status) == '0' ? 'checked' : '' }}>
                        <label for="status1" class="form-check-label">Menunggu Konfirmasi</label>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="status" id="status2" value="1" {{
                            (old('status') ?? $data->status) == '1' ? 'checked' : '' }}>
                        <label for="status2" class="form-check-label">Disetujui</label>
                    </div>

                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="status" id="status3" value="2" {{
                            (old('status') ?? $data->status) == '2' ? 'checked' : '' }}>
                        <label for="status3" class="form-check-label text-danger">Koreksi</label>
                    </div>

                    <textarea class="form-control mt-3" name="keterangan" id="keterangan" rows="3"
                        placeholder="Tuliskan keterangan koreksi..." @if ($data->status != 2) hidden @endif>
                        {{ old('keterangan') ?? $data->keterangan }}</textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-light" data-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary" id="btnSubmit">Simpan</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('js')
<script>
    $(document).ready(function(){
    const textarea = $('#keterangan');

    $('input[name="status"]').on('change', function(){
        if ($(this).val() === '2') textarea.removeAttr('hidden').focus();
        else textarea.attr('hidden', true).val('');
    });

    $('#btnSubmit').on('click', function(e){
        if ($('input[name="status"]:checked').val() === '2' && textarea.val().trim() === '') {
            alert('Keterangan koreksi harus diisi!');
            e.preventDefault();
        }
    });

    // 🔹 Spinner untuk tombol download
    $('#btnDownload').on('click', function() {
        const btn = $(this);
        const originalHTML = btn.html();
        btn.prop('disabled', true);
        btn.html('<span class="spinner-border spinner-border-sm me-2"></span> Mengunduh...');
        // Setelah 3 detik, kembalikan (browser handle download)
        setTimeout(() => {
            btn.prop('disabled', false);
            btn.html(originalHTML);
        }, 3000);
    });
});
</script>
@endpush