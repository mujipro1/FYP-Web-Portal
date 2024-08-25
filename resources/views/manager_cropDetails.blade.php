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

                        <h3 class="flex-grow-1 text-center mb-0">Crop Details</h3>
                        <div style='visibility:hidden;' class="invisible"></div>
                    </div>


                    <div class="container">
                        <div class="row p-5">
                            <div class="col-md-8">


                                <div class="d-flex">
                                    <label class="w-50" for="crop">Crop Name</label>
                                    <label class="w-50" for="crop_value">{{ $crop['identifier']}}</label>
                                </div>


                                <div class="d-flex">
                                    <label class="w-50" for="crop">Acres</label>
                                    <label class="w-50" for="crop_value">{{ $crop['acres']}}</label>
                                </div>

                                <div class="d-flex">
                                    <label class="w-50" for="crop">Sowing Date</label>
                                    <label class="w-50"
                                        for="crop_value">{{Carbon\Carbon::parse($crop['sowing_date'])->format('d M Y')}}</label>
                                </div>

                                <div class="d-flex">
                                    <label class="w-50" for="crop">Harvest Date</label>
                                    <label class="w-50"
                                        for="crop_value">{{Carbon\Carbon::parse($crop['harvest_date'])->format('d M Y')}}</label>
                                </div>

                                <div class="d-flex">
                                    <label class="w-50" for="crop">Variety</label>
                                    <label class="w-50" for="crop_value">{{ $crop['variety']}}</label>
                                </div>

                                <div class="d-flex">
                                    <label class="w-50" for="crop">Status</label>
                                    <label class="w-50" for="crop_value">{{ $crop['active'] == 1 ? 'Active' : 'Passive'}}</label>
                                </div>

                                <div class="d-flex">
                                    <label class="w-50" for="crop">Description</label>
                                    <label class="w-50" for="crop_value">{{ $crop['description']}}</label>
                                </div>

                                @if (count($crop->deras) > 0)
                                <hr class="my-4 w-75">
                                <div class="text-start my-3 fw-bold">
                                    Deras
                                </div>
                                <!-- deras -->
                                @foreach($crop->deras as $dera)
                                <div class="d-flex">
                                    <label class="w-50" for="crop_value">{{ $dera['name']}}</label>
                                    <label class="w-50" for="crop">{{ $dera['pivot']['acres']}} Acres</label>
                                </div>
                                @endforeach
                                @endif

                            </div>

                            <div class="col-md-4 m-auto">
                                <div class="cropDetailImg d-flex">
                                    <img src="{{asset('images/crops/'. str_replace(' ', '', $crop['name']) .'.jpg')}}"
                                        alt="" class="img-fluid">
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
<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>

</html>