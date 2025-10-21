@extends('adminlte::auth.auth-page', ['auth_type' => 'login'])
@section('title', 'Login Sistem Arsip')

@section('adminlte_css_pre')
<link rel="stylesheet" href="{{ asset('vendor/icheck-bootstrap/icheck-bootstrap.min.css') }}">
@stop

@section('adminlte_css')
<style>
    body.login-page {
        /* Baris ini diubah */
        background: url('{{ asset("img/background.png") }}') no-repeat center center fixed;
        -webkit-background-size: cover;
        -moz-background-size: cover;
        -o-background-size: cover;
        background-size: cover;
        /* Akhir perubahan */

        background-attachment: fixed;
        font-family: 'Poppins', sans-serif;
    }

    .login-box {
        width: 380px;
        margin-top: 4%;
    }

    .card {
        background: rgba(255, 255, 255, 0.9);
        border: none;
        border-radius: 20px;
        box-shadow: 0 10px 25px rgba(0, 0, 0, 0.1);
    }

    .login-logo a {
        font-weight: 600;
        color: #1d2b64 !important;
        letter-spacing: 0.5px;
    }

    .input-group-text {
        background-color: #1d2b64;
        color: white;
        border: none;
        border-radius: 0 10px 10px 0;
    }

    .form-control {
        border-radius: 10px 0 0 10px;
        border: 1px solid #ccc;
    }

    .btn-primary {
        background: #1d2b64;
        border: none;
        border-radius: 10px;
        transition: all 0.3s ease;
    }

    .btn-primary:hover {
        background: #162251;
        transform: scale(1.03);
    }

    .login-box-msg {
        font-size: 1.1rem;
        color: #333;
        margin-bottom: 20px;
    }

    .footer-text {
        font-size: 0.85rem;
        color: #555;
    }
</style>
@stop

@php($login_url = View::getSection('login_url') ?? config('adminlte.login_url', 'login'))
@if (config('adminlte.use_route_url', false))
@php($login_url = $login_url ? route($login_url) : '')
@else
@php($login_url = $login_url ? url($login_url) : '')
@endif

@section('auth_header')
<div class="text-center mb-3">
    <img src="{{ asset('img/citanduy.png') }}" alt="Logo" width="70" class="mb-2">
    {{-- <h4><strong>MANAJEMEN ARSIP</strong></h4> --}}
    <p class="text-muted">Silakan login untuk melanjutkan</p>
</div>
@stop

@section('auth_body')
<form method="POST" action="{{ route('login') }}">
    @csrf

    {{-- Username field --}}
    <div class="input-group mb-3">
        <input type="text" name="username" class="form-control @error('username') is-invalid @enderror"
            value="{{ old('username') }}" placeholder="Username" autofocus>
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-user"></span>
            </div>
        </div>
        @error('username')
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    {{-- Password field --}}
    <div class="input-group mb-4">
        <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
            placeholder="Password">
        <div class="input-group-append">
            <div class="input-group-text">
                <span class="fas fa-lock"></span>
            </div>
        </div>
        @error('password')
        <span class="invalid-feedback d-block" role="alert">
            <strong>{{ $message }}</strong>
        </span>
        @enderror
    </div>

    {{-- Submit --}}
    <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-block">
            <span class="fas fa-sign-in-alt"></span> Login
        </button>
    </div>
</form>
@stop

@section('auth_footer')
<div class="text-center mt-3 footer-text">
    <small>© {{ date('Y') }} SISDA BBWS Citanduy</small>
</div>
@stop