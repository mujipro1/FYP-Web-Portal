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


        <div class="container-fluid">
            <div class="row">
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>
                <div class="col-md-10 offset-md-1 ">

                    <div class="d-flex  justify-content-between align-items-center my-4">
                        <a href="{{ route('manager.farmdetails', ['farm_id' => $farm_id]) }}" class="back-button">
                            <svg xmlns="http://www.w3.org/2000/svg" class='svg' viewBox="0 0 24 24" width="512"
                                height="512">
                                <path
                                    d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                            </svg>
                        </a>
                        <h3 class="flex-grow-1 text-center mb-0">Analytics</h3>
                        <div style='visibility:hidden;' class="invisible"></div>
                    </div>

                    <div class="row mt-5">
                        <div class="col-md-6">
                            <div class="box-cont">
                                {!! $chart2->container() !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box-cont">
                                {!! $chart->container() !!}
                            </div>
                        </div>
                    </div>

                    <hr class="my-5">

                    <div class="row">

                        <div class=" col-md-6 p-2 my-3">
                            <div class="box-cont">
                                <div class="card-body row p-3">
                                    <div class="col-md-3 m-auto">
                                        <img src="{{ asset('images/SingleCrop.png') }}" alt="single" style='width:100px;'>
                                    </div>
                                    <div class="col-md-7">
                                        <h5 class="card-title">Single Crop Analytics</h5>
                                        <p class="mt-2 card-text">View analytics of any single crop</p>
                                    </div>
                                    <div class="col-md-2">
                                        <div
                                            class="text-end d-flex justify-content-center align-items-end h-100 flex-column">
                                            <button class="btn btn-brown" style="width:70px;"
                                                onclick='handleSingleCrop()'>
                                                <svg xmlns="http://www.w3.org/2000/svg" id="configuration" class='svg'
                                                    viewBox="0 0 24 24" width="512" height="512">
                                                    <path
                                                        d="M18,12h0a2,2,0,0,0-.59-1.4l-4.29-4.3a1,1,0,0,0-1.41,0,1,1,0,0,0,0,1.42L15,11H5a1,1,0,0,0,0,2H15l-3.29,3.29a1,1,0,0,0,1.41,1.42l4.29-4.3A2,2,0,0,0,18,12Z" />
                                                </svg>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <div class="col-md-6 p-2 my-3">
                            <div class="box-cont">
                                <div class="card-body row p-3">
                                    <div class="col-md-3 m-auto">
                                        <img src="{{ asset('images/DoubleCrop.png') }}" alt="compare" style='width:100px;'>
                                    </div>
                                    <div class="col-md-7">
                                        <h5 class="card-title">Compare Two Crops</h5>
                                        <p class="mt-2 card-text">Compare analytics of two crops</p>
                                    </div>
                                    <div class="col-md-2">
                                        <div
                                            class="text-end  d-flex justify-content-center align-items-end h-100 flex-column">
                                            <button class="btn btn-brown" style="width:70px;"
                                            onclick='handleCompareCrop()'
                                            >
                                                <svg xmlns="http://www.w3.org/2000/svg" id="configuration" class='svg'
                                                    viewBox="0 0 24 24" width="512" height="512">
                                                    <path
                                                        d="M18,12h0a2,2,0,0,0-.59-1.4l-4.29-4.3a1,1,0,0,0-1.41,0,1,1,0,0,0,0,1.42L15,11H5a1,1,0,0,0,0,2H15l-3.29,3.29a1,1,0,0,0,1.41,1.42l4.29-4.3A2,2,0,0,0,18,12Z" />
                                                </svg>
                                            </button>
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
{{ $chart2->script() }}
<script src="{{ asset('js/alert.js') }}"></script>
<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
function handleSingleCrop() {
    window.location.href = "{{ route('manager.singlecrop', ['farm_id' => $farm_id]) }}";
}

function handleCompareCrop() {
    window.location.href = "{{ route('manager.comparecrop', ['farm_id' => $farm_id]) }}";
}
</script>

</html>