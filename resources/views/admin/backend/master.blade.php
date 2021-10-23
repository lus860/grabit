<!DOCTYPE html>
<html>

<head>
    <title>Admin Backend</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Fonts -->
    <link href='https://fonts.googleapis.com/css?family=Lato:300,400,700,900' rel='stylesheet' type='text/css'>
    <!-- CSS Libs -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/admin/font-awesome/css/font-awesome.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/animate.css') }}">
    <!-- CSS App -->
    <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/style-backend.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/flat-blue.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ asset('/admin/css/bootstrap.css') }}">


    <!-- SCRIPTS -->
{{--    <script src="{{ asset('/js/jquery.min.js') }}" type="text/javascript"></script>--}}
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="{{asset('/admin/js/bootstrap.js')}}" ></script>
    @stack('head')
{{--    {!! Rapyd::styles(false) !!}--}}
{{--    {!! Rapyd::head() !!}--}}
    <style>
        .badge-custom{
            float: right;
            background: #16a218;
            margin-top: 15px;
        }
        .for-correct-badge{
            display: initial;
            max-width: 100px;
        }
    </style>
</head>
<body class="flat-blue">
<div class="app-container expanded">
    <div class="row content-container">
        @include('admin.backend.navbar')
        @include('admin.backend.sidebar')
        @yield('content')
    </div>
    <!-- FOOTER -->
    @include('admin.backend.footer')
            <!-- //FOOTER -->
</div>
@stack('js')
</body>
<script src="{{asset('/admin/js/script.js')}}"></script>
</html>
