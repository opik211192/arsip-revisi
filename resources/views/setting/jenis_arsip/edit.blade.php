@extends('adminlte::page')

@section('title', 'Jenis Arsip')

@section('content_header')
<div></div>
@stop
@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header mystyle">Edit Jenis Arsip</div>
            <div class="card-body">
                <form action="{{ route('jenis_arsip.edit', $jenisArsip) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="form-group">
                        <label for="role">Nama Jenis Arsip</label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="{{ old('name') ?? $jenisArsip->name }}">
                        @error('jenis')
                        <div class="text-danger mt-2 d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Ubah</button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection