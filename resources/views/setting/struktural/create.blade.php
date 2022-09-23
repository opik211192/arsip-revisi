@extends('adminlte::page')

@section('title', 'Struktural')

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
        <div class="card-header mystyle">Tambah Struktural</div>
        <div class="card-body">
            <form action="{{ route('struktural.create') }}" method="post">
                @csrf
                <div class="form-group">
                    <label for="role">Nama Sub</label>
                    <input type="text" class="form-control" id="name" name="name">
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
                        @foreach ($strukturals as $struktural)
                        <option value="{{ $struktural->id }}">{{ $struktural->name }}</option>
                        @endforeach
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('struktural.index') }}" class="btn btn-danger">Kembali</a>
            </form>
        </div>
    </div>
</div>
{{--
<button type="button" class="btn-sm btn-primary" data-toggle="modal" data-target="#exampleModal">
    <i class="fa-solid fa-plus"></i>
</button>
<!-- Modal -->
<div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
    aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Tambah Data</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="role">Nama Struktural</label>
                        <input type="text" class="form-control" id="name" name="name">
                        @error('jenis')
                        <div class="text-danger mt-2 d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
            </div>
        </div>
    </div>
</div> --}}

@endsection