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
                            <a href="{{ route('manager.farmdetails', ['farm_id' => $farm_id]) }}" class="back-button">
                                <svg xmlns="http://www.w3.org/2000/svg" class='svg' viewBox="0 0 24 24" width="512"
                                    height="512">
                                    <path
                                        d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                                </svg>
                            </a>
                            @else
                            <a href="{{ route('expense_farmer') }}" class="back-button">
                                <svg xmlns="http://www.w3.org/2000/svg" class='svg' viewBox="0 0 24 24" width="512"
                                    height="512">
                                    <path
                                        d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                                </svg>
                            </a>
                            @endif
                            <h3 class="flex-grow-1 text-center mb-0">Expenses</h3>
                            <div style='visibility:hidden;' class="invisible"></div>
                        </div>


                        <div class="row">
                            <div class="p-3">
                                <div class="button-cont px-4">
                                    <button class="deselect" onclick="handlecropExpenseClick()">Crop Expense</button>
                                    <button class="deselect" onclick="handlefarmExpenseClick()">Farm Expense</button>
                                    <button class="tab-button" onclick="handleReconClick()">Reconciliation</button>

                                </div>

                                <div class="box-cont">

                                    <div class="row m-3"  style='height:60vh;overflow-y:scroll'>
                                        <h5 class='light'>Reconcilation and Expenditure History</h5>


                                        <table class=''>
                                            <thead>
                                                <tr>
                                                    <th>Date</th>
                                                    <th>User</th>
                                                    <th>Purpose</th>
                                                    <th>Amount</th>
                                                    <th>Description</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach($reconciles as $reconcile)
                                                @if($reconcile->spent == 0)
                                                <tr class='greenRow'>
                                                @else
                                                <tr>
                                                @endif
                                                    <td>{{\Carbon\Carbon::parse($reconcile->date)->format('d M Y')}}</td>
                                                    <td>{{$reconcile->user->name}}</td>
                                                    @if($reconcile->spent == 1)
                                                        @if($reconcile->farmExpense)
                                                            <td>Farm Expense</td>
                                                        @endif
                                                        @if($reconcile->expense)
                                                            <td>Crop Expense</td>
                                                        @endif
                                                    @else
                                                    <td>Reconciled</td>
                                                    @endif
                                                    <td>{{$reconcile->amount}}</td>
                                                    
                                                    @if($reconcile->farmExpense)
                                                        <td>{{$reconcile->farmExpense->expense_type}}</td>
                                                    @endif
                                                    
                                                    @if($reconcile->expense)
                                                        <td>{{$reconcile->expense->expense_type}} of {{$reconcile->expense->crop->identifier}}</td>
                                                    @endif

                                                    @if($reconcile->spent == 0)
                                                    <td>Reconciled by Manager to {{$reconcile->user->name}}</td>
                                                    @endif


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


        <div id="footer">
            @include('components.footer')
        </div>

    </div>

</body>

<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>

<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
function handlefarmExpenseClick() {
    window.location.href = "{{ route('manager.render_farmexpense' , ['farm_id' => $farm_id]) }}"
}

function handlecropExpenseClick() {
    window.location.href = "{{ route('manager.render_cropexpense' , ['farm_id' => $farm_id]) }}"
}


function handleReconClick() {
        window.location.href = "{{ route('manager.reconciliation' , ['farm_id' => $farm_id] )}}"
    }
</script>

</html>