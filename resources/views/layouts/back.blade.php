@extends('layouts.base')


@section('body')
        <div class="container-fluid">
         <div class="">
            <div class="row">
                <div class="col-md-3" style="background-color: slategray">
                    <x-layouts.sidebar></x-layouts.sidebar>
                </div>
                <div class="col-md-9">
                    <div class="py-3">
                        @yield('content')
                    </div>
                </div>
            </div>
         </div>
        </div>
@endsection