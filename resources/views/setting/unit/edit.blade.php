{{-- @extends('layouts.back') --}}
@extends('layouts.base2')

{{-- @section('content') --}}
@section('body-content')
<div class="card mb-4">
    <div class="card-header text-white mystyle">Edit Unit</div>
    <div class="card-body">
        <form action="{{ route('unit.edit', $unit) }}" method="post">
            @csrf
            @method('PUT')
            <div class="form-group">
                <label for="name">Nama Unit</label>
                <input type="text" class="form-control" name="name" id="name" value="{{ old('name') ?? $unit->name }}">
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('unit.index') }}" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection