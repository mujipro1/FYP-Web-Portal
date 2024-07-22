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
                                        <button class="btn  mx-3 btn-brown" onclick='handleActivity()'>Activity</button>
                                    </div>
                                    <p class='light fsmall'>View current farm status, apply filter to view status at any
                                        date</p>

                                    <form action="{{route('manager.FarmStatusSearchPOST')}}" method='POST'>
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" name="farm_id" value="{{$farm['id']}}">

                                            <div class="col-md-5">
                                                <input type="date" id="date" name="date" class="form-control"
                                                    value="{{date('Y-m-d')}}" style='margin:0px;'>
                                            </div>

                                            <!-- search nutton -->
                                            <div class="col-md-3 mb-5">
                                                <button class="btn" type='submit'>
                                                    <svg xmlns="http://www.w3.org/2000/svg" class='svg'
                                                        xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1"
                                                        id="Capa_1" x="0px" y="0px" viewBox="0 0 513.749 513.749"
                                                        style="enable-background:new 0 0 513.749 513.749;"
                                                        xml:space="preserve" width="512" height="512">
                                                        <g>
                                                            <path
                                                                d="M504.352,459.061l-99.435-99.477c74.402-99.427,54.115-240.344-45.312-314.746S119.261-9.277,44.859,90.15   S-9.256,330.494,90.171,404.896c79.868,59.766,189.565,59.766,269.434,0l99.477,99.477c12.501,12.501,32.769,12.501,45.269,0   c12.501-12.501,12.501-32.769,0-45.269L504.352,459.061z M225.717,385.696c-88.366,0-160-71.634-160-160s71.634-160,160-160   s160,71.634,160,160C385.623,314.022,314.044,385.602,225.717,385.696z" />
                                                        </g>
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </form>

                                    <div class="table-responsive" style='height:40vh;'>
                                        <table id='' class="table table-scroll ">
                                            <form method="POST" action="{{ route('manager.updateCropStatus') }}"
                                                id="crop-status-form">
                                                @csrf
                                                <thead>
                                                    <tr>
                                                        <th>Crop Name</th>
                                                        <th>Sow Date</th>
                                                        <th>Acres</th>
                                                        <th>Variety</th>
                                                        <th>Status</th>
                                                        <th>Remarks</th>
                                                        <th>Actions</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach($farm->crops as $crop)
                                                    @php
                                                    $latestUpdate = $latestCropUpdates->where('crop_id', $crop->id)->first();
                                                    @endphp
                                                    @if ($crop['active'] == 1)
                                                    <tr>
                                                        <td>{{$crop['name']}}</td>
                                                        <td>{{Carbon\Carbon::parse($crop['sow_date'])->format('d M Y')}}
                                                        </td>
                                                        <td>{{$crop['acres']}}</td>
                                                        <td>{{$crop['variety']}}</td>

                                                        <td>
                                                            <select disabled name="status[{{$crop['id']}}]"
                                                                class="form-select" aria-label="Default select example"
                                                                style='cursor:pointer;' id="status-{{$crop['id']}}">
                                                                <option value="{{$crop['status']}}" selected>
                                                                    {{$crop['status']}}</option>
                                                                <option value="Germination">Germination</option>
                                                                <option value="Vegetative">Vegetative</option>
                                                                <option value="Flowering">Flowering</option>
                                                                <option value="Fruiting">Fruiting</option>
                                                                <option value="Harvesting">Harvesting</option>
                                                            </select>
                                                        </td>

                                                        <td>
                                                            <input type="text" name="remarks[{{$crop['id']}}]"  value="{{ $latestUpdate->remarks ?? '' }}"
                                                                class="form-control" id="remarks-{{$crop['id']}}"
                                                                disabled>
                                                        </td>

                                                      
                                                        <td>
                                                            <button type="button" class="btn btn-brown"
                                                                id="button-{{$crop['id']}}"
                                                                onclick="enableSelect('{{ $crop['id'] }}')">Edit</button>
                                                        </td>
                                                    </tr>
                                                    @endif
                                                    @endforeach
                                                </tbody>
                                            </form>
                                        </table>
                                    </div>
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
                                                <form action="{{route('manager.analytics')}}" method='post' id='analytics-form'>
                                                    @csrf
                                                    <div class="text-end  d-flex justify-content-center align-items-end h-100 flex-column">
                                                        <button type='button' class="btn btn-brown" style="width:70px;"
                                                            onclick='handleAnalytics()'>
                                                            <svg xmlns="http://www.w3.org/2000/svg" id="configuration"
                                                                class='svg' viewBox="0 0 24 24" width="512" height="512">
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
                                        <div class="text-start">
                                            <h4 class='light'>Crops</h4>
                                        </div>

                                        <!-- add a radio button to select active or passive crops -->
                                        <div class="crop-filter d-flex justify-content-start">
                                            <div class="m-3">
                                                <input type="radio" class='form-check-input' id='active'
                                                    name="cropFilter" value="active" checked
                                                    onclick="filterCrops('active')" style='cursor:pointer;'>
                                                <label for='active'>Active</label>
                                            </div>
                                            <div class="m-3">
                                                <input type="radio" id='passive' class='form-check-input'
                                                    name="cropFilter" value="passive" onclick="filterCrops('passive')"
                                                    style='cursor:pointer;'>
                                                <label class='passive'>Passive</label>
                                            </div>
                                        </div>
                                        <div class="row" id="cropsContainer">
                                            @foreach($farm->crops as $crop)
                                            <div
                                                class="col-md-6 my-2 crop {{ $crop['active'] == '1' ? 'active-crop' : 'passive-crop' }}">
                                                <div class="selected-crop" style="background-color:#f1f1f1"
                                                    onclick="handleCropClick('{{$crop['id']}}')">
                                                    <img src="{{asset('images/crops/'. str_replace(' ', '', $crop['name']) .'.jpg')}}"
                                                        class="selected-crop-image" />
                                                    <div class="d-flex justify-content-between">
                                                        <h5 class="m-2 my-3">{{$crop['identifier']}}</h5>
                                                        <div class="greenCircle mx-2"></div>
                                                    </div>
                                                    <div class="light mx-2 fsmall">{{$crop['status']}}</div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>


                                        @if (count($farm->crops) == 0)
                                        <div class="m-3 text-center">
                                            <p class='light'>No crops configured yet</p>
                                        </div>
                                        @endif

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
    window.location.href = "{{ route('manager.cropdetails' , ['farm_id' => $farm['id'], 'crop_id' => ':crop_id'] )}}"
        .replace(':crop_id', crop_id);
}



function filterCrops(filter) {
    var activeCrops = document.querySelectorAll('.active-crop');
    var passiveCrops = document.querySelectorAll('.passive-crop');

    if (filter === 'active') {
        activeCrops.forEach(function(crop) {
            crop.style.display = 'block';
        });
        passiveCrops.forEach(function(crop) {
            crop.style.display = 'none';
            
        });
    } else if (filter === 'passive') {
       
        activeCrops.forEach(function(crop) {
            crop.style.display = 'none';
        });
        passiveCrops.forEach(function(crop) {
            crop.style.display = 'block';
        });
    }
}
filterCrops('active');



// for passive, change greenCircle to redCircle and vice versa

document.querySelectorAll('.passive-crop').forEach(function(crop) {
    crop.querySelector('.greenCircle').classList.add('redCircle');
    crop.querySelector('.greenCircle').classList.remove('greenCircle');
});


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