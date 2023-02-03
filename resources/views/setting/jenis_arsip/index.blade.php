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
            <div class="card-header mystyle">Tambah Jenis Arsip</div>
            <div class="card-body">
                <form action="{{ route('jenis_arsip.create') }}" method="post">
                    @csrf
                    <div class="form-group">
                        <label for="role">Nama Jenis Arsip</label>
                        <input type="text" class="form-control" id="name" name="name">
                        @error('jenis')
                        <div class="text-danger mt-2 d-block">{{ $message }}</div>
                        @enderror
                    </div>
                    <button type="submit" class="btn btn-primary">Tambah</button>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            {{-- <div class="card-header"><strong>Data Jenis Arsip</strong></div> --}}
            <div class="card-body">
                <table class="table table-striped">
                    <tr>
                        <th>#</th>
                        <th>Nama Jenis Arsip</th>
                        <th>Action</th>
                    </tr>
                    @foreach ($datas as $index => $jenisArsip)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $jenisArsip->name }}</td>
                        <td>
                            <div class="d-flex">
                                <a href="{{ route('jenis_arsip.edit', $jenisArsip) }}" class="btn btn-success btn-sm"
                                    data-toggle="Update" data-placement="top" title="Update"><i
                                        class="fas fa-pen"></i></a>&nbsp;
                                <form action="{{ route('jenis_arsip.delete', $jenisArsip) }}" method="post">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-danger btn-sm" data-toggle="Delete"
                                        data-placement="top" title="Delete"
                                        onclick="return confirm('Hapus data '+'{{ $jenisArsip->name }} ?')"><i
                                            class="fas fa-trash"></i></button>
                                </form>
                            </div>

                        </td>
                    </tr>
                    @endforeach
                </table>
            </div>
        </div>
    </div>
</div>





@endsection