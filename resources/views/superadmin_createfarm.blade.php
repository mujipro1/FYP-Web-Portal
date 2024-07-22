<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navBar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/questionaire.css') }}">
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
             
                <div class="col-md-10 offset-md-1">

                <div class="container p-3  section ">
                <div class="row">
                       
                <div class="d-flex justify-content-between align-items-center my-3 mb-4">
                    <a href="{{ route('superadmin.requests') }}" class="back-button">
                        <svg xmlns="http://www.w3.org/2000/svg" class='svg' viewBox="0 0 24 24" width="512"
                            height="512">
                            <path
                                d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                        </svg>
                    </a>
                    <h3 class="flex-grow-1 text-center mb-0">Create Farm</h3>
                    <div style='visibility:hidden;' class="invisible"></div>
                </div>
                
                    <form action="{{ route('superadmin.submit_createfarm') }}" method="POST">
                        @csrf
                        <div class="container px-3">
                            <div class="row px-3">
                              
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
                                                {{$request['user_info']['farmerName']}}

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
                                        <div class="labelcontainer mx-3">
                                            <label class="">
                                                Farm Name
                                            </label>
                                            <input
                                                type="text"
                                                class="form-control w-75 ml-3"
                                                name="farmName"
                                                value="{{$request['farm_info']['farmName']}}"
                                            />
                                        </div>
                                        <div class="labelcontainer mx-3">
                                            <label class="">City</label>
                                            <select class="form-control w-75 ml-3" name="farmCity" id='citydropdown' >
                                                                                              
                                            </select>
                                        </div>
                                        <div class="labelcontainer mx-3">
                                            <label class="">Acres</label>
                                            <input
                                                type="number"
                                                class="form-control w-75 ml-3"
                                                name="acres"
                                                value="{{$request['farm_info']['acres']}}"
                                            />
                                        </div>
                                        <div class="labelcontainer mx-3">
                                            <label class="">Address</label>
                                            <input
                                                type="text"
                                                class="form-control w-75 ml-3"
                                                name="address"
                                                value="{{$request['farm_info']['farmAddress']}}"
                                            />
                                        </div>
                                        <div class="labelcontainer mx-3">
                                            <label class="">Has Deras</label>
                                            <select class="form-control w-75 ml-3" name="has_Deras" >
                                                <option selected value="{{$request['farm_info']['has_deras']}}" disabled>{{$request['farm_info']['has_deras'] == 1 ? 'Yes' : 'No'}}</option>
                                                <option value="1">Yes</option>
                                                <option value="0">No</option>
                                            </select>
                                            
                                        </div>
                                        <div class="labelcontainer mx-3">
                                            <label class="">No of Deras</label>
                                            <input
                                                type="text"
                                                class="form-control w-75 ml-3"
                                                name="numberOfDeras"
                                                value="{{$request['farm_info']['deras']}}"
                                            />
                                        </div>
                                        
                                        @if($request['farm_info']['has_deras'] == '1')
                                        <!-- json decode
                                          -->
                                        @php
                                            $deraAcres = explode(',', $request->farm_info['deraAcres']);
                                            $deraAcres = array_map('intval', $deraAcres);
                                        @endphp

                                            @foreach($deraAcres as $index => $deraAcre)
                                                <div class="labelcontainer mx-3">
                                                    <label class="">Dera {{$loop->iteration}} (Acres)</label>
                                                    <input
                                                        type="text"
                                                        class="form-control w-75 ml-3"
                                                        name="deraAcres[]"
                                                        value="{{ $deraAcre }}"
                                                    />
                                                </div>
                                            @endforeach
                                        @endif


                                       <input hidden name='user_id' value="{{$request['user_id']}}">
                                       <input hidden name='request_id' value="{{$request['id']}}">
                                        
                                       <div class="text-center mt-4 ">
                                            <button class="btn text-light btn-brown" type="submit">Create</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6 p-3  mb-3">
                                    <div class="box-cont " style='height: '>
                                        <div class="text-center">
                                            Request
                                        </div>
                                        <div class="overflow-y text-justify mt-4 light pt-3 px-4" style='height: 72vh;'>
                                        
                                            <div class="d-flex justify-content-start">
                                                <p class='question'>Hello, I am Hina and I will be asking you some questions, So lets get started!</p>
                                            </div>

                                            <div class="d-flex justify-content-start">
                                                <p class='question'>Could you please tell us your name?</p>
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <p class='answer'><strong>{{$request['user_info']['farmerName']}}</strong></p>
                                            </div>

                                            <div class="d-flex justify-content-start">
                                                <p class='question'>Please enter your email address.</p>
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <p class='answer'><strong>{{$request['user_info']['email']}}</strong></p>
                                            </div>

                                            
                                            <div class="d-flex justify-content-start">
                                                <p class='question'>Please enter your phone number.</p>
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <p class='answer'><strong>{{$request['user_info']['phone']}}</strong></p>
                                            </div>


                                            <div class="d-flex justify-content-start">
                                                <p class='question'>What is the name of your farm?</p>
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <p class='answer'><strong>{{$request['farm_info']['farmName']}}</strong></p>
                                            </div>

                                            <div class="d-flex justify-content-start">
                                                <p class='question'>Which city is your farm located in?</p>
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <p class='answer'><strong>{{$request['farm_info']['farmCity']}}</strong></p>
                                            </div>

                                            <div class="d-flex justify-content-start">
                                                <p class='question'>Could you provide the complete address of your farm?</p>
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <p class='answer'><strong>{{$request['farm_info']['farmAddress']}}</strong></p>
                                            </div>
                                            
                                            <div class="d-flex justify-content-start">
                                                <p class='question'>What is the total area of your farm in acres?</p>
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <p class='answer'><strong>{{$request['farm_info']['acres']}}</strong></p>
                                            </div>

                                            <div class="d-flex justify-content-start">
                                                <p class='question'>Does your farm have any deras?</p>
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <p class='answer'><strong>{{$request['farm_info']['has_deras']}}</strong></p>
                                            </div>

                                            @if($request['farm_info']['has_deras'] == '1')


                                                <div class="d-flex justify-content-start">
                                                    <p class='question'>Please enter the number of deras in your farm.</p>
                                                </div>

                                                <div class="d-flex justify-content-end">
                                                    <p class='answer'><strong>{{$request['farm_info']['deras']}}</strong></p>
                                                </div>

                                                <div class="d-flex justify-content-start">
                                                    <p class='question'>Please enter the number of acres in each dera.</p>
                                                </div>

                                            @foreach($deraAcres as $index => $deraAcre)
                                                <div class="d-flex justify-content-end">
                                                    <p class='answer'><strong>Dera {{$loop->iteration}} : {{$deraAcre}}</strong></p>
                                                </div>
                                                @endforeach

                                            @endif

                                            <div class="d-flex justify-content-start">
                                                <p class='question'>If there is any additional information that you want to provide to us, please share.</p>
                                            </div>

                                            <div class="d-flex justify-content-end">
                                                <p class='answer'><strong>{{$request['details']}}</strong></p>
                                            </div>

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
<script src="{{ asset('js/data/citydata.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
    const val = document.querySelector('[name="numberOfDeras"]').value;
    document.querySelector('[name="has_Deras"]').value = "{{$request['farm_info']['has_deras']}}";
    if ("{{$request['farm_info']['has_deras']}}" == 0) {
        document.querySelector('[name="numberOfDeras"]').disabled = true;
    }

    // add event listener as well
    document.querySelector('[name="has_Deras"]').addEventListener('change', function() {
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

    citydropdown.value = "{{$request['farm_info']['farmCity']}}";

</script>
</html>