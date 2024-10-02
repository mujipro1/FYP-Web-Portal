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
                                    @if (!isset($expense->farm))
                                    <button class="tab-button" onclick="handlecropExpenseClick()">Crop Expense</button>
                                    <button class="deselect" onclick="handlefarmExpenseClick()">Farm Expense</button>
                                    @else
                                    <button class="deselect" onclick="handlecropExpenseClick()">Crop Expense</button>
                                    <button class="tab-button
                                        " onclick="handlefarmExpenseClick()">Farm Expense</button>
                                    @endif
                                    <button class="deselect" onclick="handleReconClick()">Reconciliation</button>
                                </div>


                                <div class="box-cont p-5">

                                    <div class="d-flex justify-content-between align-items-center my-3">
                                        @if (!isset($expense->farm))
                                        <a href="{{ route('manager.view_cropexpense', ['farm_id' => $farm_id]) }}"
                                            class="back-button">
                                            @else
                                            <a href="{{ route('manager.view_farmexpense', ['farm_id' => $farm_id]) }}"
                                                class="back-button">
                                                @endif
                                                <svg xmlns="http://www.w3.org/2000/svg" class='svg' viewBox="0 0 24 24"
                                                    width="512" height="512">
                                                    <path
                                                        d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                                                </svg>
                                            </a>
                                            <label class='or-width'></label>
                                            <h3 class="flex-grow-1 text-center mb-0">Expense Details</h3>
                                            <div style='visibility:hidden;' class="invisible"></div>
                                            <button class='btn-orange or-width' id="expense-edit-button">Edit</button>
                                    </div>

                                    <div class="row p-5">
                                        <form action="{{route('manager.saveEditExpenses')}}" method='post'>
                                            @csrf
                                            <div class="col-md-8">

                                                <input hidden name='expense_id' value="{{$expense->id}}"/>
                                                <input hidden name='farm_id' value="{{$farm_id}}"/>
                                                <div class="d-flex">
                                                    <label class="w-50" for="date">Date</label>
                                                    <input id='date' name='date' class='form-control mb-2 w-50' disabled
                                                        type='date' value="{{$expense->date}}"></input>
                                                </div>

                                                @if (!isset($expense->farm))

                                                <div class="d-flex">
                                                    <label class="w-50" for="crop">Crop</label>
                                                    <label class="w-50"
                                                        for="crop_value">{{ $expense->crop['identifier']}}</label>
                                                </div>
                                                @endif
                                                <div class="d-flex">
                                                    <label class="w-50" for="expense_type">Expense Type</label>
                                                    <label class="w-50"
                                                        for="expense_type_value">{{ $expense->expense_type }}</label>
                                                </div>
                                                <div class="d-flex ">
                                                    <label class="w-50" for="expense_subtype">Expense Subtype</label>
                                                    <label class="w-50"
                                                        for="expense_subtype_value">{{ $expense->expense_subtype }}</label>
                                                </div>

                                                <!-- seperator -->
                                                <hr class="my-4">

                                                @foreach (json_decode($expense->details) as $key => $value)
                                                @if ($key != 'amount' && $key != 'description')
                                                <div class="d-flex">
                                                    <label class="w-50"
                                                        for="{{ $key }}">{{ ucwords(str_replace('_', ' ', $key)) }}</label>
                                                    <label class="w-50" for="{{ $key }}_value">{{ $value }}</label>
                                                </div>
                                                @endif
                                                @endforeach
                                                <div class="d-flex my-3">
                                                    <label class="w-50" for="amount">Amount</label>
                                                    <label class="w-50  fw-bold"
                                                        for="amount_value">{{ $expense->total }}</label>
                                                </div>

                                                <div class="d-flex my-3">
                                                    <label for='description' class="w-50">Description</label>
                                                    @php 
                                                        $description = json_decode($expense->details);
                                                        $desc = $description->description;
                                                    @endphp
                                                    <textarea id="description" class="w-50 form-control"
                                                        name="description"
                                                        disabled>{{$desc}}</textarea>
                                                </div>
                                            </div>

                                            <div class="text-center mt-5">
                                                <button class="btn btn-orange or-width" disabled
                                                    type='submit'>Save</button>
                                            </div>
                                        </form>
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
    window.location.href = "{{ route('manager.reconciliation' , ['farm_id' => $farm_id]) }}"
}

function handleViewExpenseClick() {
    window.location.href = "{{ route('manager.view_cropexpense' , ['farm_id' => $farm_id]) }}"
}

document.getElementById('expense-edit-button').addEventListener('click', () => {
    date = document.getElementById('date');
    description = document.getElementById('description');

    document.querySelector('button[type="submit"]').disabled = !document.querySelector('button[type="submit"]')
        .disabled;

    date.disabled = !date.disabled;
    description.disabled = !description.disabled;
})

</script>

</html>