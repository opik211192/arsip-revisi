@extends('adminlte::page')

@section('title', 'Tambah User')

@section('content_header')
<div></div>
@stop
@section('content')
@if (session('pesan'))
<div class="alert alert-success">
    {{ session('pesan') }}
</div>
@endif
{{-- <div class="container"> --}}
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header bg-dark">{{ __('Register User') }}</div>

                <div class="card-body">
                    {{-- <form method="POST" action="{{ route('register') }}"> --}}
                        <form method="POST" action="{{ route('user.store') }}">
                            @csrf

                            <div class="form-group row">
                                <label for="name" class="col-md-4 col-form-label text-md-right">{{ __('Name') }}</label>

                                <div class="col-md-6">
                                    <input id="name" type="text"
                                        class="form-control @error('name') is-invalid @enderror" name="name"
                                        value="{{ old('name') }}" required autocomplete="name" autofocus>

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
                                        value="{{ old('email') }}" required autocomplete="email">

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
                                    <input id="username" type="text"
                                        class="form-control @error('username') is-invalid @enderror" name="username"
                                        value="{{ old('username') }}" required autocomplete="username" autofocus>

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
                                        <option value="{{ old('struktural') }}" selected disabled>Pilih Struktural
                                        </option>
                                        @foreach ($strukturals as $struktural)
                                        <option value="{{ $struktural->id }}">{{ $struktural->name }}</option>
                                        @endforeach
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
                                        <option value="{{ old('struktural_id') }}" selected disabled>Pilih Sub</option>
                                    </select>
                                </div>
                            </div>

                            <div class="form-group row">
                                <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password')
                                    }}</label>

                                <div class="col-md-6">
                                    <input id="password" type="password"
                                        class="form-control @error('password') is-invalid @enderror" name="password"
                                        required autocomplete="new-password">

                                    @error('password')
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $message }}</strong>
                                    </span>
                                    @enderror
                                </div>
                            </div>



                            <div class="form-group row">
                                <label for="password-confirm" class="col-md-4 col-form-label text-md-right">{{
                                    __('Confirm
                                    Password') }}</label>

                                <div class="col-md-6">
                                    <input id="password-confirm" type="password" class="form-control"
                                        name="password_confirmation" required autocomplete="new-password">
                                </div>
                            </div>

                            <div class="form-group row mb-0">
                                <div class="col-md-6 offset-md-4">
                                    <button type="submit" class="btn btn-primary">
                                        {{ __('Simpan') }}
                                    </button>
                                </div>
                            </div>
                        </form>
                </div>
            </div>
        </div>
    </div>
    {{--
</div> --}}
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
@endpush