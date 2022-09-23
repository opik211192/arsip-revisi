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

<div class="card mb-3">
    <div class="card-header mystyle">Tambah Struktural</div>
    <div class="card-body">
        <form action="{{ route('struktural_create.create') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="role">Nama Struktural</label>
                <input type="text" class="form-control" id="name" name="name">
                @error('jenis')
                <div class="text-danger mt-2 d-block">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
            <a href="{{ route('struktural.create') }}" class="btn btn-danger">Kembali</a>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header text-white mystyle">Data Struktural</div>
    <div class="card-body">
        <table class="table table-striped">
            <tr>
                <th>#</th>
                <th>Nama Struktural</th>
                <th>Action</th>
            </tr>
            @foreach ($strukturals as $index => $struktural)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $struktural->name }}</td>
                <td>
                    <div class="d-flex">
                        <a href="{{ route('struktural_create.edit', $struktural) }}" class="btn btn-success btn-sm"
                            data-toggle="Update" data-placement="top" title="Update"><i
                                class="fas fa-pen"></i></a>&nbsp;
                        <form action="{{ route('struktural_create.delete', $struktural) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" data-toggle="Delete"
                                data-placement="top" title="Delete" onclick="return confirm('Hapus data ini')"><i
                                    class="fas fa-trash"></i></button>
                        </form>
                    </div>

                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection