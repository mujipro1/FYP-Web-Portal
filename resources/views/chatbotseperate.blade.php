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
    <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
    <script src="{{ asset('js/alert.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/showdown/dist/showdown.min.js"></script>

</head>
<script>
    const farm_id = @json($farm_id);
</script>

<body>
    <div class="c1">

    <div class="container mb-4">
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
            <div class="row">
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>

                <div class="col-md-6 offset-md-3">
                    <div class="chatbot-sep">
                        <button class="hidden" id="chatbot-toggle"></button>
                        <div id="chatbot-header">
                            <span>Virtual Assistant</span>
                            <button class="hidden" id="chatbot-close">✖</button>
                        </div>
                        <div id="chatbot-messages" class='chatbot-messages-sep'></div>
                        <div class="labelcontainer mb-3">
                            <input type="text" class="form-control" id="chatbot-text" placeholder="Type a Message..." required>
                            <button class="btn-orange or-width mx-3" id="chatbot-send-btn">Send</button>
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
<script src="{{ asset('js/chatbot.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>


</html>