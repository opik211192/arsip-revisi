@extends('adminlte::page')

@section('title', 'Data Struktural')

@section('content_header')
<div></div>
@stop
@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="col-md-12">
    <div class="card">
        <div class="card-header mystyle">Data Struktural</div>
        <div class="card-body">
            <a href="{{ route('struktural.create') }}" class="mb-2 btn btn-sm btn-primary">Tambah</a>
            <table class="table table-striped table-bordered">
                <tr>
                    <th>#</th>
                    <th>Nama Sub</th>
                    <th>Struktural</th>
                    <th>Action</th>
                </tr>
                @foreach ($details as $index => $struktural_detail)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $struktural_detail->name }}</td>
                    <td>{{ $struktural_detail->struktural->name }}</td>
                    <td>
                        <div class="d-flex">
                            <a href="{{ route('struktural.edit', $struktural_detail) }}" class="btn btn-success btn-sm"
                                data-toggle="Update" data-placement="top" title="Update"><i
                                    class="fas fa-pen"></i></a>&nbsp;
                            <form action="{{ route('struktural.delete', $struktural_detail) }}" method="post">
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

</div>
@endsection