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
                        <a href="{{ route('manager.farmdetails', ['farm_id' => $farm_id]) }}" class="back-button">
                            <svg xmlns="http://www.w3.org/2000/svg"  class='svg' viewBox="0 0 24 24" width="512" height="512">
                                <path
                                    d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                            </svg>
                        </a>
                        <h3 class="flex-grow-1 text-center mb-0">Farm Configuration</h3>
                        <div style='visibility:hidden;' class="invisible"></div>
                    </div>

                    <div class="row section my-4 px-5">

                        <div class=" col-md-4 px-4 py-5">
                            <div class="mycard">
                                <img src="{{ asset('images/crops/Wheat.jpg') }}" alt="" class="img-fluid">
                                <div class="mycardInner">
                                    <div class="py-3 d-flex justify-content-center">
                                        <div class="line"></div>
                                    </div>
                                    <div class="text-center pt-2 ">
                                        <h4>Edit Crops</h4>
                                        <p class='fsmall'>Click here to edit crops</p>
                                    </div>
                                    <div class="text-center ">
                                        <button onclick="handleEditCrop()" class='btn or-width btn-orange'>Edit Crops</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-4 px-4 py-5">
                            <div class="mycard">
                                <img src="{{ asset('images/farm1.jpg') }}" alt="" class="img-fluid">
                                <div class="mycardInner">
                                    <div class="py-3 d-flex justify-content-center">
                                        <div class="line"></div>
                                    </div>
                                    <div class="text-center pt-2 ">
                                        <h4>Edit Deras</h4>
                                        <p class='fsmall'>Click here to edit deras</p>
                                    </div>
                                    <div class="text-center">
                                        <button onclick="handleEditDeras()" class='btn or-width btn-orange'>Edit Deras</button>
                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="col-md-4 py-5 px-4">
                            <div class="mycard">
                                <img src="{{ asset('images/farm3.jpg') }}" alt="" class="img-fluid">
                                <div class="mycardInner">
                                    <div class="py-3 d-flex justify-content-center">
                                        <div class="line"></div>
                                    </div>
                                    <div class="text-center pt-2 ">
                                        <h4>Config. Expenses</h4>
                                        <p class='fsmall'>Click here to configure Expenses</p>
                                    </div>
                                    <div class="text-center ">
                                        <button onclick="handleConfigureExpenses()" class='btn or-width btn-orange'>Configure
                                            </button>
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
<script>
function handleAddCrop() {
    window.location.href = "{{ route('manager.addCrop' , ['farm_id' => $farm_id] )}}"
}

function handleEditDeras() {
    window.location.href = "{{ route('manager.editDeras' , ['farm_id' => $farm_id] )}}"
}

function handleEditCrop() {
    window.location.href = "{{ route('manager.editCrops' , ['farm_id' => $farm_id] )}}"
}

function handleConfigureExpenses() {
    window.location.href = "{{ route('manager.configureExpenses' , ['farm_id' => $farm_id] )}}"
}
</script>

</html>