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
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
    <script src="{{ asset('js/alert.js') }}"></script>
    </head>

<script>
    farm_id = @json($farm_id)
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
                <div class="col-md-10 offset-md-1 ">


                    <div class="container">
                        <div class="row">
                        <div class="d-flex justify-content-between align-items-center my-3">
                            <a href="{{ route('manager.farmdetails', ['farm_id' => $farm_id]) }}" class="back-button">
                                <svg xmlns="http://www.w3.org/2000/svg"  class='svg' viewBox="0 0 24 24" width="512" height="512">
                                    <path
                                        d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                                </svg>
                            </a>
                            @if($map_info == 'EMPTY')
                            <h3 class="flex-grow-1 text-center mb-0">{{__('messages.map_configuration')}}</h3>
                            @else
                            <h3 class="flex-grow-1 text-center mb-0">{{__('messages.maps')}}</h3>
                            @endif
                            <div style='visibility:hidden;' class="invisible"></div>
                        </div>
                        <p class=' mx-3 light'>{{__('messages.you_can_edit_visual_map')}}</p>


                            <div class="col-md-12">
                                <div class="map-cont">

                                <form id='map-form' action="{{route('manager.map.save', ['farm_id' => $farm_id])}}" method="POST">
                                    @csrf
                                    <input type="hidden" name="deraDetails" id="deraDetails">
                                    <input type="hidden" name="farm_id" value="{{$farm_id}}">
                                </form>

                                    <div id="map"></div>
                                    <button class='btn btn-orange or-width my-2' id="draw-button">{{__('messages.draw')}}</button>
                                    <button class='btn btn-orange2 or-width my-2' id="save-button">{{__('messages.save')}}</button>
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

<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
<script src="https://unpkg.com/leaflet-geometryutil/src/leaflet.geometryutil.js"></script>

<script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet/0.0.1-beta.5/esri-leaflet.js"></script>
<script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.css">
<script>
    has_deras = @json($has_deras);
    no_of_deras = @json($no_of_deras);
    mapdata = @json($map_info);
    mapdata = JSON.parse(JSON.parse(mapdata));
</script>
<script src="{{ asset('js/map.js') }}"></script>

</html>