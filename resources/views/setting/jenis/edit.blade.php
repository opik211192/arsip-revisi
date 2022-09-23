@extends('adminlte::page')

@section('title', 'Edit Jenis')

@section('content_header')
<div></div>
@stop
@section('content')
<div class="card mb-4">
    <div class="card-header mystyle">Edit Jenis</div>
    <div class="card-body">
        <form action="{{ route('jenis.edit', $jenis) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Jenis Arsip</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ old('name') ?? $jenis->name }}">
            </div>
            <div class="form-group">
                <label for="descripion">Keterangan</label>
                <input type="text" class="form-control" id="description" name="description"
                    value="{{ old('description') ?? $jenis->description }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('jenis.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection