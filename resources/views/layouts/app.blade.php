<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://cdn.jsdelivr.net/npm/showdown/dist/showdown.min.js"></script>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laravel App</title>
    <!-- Add your stylesheets -->
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
</head>
<body>
    @yield('content')

    @include('components.chatbot')
    <!-- Add your scripts -->
    <script src="{{ asset('js/chatbot.js') }}"></script>
</body>
</html>
