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

        @if(Session::get('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
        {{Session::forget('success')}}
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
                                <a href="{{ route('manager.farms') }}" class="back-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" class='svg' viewBox="0 0 24 24" width="512"
                                        height="512">
                                        <path
                                            d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                                    </svg>
                                </a>
                                <h3 class="flex-grow-1 text-center mb-0">{{$farm['name']}}</h3>
                                <div style='visibility:hidden;' class="invisible"></div>
                            </div>



                            <div class="col-md-7 p-3">
                                <div class="box-conts px-4">
                                    <h4 class='text-start mb-2 light'>Farm Details</h4>
                                    <div class="row px-2">

                                        <div class="labelcontainer" style='padding:0px 3px;!important'>
                                            <label class='light w-75'>Name</label>
                                            <label class='w-50'>{{$farm['name']}}</label>
                                        </div>

                                        <div class="labelcontainer" style='padding:0px 3px;!important'>
                                            <label class='light w-75'>City</label>
                                            <label class='w-50'>{{$farm['city']}}</label>
                                        </div>

                                        <div class="labelcontainer" style='padding:0px 3px;!important'>
                                            <label class='light w-75'>Address</label>
                                            <label class='w-50'>{{$farm['address']}}</label>
                                        </div>


                                        <div class="labelcontainer" style='padding:0px 3px;!important'>
                                            <label class='light w-75'>Acres</label>
                                            <label class='w-50'>{{$farm['number_of_acres']}}</label>
                                        </div>

                                        <div class="labelcontainer" style='padding:0px 3px;!important'>
                                            <label class='light w-75'>Crops</label>
                                            <label class='w-50'>{{$farm->crops->count() }} </label>
                                        </div>

                                        <div class="labelcontainer" style='padding:0px 3px;!important'>
                                            <label class='light w-75'>Has Deras</label>
                                            <label class='w-50'>{{ $farm['has_deras'] ? 'Yes' : 'No' }}</label>
                                        </div>

                                        <!-- deras -->
                                        @if($farm['has_deras'])
                                        <div class="labelcontainer" style='padding:0px 3px;!important'>
                                            <label class='light w-75'>Deras</label>
                                            <label class='w-50'>{{$farm->deras->count()}}</label>
                                        </div>
                                        @endif


                                        <div class="labelcontainer" style='padding:0px 3px;!important'>
                                            <label class='light w-75'>Workers</label>
                                            <label class='w-50'>{{count($workers)}}</label>
                                        </div>
                                    </div>
                                </div>

                            </div>
                            <div class="my-4 col-md-5">
                                <div class="piccont">
                                    <img src="{{asset('images/satellite.png')}}" />
                                </div>
                            </div>



                            <div class="col-md-12 p-3 ">
                                <div class="box-cont p-4">
                                    <div class="d-flex justify-content-between">
                                        <h4 class='light'>Farm Status</h4>
                                        <div class="d-flex">
                                            <button class="btn btn-brown" onclick='handleAddCrop()'>Add New Crop
                                                <svg class='mb-1 mx-1 svg' style='fill:white;' height="512" viewBox="0 0 24 24" width="512" xmlns="http://www.w3.org/2000/svg" data-name="Layer 1"><path d="m12 0a12 12 0 1 0 12 12 12.013 12.013 0 0 0 -12-12zm0 22a10 10 0 1 1 10-10 10.011 10.011 0 0 1 -10 10zm1-11h4v2h-4v4h-2v-4h-4v-2h4v-4h2z"/></svg>
                                            </button>
                                            <button class="btn  mx-3 btn-brown" onclick='handleActivity()'>Farm History</button>
                                        </div>
                                    </div>

                                    <div class="row" id="cropsContainer">
                                        @foreach($farm->crops as $crop)
                                        @if($crop['active'] == 1)
                                        <div
                                            class="col-md-3 mt-4 my-2 crop {{ $crop['active'] == '1' ? 'active-crop' : 'passive-crop' }}">
                                            <div class="selected-crop" style="background-color:#f1f1f1"
                                                onclick="handleCropClick('{{$crop['id']}}')">
                                                <img src="{{asset('images/crops/'. str_replace(' ', '', $crop['name']) .'.jpg')}}"
                                                    class="selected-crop-image" />
                                                <div class="d-flex justify-content-between">
                                                    <h5 class="m-2 my-3">{{$crop['identifier']}}</h5>
                                                    <div class="greenCircle mx-2"></div>
                                                </div>
                                                @if ($crop['variety'] != null)
                                                <div class="light mx-2 fsmall">{{$crop['variety']}}</div>
                                                @else
                                                <div class="light mx-2 fsmall">No variety specified</div>
                                                @endif
                                            </div>
                                        </div>
                                        @endif
                                        @endforeach
                                    </div>



                                    @if (count($farm->crops) == 0)
                                    <div class="m-3 text-center">
                                        <p class='light'>No crops configured yet</p>
                                    </div>
                                    @endif


                                </div>
                            </div>

                            <div class="col-md-7 p-3">
                                <div class="box-cont p-3 ">
                                    <div class="">
                                        <div class="card-body row p-3">
                                            <div class="col-md-3 text-center m-auto">
                                                <img src="{{asset('images/settings.png')}}" style='height:80px;' />
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="card-title">Farm Configuration</h5>
                                                <p class="mt-2 card-text"> Configure and edit your farm's assets and
                                                    settings</p>
                                            </div>
                                            <div class="col-md-3">
                                                <div
                                                    class="text-end d-flex justify-content-center align-items-end h-100 flex-column">
                                                    <button class="btn btn-brown" style="width:70px;"
                                                        onclick='handleConfiguration()'>
                                                        <svg xmlns="http://www.w3.org/2000/svg" id="configuration"
                                                            class='svg' viewBox="0 0 24 24" width="512" height="512">
                                                            <path
                                                                d="M18,12h0a2,2,0,0,0-.59-1.4l-4.29-4.3a1,1,0,0,0-1.41,0,1,1,0,0,0,0,1.42L15,11H5a1,1,0,0,0,0,2H15l-3.29,3.29a1,1,0,0,0,1.41,1.42l4.29-4.3A2,2,0,0,0,18,12Z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>



                                    <!-- seperatpr -->
                                    <div class="my-3">
                                        <hr />
                                    </div>

                                    <div class="mt-3">
                                        <div class="card-body row p-3">
                                            <div class="col-md-3 text-center m-auto">
                                                <img src="{{asset('images/statistics.png')}}" style='height:80px;' />
                                            </div>
                                            <div class="col-md-6">
                                                <h5 class="card-title">Analytics</h5>
                                                <p class="mt-2 card-text">View your farm's and crop's analytics
                                                </p>
                                            </div>
                                            <div class="col-md-3">
                                                <form action="{{route('manager.analytics')}}" method='post'
                                                    id='analytics-form'>
                                                    @csrf
                                                    <div
                                                        class="text-end  d-flex justify-content-center align-items-end h-100 flex-column">
                                                        <button type='button' class="btn btn-brown" style="width:70px;"
                                                            onclick='handleAnalytics()'>
                                                            <svg xmlns="http://www.w3.org/2000/svg" id="configuration"
                                                                class='svg' viewBox="0 0 24 24" width="512"
                                                                height="512">
                                                                <path
                                                                    d="M18,12h0a2,2,0,0,0-.59-1.4l-4.29-4.3a1,1,0,0,0-1.41,0,1,1,0,0,0,0,1.42L15,11H5a1,1,0,0,0,0,2H15l-3.29,3.29a1,1,0,0,0,1.41,1.42l4.29-4.3A2,2,0,0,0,18,12Z" />
                                                            </svg>
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>




                                <div class="box-cont mt-4">
                                    <div class="row">

                                    </div>
                                </div>
                            </div>




                            <div class="col-md-5 p-3">
                                <div class="box-cont p-3 ">
                                    <div class="">
                                        <div class="card-body row p-3">
                                            <div class="col-md-8">
                                                <h5 class="card-title">Expense</h5>
                                                <p class="mt-2 card-text">Add or View your farm's and crop's
                                                    expenses</p>
                                            </div>
                                            <div class="col-md-4">
                                                <div
                                                    class="text-end d-flex justify-content-center align-items-end h-100 flex-column">
                                                    <button class="btn btn-brown" style="width:70px;"
                                                        onclick='handleExpenseClick()'>
                                                        <svg xmlns="http://www.w3.org/2000/svg" id="configuration"
                                                            class='svg' viewBox="0 0 24 24" width="512" height="512">
                                                            <path
                                                                d="M18,12h0a2,2,0,0,0-.59-1.4l-4.29-4.3a1,1,0,0,0-1.41,0,1,1,0,0,0,0,1.42L15,11H5a1,1,0,0,0,0,2H15l-3.29,3.29a1,1,0,0,0,1.41,1.42l4.29-4.3A2,2,0,0,0,18,12Z" />
                                                        </svg>
                                                    </button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- seperatpr -->
                                    <div class="my-3">
                                        <hr />
                                    </div>

                                    <div class="mt-3">
                                        <div class="card-body row p-3">
                                            <div class="col-md-8">
                                                <h5 class="card-title">Sales</h5>
                                                <p class="mt-2 card-text">Add or View your farm's and crop's sales
                                                </p>
                                            </div>
                                            <div class="col-md-4">
                                                <div
                                                    class="text-end  d-flex justify-content-center align-items-end h-100 flex-column">
                                                    <a href="#" class="btn btn-brown" style="width:70px;">
                                                        <svg xmlns="http://www.w3.org/2000/svg" id="configuration"
                                                            class='svg' viewBox="0 0 24 24" width="512" height="512">
                                                            <path
                                                                d="M18,12h0a2,2,0,0,0-.59-1.4l-4.29-4.3a1,1,0,0,0-1.41,0,1,1,0,0,0,0,1.42L15,11H5a1,1,0,0,0,0,2H15l-3.29,3.29a1,1,0,0,0,1.41,1.42l4.29-4.3A2,2,0,0,0,18,12Z" />
                                                        </svg>
                                                    </a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="box-cont mt-4">
                                    <div class="text-start">
                                        <h4 class='light px-2'>Workers</h4>
                                        @foreach($workers as $worker)

                                        <div
                                            class="my-2 popular-crop w-100 d-flex justify-content-start align-items-center px-2 py-2">
                                            <img src="{{asset('images/profile.jpg')}}" id='profile-image'
                                                class='mx-2' />
                                            <div class='mx-3'>
                                                <div class="">{{$worker->name}}</div>
                                                <div class=" light fsmall">
                                                    {{$worker->role == 'expense_farmer' ? 'Expense Worker' : 'Sales Worker'}}
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach

                                        <div class="card-body row p-3">
                                            <div class="col-md-8">
                                                <p class="mt-2 card-text">Manage your workers</p>
                                            </div>
                                            <div class="col-md-4">
                                                <div
                                                    class="text-end d-flex justify-content-center align-items-end h-100 flex-column">
                                                    <button class="btn btn-brown" style="width:70px;"
                                                        onclick='handleWorkerClick()'>
                                                        <svg xmlns="http://www.w3.org/2000/svg" id="configuration"
                                                            class='svg' viewBox="0 0 24 24" width="512" height="512">
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
        </div>
    </div>


    <div id="footer">
        @include('components.footer')
    </div>

    </div>

</body>
<script src="{{ asset('js/alert.js') }}"></script>
<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('js/handleAnalytics.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
function handleConfiguration() {
    window.location.href = "{{ route('manager.configuration' , ['farm_id' => $farm['id']] )}}"
}

function handleExpenseClick() {
    window.location.href = "{{ route('manager.render_cropexpense' , ['farm_id' => $farm['id'] ,'worker'=> 0 ])}}"
}

function handleWorkerClick() {
    window.location.href = "{{ route('manager.render_workers' , ['farm_id' => $farm['id']] )}}"
}

function handleCropClick(crop_id) {
    window.location.href = "{{ route('manager.cropdetails' , ['farm_id' => $farm['id'], 'crop_id' => ':crop_id', 'route_id' => 0 ])}}"
        .replace(':crop_id', crop_id);
}

function handleAddCrop() {
    window.location.href = "{{ route('manager.addCrop' , ['farm_id' => $farm['id']] )}}"
}



function enableSelect(cropId) {
    const selectElement = document.getElementById('status-' + cropId);
    const remarksElement = document.getElementById('remarks-' + cropId);
    const buttonElement = document.getElementById('button-' + cropId);

    if (selectElement.disabled) {
        selectElement.disabled = false;
        remarksElement.disabled = false;
        buttonElement.innerText = 'Save';
    } else {
        // Create a hidden form to submit the data
        const form = document.getElementById('crop-status-form');
        form.submit();
    }
}

function handleActivity() {
    window.location.href = "{{ route('manager.farm_history' , ['farm_id' => $farm['id']] )}}"
}
</script>

</html>