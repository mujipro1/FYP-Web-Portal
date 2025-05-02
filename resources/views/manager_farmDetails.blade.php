@extends('layouts.app')

<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">


<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF Token -->
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navBar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/superadmin.css') }}">
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
    <script src="{{ asset('js/alert.js') }}"></script>

    <script>
    farm_id = @json($farm['id']);
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
            <div class="row section">
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>
                <div class="col-md-11 offset-md-1" style='padding-right:50px;'>


                    <div class="container">
                        <div class="row">
                            <div class="d-flex justify-content-between align-items-center my-2">
                                <div class='d-flex mx-2'>
                                    <img src="{{ asset('images/smart-farm.png') }}" style='height:80px;'>
                                    <div class='mx-4'>
                                        <h3 class="mx-3 mt-3">{{$farm['name']}}</h3>
                                        <div class="mx-3">{{$farm['address']}}, {{$farm['city']}}</div>
                                    </div>
                                </div>

                                <div class='d-flex'>
                                    <button class='btn btn-orange tooltip-container mx-1 or-width p-1 d-flex'
                                        data-tooltip='Manager Expense and Sales Workers' onclick='handleWorkerClick()'>
                                        <div class=' mx-2'> Workers</div>
                                        <svg xmlns="http://www.w3.org/2000/svg" class="svg" data-name="Layer 1"
                                            viewBox="0 0 24 24">
                                            <path
                                                d="m19,6h0c0,.552-.448,1-1,1H6c-.552,0-1-.448-1-1h0c0-.552.448-1,1-1h.023C6.262,2.365,8.366.261,11,.023v2.977c0,.552.448,1,1,1h0c.552,0,1-.448,1-1V.023c2.634.239,4.738,2.343,4.977,4.977h.023c.552,0,1,.448,1,1Zm-12.998,3.146c.078,3.241,2.738,5.854,5.998,5.854s5.92-2.613,5.998-5.854l.002-.146H6l.002.146Zm14.99,13.73c-.415-3.645-4.277-6.876-8.992-6.876s-8.577,3.231-8.992,6.876c-.032.285.053.571.243.786.189.215.463.338.75.338h16c.287,0,.56-.123.75-.338.19-.215.275-.501.243-.786Z" />
                                        </svg>
                                    </button>
                                    <button class='btn btn-orange2 tooltip-container or-width mx-1 d-flex'
                                        data-tooltip="Configure your farm's expense and dera settings"
                                        onclick='handleConfiguration()'>
                                        <div class=" mx-3">Config.</div>
                                        <svg xmlns="http://www.w3.org/2000/svg"
                                            xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" class="svg" x="0px"
                                            y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;"
                                            xml:space="preserve" width="512" height="512">
                                            <g>
                                                <path
                                                    d="M34.283,384c17.646,30.626,56.779,41.148,87.405,23.502c0.021-0.012,0.041-0.024,0.062-0.036l9.493-5.483   c17.92,15.332,38.518,27.222,60.757,35.072V448c0,35.346,28.654,64,64,64s64-28.654,64-64v-10.944   c22.242-7.863,42.841-19.767,60.757-35.115l9.536,5.504c30.633,17.673,69.794,7.167,87.467-23.467   c17.673-30.633,7.167-69.794-23.467-87.467l0,0l-9.472-5.461c4.264-23.201,4.264-46.985,0-70.187l9.472-5.461   c30.633-17.673,41.14-56.833,23.467-87.467c-17.673-30.633-56.833-41.14-87.467-23.467l-9.493,5.483   C362.862,94.638,342.25,82.77,320,74.944V64c0-35.346-28.654-64-64-64s-64,28.654-64,64v10.944   c-22.242,7.863-42.841,19.767-60.757,35.115l-9.536-5.525C91.073,86.86,51.913,97.367,34.24,128s-7.167,69.794,23.467,87.467l0,0   l9.472,5.461c-4.264,23.201-4.264,46.985,0,70.187l-9.472,5.461C27.158,314.296,16.686,353.38,34.283,384z M256,170.667   c47.128,0,85.333,38.205,85.333,85.333S303.128,341.333,256,341.333S170.667,303.128,170.667,256S208.872,170.667,256,170.667z" />
                                            </g>
                                        </svg>
                                    </button>
                                </div>

                            </div>


                            <div class="col-md-12 p-0 m-0 mt-4">
                                <div class="recommender">
                                    <div class="item-1-rec"></div>
                                    <div class="item-2-rec"></div>
                                    <div class="item-3-rec"></div>
                                    <div class="inner-recommender">
                                        <div class="row p-2">
                                            <div class="col-md-6">
                                                <h4 class="text-light">Insights by Chacha Ameer</h4>
                                                @php

                                                $kleio_data->fun_fact = json_decode('"' . $kleio_data->fun_fact . '"');

                                                @endphp
                                                <div class="recommender-1 text-light p-4">
                                                    {{$kleio_data->recommendation}}
                                                </div>
                                            </div>
                                            <div class="col-md-2">
                                                <img src="{{ asset('images/kleio.png') }}" class='kleio'>
                                            </div>
                                            <div class="col-md-4">
                                                <div class="fun-fact text-light">
                                                    <h4>Today's Fun Fact</h4>
                                                    <div class="yellow-fun text-dark p-4">
                                                        {{$kleio_data->fun_fact}}
                                                    </div>
                                                </div>
                                                <div class="timer d-flex justify-content-center align-items-center">
                                                    <div class="time-value" id="time"></div>
                                                    <script>
                                                    function startTime() {
                                                        var today = new Date();
                                                        var h = today.getHours();
                                                        var m = today.getMinutes();
                                                        var s = today.getSeconds();
                                                        m = checkTime(m);
                                                        s = checkTime(s);

                                                        // convert to 12 hours format
                                                        if (h > 12) {
                                                            h = h - 12;
                                                        }
                                                        if (h == 0) {
                                                            h = 12;
                                                        }
                                                        document.getElementById('time').innerHTML = h + ":" + m + ":" +
                                                            s;
                                                        var t = setTimeout(startTime, 500);
                                                    }

                                                    function checkTime(i) {
                                                        if (i < 10) {
                                                            i = "0" + i
                                                        }; // add zero in front of numbers < 10
                                                        return i;
                                                    }
                                                    startTime();
                                                    </script>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="col-md-8 mt-4">
                                <div class="box-cont p-4">
                                    <p class='light'>Farm Status</p>
                                    <div class="d-flex my-2 justify-content-between">
                                        <div class='d-flex'>
                                            <div class="smallCard d-flex justify-content-between p-3 pr3">
                                                <div>Crops</div>
                                                <h4 class='mt-2'>{{$farm->crops->where('active', 1)->count()}}</h4>
                                            </div>
                                            <div class="smallCard  d-flex justify-content-between p-3 pr2 mx-2">
                                                <div>Deras</div>
                                                <h4 class='mt-2'>{{$farm->deras->count()}}</h4>
                                            </div>
                                        </div>
                                        <div>
                                            <button data-tooltip='Add New Crops in your farm'
                                                class='btn tooltip-container or-width btn-orange'
                                                onclick='handleAddCrop()'>Add Crop
                                                <svg xmlns="http://www.w3.org/2000/svg" class="m-1 svg"
                                                    data-name="Layer 1" viewBox="0 0 24 24">
                                                    <path
                                                        d="m11,22C5.66,22,1.03,18.04.031,12.993c-.224-1.134.804-2.162,1.938-1.938,5.047.999,9.031,5.604,9.031,10.944Zm5.995-10.947c-.168-2.642-1.64-5.558-3.544-7.932-.745-.929-2.156-.929-2.902,0-1.904,2.374-3.377,5.29-3.544,7.932,2.213,1.421,3.969,3.495,4.995,5.947,1.026-2.452,2.782-4.526,4.995-5.947Zm5.036.003c-5.047.999-9.031,5.604-9.031,10.944,5.34,0,9.97-3.96,10.969-9.007.224-1.134-.804-2.162-1.938-1.938Z" />
                                                </svg>
                                            </button>
                                            <button data-tooltip='View previously sown and harvested crops'
                                                class='btn tooltip-container or-width btn-orange2'
                                                onclick='handleActivity()'>History
                                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-1 svg"
                                                    viewBox="0 0 24 24" width="512" height="512">
                                                    <path
                                                        d="M12,0A12.034,12.034,0,0,0,4.04,3.04L2.707,1.707A1,1,0,0,0,1,2.414V7A1,1,0,0,0,2,8H6.586a1,1,0,0,0,.707-1.707L6.158,5.158A9,9,0,0,1,21,12.26,9,9,0,0,1,3.1,13.316,1.51,1.51,0,0,0,1.613,12h0A1.489,1.489,0,0,0,.115,13.663,12.018,12.018,0,0,0,12.474,23.991,12.114,12.114,0,0,0,23.991,12.474,12.013,12.013,0,0,0,12,0Z" />
                                                    <path
                                                        d="M11.5,7h0A1.5,1.5,0,0,0,10,8.5v4.293a2,2,0,0,0,.586,1.414L12.379,16A1.5,1.5,0,0,0,14.5,13.879l-1.5-1.5V8.5A1.5,1.5,0,0,0,11.5,7Z" />
                                                </svg>


                                            </button>
                                        </div>
                                    </div>
                                    <hr class='w-100 my-4'>

                                    <div class="row" style='height:29vh;overflow-y:scroll'>
                                        @if($farm->crops->count() == 0)
                                        <div class="d-flex align-items-center justify-content-center">
                                            <h5 class='light'>No crops added</h5>
                                        </div>
                                        @endif
                                        @foreach($farm->crops as $crop)
                                        @if($crop['active'] == 1)
                                        <div class="col-md-4">
                                            <div data-tooltip='Active crop' class="selected-crop tooltip-container"
                                                style='background-color:#f3f3f3;box-shadow:none;width:100%;margin-bottom:17px;'
                                                onclick="handleCropClick('{{$crop['id']}}')">
                                                <img
                                                    src="{{asset('images/crops/'. str_replace(' ', '', $crop['name']) .'.jpg')}}">
                                                <div class='mx-2 fw-bold mt-1'>{{$crop['identifier']}}</div>
                                                @if ($crop['variety'] != null)
                                                <div class='mx-2 fsmall light'>{{$crop['variety']}}</div>
                                                @else
                                                <div class='mx-2 fsmall light'>No variety</div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>
                                </div>

                                <div class="row m-3 mt-4 ">
                                    <div class="col-md-4  tooltip-container"
                                        data-tooltip='Add or View Expenses of your farm'>
                                        <div class="bigCard pr4" onclick='handleExpenseClick()'>
                                            <div class="svgDiv">
                                                <img src="{{ asset('images/expense.png') }}">
                                            </div>
                                            <h5 class='text-light my-2 mx-3'>Expenses</h5>
                                            <div class="d-flex justify-content-between">
                                                <div class="d-flex mx-3">
                                                    <div class="circle1"></div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="svg"
                                                        style='fill:#00000060;' data-name="Layer 1" viewBox="0 0 24 24"
                                                        width="512" height="512">
                                                        <path
                                                            d="M23,13.5C23,8.355,16.021,2.372,13.881.661a3,3,0,0,0-3.762,0C7.979,2.372,1,8.354,1,13.5A6.272,6.272,0,0,0,7,20a5.82,5.82,0,0,0,3.93-1.659C10.75,20.805,10.115,22,8,22H5v2H19V22H16c-2.115,0-2.75-1.2-2.93-3.659A5.82,5.82,0,0,0,17,20,6.272,6.272,0,0,0,23,13.5Z" />
                                                    </svg>
                                                </div>
                                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-3 svg"
                                                    style='fill:white;height:30px;width:30px;' viewBox="0 0 24 24"
                                                    width="512" height="512">
                                                    <path
                                                        d="M18,12h0a2,2,0,0,0-.59-1.4l-4.29-4.3a1,1,0,0,0-1.41,0,1,1,0,0,0,0,1.42L15,11H5a1,1,0,0,0,0,2H15l-3.29,3.29a1,1,0,0,0,1.41,1.42l4.29-4.3A2,2,0,0,0,18,12Z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 tooltip-container" data-tooltip='Add or View your crop sales'>
                                        <div class="bigCard  pr3" onclick='handleSalesClick()'>
                                            <div class="svgDiv">
                                                <img src="{{ asset('images/sales.png') }}">
                                            </div>
                                            <h5 class='text-light my-2 mx-3'>Sales</h5>
                                            <div class="d-flex justify-content-between">
                                                <div class="d-flex mx-3">
                                                    <svg xmlns="http://www.w3.org/2000/svg" class=" svg"
                                                        style='fill:#ffffffab;' data-name="Layer 1" viewBox="0 0 24 24"
                                                        width="512" height="512">
                                                        <path
                                                            d="M23,14a6,6,0,0,0-5.632-5.981A5.227,5.227,0,0,0,18,5.546,5.793,5.793,0,0,0,12,0,5.793,5.793,0,0,0,6,5.546a5.23,5.23,0,0,0,.632,2.473A6,6,0,0,0,7,20a6.081,6.081,0,0,0,3.922-1.523C10.729,20.853,10.078,22,8,22H5v2H19V22H16c-2.078,0-2.729-1.147-2.922-3.523A6.081,6.081,0,0,0,17,20,6.006,6.006,0,0,0,23,14ZM11.971,22c.009-.016.02-.031.029-.047.009.016.02.031.029.047Z" />
                                                    </svg>
                                                </div>

                                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-3 svg"
                                                    style='fill:white;height:30px;width:30px;' viewBox="0 0 24 24"
                                                    width="512" height="512">
                                                    <path
                                                        d="M18,12h0a2,2,0,0,0-.59-1.4l-4.29-4.3a1,1,0,0,0-1.41,0,1,1,0,0,0,0,1.42L15,11H5a1,1,0,0,0,0,2H15l-3.29,3.29a1,1,0,0,0,1.41,1.42l4.29-4.3A2,2,0,0,0,18,12Z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-4 tooltip-container"
                                        data-tooltip='View your past trends and analytics'>
                                        <form action="{{route('manager.analytics')}}" method='POST' id='analytics-form'>
                                            @csrf
                                            <div class="bigCard pr2" onclick='handleAnalytics()'>
                                                <div class="svgDiv">
                                                    <img src="{{ asset('images/analytics.png') }}">
                                                </div>
                                                <h5 class='text-light my-2 mx-3'>Analytics</h5>
                                                <div class="d-flex justify-content-between">
                                                    <div class="d-flex mx-3">
                                                        <svg class='mx-1 svg'
                                                            style='fill:#ffffffab;height:23px;width:23px;' height="550"
                                                            viewBox="0 0 100 100" width="512"
                                                            xmlns="http://www.w3.org/2000/svg" data-name="Layer 1">
                                                            <path d="M50 0 L90 50 L50 100 L10 50 Z" />
                                                        </svg>

                                                    </div>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class="mx-3 svg"
                                                        style='fill:white;height:30px;width:30px;' viewBox="0 0 24 24"
                                                        width="512" height="512">
                                                        <path
                                                            d="M18,12h0a2,2,0,0,0-.59-1.4l-4.29-4.3a1,1,0,0,0-1.41,0,1,1,0,0,0,0,1.42L15,11H5a1,1,0,0,0,0,2H15l-3.29,3.29a1,1,0,0,0,1.41,1.42l4.29-4.3A2,2,0,0,0,18,12Z" />
                                                    </svg>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                            </div>

                            <div class="col-md-4 mt-4" onclick="map_click()">
                                <div data-tooltip='Click on Map section to configure maps.'
                                    class="box-cont tooltip-container" style='height:58vh;'>
                                    @if($map_info == 'EMPTY')
                                    <div class="" style='cursor:pointer;'>
                                        <h6 class='light'>Click to configure your farm on map</h6>
                                        <img src="{{asset('images/world-map.png')}}" class='img-fluid' />
                                    </div>
                                    @else
                                    <div id="map" style='height:100%;'></div>
                                    @endif
                                </div>

                                <div class='mt-4' style='height:40vh;border-radius:15px;overflow:hidden;'>
                                    <img src="{{ asset('images/barns/barn1.png') }}" class='img-fluid' id='barn'>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                        <div class="col-md-8 p-3 my-4">
                            <div class="cost-saver" style="cursor:pointer;">
                                <div class="cost-save-inner">
                                    <div class="d-flex justify-content-center">
                                        <div class="col-md-4 d-flex justify-content-start mb-1">
                                            <img src="{{ asset('images/cost-saver.png') }}" class='cost-saver-img'>
                                        </div>
                                            
                                            <div class="col-md-8 px-2 text-white" onClick="handleCostSaver()">
                                                <h3 class="text-white">Smart Spend</h3>
                                                <p class="mt-4">Know your spending before it grows, compare this season’s expenses with past years and make smarter farming decisions</p>

                                                <div class="d-flex justify-content-end">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-3 svg"
                                                        style='fill:white;height:30px;width:30px;' viewBox="0 0 24 24"
                                                        width="512" height="512">
                                                        <path
                                                            d="M18,12h0a2,2,0,0,0-.59-1.4l-4.29-4.3a1,1,0,0,0-1.41,0,1,1,0,0,0,0,1.42L15,11H5a1,1,0,0,0,0,2H15l-3.29,3.29a1,1,0,0,0,1.41,1.42l4.29-4.3A2,2,0,0,0,18,12Z" />
                                                    </svg>
                                                </div>
                                            </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4 p-3 my-4">
                            <div class="kisaan-link" style="cursor:pointer;">
                                    <div class="d-flex justify-content-center">
                                            
                                            <div class="col-md-8 p-2 py-4 text-white" onClick="handleKisaanLink()">
                                                <h3 class="text-white">Kisaan Link</h3>
                                                <p class="mt-4">Connect with farmers like you, share your experiences and learn from each other</p>

                                                <div class="d-flex justify-content-end">
                                                <svg xmlns="http://www.w3.org/2000/svg" class="mx-3 svg"
                                                        style='fill:white;height:30px;width:30px;' viewBox="0 0 24 24"
                                                        width="512" height="512">
                                                        <path
                                                            d="M18,12h0a2,2,0,0,0-.59-1.4l-4.29-4.3a1,1,0,0,0-1.41,0,1,1,0,0,0,0,1.42L15,11H5a1,1,0,0,0,0,2H15l-3.29,3.29a1,1,0,0,0,1.41,1.42l4.29-4.3A2,2,0,0,0,18,12Z" />
                                                    </svg>
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
    </div>


    <div id="footer">
        @include('components.footer')
    </div>

    </div>

</body>

<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('js/handleAnalytics.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
<script src="https://unpkg.com/leaflet-geometryutil/src/leaflet.geometryutil.js"></script>

<script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet/0.0.1-beta.5/esri-leaflet.js"></script>
<script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.js"></script>
<link rel="stylesheet" type="text/css"
    href="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.css">

<script>
function handleConfiguration() {
    window.location.href = "{{ route('manager.configuration' , ['farm_id' => $farm['id']] )}}"
}

function handleExpenseClick() {
    window.location.href = "{{ route('manager.render_cropexpense' , ['farm_id' => $farm['id']])}}"
}

function handleSalesClick() {
    window.location.href = "{{ route('manager.render_sales_page' , ['farm_id' => $farm['id']])}}"
}

function handleWorkerClick() {
    window.location.href = "{{ route('manager.render_workers' , ['farm_id' => $farm['id']] )}}"
}

function handleCropClick(crop_id) {
    window.location.href =
        "{{ route('manager.cropdetails' , ['farm_id' => $farm['id'], 'crop_id' => ':crop_id', 'route_id' => 0 ])}}"
        .replace(':crop_id', crop_id);
}

function handleAddCrop() {
    window.location.href = "{{ route('manager.addCrop' , ['farm_id' => $farm['id']] )}}"
}

function handleActivity() {
    window.location.href = "{{ route('manager.farm_history' , ['farm_id' => $farm['id']] )}}"
}

function map_click() {
    window.location.href = "{{ route('manager.maps' , ['farm_id' => $farm['id']] )}}"
}

function handleCostSaver() {
    window.location.href = "{{ route('manager.costsaver' , ['farm_id' => $farm['id']] )}}"
}

function handleKisaanLink() {
    window.location.href = "{{ route('manager.kisaanlink' , ['farm_id' => $farm['id']] )}}"
}


var images = [];
for (var j = 1; j <= 20; j++) {
    var img = new Image();
    img.src = "{{ asset('images/barns/barn') }}" + j + ".png";
    images.push(img);
}


var i = 0;
var barn = document.getElementById('barn');

setInterval(function() {
    setTimeout(function() {
        if (i == 20) {
            i = 0;
        }
        barn.src = images[i].src;
        i++;
    }, 250); // Match this timing with half of the transition time (0.5s)

}, 300);

mapdata = JSON.parse(@json($map_info));
map_var = 1


document.querySelectorAll('.tooltip-container').forEach(function(container) {
    container.addEventListener('mouseenter', function() {
        const tooltipText = container.getAttribute('data-tooltip');
        let tooltip = document.createElement('div');
        tooltip.className = 'tooltip-text';
        tooltip.innerText = tooltipText;

        container.appendChild(tooltip);
    });

    container.addEventListener('mouseleave', function() {
        const tooltip = container.querySelector('.tooltip-text');
        if (tooltip) {
            tooltip.remove();
        }
    });
});
</script>
<script src="{{ asset('js/common_map.js') }}"></script>

<script>
    function checkTimeAndSendRequest() {
        
        const kleioDataDate = new Date("{{ $kleio_data->record_date }}");
        const today = new Date();

        kleioDataDate.setHours(0, 0, 0, 0);
        today.setHours(0, 0, 0, 0);
        
        if (today > kleioDataDate) {
            queryData = @json($queryData);
            getRecommendation();
        }
    }

    
    farm_id = @json($farm['id']);
    
    async function getRecommendation() {
        try {
        const response = await fetch('https://10.3.16.62:443/recommendations', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({ query: queryData }),
        });

        if (!response.ok) {
            throw new Error(`HTTP error! Status: ${response.status}`);
        }

        const data = await response.json();
        sendRequest(data.response);
    } catch (err) {
        console.error('Fetch Error:', err); // Improved error logging
        return `Sorry, something went wrong. Error: ${err.message}`;
    }
    }

    function sendRequest(data) {
        fetch("{{ route('daily.task') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                "Accept": "application/json"
            },
            body: JSON.stringify({
                farm_id: farm_id,
                data: Array.isArray(data) ? data : [data] // Ensure it's an array
            })
        })
        .then(response => response.json())
        .then(data => console.log("Request sent successfully:", data))
        .catch(error => console.error("Error:", error));


    }

    // Start checking time on page load
    checkTimeAndSendRequest();

</script>

</html>