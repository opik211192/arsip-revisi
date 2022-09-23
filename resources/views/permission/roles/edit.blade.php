@extends('adminlte::page')

@section('title', 'Edit Roles')

@section('content_header')
<div></div>
@stop
@section('content')
<div class="card mb-4">
    <div class="card-header bg-dark">Edit Role</div>
    <div class="card-body">
        <form action="{{ route('roles.edit', $role) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Name</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ old('name') ?? $role->name }}">
            </div>
            <div class="form-group">
                <label for="guard_name">Guard Name</label>
                <input type="text" class="form-control" name="guard_name" id="guard_name"
                    value="{{ old('guard_name') ?? $role->guard_name }}" placeholder='Default to "Web"'>
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Update</button>
        </form>
    </div>
</div>
@endsection