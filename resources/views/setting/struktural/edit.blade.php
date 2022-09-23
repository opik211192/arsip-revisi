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

<div class="col-md-8">
    <div class="card mb-3">
        <div class="card-header text-white mystyle">Edit Struktural</div>
        <div class="card-body">
            <form action="" method="post">
                @csrf
                @method('PUT')
                <div class="form-group">
                    <label for="role">Nama Sub</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="{{ old('name') ?? $struktural_detail->name }}">
                    @error('jenis')
                    <div class="text-danger mt-2 d-block">{{ $message }}</div>
                    @enderror
                </div>
                <div class="form-group">
                    <label for="descripion">Struktural <a href="{{ route('struktural_create.create') }}"
                            class="btn btn-sm btn-primary"><i class="fas fa-plus"></i>
                        </a></label>
                    <select name="struktural_id" id="struktural_id" class="form-control">
                        <option value="" selected disabled>Pilih Struktural</option>
                        @foreach ($strukturals as $item)
                        <option {{ $struktural_detail->struktural_id == $item->id ? 'selected' : '' }} value="{{
                            $item->id }}">{{ $item->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('struktural.index') }}" class="btn btn-danger">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection