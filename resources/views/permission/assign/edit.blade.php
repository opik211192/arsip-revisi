{{-- @extends('layouts.back') --}}
@extends('layouts.base2')

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


{{-- @section('content') --}}
@section('body-content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card mb-3">
    <div class="card-header bg-dark">Assign Permission Sync</div>
    <div class="card-body">
        <form action="{{ route('assign.edit', $role) }}" method="post">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="role">Role Name</label>
                <select name="role" id="role" class="form-control">
                    <option disabled selected>Choose a role</option>
                    @foreach ($roles as $item)
                    <option {{ $role->id == $item->id ? 'selected' : '' }} value="{{ $item->id }}">{{ $item->name }}
                    </option>
                    @endforeach
                </select>
                @error('role')
                <div class="text-danger mt-2 d-block">{{ $message }}</div>
                @enderror
            </div>

            <div class="form-group">
                <label for="permissions">Permissions</label>
                <select name="permissions[]" id="permissions" class="form-control select2" multiple>
                    @foreach ($permissions as $permission)
                    <option {{ $role->permissions()->find($permission->id) ? 'selected' : '' }} value="{{
                        $permission->id }}">{{ $permission->name }}</option>
                    @endforeach
                </select>
                @error('permissions')
                <div class="text-danger mt-2 d-block">{{ $message }}</div>
                @enderror
            </div>
            <button type="submit" class="btn btn-secondary">Sync</button>
        </form>
    </div>
</div>
@endsection