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

    <script type="text/javascript">
        function googleTranslateElementInit() {
            console.log("Google Translate Element Initialized");
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,ur',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
    </script>

</head>
<body>
    @yield('content')

</body>
<script src="//translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</html>
