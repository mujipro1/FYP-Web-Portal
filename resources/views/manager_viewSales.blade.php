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
        @if(Session::get('success'))
        <div class="alert alert-success">
            {{Session::get('success')}}
        </div>
        {{Session::forget('success')}}
        @endif

        @if(Session::get('error'))
        <div class="alert alert-danger">
            {{Session::get('error')}}
        </div>
        {{Session::forget('error')}}
        @endif


        <div class="container-fluid">
        <div class="row">
            @if($worker == 0)
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>
            @endif
                    <div class="col-md-10 offset-md-1 ">


                    <div class="container">

                        <div class="d-flex justify-content-between align-items-center my-3">
                          
                            <a href="{{ route('manager.render_sales_page', ['farm_id'=>$farm_id]) }}"
                            class="back-button">
                                    <svg xmlns="http://www.w3.org/2000/svg"  class='svg'  viewBox="0 0 24 24" width="512" height="512"><path d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z"/></svg>
                                </a>
                                <h3 class="flex-grow-1 text-center mb-0">Sales</h3>
                                <div style='visibility:hidden;' class="invisible"></div>
                                
                        </div>

                        <div class="row">
                            <div class="p-3">

                                <div class="box-cont p-5">

                                    <form method='POST' action="{{route('manager.apply_salesSearch')}}">
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" name="farm_id" value="{{$farm_id}}">
                                            <p class='mx-2 light'>Apply any filter to view expenses</p>
                                            <div class="col-md-3">
                                                <select class="form-select" id="crop_id" name="crop_id"
                                                    aria-label="Default select example">
                                                    <option value='' selected>Select Crop</option>
                                                    @foreach ($crops as $crop)
                                                    <option value="{{$crop->id}}">{{$crop->identifier}}</option>
                                                    @endforeach
                                                </select>
                                            </div>


                            
                                            <div class="col-md-3">
                                                <input type="date" id="date" name="date" class="form-control" value=""
                                                    style='margin:0px;'>
                                            </div>

                                            <!-- search nutton -->
                                            <div class="col-md-3 mb-5">
                                                <button class="btn" type='submit'>
                                                    <svg xmlns="http://www.w3.org/2000/svg"  class='svg'
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

                                    <!-- seperatot -->
                                    <hr class="my-4">


                                    <div class="row">
                                        <!-- table -->
                                        <div class="table-responsive">
                                            <table id='cropexpensetable' class="table table-scroll table-striped">
                                                <thead>
                                                    <tr>
                                                        <th scope="col">Date</th>
                                                        <th scope="col">Crop</th>
                                                        <th scope="col">Weight(Kgs)</th>
                                                        <th scope="col">Amount</th>
                                                        <th scope="col">Description</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($sales as $sale)
                                                    @php
                                                    $sale->details = json_decode($sale->details, true);
                                                    @endphp
                                                    <tr onclick="handleSaleRowClick({{$sale->id}})"
                                                        style='cursor:pointer;'>
                                                        <td>{{$sale->date}}</td>
                                                        <td>{{$sale->crop->identifier}}</td>
                                                        <td>{{$sale->details['Weight_(KGs)']}}</td>
                                                        <td>{{$sale->amount}}</td>
                                                        <td>{{$sale->details['Remarks']}}</td>
                                                    </tr>
                                                    @endforeach
                                                </tbody>
                                            </table>
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
<script src="{{ asset('js/data/cropExpenseData.js') }}"></script>
<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>

<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
    function handleSaleRowClick(sale_id){
        window.location.href = `/manager/viewSalesRow/{{$farm_id}}/${sale_id}`;
    }
</script>

</html>