<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navBar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/questionaire.css') }}">
</head>

<body>
<div class="container mb-4">
        <div id="navbar">
        @include('components.navbar')
        </div>
        @yield('content')
    </div>

    <div class="container my-5">
        <div class="row box-cont">
            <div class="col-md-6">
                <img  id='signup-image' style='width:100%'/>
            </div>
            <div class="col-md-6" id="form-container">
                <div id="get-started" style="height:100%">
                    <div class="d-flex justify-content-center align-items-center flex-column" style="height:100%" >
                        <h3>Get Registered!</h3>
                        <p class='text-center p-3'>Register your farm with us by answering some short questions by our agent Hina!</p>
                        <button class="text-light btn-brown" onclick="handleGetStartedClick()">Start</button>
                    </div>
                </div>
                <div id="questionnaire" class="d-none">
                    <div class="container">
                        <div class="row">
                            <div class="d-flex justify-content-start mb-3 align-items-center">
                                <img src='images/profile.jpg' alt="Hina" id='profile-image' />
                                <h6 class='mx-3 mt-2'>Hina</h6>
                            </div>
                            <div class="questionnaire" id="chat-container">
                                <div class="d-flex justify-content-start">
                                    <p class='question'>Hello, I am Hina and I will be asking you some questions, So lets get started!</p>
                                </div>
                            </div>
                            <form id="question-form" class='fixed-form' onsubmit="handleNextQuestion(event)">
                                <input type="text" id="answer-input" autofocus />
                                <button type="submit" class='next-button'>Next</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <div id="footer">
        @include('components.footer')
    </div>

</div>



    <script src="{{ asset('js/alert.js') }}"></script>
    <script src="{{ asset('js/signup.js') }}"></script>

</html>