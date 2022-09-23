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
    <div class="card-header">Sync Roles for {{ $user->name }}</div>
    <div class="card-body">
        <form action="{{ route('assign.user.edit', $user) }}" method="post">
            @method('PUT')
            @csrf
            <div class="form-group">
                <label for="user">User</label>
                <input type="text" name="email" id="email" class="form-control" value="{{ $user->email }}">
            </div>

            <div class="form-group">
                <label for="roles">Pick Roles</label>
                <select name="roles[]" id="roles" class="form-control select2" multiple>
                    @foreach ($roles as $role)
                    <option {{ $user->roles()->find($role->id) ? 'selected' : '' }} value="{{ $role->id }}">{{
                        $role->name }}</option>
                    @endforeach
                </select>
            </div>

            <button type="submit" class="btn btn-secondary">Sync</button>
        </form>
    </div>
</div>

@endsection