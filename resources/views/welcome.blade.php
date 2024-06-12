<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <script defer src="/js/manifest.js"></script>
        <script defer src="/js/vendor.js"></script>
        <script defer src="/js/app.js"></script>
        <title>Laravel</title>
        <link href="{{ mix('css/app.css') }}" rel="stylesheet">
        <link href="{{ mix('css/signup.css') }}" rel="stylesheet">
        <link href="{{ mix('css/navBar.css') }}" rel="stylesheet">
        <link href="{{ mix('css/bootstrap.css') }}" rel="stylesheet">
        <link href="{{ mix('css/bootstrap.min.css') }}" rel="stylesheet">

        <style>
            body {
                font-family: 'Poppins';
            }
        </style>

    </head>
    <body>
        <div id="app"></div>
        <script src="{{ mix('js/app.js') }}"></script>
    </body>

</html>
