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
                            <h3 class="flex-grow-1 text-center mb-0">Expenses</h3>
                            <div style='visibility:hidden;' class="invisible"></div>
                            @else
                            <a href="{{ route('expense_farmer') }}" class="back-button">
                                <svg xmlns="http://www.w3.org/2000/svg" class='svg' viewBox="0 0 24 24" width="512"
                                    height="512">
                                    <path
                                        d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                                </svg>
                            </a>
                            <h3 class="flex-grow-1 text-center mb-0">Expenses</h3>
                            <div style='visibility:hidden;' class="invisible"></div>

                            @endif
                        </div>

                        <div class="row">
                            <div class="p-3">
                                <div class="button-cont px-4">
                                    <button class="tab-button" onclick="handlecropExpenseClick()">Crop Expense</button>
                                    <button class="deselect" onclick="handlefarmExpenseClick()">Farm Expense</button>
                                    <button class="deselect" onclick="handleReconClick()">Reconciliation</button>

                                </div>


                                <div class="box-cont p-5">

                                    <form action="{{route('manager.manager_applyExpenseSearch')}}" method='POST'>
                                        @csrf
                                        <div class="row">
                                            <input type="hidden" name="farm_id" value="{{$farm_id}}">
                                            <p class='mx-2 light'>Apply any filter to view expenses</p>

                                            <div class="row mb-3">
                                                <div class="col-md-3">
                                                    <select class="form-select" id="active_passive"
                                                        name="active_passive" aria-label="Default select example">

                                                        @if(isset($active_passive))
                                                        <option value="0"
                                                            {{ $active_passive == '0' ? 'selected' : '' }}>Active Crops
                                                        </option>
                                                        <option value="1"
                                                            {{ $active_passive == '1' ? 'selected' : '' }}>Passive Crops
                                                        </option>
                                                        <option value="2"
                                                            {{ $active_passive == '2' ? 'selected' : '' }}>All</option>
                                                        @else
                                                        <option value="0">Active Crops</option>
                                                        <option value="1">Passive Crops</option>
                                                        <option value="2">All</option>
                                                        @endif
                                                    </select>
                                                </div>
                                                <!-- search nutton -->
                                                <div class="col-md-3 ">
                                                    <button class="btn" type='submit'>
                                                        <svg xmlns="http://www.w3.org/2000/svg" class='svg'
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

                                            <div class="row">

                                                <div class="col-md-3">
                                                    <select class="form-select" id="crop_id" name="crop_id"
                                                        aria-label="Default select example">
                                                        <option value='' selected>Select Crop</option>
                                                        @foreach ($crops as $crop)
                                                        <option value="{{ $crop->id }}">
                                                            {{ $crop->identifier }}{{ $crop->variety ? ' (' . $crop->variety . ')' : '' }}
                                                        </option>
                                                        @endforeach
                                                    </select>
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


                                                <div class="col-md-2 offset-md-1">
                                                    <button class="btn btn-orange2 or-width" style="display:none"
                                                        id="delete-expenses">Delete</button>
                                                </div>

                                                <div class="col-md-4">
                                                    <h5 id="sum-of-expenses"style="display:none"></h5>
                                                </div>


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
                                                        <th scope="col">Select</th>
                                                        <th scope="col">Expense Date</th>
                                                        <th scope="col">Crop</th>
                                                        <th scope="col">Expense</th>
                                                        <th scope="col">Subtype</th>
                                                        <th scope="col">Amount</th>
                                                        <th scope="col">Description</th>
                                                        <th scope="col">System Entry Date</th>

                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    @foreach ($expenses as $expense)
                                                    <tr data-expense="{{$expense->id}}">
                                                        <td><input type="checkbox" name="expense_id[]"
                                                                value="{{$expense->id}}"></td>
                                                        <td onclick="handleExpenseRowClick({{$expense->id}})"
                                                            data-expense="{{$expense->id}}" style='cursor:pointer;'>
                                                            {{\Carbon\Carbon::parse($expense->date)->format('d M Y')}}
                                                        </td>
                                                        <td onclick="handleExpenseRowClick({{$expense->id}})"
                                                            data-expense="{{$expense->id}}" style='cursor:pointer;'>
                                                            {{$expense->crop->identifier}}</td>
                                                        <td onclick="handleExpenseRowClick({{$expense->id}})"
                                                            data-expense="{{$expense->id}}" style='cursor:pointer;'>
                                                            {{$expense->expense_type}}</td>
                                                        @if ($expense->expense_subtype)
                                                        <td onclick="handleExpenseRowClick({{$expense->id}})"
                                                            data-expense="{{$expense->id}}" style='cursor:pointer;'>
                                                            {{$expense->expense_subtype}}</td>
                                                        @else
                                                        <td onclick="handleExpenseRowClick({{$expense->id}})"
                                                            data-expense="{{$expense->id}}" style='cursor:pointer;'>
                                                            <label class='text-danger'><i>Not Found</i></label></td>
                                                        @endif
                                                        <td onclick="handleExpenseRowClick({{$expense->id}})"
                                                            data-expense="{{$expense->id}}" style='cursor:pointer;'>
                                                            {{$expense->total}}</td>
                                                        @php
                                                        $details = json_decode($expense->details, true);
                                                        @endphp
                                                        @if (is_array($details) && array_key_exists('description',
                                                        $details))
                                                        <td onclick="handleExpenseRowClick({{$expense->id}})"
                                                            data-expense="{{$expense->id}}" style='cursor:pointer;'>
                                                            {{ $details['description']}}</td>
                                                        @else
                                                        <td onclick="handleExpenseRowClick({{$expense->id}})"
                                                            data-expense="{{$expense->id}}" style='cursor:pointer;'>
                                                            <label class='text-danger'><i>Not Found</i></label></td>
                                                        @endif
                                                        <td onclick="handleExpenseRowClick({{$expense->id}})"
                                                            data-expense="{{$expense->id}}" style='cursor:pointer;'>
                                                            {{ \Carbon\Carbon::parse($expense->created_at)->format('d M Y') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($expense->created_at)->format('h:i A') }}
                                                        </td>
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

<script src="{{ asset('js/data/cropExpenseData.js') }}"></script>
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
    window.location.href = `/manager/view_cropexpense_details/{{$farm_id}}/${expense_id}`;
}

function handleReconClick() {
    window.location.href = "{{ route('manager.reconciliation' , ['farm_id' => $farm_id] )}}"
}

expenseDropdown = document.getElementById('expense_type');
cropExpenseData.forEach(expense => {
    let option = document.createElement('option');
    option.value = expense['head']
    option.innerHTML = expense['head'];
    expenseDropdown.appendChild(option);
});

document.addEventListener('DOMContentLoaded', function() {
    // Function to apply filters
    function applyFilters() {
        const cropId = document.getElementById('crop_id').value;
        const expenseType = document.getElementById('expense_type').value;
        const date = document.getElementById('date').value;

        const rows = document.querySelectorAll('tr[data-expense]');

        rows.forEach(row => {
            const expenseId = row.getAttribute('data-expense');
            const expense = @json($expenses).find(item => item.id ==
            expenseId); // Assuming $expenses is available in the view

            // Get data from the row
            const isCropMatch = cropId ? expense.crop_id == cropId : true;
            const isExpenseTypeMatch = expenseType ? expense.expense_type == expenseType : true;
            const isDateMatch = date ? expense.date == date : true;


            // Check if all selected filters match
            if (isCropMatch && isExpenseTypeMatch && isDateMatch) {
                row.style.display = ''; // Show row
            } else {
                row.style.display = 'none'; // Hide row
            }
        });
    }

    // Attach event listeners to the filter inputs
    document.getElementById('crop_id').addEventListener('change', applyFilters);
    document.getElementById('expense_type').addEventListener('change', applyFilters);
    document.getElementById('date').addEventListener('change', applyFilters);

    // Initial application of filters (in case some filters are already selected)
    applyFilters();
});

document.addEventListener('DOMContentLoaded', function () {
    const deleteButton = document.getElementById('delete-expenses');
    const sumOfExpenses = document.getElementById('sum-of-expenses');
    const checkboxes = document.querySelectorAll('input[name="expense_id[]"]');
    let selectedCount = 0;

    // Initially hide the delete button and sum heading
    deleteButton.style.display = 'none';
    sumOfExpenses.style.display = 'none';

    // Function to update the delete button text, visibility, and sum of selected rows
    function updateDeleteButtonAndSum() {
        selectedCount = 0;
        let totalSum = 0;

        checkboxes.forEach(checkbox => {
            if (checkbox.checked) {
                selectedCount++;
                const row = checkbox.closest('tr');
                const amountCell = row.querySelector('td:nth-child(6)'); // Assuming the 6th column contains the amount
                const amount = parseFloat(amountCell.textContent) || 0;
                totalSum += amount;
            }
        });

        if (selectedCount > 0) {
            deleteButton.style.display = 'block';
            sumOfExpenses.style.display = 'block';
            deleteButton.textContent = `Delete ${selectedCount} row(s)`;
            sumOfExpenses.textContent = `Sum of Selected Rows: ${totalSum.toFixed(2)}`;
        } else {
            deleteButton.style.display = 'none';
            sumOfExpenses.style.display = 'none';
        }
    }

    // Add event listeners to checkboxes
    checkboxes.forEach(checkbox => {
        checkbox.addEventListener('change', updateDeleteButtonAndSum);
    });

    // Handle delete button click
    deleteButton.addEventListener('click', function (e) {
        e.preventDefault();

        if (selectedCount > 0) {
            // Show confirmation modal
            if (confirm(`Are you sure you want to delete ${selectedCount} row(s)?`)) {
                // Collect selected expense IDs
                const selectedIds = Array.from(checkboxes)
                    .filter(checkbox => checkbox.checked)
                    .map(checkbox => checkbox.value);

                // Send a POST request to delete the selected rows
                fetch('{{ route("manager.deleteCropExpenses") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ expense_ids: selectedIds })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert(data.message);
                        location.reload(); // Reload the page to reflect changes
                    } else {
                        alert('Failed to delete expenses. Please try again.');
                    }
                })
                .catch(error => console.error('Error:', error));
            }
        }
    });
});
</script>


</html>