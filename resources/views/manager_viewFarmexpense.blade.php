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
                        <a href="{{ route('manager.farmdetails', ['farm_id' => $farm_id]) }}"
                        class="back-button">
                                <svg xmlns="http://www.w3.org/2000/svg"  class='svg' viewBox="0 0 24 24" width="512" height="512"><path d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z"/></svg>
                            </a>
                        @else
                        <a href="{{ route('expense_farmer') }}"
                        class="back-button">
                                <svg xmlns="http://www.w3.org/2000/svg"  class='svg' viewBox="0 0 24 24" width="512" height="512"><path d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z"/></svg>
                            </a>
                        @endif
                        <h3 class="flex-grow-1 text-center mb-0">Expenses</h3>
                        <div style='visibility:hidden;' class="invisible"></div>
                        </div>


                        <div class="row">
                            <div class="p-3">
                                <div class="button-cont px-4">
                                    <button class="deselect" onclick="handlecropExpenseClick()">Crop Expense</button>
                                    <button class="tab-button" onclick="handlefarmExpenseClick()">Farm Expense</button>
                                    <button class="deselect" onclick="handleReconClick()">Reconciliation</button>

                                </div>


                                <div class="box-cont p-5">

                                    <form action="{{route('manager.manager_applyExpenseSearchfarm')}}" method='POST'>
                                        @csrf
                                        <input hidden name="worker" value="{{$worker}}">
                                    <div class="row">
                                        <input type="hidden" name="farm_id" value="{{$farm_id}}">
                                        <p class='mx-2 light'>Apply any filter to view expenses</p>

                                            <div class="row mb-3">
                                                <div class="col-md-4">
                                                    <select class="form-select" id="active_passive" name="active_passive" aria-label="Default select example">
                                                    
                                                        @if(isset($active_passive))
                                                        <option value="0" {{ $active_passive == '0' ? 'selected' : '' }}>Expenses For Active Crops</option>
                                                        <option value="1" {{ $active_passive == '1' ? 'selected' : '' }}>Expenses For Passive Crops</option>
                                                        <option value="2" {{ $active_passive == '2' ? 'selected' : '' }}>All</option>
                                                        @else
                                                        <option value="0">Expenses For Active Crops</option>
                                                        <option value="1">Expenses For Passive Crops</option>
                                                        <option value="2">All</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                 <!-- search nutton -->
                                                <div class="col-md-3 ">
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

                                        <div class="col-md-3">
                                            <select class="form-select" id="expense_type" name="expense_type"
                                                aria-label="Default select example">
                                                <option value='' selected>Select Expense</option>
                                            </select>
                                        </div>

                                        <div class="col-md-3">
                                            <input type="date" id="date" name="date" class="form-control"
                                                value="" style='margin:0px;'>
                                        </div>

                                       
                                    </div>
                                        </form>

                                    <!-- seperatot -->
                                    <hr class="my-4">


                                    <div class="row">
                                        <!-- table -->
                                        <div class="table-responsive">
                                        <table id='cropexpensetable' class="table table-scroll table-striped" >
                                            <thead>
                                                <tr>
                                                    <th scope="col">Expense Date</th>
                                                    <th scope="col">Expense</th>
                                                    <th scope="col">Subtype</th>
                                                    <th scope="col">Amount</th>
                                                    <th scope="col">Description</th>
                                                    <th scope="col">System Entry Date</th>

                                                </tr>
                                            </thead>
                                            <tbody>
                                                @foreach ($expenses as $expense)
                                                <tr onclick="handleExpenseRowClick({{$expense->id}})" data-expense="{{$expense->id}}" style='cursor:pointer;'>
                                                    <td>{{\Carbon\Carbon::parse($expense->date)->format('d M Y')}}</td>
                                                    <td>{{$expense->expense_type}}</td>
                                                    @if ($expense->expense_subtype)
                                                    <td>{{$expense->expense_subtype}}</td>
                                                    @else
                                                    <td><label class='text-danger'><i>Not Found</i></label></td>
                                                    @endif
                                                    <td>{{$expense->total}}</td>
                                                    <!-- convert json to presentable in details -->
                                                   
                                                    <!-- fetch description from details json -->
                                                     <!-- check if the field decription exists -->
                                                     @php
                                                        $details = json_decode($expense->details, true); 
                                                    @endphp
                                                    @if (is_array($details) && array_key_exists('description', $details))
                                                        <td>{{ $details['description']}}</td>
                                                    @else
                                                        <td><label class='text-danger'><i>Not Found</i></label></td>
                                                        @endif
                                                        <td>{{ \Carbon\Carbon::parse($expense->created_at)->format('d M Y') }} - 
                                                            {{ \Carbon\Carbon::parse($expense->created_at)->format('h:i A') }}</td>
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

<script src="{{ asset('js/data/farmExpenseData.js') }}"></script>
<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>

<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
function handlefarmExpenseClick() {
    window.location.href = "{{ route('manager.render_farmexpense' , ['farm_id' => $farm_id] )}}"
}

function handlecropExpenseClick() {
    window.location.href = "{{ route('manager.render_cropexpense' , ['farm_id' => $farm_id] )}}"
}

function handleViewExpenseClick() {
    window.location.href = "{{ route('manager.view_cropexpense' , ['farm_id' => $farm_id] )}}"
}

function handleExpenseRowClick(expense_id) {
    window.location.href = `/manager/view_farmexpense_details/{{$farm_id}}/${expense_id}`;
}

function handleReconClick() {
    window.location.href = "{{ route('manager.reconciliation' , ['farm_id' => $farm_id] )}}"
}

expenseDropdown = document.getElementById('expense_type');
farmExpenseData.forEach(expense => {
    let option = document.createElement('option');
    option.value = expense['head']
    option.innerHTML = expense['head'];
    expenseDropdown.appendChild(option);
});


document.addEventListener('DOMContentLoaded', function () {
    function applyFilters() {
        const expenseType = document.getElementById('expense_type').value;
        const date = document.getElementById('date').value;

        const rows = document.querySelectorAll('tr[data-expense]');

        rows.forEach(row => {
            const expenseId = row.getAttribute('data-expense');
            const expense = @json($expenses).find(item => item.id == expenseId);

            const isExpenseTypeMatch = expenseType ? expense.expense_type == expenseType : true;
            const isDateMatch = date ? expense.date == date : true;

            if (isExpenseTypeMatch && isDateMatch) {
                row.style.display = ''; 
            } else {
                row.style.display = 'none'; 
            }
        });
    }

    document.getElementById('expense_type').addEventListener('change', applyFilters);
    document.getElementById('date').addEventListener('change', applyFilters);
    applyFilters();
});


</script>

</html>