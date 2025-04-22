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
    <script src="{{ asset('js/alert.js') }}"></script>

    <link rel="stylesheet" href="{{ asset('css/superadmin.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
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
                        @if($route_id == 0)
                        <a href="{{ route('manager.farmdetails', ['farm_id' => $farm_id]) }}" class="back-button">
                            <svg xmlns="http://www.w3.org/2000/svg"  class='svg' viewBox="0 0 24 24" width="512" height="512">
                                <path
                                    d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                            </svg>
                        </a>
                        @else
                        <a href="{{ route('manager.farm_history', ['farm_id' => $farm_id]) }}" class="back-button">
                            <svg xmlns="http://www.w3.org/2000/svg"  class='svg' viewBox="0 0 24 24" width="512" height="512">
                                <path
                                    d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                            </svg>
                        </a>
                        @endif

                        <h3 class="flex-grow-1 text-center mb-0">{{__('messages.crop_details')}}</h3>
                        <div style='visibility:hidden;' class="invisible"></div>
                    </div>


                    <div class="container">
                        <div class="row p-5">
                            <div class="col-md-8">


                                <div class="d-flex">
                                    <label class="w-50" for="crop">{{__('messages.crop_name')}}</label>
                                    <label class="w-50" for="crop_value">{{ $crop['identifier']}}</label>
                                </div>


                                <div class="d-flex">
                                    <label class="w-50" for="crop">{{__('messages.acres')}}</label>
                                    <label class="w-50" for="crop_value">{{ $crop['acres']}}</label>
                                </div>

                                <div class="d-flex">
                                    <label class="w-50" for="crop">{{__('messages.sowing_date')}}</label>
                                    <label class="w-50"
                                        for="crop_value">{{ $crop['sow_date'] ? \Carbon\Carbon::parse($crop['sow_date'])->format('d M Y') : __('messages.date_not_available') }}</label>
                                </div>

                                <div class="d-flex">
                                    <label class="w-50" for="crop">{{__('messages.harvest_date')}}</label>
                                    <label class="w-50" for="crop_value">{{ $crop['harvest_date'] ? \Carbon\Carbon::parse($crop['harvest_date'])->format('d M Y') :__('messages.date_not_available') }}</label>
                                </div>

                                <div class="d-flex">
                                    <label class="w-50" for="crop">{{__('messages.variety')}}</label>
                                    <label class="w-50" for="crop_value">{{ $crop['variety']}}</label>
                                </div>

                                <div class="d-flex">
                                    <label class="w-50" for="crop">{{__('messages.status')}}</label>
                                    <label class="w-50" for="crop_value">{{ $crop['active'] == 1 ? __('messages.active') : __('messages.passive')}}</label>
                                </div>

                                <div class="d-flex">
                                    <label class="w-50" for="crop">{{__('messages.description')}}</label>
                                    <label class="w-50" for="crop_value">{{ $crop['description']}}</label>
                                </div>

                                @if (count($crop->deras) > 0)
                                <hr class="my-4 w-75">
                                <div class="text-start my-3 fw-bold">
                                {{__('messages.deras')}}
                                </div>
                                    @foreach($crop->deras as $dera)
                                    <div class="d-flex">
                                        <label class="w-50" for="crop_value">{{ $dera['name']}}</label>
                                        <label class="w-50" for="crop">{{ $dera['pivot']['acres']}} {{__('messages.acres')}}</label>
                                    </div>
                                    @endforeach
                                @endif


                                <hr class="my-4 w-75">

                                @if($crop['name'] == 'Sugarcane')
                                    @if ($all_versions == [])
                                    <p class='light my-3' >{{__('messages.no_prev_ver_of')}} {{$crop['identifier']}}</p>
                                    @else
                                    <p class='light my-3' >{{__('messages.prev_ver_of')}} {{$crop['identifier']}}</p>
                                        @foreach($all_versions as $version)
                                        <h4 class="my-3">{{$version->identifier}}</h4>
                                        <div class="d-flex">
                                            <label class="w-50" >{{__('messages.sowing_date')}} </label>
                                            <label class="w-50" >{{$crop['sow_date'] ? \Carbon\Carbon::parse($crop['sow_date'])->format('d M Y') :  __('messages.date_not_available')}}</label>
                                        </div>
                                        <div class="d-flex">
                                            <label class="w-50" >{{__('messages.harvest_date')}} </label>
                                            <label class="w-50" >{{$crop['harvest_date'] ? \Carbon\Carbon::parse($crop['harvest_date'])->format('d M Y') :  __('messages.date_not_available')}}</label>
                                        </div>
                                        @endforeach
                                    @endif
                                @endif
                            </div>

                            <div class="col-md-4">
                                <div class="cropDetailImg ">
                                    <div class="mb-4">
                                        <img src="{{asset('images/crops/'. str_replace(' ', '', $crop['name']) .'.jpg')}}"
                                        alt="" class="img-fluid">
                                    </div>
                                        <button class="btn btn-orange2 or-width" id='harvest'>{{__('messages.harvest_crop')}} </button>
                                        <div class="d-none box-cont p-4" id="harvest-cont">
                                            <form action="{{ route('manager.harvest_crop', ['crop_id' => $crop['id']]) }}" method="POST">
                                                @csrf
                                                <input hidden type="text" name="crop_id" value="{{$crop['id']}}">
                                                <p class="light">{{__('messages.add_harvesting_date')}}</p>
                                            <div class="d-flex mt-3">
                                                <label class='form-label w-50' for="harvest_date">{{__('messages.date')}}</label>
                                                <input class='form-control' type="date" name="harvest_date" id="harvest_date" 
                                                value="{{ $crop['harvest_date'] ? Carbon\Carbon::parse($crop['harvest_date'])->format('Y-m-d') : Carbon\Carbon::now()->format('Y-m-d') }}">
                                            </div>

                                            <div class="d-flex my-3 justify-content-start">
                                                @if ($crop->active == '1')
                                                <input class='form-check form-check-input' type="checkbox" name="passive" id="passive" checked>
                                                <label class='form-label m-3' for="passive">{{__('messages.mark_as_passive')}}</label>
                                                @else
                                                <input disabled class='form-check form-check-input' type="checkbox" name="passive" id="passive" checked>
                                                <label class='form-label form-check light m-3' for="passive">{{__('messages.mark_as_passive')}}</label>
                                                @endif
                                            </div>
                                            <div class="text-center">
                                                <button type="submit" class="btn btn-orange2 or-width" id='harvest'>{{__('messages.save')}}</button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>

                            @if ($cropMap == 'Empty')
                            <div class="section d-flex justify-content-center align-items-center">
                                <div class="light text-center"><h3>{{__('messages.no_map_data')}}</h2></div>
                            </div>
                            @else
                            <div class="row">
                                <div class="box-cont">
                                    <p class="light">{{__('messages.visualization_of')}}  {{$crop['identifier']}} {{__('messages.on_map')}}</p>
                                        <div id='map' style='height:60vh'></div>
                                    </div>
                                </div>
                            </div>
                            @endif
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

    mapdata = @json($cropMap);
    if (mapdata != 'Empty'){
        mapdata = JSON.parse(mapdata['coords']);
        map_var = 1;
    }

    document.getElementById('harvest').addEventListener('click', function() {
        document.getElementById('harvest-cont').classList.remove('d-none');
        document.getElementById('harvest').classList.add('d-none');

    });

    
    document.getElementById('harvest_date').min = "{{Carbon\Carbon::parse($crop['sowing_date'])->format('Y-m-d')}}";


</script>

<!-- <script src="{{ asset('js/common_map.js') }}"></script> -->

</html>