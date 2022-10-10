@extends('adminlte::page')

@section('title', 'Edit User')

@section('content_header')
<div></div>
@stop
@section('content')

@if (session('success'))
<div class="alert alert-success">
    {{ session('success') }}
</div>
@endif

@if (session('error'))
<div class="alert alert-danger">
    {{ session('error') }}
</div>
@endif

<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header mystyle text-white">{{ __('Register User Edit') }}</div>

                <div class="card-body">
                    {{-- <form method="POST" action="{{ route('register.update', $user->id) }}"> --}}
                        <form method="POST" action="{{ route('user.update', $user) }}">
                            @csrf
                            @method('PUT')

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') ?? $user->name }}" required autocomplete="name" autofocus>

                                    @error('name')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address')
                                    }}</label>

                                <div class="col-md-6">
                                    <input id="email" type="email"
                                        class="form-control @error('email') is-invalid @enderror" name="email"
                                        value="{{ old('email') ?? $user->email }}" required autocomplete="email">

                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>


                            <div class="form-group row">
                                <label for="username" class="col-md-4 col-form-label text-md-right">{{ __('Username')
                                    }}</label>

                                <div class="col-md-6">
                                    <input id="username" type="username"
                                        class="form-control @error('username') is-invalid @enderror" name="username"
                                        value="{{ old('username') ?? $user->username }}" required
                                        autocomplete="username">

                                    @error('username')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="struktural" class="col-md-4 col-form-label text-md-right">{{
                                    __('Struktural')
                                    }}</label>

                                <div class="col-md-6">
                                    <select name="struktural_id" id="struktural_id" class="form-control">
                                        {{-- <option value="{{ old('struktural_id') }}" selected disabled>Pilih
                                            Struktural
                                        </option> --}}
                                        {{-- @foreach ($strukturals as $item)
                                        <option {{ $struktur[0]->struktural_id == $item->id ? 'selected' : ''}}
                                            value="{{
                                            $item->id }}">{{ $item->name }}</option>
                                        @endforeach --}}
                                        <option value="{{ $user->struktural_id }}">{{ $user->struktural->name }}
                                        </option>
                                    </select>
                                    @error('struktural')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="struktural" class="col-md-4 col-form-label text-md-right">
                                </label>
                                <div class="col-md-6">
                                    <select name="struktural_detail_id" id="struktural_detail_id" class="form-control">
                                        {{-- <option value="{{ old('struktural_detail_id') }}" selected disabled>Pilih
                                            Sub
                                        </option> --}}
                                        {{-- @foreach ($get_struktural_detail as $item)
                                        <option {{ $struktur[0]->struktural_detail_id == $item->id ? 'selected': '' }}
                                            value="{{ $item->id }}">{{ $item->name }}</option>
                                        @endforeach --}}
                                        <option value="{{ $user->struktural_detail_id }}">{{
                                            $user->struktural_detail->name }}</option>
                                    </select>
                                </div>
                            </div>

                            <div class="card mt-4 border-danger">
                                <div class="card-header bg-warning text-white">
                                    <i>Kosongkan jika tidak ubah password</i>
                                </div>
                                <div class="card-body">
                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Old
                                            Password') }}</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="oldpassword">

                                            @error('oldpassword')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('New
                                            Password') }}</label>

                                        <div class="col-md-6">
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror"
                                                name="newpassword">

                                            @error('password')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="form-group row">
                                        <label for="newpassword-confirm"
                                            class="col-md-4 col-form-label text-md-right">{{
                                            __('Confirm Password') }}</label>

                                        <div class="col-md-6">
                                            <input id="newpassword-confirm" type="password" class="form-control"
                                                name="newpassword_confirmation">
                                        </div>
                                    </div>
                                </div>
                            </div>



                            <div class="form-group row mb-0 mt-4">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Update') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<script src="https://unpkg.com/axios/dist/axios.min.js"></script>
<script>
    $(function () {
        $('#struktural_id').on('change', function () {
            axios.post('{{ route('ambilData') }}', {id: $(this).val()})
                .then(function (response) {
                    $('#struktural_detail_id').empty();
                    $('#struktural_detail_id').append(`<option selected value="" disabled>Pilih Sub</option>`);
                    $.each(response.data, function (id, name) {
                        $('#struktural_detail_id').append(new Option(name, id))
                    })

                });

        });

    });

</script>
{{-- <script>
    $(document).ready(function () {
        $('#struktural_id').prop('disabled', true);
        $('#struktural_detail_id').prop('disabled', true );
        return false;
    });
</script> --}}
@endpush