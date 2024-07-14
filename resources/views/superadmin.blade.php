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
        
    <div class="container mb-4">
        <div id="navbar">
        @include('components.navbar')
        </div>
        @yield('content')
    </div>

        
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 mt-3 sidebarcol">
                    <div class="SuperAdminSidebar sidebar"></div>
                </div>
                <div class="col-md-10 ">
                    <div class="container">
                        <div class="row">
                        <div class="col-md-12 my-3">
                            <div class="text-center">
                                <h2 class='mx-4'>Welcome Hassan!</h2>
                            </div>
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

</body>
<script src="{{ asset('js/alert.js') }}"></script>
<script src="{{ asset('js/SuperadminSidebar.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
</html>