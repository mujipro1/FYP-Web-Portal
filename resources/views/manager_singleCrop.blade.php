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


        <div class="container-fluid">
            <div class="row">
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>
                <div class="col-md-10 section offset-md-1 ">

                    <div class="d-flex  justify-content-between align-items-center my-4">
                        <a href="{{ route('manager.analytics', ['farm_id' => $farm_id]) }}" class="back-button">
                            <svg xmlns="http://www.w3.org/2000/svg" class='svg' viewBox="0 0 24 24" width="512"
                                height="512">
                                <path
                                    d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                            </svg>
                        </a>
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
                                    <option value=" ">Select a crop</option>
                                        @foreach($crops as $crop)
                                        <option value="{{ $crop->id }}">{{ $crop->identifier }}</option>
                                        @endforeach
                                    </select>
                                    <button class="btn mx-4" type='submit'>
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
                    <div class="col-md-6">
                        <div class="popular-crop p-4">
                            <h5 class="text-start">Total Expenses</h5>
                            <div>PKR {{$totalExpenses}}/-</div>
                        </div>
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

<script src="{{ asset('js/alert.js') }}"></script>
<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
@if ($id == 1)
<script src="{{ $expenseChart->cdn() }}"></script>
{{ $expenseChart->script() }}
{{ $expenseChartPerAcre->script() }}
@endif
<script>
function handleSingleCrop() {
    window.location.href = "{{ route('manager.singlecrop', ['farm_id' => $farm_id]) }}";
}

function handleCompareCrop() {
    window.location.href = "{{ route('manager.comparecrop', ['farm_id' => $farm_id]) }}";
}
</script>

</html>