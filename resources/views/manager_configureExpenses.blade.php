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
    <link rel="stylesheet" href="{{ asset('css/superadmin.css') }}">
    <script src="{{ asset('js/alert.js') }}"></script>

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
                            <a href="{{ route('manager.configuration', ['farm_id' => $farm_id]) }}" class="back-button">
                                <svg xmlns="http://www.w3.org/2000/svg"  class='svg' viewBox="0 0 24 24" width="512" height="512">
                                    <path
                                        d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                                </svg>
                            </a>
                            <h3 class="flex-grow-1 text-center mb-0">{{__('messages.configure_expenses')}}</h3>
                            <div style='visibility:hidden;' class="invisible"></div>
                        </div>

                        <div class="row px-4">
                        <div class="offset-md-1 col-md-5 py-5 px-4">
                            <div class="mycard">
                                <img src="{{ asset('images/crops/Sunflower.jpg') }}" alt="" class="img-fluid">
                                <div class="mycardInner">
                                    <div class="py-3 d-flex justify-content-center">
                                        <div class="line"></div>
                                    </div>
                                    <div class="text-center pt-2 ">
                                        <h4>{{__('messages.farm_expenses')}}</h4>
                                        <p class='fsmall'>{{__('messages.click_to_configure_farm_expenses')}}</p>
                                    </div>
                                    <div class="text-center ">
                                        <button onclick="handleFarmExpenseConfig()" class='btn btn-primary'>{{__('messages.configure')}}</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class=" col-md-5 py-5 px-4">
                            <div class="mycard">
                                <img src="{{ asset('images/crops/Sunflower.jpg') }}" alt="" class="img-fluid">
                                <div class="mycardInner">
                                    <div class="py-3 d-flex justify-content-center">
                                        <div class="line"></div>
                                    </div>
                                    <div class="text-center pt-2 ">
                                        <h4>{{__('messages.crop_expenses')}}</h4>
                                        <p class='fsmall'>{{__('messages.click_to_configure_crop_expenses')}}</p>
                                    </div>
                                    <div class="text-center ">
                                        <button onclick="handleCropExpenseConfig()" class='btn btn-primary'>{{__('messages.configure')}}</button>
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

    
    <script src="{{ asset('js/ManagerSidebar.js') }}"></script>
    <script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/data/cropExpenseData.js') }}"></script>
    <script>
        function handleFarmExpenseConfig() {
            window.location.href = "{{ route('manager.configureFarmExpense', ['farm_id' => $farm_id]) }}";
        }

        function handleCropExpenseConfig() {
            window.location.href = "{{ route('manager.configureCropExpense', ['farm_id' => $farm_id]) }}";
        }
    </script>
</body>

</html>
