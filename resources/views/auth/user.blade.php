@extends('adminlte::page')

@section('title', 'Data user')

@section('content_header')
<div></div>
@stop
@section('content')
@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif
@if (session('pesan'))
<div class="alert alert-danger">
    {{ session('pesan') }}
</div>
@endif
<div class="card">
    <div class="card-header bg-dark">Data User</div>
    <div class="card-body">
        <table class="table table-hover">
            <tr>
                <th>#</th>
                <th style="width: 160px">Name</th>
                <th>Username</th>
                <th>Email</th>
                <th>Unit Kerja</th>
                <th>Action</th>
            </tr>
            @foreach ($users as $index => $user)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $user->name }} ({{ implode(', ', $user->getRoleNames()->toArray()) }})</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->struktural->name }} || {{ $user->struktural_detail->name }}</td>
                <td>
                    <div class="d-flex">

                        {{-- SUPER ADMIN & ADMIN --}}
                        @if(auth()->user()->hasAnyRole(['super admin', 'admin']))
                        <a href="{{ route('user.edit', $user) }}" class="btn btn-success btn-sm">
                            <i class="fas fa-pen"></i>
                        </a>&nbsp;

                        <form action="{{ route('user.delete', $user) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm" onclick="return confirm('Are you sure?')">
                                <i class="fas fa-trash"></i>
                            </button>
                        </form>

                        {{-- USER BIASA → HANYA DIRI SENDIRI --}}
                        @elseif(auth()->id() === $user->id)
                        <a href="{{ route('user.edit', $user) }}" class="btn btn-success btn-sm" title="Edit">
                            <i class="fas fa-pen"></i>
                        </a>
                        @endif

                    </div>
                </td>
            </tr>
            @endforeach
        </table>
        </di @endsection