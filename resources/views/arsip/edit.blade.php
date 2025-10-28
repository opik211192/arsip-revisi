@extends('adminlte::page')

@section('title', 'Edit Arsip')

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
</style>
@endsection

@section('content')
@if (session('success'))
<div class="alert alert-success">{{ session('success') }}</div>
@endif

<div class="col-lg-12 col-md-11 mx-auto">
    <div class="card">
        <div class="card-header bg-gradient-dark text-white d-flex justify-content-between align-items-center">
            <span><i class="fas fa-edit"></i> Edit Arsip</span>
            <span>
                @if ($arsip->status == 0)
                <small class="badge badge-warning">Menunggu Konfirmasi</small>
                @elseif ($arsip->status == 1)
                <small class="badge badge-success">Disetujui</small>
                @elseif ($arsip->status == 2)
                <small class="badge badge-danger">Koreksi</small>
                @endif
            </span>
        </div>

        <div class="card-body">
            <form action="{{ route('arsip.edit', $arsip->id) }}" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')

                {{-- 🔹 Dua kolom utama --}}
                <div class="form-section">
                    {{-- 🔸 Kolom kiri --}}
                    <div class="form-column">
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

                        <div class="form-group">
                            <label for="jenis_id">Jenis Klasifikasi</label>
                            <select name="jenis_id" id="jenis_id" class="form-control select2" required>
                                @foreach ($jenis as $item)
                                <option value="{{ $item->id }}" {{ $arsip->jenis_id == $item->id ? 'selected' : '' }}>
                                    {{ $item->name }}
                                </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="tahun">Tahun</label>
                            <select name="tahun" id="tahun" class="form-control" required>
                                @for ($i = date('Y'); $i >= 1985; $i--)
                                <option value="{{ $i }}" {{ $arsip->tahun == $i ? 'selected' : '' }}>{{ $i }}</option>
                                @endfor
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="no_berkas">No. Berkas</label>
                            <input type="text" class="form-control" name="no_berkas" value="{{ $arsip->no_berkas }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="no_item">No. Item</label>
                            <input type="text" class="form-control" name="no_item" value="{{ $arsip->no_item }}"
                                required>
                        </div>

                        <div class="form-group">
                            <label for="no_box">No. Boks</label>
                            <input type="text" class="form-control" name="no_box" value="{{ $arsip->no_box }}" required>
                        </div>
                    </div>

                    {{-- 🔸 Kolom kanan --}}
                    <div class="form-column">
                        <div class="form-group">
                            <label for="id_pencipta_arsip">Pencipta Arsip</label>
                            @if (Auth::user()->hasRole('super admin') || Auth::user()->hasRole('admin'))
                            <select name="id_pencipta_arsip" id="id_pencipta_arsip" class="form-control select2">
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

                        <div class="form-group">
                            <label for="lokasi_arsip">Lokasi Arsip</label>
                            <input type="text" class="form-control" name="lokasi_arsip"
                                value="{{ $arsip->lokasi_arsip }}" required>
                        </div>

                        <div class="form-group">
                            <label for="uraian_arsip">Uraian Arsip</label>
                            <textarea name="uraian_arsip" class="form-control" rows="5"
                                required>{{ $arsip->uraian_arsip }}</textarea>
                        </div>
                    </div>
                </div>

                {{-- 🔹 Upload File Arsip --}}
                <div class="col-md-6">
                    <fieldset class="mt-4 border rounded-3 p-3 upload-fieldset">
                        <legend class="float-none w-auto px-2 text-primary" style="font-size: 14px;">
                            <i class="fas fa-file-upload me-1"></i> Upload File Arsip
                        </legend>

                        @php $fileCount = $arsip->uploads->count(); @endphp
                        @if ($fileCount > 0)
                        <label class="fw-bold text-secondary d-block mb-2">📁 File Arsip Saat Ini:</label>

                        <div class="table-responsive">
                            <table class="table table-bordered align-middle mb-3" id="file-list">
                                <thead class="table-secondary">
                                    <tr>
                                        <th style="width: 5%; text-align:center;">No</th>
                                        <th>Nama File</th>
                                        <th style="width: 25%; text-align:center;">Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($arsip->uploads as $index => $file)
                                    <tr class="file-item" data-id="{{ $file->id }}">
                                        <td class="text-center">{{ $index + 1 }}</td>
                                        <td><i class="fas fa-file-pdf text-danger me-1"></i> {{
                                            basename($file->file_path) }}</td>
                                        <td class="text-center">
                                            <a href="{{ route('arsip.viewFile', $file->id) }}" target="_blank"
                                                class="btn btn-info btn-sm me-1" title="Lihat File">
                                                <i class="fas fa-eye"></i>
                                            </a>
                                            <button type="button" class="btn btn-danger btn-sm delete-file"
                                                data-id="{{ $file->id }}" title="Hapus File">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        @else
                        <p id="no-files" class="text-muted">Belum ada file diarsipkan.</p>
                        @endif

                        {{-- 🔸 Tambah file baru --}}
                        <div id="file-wrapper">
                            <div class="file-group mb-3">
                                <label class="pt-2">
                                    <i class="fas fa-paperclip text-secondary me-1"></i> Tambah File Baru (Opsional)
                                </label>
                                <div class="w-100 d-flex align-items-center gap-2">
                                    <input type="file" name="file_arsip[]" class="form-control mr-1"
                                        accept="application/pdf">
                                    <button type="button" class="btn btn-success btn-sm add-file">
                                        <i class="fas fa-plus"></i>
                                    </button>
                                </div>
                            </div>
                        </div>

                        <small class="text-muted d-block mt-2">
                            <i class="fas fa-info-circle me-1"></i> Maksimal total file (lama + baru):
                            <strong>10</strong> file. Ukuran maksimum per file: <strong>25x MB</strong>.
                        </small>
                    </fieldset>
                </div>

                <input type="hidden" name="user_id" value="{{ $arsip->user_id }}">

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
<script>
    $(document).ready(function() {
    $('.select2').select2({ theme: 'classic', width: '100%' });

    const maxFiles = 10;
    let existingFiles = {{ $fileCount }};

    // Tambah file input
    $(document).on('click', '.add-file', function() {
        const currentFiles = $('#file-wrapper .file-group').length;
        const totalFiles = existingFiles + currentFiles;
        if (totalFiles >= maxFiles) {
            alert(`Total maksimal ${maxFiles} file (termasuk file lama).`);
            return;
        }

        $('#file-wrapper').append(`
            <div class="file-group mb-3">
                <label class="pt-2"><i class="fas fa-paperclip text-secondary me-1"></i> File Tambahan</label>
                <div class="w-100 d-flex align-items-center gap-2">
                    <input type="file" name="file_arsip[]" class="form-control mr-1" accept="application/pdf">
                    <button type="button" class="btn btn-danger btn-sm remove-file" title="Hapus file">
                        <i class="fas fa-minus"></i>
                    </button>
                </div>
            </div>`);
    });

    // Hapus input file
    $(document).on('click', '.remove-file', function() {
        $(this).closest('.file-group').remove();
    });

    // Validasi sebelum submit
    $('form').on('submit', function(e) {
        let isValid = true;
        let submitBtn = $(this).find('button[type="submit"]');

        $('input[name="file_arsip[]"]').each(function() {
            let files = this.files;
            for (let i = 0; i < files.length; i++) {
                let file = files[i];
                let sizeMB = file.size / (1024 * 1024);

                if (!file.name.toLowerCase().endsWith('.pdf')) {
                    alert(`File "${file.name}" tidak valid. Hanya file PDF yang diperbolehkan.`);
                    isValid = false;
                    break;
                }

                if (sizeMB > 25) {
                    alert(`File "${file.name}" melebihi batas ukuran 25 MB.`);
                    isValid = false;
                    break;
                }
            }
        });

        if (!isValid) {
            e.preventDefault();
            submitBtn.prop('disabled', false).text('Simpan');
            return false;
        }

        submitBtn.prop('disabled', true).text('Menyimpan...');
    });


    // ===========================================================
    // 🔹 Cek jumlah file dan sembunyikan tombol delete jika hanya 1
    // ===========================================================
    function updateDeleteButtonVisibility() {
        const fileItems = $('#file-list .file-item');
        if (fileItems.length <= 1) {
            fileItems.find('.delete-file').hide(); // sembunyikan kalau cuma 1 file
        } else {
            fileItems.find('.delete-file').show(); // tampilkan kalau >1
        }
    }

    // Jalankan saat halaman pertama kali dimuat
    updateDeleteButtonVisibility();

    // ===========================================================
    // 🔥 Hapus File Arsip via AJAX tanpa reload halaman
    // ===========================================================
    $(document).on('click', '.delete-file', function() {
        const fileId = $(this).data('id');
        const li = $(this).closest('.file-item');

        // 🔹 Ambil nama file dari kolom kedua (td:nth-child(2))
        const fileName = li.find('td:nth-child(2)').text().trim() || 'file ini';

        if (!confirm(`Yakin ingin menghapus ${fileName}?`)) return;

        $.ajax({
            url: `/arsip/delete-file/${fileId}`,
            type: 'DELETE',
            data: { _token: '{{ csrf_token() }}' },
            beforeSend: function() {
                li.find('.delete-file').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i>');
            },
            success: function(response) {
                if (response.success) {
                    li.fadeOut(300, function() {
                        $(this).remove();

                        // Kalau sudah tidak ada file, tampilkan pesan
                        if ($('#file-list .file-item').length === 0) {
                            $('#no-files').show();
                        }

                        // Periksa ulang tombol hapus
                        updateDeleteButtonVisibility();
                    });
                } else {
                    alert(response.message || 'Gagal menghapus file.');
                    li.find('.delete-file').prop('disabled', false).html('<i class="fas fa-trash"></i>');
                }
            },
            error: function(xhr) {
                alert('Terjadi kesalahan: ' + (xhr.responseJSON?.message ?? 'Tidak diketahui.'));
                li.find('.delete-file').prop('disabled', false).html('<i class="fas fa-trash"></i>');
            }
        });
    });
});
</script>
@endpush