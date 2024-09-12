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
                @if ($worker == 0)
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>
                @endif
                    <div class="col-md-10 offset-md-1 ">


                    <div class="container">

                        <div class="d-flex justify-content-between align-items-center my-3">
                            @if ($worker == 0)
                            <a href="{{ route('manager.view_sales', ['farm_id' => $farm_id]) }}"
                            class="back-button">
                                    <svg xmlns="http://www.w3.org/2000/svg"  class='svg' viewBox="0 0 24 24" width="512" height="512"><path d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z"/></svg>
                                </a>
                            @else
                            <a href="{{ route('sales_farmer') }}"
                            class="back-button">
                                    <svg xmlns="http://www.w3.org/2000/svg"  class='svg' viewBox="0 0 24 24" width="512" height="512"><path d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z"/></svg>
                                </a>
                                
                            @endif
                            <h3 class="flex-grow-1 text-center mb-0">Sale Details</h3>
                            <div style='visibility:hidden;' class="invisible"></div>
                        </div>

                        <div class="row">
                            <div class="p-3">
                            
                                <div class="box-cont p-5">
                                    <div class="row p-5">
                                        <div class="col-md-8">

                                            <div class="d-flex">
                                                <label class="w-50" for="date">Date</label>
                                                <label class="w-50" for="date_value">
                                                    <!-- use carbon to set format -->
                                                    {{ \Carbon\Carbon::parse($sale->date)->format('d M Y') }}</label>
                                            </div>
                                                                                        
                                            <div class="d-flex">
                                                <label class="w-50" for="crop">Crop</label>
                                                <label class="w-50"
                                                    for="crop_value">{{ $sale->crop['identifier']}}</label>
                                            </div>
                                         
                                            <!-- seperator -->
                                             <hr class="my-4">

                                            @foreach (json_decode($sale->details) as $key => $value)
                                            <div class="d-flex">
                                                <label class="w-50"
                                                    for="{{ $key }}">{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                                                <label class="w-50" for="{{ $key }}_value">{{ $value }}</label>
                                            </div>
                                            @endforeach
                                            <div class="d-flex my-3">
                                                <label class="w-50" for="amount">Amount</label>
                                                <label class="w-50  fw-bold" for="amount_value">{{ $sale->amount }}</label>
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
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>

<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>

</html>