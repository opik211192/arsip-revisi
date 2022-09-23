@extends('adminlte::page')
@section('title', 'Buat Arsip')
@section('content')

@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>
<script>
    $(document).ready(function() {
            $('.select2').select2();
        });
</script>
@endpush


@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif


<div class="card col-md-10">
    <div class="card-header bg-dark">Create Arsip</div>
    <div class="card-body">

        <form action="{{ route('arsip.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
                <div class="col">
                    <label for="jenis_arsip_id">Jenis Arsip</label>
                    <select name="jenis_arsip_id" id="jenis_arsip_id" class="form-control">
                        <option value="" selected disabled>Pilih Jensi Arsip</option>
                        @foreach ($jenis_arsip as $item)
                        <option value="{{ $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label for="jenis_id">Jenis Klasifikasi</label>
                    <select name="jenis_id" id="jenis_id" class="form-control" required>
                        <option value="" selected disabled>Pilih Jenis Klasifikasi</option>
                        @foreach ($jeniss as $jenis)
                        <option value="{{ $jenis->id }}">{{ $jenis->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <label for="id_pencipta_arsip">Pencipta Arsip</label>
                    @if (Auth::user()->hasRole('super admin') || Auth::user()->hasRole('admin'))
                    <select name="id_pencipta_arsip" id="id_pencipta_arsip" class="form-control select2">
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
                    <select name="id_pencipta_arsip" id="id_pencipta_arsip" class="form-control">
                        <option value="{{ $user->struktural_detail->id }}" selected>{{
                            $user->struktural_detail->name }}
                        </option>
                    </select>
                    @endif
                </div>
                <div class="col">
                    <label for="lokasi_arsip">Lokasi Arsip</label>
                    <input type="text" class="form-control" id="lokasi_arsip" name="lokasi_arsip"
                        placeholder="Lokasi Arsip" required>
                    @error('lokasi_arsip')
                    <div class="text-danger mt-2 d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <label for="no_berkas">No. Berkas</label>
                    <input type="text" class="form-control" name="no_berkas" id="no_berkas" placeholder="No. Berkas"
                        required>
                    @error('no_berkas')
                    <div class="text-danger mt-2 d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col">
                    <label for="no_box">No. Box</label>
                    <input type="text" class="form-control" id="no_box" name="no_box" placeholder="No. Box" required>
                    @error('no_box')
                    <div class="text-danger mt-2 d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col">
                    <label for="tahun">Tahun</label>
                    <select name="tahun" id="tahun" class="form-control" required>
                        <option value="" selected disabled>Pilih Tahun</option>
                        @for ($i = 1985; $i <= date("Y") ; $i++) <option value="{{ $i }}"> {{ $i }}</option>
                            @endfor
                    </select>
                </div>
            </div>
            <div class="row mt-3">
                <label for="uraian_arsip">Uraian</label>
                <textarea class="form-control" name="uraian_arsip" id="uraian_arsip" cols="30" rows="4"
                    required></textarea>
                @error('uraian_arsip')
                <div class="text-danger mt-2 d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="row mt-3">
                <label for="file_arsip">Unggah File</label>
                <input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                    name="file_arsip" id="file_arsip" class="form-control">
                @error('file_arsip')
                <div class="text-danger mt-2 d-block">{{ $message }}</div>
                @enderror
            </div>

            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">
            <div class="row mt-4">
                <button class="btn btn-primary btn-sm">Simpan</button>
            </div>
        </form>


        {{-- <form action="{{ route('arsip.store') }}" method="post" enctype="multipart/form-data">
            @csrf

            <div class="form-group">
                <label for="judul_arsip">Judul Arsip</label>
                <input type="text" class="form-control" name="judul_arsip" id="judul_arsip" required>
            </div>
            @error('judul_arsip')
            <div class="text-danger mt-2 d-block">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="lokasi_arsip">Lokasi Arsip</label>
                <input type="text" class="form-control" name="lokasi_arsip" id="lokasi_arsip" required>
            </div>
            @error('lokasi_arsip')
            <div class="text-danger mt-2 d-block">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="jenis_arsip">Jenis</label>
                <select name="jenis_arsip" id="jenis_arsip" class="form-control" required>
                    <option value="" selected disabled>Pilih Jenis</option>
                    <option value="ku">KU</option>
                    <option value="hk">HK</option>
                    <option value="pl">PL</option>
                    <option value="um">UM</option>
                    <option value="pr">PR</option>
                    <option value="tn">TN</option>
                </select>
            </div>

            <div class="form-group">
                <label for="no_berkas">No. Berkas</label>
                <input type="text" class="form-control" name="no_berkas" id="no_berkas" required>
            </div>
            @error('no_berkas')
            <div class="text-danger mt-2 d-block">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="tahun">Tahun</label>
                <select name="tahun" id="tahun" class="form-control" required>
                    <option value="" selected disabled>Pilih Tahun</option>
                    @for ($i = 1985; $i <= date("Y") ; $i++) <option value="{{ $i }}"> {{ $i }}</option>
                        @endfor
                </select>
            </div>

            <div class="form-group">
                <label for="pencipta_arsip">Pencipta Arsip</label>
                <input type="text" class="form-control" name="pencipta_arsip" id="pencipta_arsip" required>
            </div>
            @error('pencipta_arsip')
            <div class="text-danger mt-2 d-block">{{ $message }}</div>
            @enderror

            <div class="form-group">
                <label for="uraian_arsip">Uraian</label>
                <textarea class="form-control" name="uraian_arsip" id="uraian_arsip" cols="30" rows="5"
                    required></textarea>
            </div>
            @error('uraian')
            <div class="text-danger mt-2 d-block">{{ $message }}</div>
            @enderror --}}
            {{-- <div class="form-group">
                <label for="user_id">User ID</label>
                <input type="text" class="form-control" name="user_id" id="user_id" value="{{ $user->id }}">
            </div> --}}
            {{-- <div class="form-group">
                <label for="arsip">Unggah File</label>
                <input type="file" name="file_arsip" id="file_arsip" class="form-control">
            </div>
            <input type="hidden" name="user_id" id="user_id" value="{{ $user->id }}">

            <button class="btn btn-primary mt-3">Simpan</button>

        </form> --}}


    </div>
</div>
@endsection