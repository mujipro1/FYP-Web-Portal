@extends('layouts.chatbot-layout') 

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>

    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navBar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/superadmin.css') }}">
    <link rel="stylesheet" href="{{ asset('css/chatbot-sep.css') }}">
    <script src="{{ asset('js/alert.js') }}"></script>
    <script src="{{ asset('js/chatbot.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/showdown/dist/showdown.min.js"></script>
    
</head>
<script>
const farm_id = @json($farm_id);
</script>

<body>
    <div class="c1">

        <div class="container">
            <div id="navbar">
                @include('components.navbar')
            </div>
            @yield('content')
        </div>


        <div class='alertDiv fade justify-content-center align-items-center' id="alertDiv"></div>

        @if(Session::get('success') || Session::get('error'))
        @if(Session::get('success'))
        <script>
        showAlert("{{ Session::get('success') }}", 'success', 9000);
        </script>
        @php
        Session::forget('success');
        @endphp
        @endif

        @if(Session::get('error'))
        <script>
        showAlert("{{ Session::get('error') }}", 'error', 9000);
        </script>
        @php
        Session::forget('error');
        @endphp
        @endif
        @endif

        <div class="container-fluid">
            <div class="row chatbot-sep-chacha">
                <img src="{{ asset('images/kleio.png') }}" class="chacha-pura" style="display:none" id="chacha-pura">
                <div class="temp-msg-chatbot-parent" id="temp-msg-chatbot-parent">
                    <div class="temp-msg-chatbot">
                        <div class="d-flex justify-content-center align-items-center flex-row">
                            <div>
                                <img src="{{ asset('images/kleio.png') }}" class="img-fluid chacha-start">
                            </div>
                            <div class="px-3">
                                <h2>Hi, I'm Chacha Ameer</h2>
                                <p>How can I help you today?</p>

                                <div class="mt-4">

                                    <p class="chacha-question my-2" onclick="sendDefaultMessage(1)">What are some famous crops in Punjab?</p>
                                    <p class="chacha-question my-2" onclick="sendDefaultMessage(2)">Guide me about basics of farming techniques in
                                        Pakistan</p>
                                    <p class="chacha-question my-2" onclick="sendDefaultMessage(3)">Tell me a fun fact</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>

                <div class="col-md-12 m-0 p-0">

                    <div class="chatbot-sep">

                        <div id="chatbot-messages" class='chatbot-messages-sep'></div>
                        <div class="labelcontainer mb-3">
                            <textarea style="max-height: 100px" rows="2" type="text" class="form-control chatbot-typer"
                                id="chatbot-text" placeholder="Type a Message..." required></textarea>
                                <button class="btn mx-2" id="chatbot-send-btn">
                                    <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" data-name="Layer 1"
                                    viewBox="0 0 24 24" width="25" height="25">
                                    <path
                                    d="m.172,3.708C-.216,2.646.076,1.47.917.713,1.756-.041,2.951-.211,3.965.282l18.09,8.444c.97.454,1.664,1.283,1.945,2.273H4.048L.229,3.835c-.021-.041-.04-.084-.057-.127Zm3.89,9.292L.309,20.175c-.021.04-.039.08-.054.122-.387,1.063-.092,2.237.749,2.993.521.467,1.179.708,1.841.708.409,0,.819-.092,1.201-.279l18.011-8.438c.973-.456,1.666-1.288,1.945-2.28H4.062Z" />
                                </svg>
                            </button>
                            <button class="btn mx-2" id="mic-btn">
                                <svg xmlns="http://www.w3.org/2000/svg" id="Layer_1" width="35" height="35" data-name="Layer 1" viewBox="0 0 24 24">
                                <path d="m12,0C5.383,0,0,5.383,0,12s5.383,12,12,12,12-5.383,12-12S18.617,0,12,0Zm-2,7c0-1.105.895-2,2-2s2,.895,2,2v5c0,1.105-.895,2-2,2s-2-.895-2-2v-5Zm3,10.916v2.084h-2v-2.084c-2.834-.477-5-2.948-5-5.916h2c0,2.206,1.794,4,4,4s4-1.794,4-4h2c0,2.968-2.166,5.439-5,5.916Z"/>
                                </svg>
                                </button>
                        </div>
                    </div>
                </div>

            </div>
        </div>


        <div id="footer">
            @include('components.footer')
        </div>


    </div>

</body>
<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('js/speech.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
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
</html>