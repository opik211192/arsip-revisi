@extends('adminlte::master')

@php( $dashboard_url = View::getSection('dashboard_url') ?? config('adminlte.dashboard_url', 'home') )

@if (config('adminlte.use_route_url', false))
@php( $dashboard_url = $dashboard_url ? route($dashboard_url) : '' )
@else
@php( $dashboard_url = $dashboard_url ? url($dashboard_url) : '' )
@endif

@section('adminlte_css')
@stack('css')
@yield('css')
@stop

@section('classes_body'){{ ($auth_type ?? 'login') . '-page' }}@stop

@section('body')
<div class="{{ $auth_type ?? 'login' }}-box">

    {{-- Logo --}}
    <div class="{{ $auth_type ?? 'login' }}-logo text-center">
        <a href="{{ $dashboard_url }}" class="text-decoration-none d-flex flex-column align-items-center">
            <img src="{{ asset('img/login.png') }}" height="60" class="mb-2" alt="Logo M-ARSIP">
            <h3 class="fw-bold text-uppercase mb-0"
                style="color: #fff; letter-spacing: 2px; text-shadow: 0 0 8px rgba(0, 192, 239, 0.6);">
                <span style="color: #00c0ef;">M</span>-ARSIP
            </h3>
        </a>
    </div>

    {{-- Card Box --}}
    <div class="card {{ config('adminlte.classes_auth_card', 'card-outline card-primary') }}">

        {{-- Card Header --}}
        @hasSection('auth_header')
        <div class="card-header {{ config('adminlte.classes_auth_header', '') }}">
            <h3 class="card-title float-none text-center">
                @yield('auth_header')
            </h3>
        </div>
        @endif

        {{-- Card Body --}}
        <div class="card-body {{ $auth_type ?? 'login' }}-card-body {{ config('adminlte.classes_auth_body', '') }}">
            @yield('auth_body')
        </div>

        {{-- Card Footer --}}
        @hasSection('auth_footer')
        <div class="card-footer {{ config('adminlte.classes_auth_footer', '') }}">
            @yield('auth_footer')
        </div>
        @endif

    </div>

</div>
@stop

@section('adminlte_js')
@stack('js')
@yield('js')
@stop