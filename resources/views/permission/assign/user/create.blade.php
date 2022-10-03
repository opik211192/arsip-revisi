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
                // placeholder: "Select Permissions"
            });
        });
</script>
@endpush


{{-- @section('content') --}}
@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

<div class="card mb-3">
    <div class="card-header bg-dark">Pick user by email address</div>
    <div class="card-body">
        <form action="{{ route('assign.user.create') }}" method="post">
            @csrf

            <div class="form-group">
                <label for="username">User</label>
                <select name="username" id="username" class="form-control select2">
                    <option value="">--Pilih User--</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->email }}">{{ $user->name }}</option>
                    @endforeach
                </select>
            </div>
            @error('username')
            <div class="text-danger mt-2 d-block">{{ $message }}</div>
            @enderror

            {{-- <div class="form-group">
                <label for="email">User</label>
                {{-- <input type="text" name="email" id="email" class="form-control"> --}}
                <select name="email" id="email" class="form-control select2">
                    <option value="">--Pilih User--</option>
                    @foreach ($users as $user)
                    <option value="{{ $user->email }}">{{ $user->name }}
                    </option>
                    @endforeach
                </select>
            </div>
            @error('email')
            <div class="text-danger mt-2 d-block">{{ $message }}</div>
            @enderror --}}

            <div class="form-group">
                <label for="roles">Pick Roles</label>
                <select name="roles[]" id="roles" class="form-control select2" multiple>
                    @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                    @endforeach
                </select>
            </div>
            @error('roles')
            <div class="text-danger mt-2 d-block">{{ $message }}</div>
            @enderror

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
                <th>The Roles</th>
                <th>Action</th>
            </tr>
            @foreach ($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }}</td>
                <td>{{ implode(', ', $user->getRoleNames()->toArray()); }}</td>
                <td>
                    <a href="{{ route('assign.user.edit', $user) }}">Sync</a>
                </td>
            </tr>
            @endforeach
        </table>
    </div>
</div>
@endsection