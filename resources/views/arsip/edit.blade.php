@extends('adminlte::page')

@section('title', 'Edit Arsip')

@section('content')

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif


<div class="card mb-4">
    <div class="card-header text-white" style="background-color: slategray">Create Arsip</div>
    <div class="card-body">
        <form action="{{ route('arsip.edit', $arsip) }}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <div class="form-group row">
                <label for="jenis_arsip_id" class="col-sm-2 col-form-label">Jenis Arsip</label>
                <div class="col-sm-4">
                    <select name="jenis_arsip_id" id="jenis_arsip_id" class="form-control">
                        <option value="" selected disabled>Pilih Jensi Arsip</option>
                        @foreach ($jenis_arsip as $item)
                        <option {{ $arsip->jenis_arsip_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{
                            $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="lokasi_arsip" class="col-sm-2 col-form-label">Lokasi Arsip</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="lokasi_arsip" name="lokasi_arsip"
                        value="{{ old('lokasi_arsip') ?? $arsip->lokasi_arsip }}" required>
                    @error('lokasi_arsip')
                    <div class="text-danger mt-2 d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="jenis_id" class="col-sm-2 col-form-label">Jenis Arsip</label>
                <div class="col-sm-4">
                    <select name="jenis_id" id="jenis_id" class="form-control" required>
                        <option value="" selected disabled>Pilih Jenis</option>
                        @foreach ($jenis as $item)
                        <option {{ $arsip->jenis_id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{
                            $item->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div class="form-group row">
                <label for="no_berkas" class="col-sm-2 col-form-label">No. Berkas</label>
                <div class="col-sm-4">
                    <input type="text" class="form-control" name="no_berkas" id="no_berkas"
                        value="{{ old('no_berkas') ?? $arsip->no_berkas }}" required>
                    @error('no_berkas')
                    <div class="text-danger mt-2 d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="judul_arsip" class="col-sm-2 col-form-label">No. Box</label>
                <div class="col-sm-10">
                    <input type="text" class="form-control" id="no_box" name="no_box"
                        value="{{ old('no_box') ?? $arsip->no_box }}" required>
                    @error('no_box')
                    <div class="text-danger mt-2 d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <label for="tahun" class="col-sm-2 col-form-label">Tahun</label>
                <div class="col-sm-4">
                    <select name="tahun" id="tahun" class="form-control" required>
                        <option value="" selected disabled>Pilih Tahun</option>
                        @for ($i = 1980; $i <= date('Y'); $i++) <option {{ $arsip->tahun == $i ? 'selected' : '' }}
                            value="{{ $i }}">{{ $i }}</option>
                            @endfor
                    </select>
                </div>
            </div>

            {{-- <div class="form-group row">
                <label for="id_pencipta_arsip" class="col-sm-2 col-form-label">Pencipta Arsip</label>
                <div class="col-sm-10">
                    <select name="id_pencipta_arsip" id="id_pencipta_arsip" class="form-control select2">
                        <option value="" disabled selected>-- Pilih Pencipta Arsip --</option>
                        @foreach ($models as $model => $strukturals)
                        <optgroup label="{{ $model }}">
                            @foreach ($strukturals as $s)
                            <option {{ $arsip->id_pencipta_arsip == $s->id ? 'selected': '' }} value="{{ $s->id }}">{{
                                $s->name }}</option>
                            @endforeach
                        </optgroup>
                        @endforeach
                    </select>
                    @error('id_encipta_arsip')
                    <div class="text-danger mt-2 d-block">{{ $message }}</div>
                    @enderror

                </div>
            </div> --}}

            @if (Auth::user()->hasRole('super admin') || Auth::user()->hasRole('admin'))
            {{-- <div class="form-group row">
                <label for="id_pencipta_arsip" class="col-sm-2 col-form-label">Pencipta Arsip</label>
                <div class="col-sm-10">
                    <select name="id_pencipta_arsip" id="id_pencipta_arsip" class="form-control select2">
                        <option value="" disabled selected>-- Pilih Pencipta Arsip --</option>
                        @foreach ($models as $model => $strukturals)
                        <optgroup label="{{ $model }}">
                            @foreach ($strukturals as $s)
                            <option {{ $arsip->id_pencipta_arsip == $s->id ? 'selected': '' }} value="{{ $s->id }}">{{
                                $s->name }}</option>
                            @endforeach
                        </optgroup>
                        @endforeach
                    </select>
                    @error('id_encipta_arsip')
                    <div class="text-danger mt-2 d-block">{{ $message }}</div>
                    @enderror

                </div>
            </div> --}}
            <div class="form-group row">
                <label for="id_pencipta_arsip" class="col-sm-2 col-form-label">Pencipta Arsip</label>
                <div class="col-sm-10">
                    <select name="id_pencipta_arsip" id="id_pencipta_arsip" class="form-control" readonly>
                        <option value="{{ $arsip->id_pencipta_arsip }}" selected>{{
                            $arsip->struktural_detail->name }}
                        </option>
                    </select>
                </div>
            </div>
            @else
            {{-- ini untuk user biasa --}}
            <div class="">
                <div class="form-group row">
                    <label for="id_pencipta_arsip" class="col-sm-2 col-form-label">Pencipta Arsip</label>
                    <div class="col-sm-10">
                        <select name="id_pencipta_arsip" id="id_pencipta_arsip" class="form-control">
                            <option value="{{ $user->struktural_detail->id }}" selected>{{
                                $user->struktural_detail->name }}
                            </option>
                        </select>
                    </div>
                </div>
            </div>
            @endif


            <div class="form-group row">
                <label for="uraian_arsip" class="col-sm-2 col-form-label">Uraian</label>
                <div class="col-sm-10">
                    <textarea class="form-control" name="uraian_arsip" id="uraian_arsip" cols="30" rows="5"
                        required>{{ old('uraian_arsip') ?? $arsip->uraian_arsip }}</textarea>
                    @error('uraian_arsip')
                    <div class="text-danger mt-2 d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>


            <div class="form-group row">
                <label for="file_arsip" class="col-sm-2 col-form-label">Unggah File</label>
                <div class="col-sm-10">
                    <input type="file" name="file_arsip" id="file_arsip" class="form-control">
                    <span class="text-danger"><i>Kosongkan jika tidak ubah file</i></span>
                </div>
            </div>

            <input type="hidden" name="user_id" id="user_id" value="{{ $arsip->user_id }}">


            <div class="form-group row">
                <label for="" class="col-sm-2 col-form-label"></label>
                <div class="col-sm-6 d-flex">
                    <button class="btn btn-primary btn-sm mt-3 mr-2">Simpan</button>
                    <a href="{{ route('arsip.data') }}" class="btn btn-danger btn-sm mt-3 mr-2">Kembali</a>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection