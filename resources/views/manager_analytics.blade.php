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
                   
                    <div class="row ">

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

                    <hr class="my-4">

                    <h3 class='text-center' >Farm Analytics</h3>

                    <div class="container">
                        <form  method="POST">
                            @csrf
                            <div class="row">
                                <input type="hidden" name="farm_id" value="{{ $farm_id }}">
                                <p class="mx-2 light">Apply any filter to view expenses</p>

                                <div class="col-md-3">
                                    <div class="d-flex ">
                                        <label class='mx-2' for="from_date">From</label>
                                        <input type="date" id="from_date" name="from_date" class="form-control" value="" style="margin:0px;">
                                    </div>
                                </div>

                                <div class="col-md-3">
                                    <div class="d-flex ">
                                        <label class='mx-2' for="to_date">To</label>
                                        <input type="date" id="to_date" name="to_date" class="form-control" value="" style="margin:0px;">
                                    </div>
                                </div>

                                <!-- search button -->
                                <div class="col-md-3 mb-5">
                                    <button class="btn" type="submit">
                                        <svg xmlns="http://www.w3.org/2000/svg"  class='svg' xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 513.749 513.749" style="enable-background:new 0 0 513.749 513.749;" xml:space="preserve" width="512" height="512">
                                        <g>
                                            <path d="M504.352,459.061l-99.435-99.477c74.402-99.427,54.115-240.344-45.312-314.746S119.261-9.277,44.859,90.15   S-9.256,330.494,90.171,404.896c79.868,59.766,189.565,59.766,269.434,0l99.477,99.477c12.501,12.501,32.769,12.501,45.269,0   c12.501-12.501,12.501-32.769,0-45.269L504.352,459.061z M225.717,385.696c-88.366,0-160-71.634-160-160s71.634-160,160-160   s160,71.634,160,160C385.623,314.022,314.044,385.602,225.717,385.696z"/>
                                        </g></svg>
                                    </button>
                                </div>
                            </div>
                        </form>

                        <div class="row">
                            <div class="d-flex light">
                                <h5>Expenses from {{Carbon\Carbon::parse($from_date)->format('d M, Y')}} to {{Carbon\Carbon::parse($to_date)->format('d M, Y')}}</h5>
                            </div>
                        </div>

                    </div>
                   
                    <div class="row mt-5">
                        <div class="col-md-6" >
                            @if ($chart2 == [])
                                <div class="box-cont d-flex justify-content-center align-items-center" style='height: 50vh;'>
                                    <h6 class="light">No data available for Farm Expenses<br> in given time span</h6>
                                </div>
                            @else    
                                <div class="box-cont" style='height: 70vh;'>
                                    {!! $chart2->container() !!}
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6" >
                            <div class="box-cont " style='height: 70vh;'>
                                {!! $chart->container() !!}
                            </div>
                        </div>
                    </div>

                    <hr class="my-4">

                    @php
                        $expenseTypes = [];
                        foreach($charts as $expenseType => $chartx){
                            $expenseTypes[] = $expenseType;
                        }                        
                    @endphp


                    <div class="col-md-6">
                        <div class="labelcontainer">
                            <label class="label w-50">Expense Type</label>
                            <!-- drop down -->
                            <select class="form-select" id="expenseType" name="expenseType">
                                <option value="">Select Expense Type</option>
                                @foreach ($expenseTypes as $expenseType)
                                <option value="{{ $expenseType }}">{{ $expenseType }}</option>
                                @endforeach
                                <option value="all">All</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row">
                        @foreach ($charts as $expenseType => $chartx)
                        <div class="col-md-6 p-3"  id="{{ $expenseType }}" hidden>
                            <div class="box-cont">
                                <h5>{{ $expenseType }} Expenses</h5>
                                {!! $chartx->container() !!}
                            </div>
                        </div>
                        @endforeach
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
@if ($chart2 != [])
    {{ $chart2->script() }}
@endif
@foreach ($charts as $chartx)
    {!! $chartx->script() !!}
@endforeach

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

document.addEventListener('DOMContentLoaded', function() {
        const fromDateInput = document.getElementById('from_date');
        const toDateInput = document.getElementById('to_date');

        const currentDate = new Date();
        const currentYear = currentDate.getFullYear();
        const midYearDate = new Date(currentYear, 6, 1); // July 1st

        let fromDate, toDate;

        if (currentDate < midYearDate) {
            fromDate = new Date(currentYear, 0, 1); // January 1st
        } else {
            fromDate = midYearDate;
        }

        toDate = currentDate;

        fromDateInput.value = fromDate.toISOString().split('T')[0];
        toDateInput.value = toDate.toISOString().split('T')[0];
    });

    document.getElementById('expenseType').addEventListener('change', function() {
        const expenseType = this.value;
        const expenseTypes = @json($expenseTypes);

        if (expenseType === 'all') {
            expenseTypes.forEach(type => {
                const element = document.getElementById(type);
                element.hidden = false;
            });
            return;
        }
        expenseTypes.forEach(type => {
            const element = document.getElementById(type);
            
            if (type === expenseType) {
                element.hidden = false;
            } else {
                element.hidden = true;
            }
        });
    });
   
</script>

</html>