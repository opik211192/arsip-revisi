@extends('adminlte::page')

@section('title', 'Edit Struktural')

@section('content_header')
<div></div>
@stop
@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card mb-3">
    <div class="card-header text-white mystyle">Edit Struktural</div>
    <div class="card-body">
        <form action="{{ route('struktural_create.edit', $struktural) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="role">Nama Struktural</label>
                <input type="text" class="form-control" id="name" name="name"
                    value="{{ old('name') ?? $struktural->name }}">
                @error('jenis')
                <div class="text-danger mt-2 d-block">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('struktural_create.create') }}" class="btn btn-danger">Kembali</a>
        </form>
    </div>
</div>

@endsection