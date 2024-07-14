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

        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 mt-3 sidebarcol">
                    <div class="SuperAdminSidebar sidebar"></div>
                </div>
                <div class="col-md-10 ">

                <div class="container p-3 mt-3 section ">
                <div class="row">
                        <div class="text-center my-3">
                            <h4>Create Farm</h4>
                        </div>

                    <form action="{{ route('superadmin.submit_createfarm') }}" method="POST">
                        @csrf
                        <div class="container px-3">
                            <div class="row px-3">
                                <!-- <div class="my-4 px-5">
                                    <div class="progress">
                                        <div class="progress-bar progress-bar-striped active" role="progressbar"
                                             aria-valuenow="33" aria-valuemin="0" aria-valuemax="100" style="width:33%">
                                            33%
                                        </div>
                                    </div>
                                </div> -->
                                <div class="col-md-6">
                                    <div class=" p-3 mb-3">
                                        <div class="box-cont">
                                            <div class="text-center">
                                                Request Status
                                            </div>
                                            <div class=" labelcontainer1 light pt-3 px-4">
                                                <label class="w-50">
                                                    Request ID
                                                </label>
                                                <label class="">
                                                    {{$request['id']}}
                                                </label>
                                            </div>
                                            <div class=" labelcontainer1 light px-4">
                                                <label class="w-50">
                                                    Farmer
                                                </label>
                                                <label class="">
                                                {{$request['user_info']['name']}}

                                                </label>
                                            </div>
                                            <div class=" labelcontainer1 light px-4">
                                                <label class="w-50">Date</label>
                                                <label class="">
                                                    {{$request['created_at']}}
                                                </label>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="">
                                        <div class="labelcontainer">
                                            <label class="">
                                                Farm Name
                                            </label>
                                            <input
                                                type="text"
                                                class="form-control ml-3"
                                                name="farmName"
                                                value="{{$request['farm_info']['name']}}"
                                            />
                                        </div>
                                        <div class="labelcontainer">
                                            <label class="">City</label>
                                            <select class="form-control ml-3" name="farmCity" id='citydropdown' >
                                                                                              
                                            </select>
                                        </div>
                                        <div class="labelcontainer">
                                            <label class="">Acres</label>
                                            <input
                                                type="number"
                                                class="form-control ml-3"
                                                name="acres"
                                                value="{{$request['farm_info']['number_of_acres']}}"
                                            />
                                        </div>
                                        <div class="labelcontainer">
                                            <label class="">Address</label>
                                            <input
                                                type="text"
                                                class="form-control ml-3"
                                                name="address"
                                                value="{{$request['farm_info']['address']}}"
                                            />
                                        </div>
                                        <div class="labelcontainer">
                                            <label class="">Has Deras</label>
                                            <select class="form-control ml-3" name="hasDeras" >
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                            
                                        </div>
                                        <div class="labelcontainer mb-3">
                                            <label class="">No of Deras</label>
                                            <input
                                                type="text"
                                                class="form-control ml-3"
                                                name="numberOfDeras"
                                                value="{{$request['farm_info']['number_of_deras']}}"
                                            />
                                        </div>
                                       <input hidden name='user_id' value="{{$request['user_id']}}">
                                       <input hidden name='request_id' value="{{$request['id']}}">
                                        
                                       <div class="text-center mt-3 ">
                                            <button class="btn text-light btn-brown" type="submit">Next</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 p-3  mb-3">
                                    <div class="box-cont ">
                                        <div class="text-center">
                                            Request
                                        </div>
                                        <div class="overflow-y text-justify mt-4 light pt-3 px-4">
                                            <p>Request</p>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
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
<script src="{{ asset('js/SuperadminSidebar.js') }}"></script>
<script src="{{ asset('js/data/citydata.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
    const val = document.querySelector('[name="numberOfDeras"]').value;
    document.querySelector('[name="hasDeras"]').value = "{{$request['farm_info']['has_deras']}}";
    if ("{{$request['farm_info']['has_deras']}}" == 0) {
        document.querySelector('[name="numberOfDeras"]').disabled = true;
    }

    // add event listener as well
    document.querySelector('[name="hasDeras"]').addEventListener('change', function() {
        if (this.value == 0) {
            document.querySelector('[name="numberOfDeras"]').disabled = true;
            document.querySelector('[name="numberOfDeras"]').value = "0";
        } else {
            document.querySelector('[name="numberOfDeras"]').disabled = false;
            document.querySelector('[name="numberOfDeras"]').value = val;
            
        }
    });

    // 
    var citydropdown = document.getElementById('citydropdown');
    citydata.forEach(function(city) {
        var option = document.createElement('option');
        option.value = city;
        option.text = city;
        citydropdown.appendChild(option);
    });

    citydropdown.value = "{{$request['farm_info']['city']}}";

</script>
</html>