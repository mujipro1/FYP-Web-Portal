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

        
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-10 section offset-md-1">
                    <div class="container">
                        <div class="row">
                        <div class="col-md-12 my-3">
                            
                            <div class="text-center">
                                <h2 class='mx-4'>Welcome Hassan!</h2>
                            </div>
                            <div class="row mt-5">
                                <div class="col-md-8">
                                    <div class=" p-4">
                                        <h4 class='light m-2 mb-3'>Statistics</h4>
                                        <div class="row">
                                            <div class="col-md-4">
                                                <div class="box-cont p-3">
                                                    <h1 class='text-center mt-4'>{{$totalFarms}}</h1>
                                                    <p class='text-center'>Farms</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="box-cont p-3">
                                                    <h1 class='text-center mt-4'>{{$totalUsers}}</h1>
                                                    <p class='text-center'>Users</p>
                                                </div>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="box-cont p-3">
                                                    <h1 class='text-center mt-4'>30</h1>
                                                    <p class='text-center'>Cities</p>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="px-5 mt-4 box-cont">
                                            <div class="row  mt-3">
                                                {!! $chart->container() !!}
                                            </div>
                                        </div>

                                    </div>
                                </div>
                                <div class="col-md-4 py-4">
                                    <h4 class='light m-2 mb-3'>Requests</h4>
                                    <div class="box-cont p-4">
                                        <div class="my-2 labelcontainer" style='padding:0px 3px;!important'>
                                            <label class='light w-75'>Pending </label>
                                            <label class='w-50'>{{$totalPendingRequests}}</label>
                                        </div>
                                        <div class="my-2 labelcontainer" style='padding:0px 3px;!important'>
                                            <label class='light w-75'>Approved</label>
                                            <label class='w-50'>{{$totalRequests-$totalPendingRequests}}</label>
                                        </div>
                                        <div class="labelcontainer" style='padding:0px 3px;!important'>
                                            <label class='light w-75'>Total </label>
                                            <label class='w-50'>{{$totalRequests}}</label>
                                        </div>

                                        <div class="text-center">
                                            <button class='btn btn-brown mt-4' onclick='handleRequests()'>View Requests</button>
                                        </div>

                                    </div>
                                </div>
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
<script src="{{ $chart->cdn() }}"></script>
{{ $chart->script() }}
<script src="{{ asset('js/alert.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
    function handleRequests(){
        window.location.href = "{{route('superadmin.requests')}}"
    }
</script>
</html>