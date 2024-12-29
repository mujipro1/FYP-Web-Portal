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
                <div class="col-md-10 section offset-md-1 ">

                    <div class="d-flex  justify-content-between align-items-center my-4">
                        <form action="{{route('manager.analytics')}}" method='post' id='analytics-form'>
                        @csrf
                        <button type='button' class="btn back-button"  onclick='handleAnalytics()'>
                            <svg xmlns="http://www.w3.org/2000/svg" class='svg' viewBox="0 0 24 24" width="512"
                                height="512">
                                <path
                                    d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                            </svg>
                        </button>
                        </form>
                        @if ($id == 0)
                        <h3 class="flex-grow-1 text-center mb-0">Crop Analytics</h3>
                        @else
                        <h3 class="flex-grow-1 text-center mb-0">Crop Analytics <span class="light">|</span> {{$crop->identifier}}</h3>
                        @endif
                        <div style='visibility:hidden;' class="invisible"></div>
                    </div>



                    <form action="{{route('manager.singlecropPost')}}" method='post'>
                        @csrf
                        <div class="row m-4 mt-5">
                            <input type="hidden" name="farm_id" value="{{ $farm_id }}">
                            <div class="col-md-7">
                                <div class="labelcontainer">
                                    <label class='w-50' for="crop">Select Crop</label>
                                    <select class="form-select" id="crop" name="crop">
                                    <option value="">Select a crop</option>
                                        @foreach($crops as $cropx)
                                        <option value="{{ $cropx->id }}">{{ $cropx->identifier }}</option>
                                        @endforeach
                                    </select>
                                    <button disabled  class="btn specialSubmitButton mx-4" type='submit'>
                                        <svg xmlns="http://www.w3.org/2000/svg" class='svg'
                                            xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px"
                                            y="0px" viewBox="0 0 513.749 513.749"
                                            style="enable-background:new 0 0 513.749 513.749;" xml:space="preserve"
                                            width="512" height="512">
                                            <g>
                                                <path
                                                    d="M504.352,459.061l-99.435-99.477c74.402-99.427,54.115-240.344-45.312-314.746S119.261-9.277,44.859,90.15   S-9.256,330.494,90.171,404.896c79.868,59.766,189.565,59.766,269.434,0l99.477,99.477c12.501,12.501,32.769,12.501,45.269,0   c12.501-12.501,12.501-32.769,0-45.269L504.352,459.061z M225.717,385.696c-88.366,0-160-71.634-160-160s71.634-160,160-160   s160,71.634,160,160C385.623,314.022,314.044,385.602,225.717,385.696z" />
                                            </g>
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </form>

                    @if ($id == 0)

                    <div class="row mt-5">
                        <div class="d-flex light">
                            Select a crop to view analytics
                        </div>
                    </div>

                    @else
                    <div class="row">
                        <div class="col-md-5">
                            <div class="popular-cropz d-flex justify-content-start p-2">
                                <div>
                                    <img src="{{asset('images/crops/'. str_replace(' ', '', $crop['name']) .'.jpg')}}" class='anal-img img-fluid'/>
                                </div>
                                <div class="">
                                    <h5 class="text-start text-success">PKR {{$totalExpenses}}/-</h5>
                                    <div class='fsmall'>Total Expenses of {{$crop->identifier}}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="my-4 text-center">
                        <button class="btn btn-orange2 or-width csv-export" onclick=" window.location.href = '/export-csv/{{$crop->id}}'">Export Data
                            <svg xmlns="http://www.w3.org/2000/svg" id="svg1" data-name="Layer 1" viewBox="0 0 24 24" ><path d="M24,24H0v-2H24v2Zm-9.86-4.89l9.82-10.11h-6.95V0H7V9H.07l9.8,10.11h0c.57,.58,1.32,.89,2.12,.89h0c.8,0,1.56-.31,2.13-.89Z"/></svg>
                        </button>
                    </div>

                       
                    <div class="row mt-5">
                        <div class="col-md-6">
                            <div class="box-cont">
                                {!! $expenseChart->container() !!}
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="box-cont">
                                {!! $expenseChartPerAcre->container() !!}
                            </div>
                        </div>
                    </div>

                    
                    @if ($quantityChart != 'empty')
                    <hr class="my-4">
                        
                    <div class="col-md-12">
                        <div class="box-cont">
                            {!! $quantityChart->container() !!}
                        </div>
                    </div>

                    @endif

                    <hr class="my-4">

                    <div class="row">
                        @foreach ($charts as $expenseType => $chartx)
                        <div class="col-md-6 p-3">
                            <div class="box-cont">
                                <h5>{{ $expenseType }} Expenses</h5>
                                {!! $chartx->container() !!}
                            </div>
                        </div>
                        @endforeach
                    </div>

                    @endif

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
<script src="{{ asset('js/handleAnalytics.js') }}"></script>

@if ($id == 1)
<script src="{{ $expenseChart->cdn() }}"></script>
{{ $expenseChart->script() }}
{{ $expenseChartPerAcre->script() }}
@foreach ($charts as $chartx)
{!! $chartx->script() !!}
@endforeach
@if ($quantityChart != 'empty')
    {!! $quantityChart->script() !!}
@endif
@endif

<script>
function handleSingleCrop() {
    window.location.href = "{{ route('manager.singlecrop', ['farm_id' => $farm_id]) }}";
}

function handleCompareCrop() {
    window.location.href = "{{ route('manager.comparecrop', ['farm_id' => $farm_id]) }}";
}


cropSelect = document.getElementById('crop');
cropSelect.addEventListener('change', () => {
    if (cropSelect.value != '') {
        document.querySelector('button[type="submit"]').disabled = false;
    } else {
        document.querySelector('button[type="submit"]').disabled = true;
    }
});

</script>

</html>