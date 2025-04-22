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

    <script>
    farm_id = @json($farm_id);
    </script>
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
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>
                <div class="col-md-10 offset-md-1 ">


                    <div class="d-flex justify-content-between align-items-center my-3">
                        <a href="{{ route('manager.farmdetails', ['farm_id' => $farm_id]) }}" class="back-button">
                            <svg xmlns="http://www.w3.org/2000/svg" class='svg' viewBox="0 0 24 24" width="512"
                                height="512">
                                <path
                                    d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                            </svg>
                        </a>
                        <h3 class="flex-grow-1 text-center mb-0">{{__('messages.farm_history')}}</h3>
                        <div style='visibility:hidden;' class="invisible"></div>
                    </div>

                @php
                $cropsGroupedByYear = $crops->groupBy(function($crop) {
                    return $crop['year'];
                });

                $cropsGroupedByYear = $cropsGroupedByYear->sortByDesc(function($group, $year) {
                    return $year;
                });

                @endphp

                @if($cropsGroupedByYear->isEmpty())
                    <div class="alert alert-info">
                    {{__('messages.no_history_found')}}
                    </div>
                @endif
                @foreach($cropsGroupedByYear as $year => $cropsOfYear)
                    <div class="box-cont my-3">
                        <h4 class='light m-3 my-4'>{{__('messages.crops_of')}}  {{ $year }}</h4>
                        @foreach($cropsOfYear as $crop)
                        <div class="col-md-3 my-3 crop {{ $crop['active'] == '1' ? 'active-crop' : 'passive-crop' }}" style='display:inline-block;'>
                            <div class="selected-crop" style="background-color:#f1f1f1"
                                onclick="handleCropClick('{{$crop['id']}}')">
                                <img src="{{asset('images/crops/'. str_replace(' ', '', $crop['name']) .'.jpg')}}"
                                    class="selected-crop-image" />
                                <div class="d-flex justify-content-between">
                                    <h5 class="m-2 my-3">{{$crop['identifier']}}</h5>
                                    @if($crop['active'] == '1')
                                    <div class="greenCircle mx-2"></div>
                                    @endif
                                </div>
                                <div class="light mx-2 fsmall">{{$crop['acres']}} {{__('messages.acres')}}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @endforeach

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
    
function handleCropClick(crop_id) {
    window.location.href = "{{ route('manager.cropdetails' , ['farm_id' => $farm_id, 'crop_id' => ':crop_id', 'route_id' => 1] )}}"
        .replace(':crop_id', crop_id);
}


</script>

</html>