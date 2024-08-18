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
</head>

<body>
    <div class="container mb-4">
        <div id="navbar">
        @include('components.nav2')
        </div>
        @yield('content')
    </div>

    @if(Session::get('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
        {{Session::forget('success')}}
        @endif

        @if(Session::get('error'))
        <div class="alert alert-danger">
            {{Session::get('error')}}
        </div>
        {{Session::forget('error')}}
        @endif



    <div class="d-flex container-fluid section background-image">
        <div class="row overlay-text w-100 text-center">
            <h1><b>{{ __('messages.welcome') }}</b></h1>
            <p>{{__('messages.welcome_message')}}</p>
            <div class='col'>
                <button class="btn-brown"  onclick="scrollToSignup()"
                    >{{__('messages.get_started')}}</button>
            </div>
        </div>
    </div>

    <div id='filler'></div>

    <div class="container back-grey my-5 px-5 section">
        <h3 class='services'>{{__('messages.our_services')}}</h3>
        <div class='row h-100 p-3'>
            <div class='col text-center service-card'>

                <h4 class='px-5 h-20 p-2 text-center gradient-fill'><b>{{__('messages.AI_chat_bot')}}</b></h4>
                <p class='px-4'>{{__('messages.AI_chat_bot_message')}}
                </p>
            </div>

            <div class='col text-center service-card'>


                <h4 class='px-5 h-20 p-2 text-center gradient-fill'><b>{{__('messages.farm_mgmt_portal')}}</b></h4>
                <p class='px-4'>{{__('messages.farm_mgmt_portal_message')}}
            </div>

            <div class='col text-center service-card'>

                <h4 class='px-5 h-20 p-2 text-center gradient-fill'><b>{{__('messages.recommender_system')}}</b></h4>
                <p class='px-4'>{{__('messages.recommender_system_message')}}
                </p>
            </div>
        </div>
    </div>

    <div id='filler'></div>

    <div class='container-fluid text-center my-3 gradient d-flex justify-content-center text-light'>
        <h1>{{__('messages.empowering_message')}}</h1>
    </div>


    <div class="container-fluid px-5 my-5 grey-gradient">
        <div class='row p-4 text-center'>

            <div class='col p-5'>
                <h1 class='heading gradient-fill'>2k+</h1>
                <h3>{{__('messages.farms')}}</h3>
            </div>

            <div class='col p-5'>
                <h1 class='heading gradient-fill'>10k+</h1>
                <h3>{{__('messages.users')}}</h3>
            </div>

            <div class='col p-5'>
                <h1 class='heading gradient-fill'>100+</h1>
                <h3>{{__('messages.crops')}}</h3>
            </div>

        </div>
    </div>

    <div id='filler'></div>

    <div class="pt-4">

        <div class=' container signup-cont' id='signup'>
            <div class='row'>
                <div class='col-md-5' id='signup-image'>
                </div>

                <div class='p-4 col-md-6'>

                    <div class="text-center">
                        <h4 class='my-3 text-center'>{{__('messages.login_or_signup')}}</h4>
                        <div class='mt-5'>{{__('messages.email_login')}}</div>
                        <div class='light'>{{__('messages.or')}}</div>
                        <div>{{__('messages.send_request')}}</div>
                    </div>

                    <form class='p-5' action="{{ route('login') }}" method="POST">
                        @csrf
                        <div class="form-group mx-5">
                            <label htmlFor="exampleInputEmail1">{{__('messages.email')}}</label>
                            <input type="email" class="form-control" autoComplete="new-email" name="email"
                                required />
                        </div>

                        <div class="form-group mx-5 mt-3">
                            <label htmlFor="exampleInputPassword1">{{__('messages.password')}}</label>
                            <input type="password" class="form-control" autoComplete="new-password" name="password"
                                required />
                        </div>

                        <div class="text-center">
                            <button type="submit" class="btn btn-dark w-75 my-4 mx-5">{{__('messages.submit')}}</button>
                        </div>

                        <div class='text-center'>
                            {{__('messages.dont_have_account')}} <a href="/signup">{{__('messages.signup')}}</a>
                    </form>


                </div>
            </div>
        </div>
    </div>

    <div id='filler'></div>

    <div id="footer">
        @include('components.footer')
    </div>
</body>
    <script src="{{ asset('js/alert.js') }}"></script>
    <script>
      function scrollToSignup() {
            document.getElementById('signup').scrollIntoView({ behavior: 'smooth' });
        }   
    </script>

</html>