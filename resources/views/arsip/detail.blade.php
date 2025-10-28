@extends('adminlte::page')

@section('title', 'Detail Arsip')

@section('styles')
<style>
    .detail-label {
        font-weight: 600;
        color: #333;
        width: 25%;
        vertical-align: top;
    }

    .detail-value {
        color: #222;
    }

    .badge {
        font-size: 0.85rem;
        padding: 5px 10px;
    }

    .file-list .list-group-item {
        background: #fafafa;
        border-radius: 6px;
        border: 1px solid #e0e0e0;
        margin-bottom: 5px;
    }

    .file-list .btn {
        padding: 4px 8px;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        font-weight: bold;
    }

    @media (max-width: 768px) {
        .detail-label {
            width: 100%;
            display: block;
            margin-bottom: 4px;
        }
    }
</style>
@endsection

@section('content')
<div class="col-lg-10 col-md-11 mx-auto">
    <div class="card">
        <div class="card-header bg-gradient-dark text-white d-flex justify-content-between align-items-center">
            <span><i class="fas fa-file-alt"></i> Detail Arsip</span>
            <span>
                @if ($data->status == 0)
                <small class="badge badge-warning">Menunggu Konfirmasi</small>
                @elseif ($data->status == 1)
                <small class="badge badge-success">Disetujui</small>
                @elseif ($data->status == 2)
                <small class="badge badge-danger">Koreksi</small>
                @endif
            </span>
        </div>

        <div class="card-body">
            <table class="table table-bordered">
                <tbody>
                    <tr>
                        <td class="detail-label">Uraian Arsip</td>
                        <td class="detail-value">{{ $data->uraian_arsip }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Jenis Arsip</td>
                        <td class="detail-value">{{ $data->jenis_arsip->name }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Jenis Klasifikasi</td>
                        <td class="detail-value">{{ $data->jenis->name }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">No. Berkas</td>
                        <td class="detail-value">{{ $data->no_berkas }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">No. Item</td>
                        <td class="detail-value">{{ $data->no_item }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">No. Box</td>
                        <td class="detail-value">{{ $data->no_box }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Tahun Arsip</td>
                        <td class="detail-value">{{ $data->tahun }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Lokasi Arsip</td>
                        <td class="detail-value">{{ $data->lokasi_arsip }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Pencipta Arsip</td>
                        <td class="detail-value">{{ $struktural[0]->struktural_detail }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label">Unit Kerja</td>
                        <td class="detail-value">{{ $struktural[0]->struktural }}</td>
                    </tr>
                    <tr>
                        <td class="detail-label"></td>
                        <td class="detail-value">{{ $struktural[0]->struktural_detail }}</td>
                    </tr>

                    {{-- ✅ File Unggahan --}}
                    <tr>
                        <td class="detail-label">File Unggahan</td>
                        <td>
                            @if ($data->uploads && $data->uploads->count() > 0)
                            <ul class="list-group file-list">
                                @foreach ($data->uploads as $file)
                                <li class="list-group-item d-flex justify-content-between align-items-center">
                                    <div>
                                        <i class="fas fa-file-pdf text-danger me-1"></i>
                                        {{ basename($file->file_path) }}
                                    </div>
                                    <div>
                                        <a href="{{ route('arsip.viewFile', $file->id) }}" target="_blank"
                                            class="btn btn-info btn-sm me-1" title="Lihat">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        {{-- <a href="{{ route('arsip.downloadFile', $file->id) }}"
                                            class="btn btn-success btn-sm" title="Download">
                                            <i class="fas fa-download"></i>
                                        </a> --}}
                                    </div>
                                </li>
                                @endforeach
                            </ul>
                            @else
                            <p class="text-muted">Belum ada file diarsipkan.</p>
                            @endif
                        </td>
                    </tr>

                    @if (Auth::user()->hasRole('super admin') || Auth::user()->hasRole('admin'))
                    <tr>
                        <td class="detail-label">User Pengunggah</td>
                        <td class="detail-value">{{ $data->user->name."
                            (".implode($data->user->getRoleNames()->toarray()).")" }}</td>
                    </tr>
                    @endif

                    @if (!empty($data->keterangan))
                    <tr>
                        <td class="detail-label text-danger">Keterangan</td>
                        <td class="detail-value text-danger"><i>{{ $data->keterangan }}</i></td>
                    </tr>
                    @endif
                </tbody>
            </table>

            {{-- Tombol Aksi --}}
            <div class="mt-3">
                @if (Auth::user()->hasRole('super admin') || Auth::user()->hasRole('admin'))
                <button type="button" id="approval" class="btn btn-secondary btn-sm" data-toggle="modal"
                    data-target="#exampleModal">
                    <i class="fas fa-check-circle"></i> Approval
                </button>
                @endif

                <a href="{{ route('arsip.data') }}" class="btn btn-primary btn-sm">
                    <i class="fas fa-arrow-left"></i> Kembali
                </a>
            </div>
        </div>
    </div>
</div>

{{-- ✅ Modal Approval Arsip --}}
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <form id="frm_edit" class="form-horizontal" action="{{ route('arsip.approval', $data) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header bg-dark text-white">
                    <h5 class="modal-title" id="exampleModalLabel">Form Persetujuan Arsip</h5>
                    <button type="button" class="close text-white" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>

                <div class="modal-body">
                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status0" value="0" {{
                            (old('status') ?? $data->status) == '0' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status0">Menunggu Konfirmasi</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status1" value="1" {{
                            (old('status') ?? $data->status) == '1' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status1">Disetujui</label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="status" id="status2" value="2" {{
                            (old('status') ?? $data->status) == '2' ? 'checked' : '' }}>
                        <label class="form-check-label" for="status2">Koreksi</label>
                    </div>

                    <textarea class="form-control mt-2" name="keterangan" id="keterangan"
                        placeholder="Keterangan Koreksi..." {{ ($data->status == 2) ? '' : 'hidden' }}>
                        {{ old('keterangan') ?? $data->keterangan }}
                    </textarea>
                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <button type="submit" id="btnSubmit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Simpan
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    $(document).ready(function() {
    // toggle keterangan otomatis
    $('input[name="status"]').on('change', function() {
        if ($(this).val() == '2') {
            $('#keterangan').removeAttr('hidden');
        } else {
            $('#keterangan').attr('hidden', true).val('');
        }
    });

    $('#btnSubmit').on('click', function(e) {
        if ($('input[name="status"]:checked').val() == '2' && $('#keterangan').val().trim() === '') {
            alert('Keterangan koreksi harus diisi!');
            e.preventDefault();
        }
    });
});
</script>
@endpush