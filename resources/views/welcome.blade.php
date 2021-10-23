<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Grab It</title>

        <!-- Fonts -->

        <link rel="stylesheet" href="{{asset('css/app.css')}}">

        <link href="https://fonts.googleapis.com/css?family=Ubuntu:400,500,700,300" rel="stylesheet" type="text/css">
        <link href="https://fonts.googleapis.com/css?family=Signika+Negative:400,300,600,700" rel="stylesheet" type="text/css">
        <link rel="icon" href="{{asset('images/ico/favicon.ico')}}">
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{asset('images/ico/apple-touch-icon-144-precomposed.png')}}">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{asset('images/ico/apple-touch-icon-114-precomposed.png')}}">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{asset('images/ico/apple-touch-icon-72-precomposed.png')}}">
        <link rel="apple-touch-icon-precomposed" sizes="57x57" href="{{asset('images/ico/apple-touch-icon-57-precomposed.png')}}">
        logo-72.jpeg
        <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
        <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    </head>
    <body>
        <div id="app"></div>


        <script src="{{asset('js/app.js')}}"></script>
        <script src="https://maps.google.com/maps/api/js?sensor=true"></script>
    </body>
</html>
