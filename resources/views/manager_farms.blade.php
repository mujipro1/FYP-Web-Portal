@extends('layouts.app') 

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
    <script src="{{ asset('js/alert.js') }}"></script>
    
</head>
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
                <div class="col-md-10 offset-md-1">
                    <div class="container">
                    <div class="row">
                        <div class='text-center my-3'><h3>{{__('messages.farms')}}</h3></div>
                        <div class="row section">
                        @foreach($farms as $farm)
                            <div class="col-md-4 my-2">
                                <div class="selected-farm box-cont" onclick="handleClick({{ $farm['id'] }})" style="background-color:white;cursor:pointer;">
                                    <img src="{{ asset('images/farm1.jpg') }}" class="selected-farm-image" />
                                    <h5 class='mt-3 mx-2'>{{ $farm['name'] }}</h5>
                                    <div class='mx-2 light fsmall'>{{ $farm['city'] }}</div>
                                </div>
                            </div>
                        @endforeach


                        @if (count($farms) == 0)
                                <div class="mx-3 text-center">
                                    <p class=''>{{__('messages.no_farms_found')}}</p>
                                </div>
                        @endif
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

<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
    function handleClick(farm_id){
        const baseUrl = "{{ route('manager.farmdetails', ['farm_id' => ':farm_id']) }}";
        const url = baseUrl.replace(':farm_id', farm_id);
        window.location.href = url;
    }
</script>
</html>