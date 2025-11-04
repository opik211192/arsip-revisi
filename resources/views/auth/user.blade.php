@extends('adminlte::page')

@section('title', 'Data User')

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
    <div class="card-header bg-dark text-white">Data User</div>
    <div class="card-body">
        <table class="table table-hover">
            <thead>
                <tr>
                    <th>#</th>
                    <th style="width: 160px">Name</th>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Unit Kerja</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($users as $index => $user)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $user->name }} ({{ implode(', ', $user->getRoleNames()->toArray()) }})</td>
                    <td>{{ $user->username }}</td>
                    <td>{{ $user->email }}</td>
                    <td>
                        {{ optional($user->struktural)->name }} ||
                        {{ optional($user->struktural_detail)->name }}
                    </td>
                    <td>
                        <div class="d-flex gap-2">
                            {{-- Tombol Edit selalu tampil --}}
                            <a href="{{ route('user.edit', $user) }}" class="btn btn-success btn-sm" title="Edit">
                                <i class="fas fa-pen"></i>
                            </a>

                            {{-- Tombol Delete hanya untuk super admin & admin --}}
                            @if (auth()->user()->hasRole(['super admin', 'admin']))
                            <form action="{{ route('user.delete', $user) }}" method="POST"
                                onsubmit="return confirm('Yakin ingin menghapus user ini?')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-danger btn-sm" title="Delete">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                            @endif
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection