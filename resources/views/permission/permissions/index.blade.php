@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
<div></div>
@stop
@section('content')
<div class="card mb-4">
    <div class="card-header bg-dark">Create New Permissions</div>
    <div class="card-body">
        <form action="{{ route('permissions.create') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name">
            </div>
            <div class="form-group">
                <label for="guard_name">Guard Name</label>
                <input type="text" class="form-control" name="guard_name" id="guard_name"
                    placeholder='Default to "Web"'>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Create</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-dark">Table Of Permission</div>
    <div class="card-body">
        <table class="table table-hover">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Guard Name</th>
                <th>Create At</th>
                <th>Act</th>
            </tr>
            @foreach ($permissions as $index => $permission)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $permission->name }}</td>
                <td>{{ $permission->guard_name }}</td>
                <td>{{ $permission->created_at->format("d F Y") }}</td>
                <td>
                    <div class="d-flex">
                        <a href="{{ route('permissions.edit', $permission) }}" class="btn btn-success btn-sm"
                            data-toggle="Update" data-placement="top" title="Update"><i
                                class="fas fa-pen"></i></a>&nbsp;
                        <form action="{{ route('permissions.delete', $permission) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm" data-toggle="Delete"
                                data-placement="top" title="Delete" onclick="return confirm('Are you sure?')"><i
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