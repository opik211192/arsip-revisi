{{-- @extends('layouts.back') --}}
@extends('layouts.base2')

@section('body-content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card mb-3">
    <div class="card-header text-white mystyle">Tambah Unit</div>
    <div class="card-body">
        <form action="{{ route('unit.create') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="role">Nama Unit</label>
                <input type="text" class="form-control" id="name" name="name">
                @error('jenis')
                <div class="text-danger mt-2 d-block">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header text-white mystyle">Data Unit</div>
    <div class="card-body">
        <table class="table table-striped">
            <tr>
                <th>#</th>
                <th>Nama Unit</th>
                <th>Action</th>
            </tr>
            @foreach ($units as $index => $unit)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $unit->name }}</td>
                <td>
                    <div class="d-flex">
                        <a href="{{ route('unit.edit', $unit) }}" class="btn btn-success btn-sm" data-toggle="Update"
                            data-placement="top" title="Update"><i class="fa-solid fa-user-pen"></i></a>&nbsp;
                        <form action="{{ route('unit.delete', $unit) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-secondary btn-sm" data-toggle="Delete"
                                data-placement="top" title="Delete" onclick="return confirm('Hapus data ini')"><i
                                    class="fa-solid fa-trash-can"></i></button>
                        </form>
                    </div>

                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection