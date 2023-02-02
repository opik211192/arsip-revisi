@extends('adminlte::page')

@section('title', 'Edit Arsip')

@section('content')

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif


<div class="card mb-4">
    <div class="card-header text-white" style="background-color: slategray">Edit Arsip</div>
    <div class="card-body">
        <form action="{{ route('arsip.edit', $arsip) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="row">
                <div class="col">
                    <label for="jenis_arsip_id">Jenis Arsip</label>
                    <select name="jenis_arsip_id" id="jenis_arsip_id" class="form-control">
                        <option value="" selected disabled>Pilih Jensi Arsip</option>
                        @foreach ($jenis_arsip as $item)
                        <option {{ $arsip->jenis_arsip_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{
                            $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col">
                    <label for="jenis_id">Jenis Klasifikasi</label>
                    <select name="jenis_id" id="jenis_id" class="form-control" required>
                        <option value="" selected disabled>Pilih Jenis Klasifikasi</option>
                        @foreach ($jenis as $item)
                        <option {{ $arsip->jenis_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{
                            $item->name }}</option>
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
                        <option value="{{ $arsip->id_pencipta_arsip }}" selected>{{
                            $arsip->struktural_detail->name }}
                        </option>
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
                        value="{{ old('lokasi_arsip') ?? $arsip->lokasi_arsip }}" required>
                    @error('lokasi_arsip')
                    <div class="text-danger mt-2 d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row mt-3">
                <div class="col">
                    <label for="no_berkas">No. Berkas</label>
                    <input type="text" class="form-control" name="no_berkas" id="no_berkas"
                        value="{{ old('no_berkas') ?? $arsip->no_berkas }}" required>
                    @error('no_berkas')
                    <div class="text-danger mt-2 d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="col">
                    <label for="no_box">No. Box</label>
                    <input type="text" class="form-control" id="no_box" name="no_box"
                        value="{{ old('no_box') ?? $arsip->no_box }}" required>
                    @error('no_box')
                    <div class="text-danger mt-2 d-block">{{ $message }}</div>
                    @enderror
                </div>

            </div>
            <div class="row mt-3">
                <div class="col">
                    <label for="tahun">Tahun</label>
                    <select name="tahun" id="tahun" class="form-control" required>
                        <option value="" selected disabled>Pilih Tahun</option>
                        @for ($i = 1980; $i <= date('Y'); $i++) <option {{ $arsip->tahun == $i ? 'selected' : '' }}
                            value="{{ $i }}">{{ $i }}</option>
                            @endfor
                    </select>
                </div>
                <div class="col">
                    <label for="file_arsip">Unggah File</label>
                    <input type="file" accept="application/vnd.openxmlformats-officedocument.spreadsheetml.sheet"
                        name="file_arsip" id="file_arsip" class="form-control">
                    <span class="text-danger"><i>Kosongkan jika tidak ubah file</i></span>
                    @error('file_arsip')
                    <div class="text-danger mt-2 d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            <div class="row mt-2">
                <label for="uraian_arsip">Uraian</label>
                <textarea class="form-control" name="uraian_arsip" id="uraian_arsip" cols="30" rows="5"
                    required>{{ old('uraian_arsip') ?? $arsip->uraian_arsip }}</textarea>
                @error('uraian_arsip')
                <div class="text-danger mt-2 d-block">{{ $message }}</div>
                @enderror
            </div>

            <input type="hidden" name="user_id" id="user_id" value="{{ $arsip->user_id }}">
            <div class="mt-2">
                <button class="btn btn-primary btn-sm mt-2">Simpan</button>
                <a href="{{ route('arsip.data') }}" class="btn btn-danger btn-sm mt-2">Batal</a>
            </div>
        </form>
    </div>
</div>
@endsection