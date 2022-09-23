<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/css/all.min.css"
        integrity="sha512-KfkfwYDsLkIlwQp6LFnl8zNdLGxu9YAA1QvwINks4PhcElQSvqcyVLLD9aMhXd13uQjoXtEKNosOWaZqXgel0g=="
        crossorigin="anonymous" referrerpolicy="no-referrer" />

    <!-- Scripts -->

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    {{--
    <link rel="stylesheet" href="{{ asset('css/adminlte.min.css') }}"> --}}
    @yield('adminlte')
    @yield('styles2')

    <link href="{{ asset('/css/app.css') }}" rel="stylesheet">

    <!-- Styles box dashboard -->
    <link rel="stylesheet" href="{{ asset('/css/box.css') }}">
    <link rel="stylesheet" href="{{ asset('/css/form.css') }}">


    @yield('styles')
</head>

<body>
    <div id="app">
        @yield('body')
    </div>


    <script src="{{ asset('/js/app.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.1/js/all.min.js"></script>
    <script src="https://unpkg.com/axios/dist/axios.min.js"></script>
    @stack('scripts')
    <script>
        $(function () {
       $('[data-toggle="tooltip"]').tooltip()
    });

    $(function () {
        setTimeout(function(){
          $('.alert').slideUp('slow');
         }, 5000);
    });
    </script>
</body>

</html>