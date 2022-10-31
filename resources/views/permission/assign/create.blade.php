@extends('adminlte::page')
@section('title', 'Create Assign Permission')

@section('content_header')
<div></div>
@stop
@section('styles')
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.min.css" rel="stylesheet" />
@endsection

@push('scripts')
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.full.js"></script>

<script>
    $(document).ready(function() {
            $('.select2').select2({
                placeholder: "Select Permissions"
            });
        });
</script>
@endpush

@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card mb-3">
    <div class="card-header bg-dark">Assign Permission</div>
    <div class="card-body">
        <form action="{{ route('assign.create') }}" method="post">
            @csrf
            <div class="form-group">
                <label for="role">Role Name</label>
                <select name="role" id="role" class="form-control">
                    <option disabled selected>Choose a role</option>
                    @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
                @error('role')
                <div class="text-danger mt-2 d-block">{{ $message }}</div>
                @enderror
            </div>
            <div class="form-group">
                <label class="mr-3">Permission</label>

                {{-- <label for="permissions">Permissions</label> --}}
                {{-- <select name="permissions[]" id="permissions" class="form-control select2" multiple>
                    @foreach ($permissions as $permission)
                    <option value="{{ $permission->id }}">{{ $permission->name }}</option>
                    @endforeach
                </select> --}}
                <div class="row">
                    <div class="col-md-6">

                        @foreach ($permissions as $permission)
                        <div class="form-check form-check-inline">
                            <input type="checkbox" class="form-check-input" id="permissions" name="permissions[]"
                                value="{{ $permission->id }}">
                            <label class="form-check-label">{{ $permission->name }}</label>
                        </div>
                        @endforeach
                    </div>

                </div>
                @error('permissions')
                <div class="text-danger mt-2 d-block">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-primary btn-sm">Assign</button>
        </form>
    </div>
</div>

<div class="card">
    <div class="card-header bg-dark">Table of Role & Permission</div>
    <div class="card-body">
        <table class="table table-hover">
            <tr>
                <th>#</th>
                <th>Name</th>
                <th>Guard Name</th>
                <th>The Permission</th>
                <th>Action</th>
            </tr>
            @foreach ($roles as $index => $role)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $role->name }}</td>
                <td>{{ $role->guard_name }}</td>
                <td>{{ implode(', ', $role->getPermissionNames()->toArray()); }}</td>
                <td>
                    <a href="{{ route('assign.edit', $role) }}">Sync</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection