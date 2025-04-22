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

</head>

<body>
    <div class="c1">
        <img src="{{asset('images/animation1.png')}}" class="img-fluid">

        <div class="container">
            <div id="navbar">
                @include('components.nav2')
            </div>
            @yield('content')
        </div>




        <div class="container">
            <div class="row">
                <div class="col-md-6 p-5 offset-md-6">
                    <div class="card">


                        <div class="bg-white shadow-md inner p-5">
                            <div class="mb-4 text-center">
                                <svg xmlns="http://www.w3.org/2000/svg" class="text-success" width="75" height="75"
                                    fill="currentColor" class="bi bi-check-circle" viewBox="0 0 16 16">
                                    <path d="M8 15A7 7 0 1 1 8 1a7 7 0 0 1 0 14zm0 1A8 8 0 1 0 8 0a8 8 0 0 0 0 16z" />
                                    <path
                                        d="M10.97 4.97a.235.235 0 0 0-.02.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-1.071-1.05z" />
                                </svg>
                            </div>
                            <div class="text-center">
                                <h1>{{__('messages.thankyou')}}</h1>
                                <p>{{__('messages.thankyou_register')}}</p>
                            </div>
                        </div>
                                            
                        
                        <div class="text-center">
                            <button onclick="home()" class="btn btn-orange2 or-width shadow-md mt-3">{{__('messages.go_to_home')}}</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>


    </div>

</body>
<style>
body {
    overflow: hidden;
}

img {
    width: 100%;
    height: auto;
    position: fixed;
}

.card {
    background-color: #ffffff60;
    border-radius: 20px;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
    padding: 20px;
    margin: 0px 40px;
    height: 74vh;
    border: 1px solid #ffffff90;
}
.inner{
    border-radius: 12px 12px 0px 0px;
}
</style>

<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
    function home(){
        window.location.href = "{{route('home')}}";
    }
</script>
</html>