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
                <div class="col-md-10 offset-md-1 section">

                    <div class="container mt-4">
                    <div class="d-flex justify-content-between align-items-center mb-5 my-3">
                        <a href="{{ route('superadmin') }}" class="back-button">
                            <svg xmlns="http://www.w3.org/2000/svg" class='svg' viewBox="0 0 24 24" width="512"
                                height="512">
                                <path
                                    d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                            </svg>
                        </a>
                        <h3 class="flex-grow-1 text-center mb-0">Requests</h3>
                        <div style='visibility:hidden;' class="invisible"></div>
                    </div>
                

                        <div class="row px-3 mt-3">
                            <div class="col-md-5 offset-md-1 px-3">
                                <div class="mx-4 labelcontainer">
                                    <label class='w-25'>Status</label>
                                    <select id='request-filter' class='form-control ml-3' value="" onChange="">
                                        <option selected value='all'>All</option>
                                        <option value='pending'>Pending</option>
                                        <option value='approved'>Approved</option>
                                    </select>
                                </div>
                            </div>

                        </div>
                        <div class="row p-3">
                            <div class="col-md-10 p-3 offset-md-1">
                                <div class="request-list ">

                                    <div hidden  id='no-requests' class='my-3 text-center'>
                                    </div>

                                    @foreach($requests as $request)


                                    <div class="request-item" data-status="{{ strtolower($request['status']) }}">
                                        <div class="box-cont row my-4">
                                            <div class="col-md-9">
                                                <h5 class='px-3 pt-3'>{{$request['user_info']['farmerName']}}</h5>
                                                <p class=' px-3 text-success'>{{$request['created_at']}}</p>
                                                <div class="px-3 labelcontainer1">
                                                    <label class=' light w-25'>Farm Name</label>
                                                    <label>{{$request['farm_info']['farmName']}}</label>
                                                </div>
                                                <div class="px-3 labelcontainer1">
                                                    <label class=' light w-25'>City</label>
                                                    <label>{{$request['farm_info']['farmCity']}}</label>
                                                </div>
                                                <div class="px-3 labelcontainer1">
                                                    <label class=' light w-25'>Acres</label>
                                                    <label>{{$request['farm_info']['acres']}}</label>
                                                </div>
                                                <div class="px-3 labelcontainer1">
                                                    <label class=' light w-25'>Has Deras</label>
                                                    <label>{{$request['farm_info']['deras'] == 0 ? 'No' : 'Yes'}}</label>
                                                </div>
                                                @if($request['farm_info']['deras'] > 0)
                                                <div class="px-3 labelcontainer1">
                                                    <label class=' light w-25'>No of Deras</label>
                                                    <label>{{$request['farm_info']['deras']}}</label>
                                                </div>
                                                @endif
                                                <div class="px-3 labelcontainer1">
                                                    <label class=' light w-25'>Address</label>
                                                    <label>{{$request['farm_info']['farmAddress']}}</label>
                                                </div>
                                            </div>
                                            <div class='col-md-3 py-4 text-center'>
                                                <div class="d-flex justify-content-center">
                                                    
                                                <!-- convert to lowercase then check -->
                                                    @if(strtolower($request['status']) == 'pending')
                                                    <svg class='mb-3 svg mx-2' xmlns="http://www.w3.org/2000/svg"
                                                        id="Layer_1" data-name="Layer 1" viewBox="0 0 24 24" width="512"
                                                        height="512">
                                                        <path
                                                            d="M12,24C5.383,24,0,18.617,0,12S5.383,0,12,0s12,5.383,12,12-5.383,12-12,12Zm0-22C6.486,2,2,6.486,2,12s4.486,10,10,10,10-4.486,10-10S17.514,2,12,2Zm5,10c0-.553-.447-1-1-1h-3V6c0-.553-.448-1-1-1s-1,.447-1,1v6c0,.553,.448,1,1,1h4c.553,0,1-.447,1-1Z" />
                                                    </svg>
                                                    @else
                                                    <p class='mx-2'>✔</p>
                                                    @endif

                                                    <p>{{$request['status']}}</p>
                                                </div>
                                                @if(strtolower($request['status']) == 'pending')
                                                <form method="POST"
                                                    action="{{ route('superadmin.render_createfarm') }}">
                                                    @csrf
                                                    <input type="hidden" name="request_Id" value="{{$request['id']}}">
                                                    <button type="submit" class="btn text-light btn-brown">
                                                        Create Farm
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>

                                    @endforeach
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

<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
document.getElementById('request-filter').addEventListener('change', function() {
    const selectedStatus = this.value;
    const requests = document.querySelectorAll('.request-item');

    // if selected status has no requests, show no requests message
    let hasRequests = false;
    requests.forEach(function(request) {
        if (request.getAttribute('data-status') === selectedStatus) {
            hasRequests = true;
        }
        // check all condition as well
        if (selectedStatus === 'all') {
            hasRequests = true;
        }
    });

    if (!hasRequests) {
        document.getElementById('no-requests').innerText = 'No requests found';
        document.getElementById('no-requests').hidden = false;
    } else {
        document.getElementById('no-requests').innerText = '';
        document.getElementById('no-requests').hidden = true;
    }

    requests.forEach(function(request) {
        if (selectedStatus === 'all') {
            request.style.display = 'block';
        } else if (request.getAttribute('data-status') === selectedStatus) {
            request.style.display = 'block';
        } else {
            request.style.display = 'none';
        }
    });
});
</script>


</html>