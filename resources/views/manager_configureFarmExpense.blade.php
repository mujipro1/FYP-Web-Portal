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
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>
                    <div class="col-md-10 offset-md-1 ">
                    <form id="expenseForm" action="{{ route('manager.saveExpenses', ['farm_id' => $farm_id, 'id' => $id]) }}" method="POST">
                        @csrf
                        <div class="d-flex justify-content-between align-items-center my-3">
                            <a href="{{ route('manager.configureExpenses', ['farm_id' => $farm_id]) }}" class="back-button">
                                <svg xmlns="http://www.w3.org/2000/svg"  class='svg' viewBox="0 0 24 24" width="512" height="512">
                                    <path
                                        d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                                </svg>
                            </a>
                            <h3 class="flex-grow-1 text-center mb-0">Configure 
                            @if ($id=='FARM')
                            Farm
                            @else
                            Crop
                            @endif     
                            Expenses</h3>
                            <div style='visibility:hidden;' class="invisible"></div>
                        </div>

                        <div class="row px-4">
                            <div class="col-md-6 my-4">
                                <p class='fsmall light'>Add New Expenses</p>
                                <div class="d-flex">
                                    <label for="expense" class="w-25 mx-2 form-label">Expense</label>
                                    <input type="text" class="form-control" id="expense" name="expense">
                                    <button type="button" class="mx-3 btn btn-primary" id="addExpense">+</button>
                                </div>
                            </div>
                        </div>

                        <div class="row my-3 px-4">
                            <div class="col-md-8 mt-2">
                                <div class="box-cont">
                                    <h5 class="text-center">Added Expenses</h5>
                                    <div id="addedExpenses" class="row mt-4 px-4">
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-4 mt-2">
                                <div class="box-cont">
                                    <h5 class="text-center">Removed Expenses</h5>
                                    <div id="removedExpenses" class="mt-4 px-4">
                                    </div>
                                </div>
                            </div>

                            <div class="text-center my-3 mt-5">
                                <button type="submit" class="btn btn-brown">Save</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div id="footer">
            @include('components.footer')
        </div>
    </div>

    <script src="{{ asset('js/alert.js') }}"></script>
    <script src="{{ asset('js/ManagerSidebar.js') }}"></script>
    <script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('js/data/cropExpenseData.js') }}"></script>
    <script src="{{ asset('js/data/farmExpenseData.js') }}"></script>
    <script>
document.addEventListener('DOMContentLoaded', () => {
    const addedExpenses = document.getElementById('addedExpenses');
    const removedExpenses = document.getElementById('removedExpenses');
    const addExpenseButton = document.getElementById('addExpense');
    const expenseInput = document.getElementById('expense');
    const form = document.getElementById('expenseForm');
    justAdded = [];
    // PHP variables
    let addedExpensesFromBackend = @json($added_expenses);
    let removedExpensesFromBackend = @json($removed_expenses);

    // Template expenses
    @if ($id == 'FARM')
    let expenses = farmExpenseData;
    @else
    let expenses = cropExpenseData;
    @endif



    let predefinedExpenses = new Set(expenses.map(e => e.head));
    let addedExpenseList = new Set(addedExpensesFromBackend);
    let removedExpenseList = new Set(removedExpensesFromBackend);


    // in expenses array, remove the removedExpense items
    removedExpenseList.forEach(expense => {
        expenses = expenses.filter(e => e.head !== expense);
    });

    function renderExpenses() {
        addedExpenses.innerHTML = '';
        removedExpenses.innerHTML = '';

        // Render added expenses (user-defined and templates not in removed list)
        expenses.forEach(expense => {
            console.log(expense.head);
            if (!removedExpenseList.has(expense.head)) {
                const expenseDiv = document.createElement('div');
                expenseDiv.classList.add('col-md-6');
                expenseDiv.innerHTML = `
                    <div class="d-flex justify-content-between grey-div wordbreak my-1 align-items-center">
                        <div class='wordbreak'>${expense.head}</div>
                        <button type="button" class="btn remove-expense-btn">
                            <svg xmlns="http://www.w3.org/2000/svg" style='fill:grey;'  class='svg' xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="512" height="512">
                                <g>
                                    <path d="M448,85.333h-66.133C371.66,35.703,328.002,0.064,277.333,0h-42.667c-50.669,0.064-94.327,35.703-104.533,85.333H64c-11.782,0-21.333,9.551-21.333,21.333S52.218,128,64,128h21.333v277.333C85.404,464.214,133.119,511.93,192,512h128c58.881-0.07,106.596-47.786,106.667-106.667V128H448c11.782,0,21.333-9.551,21.333-21.333S459.782,85.333,448,85.333zM234.667,362.667c0,11.782-9.551,21.333-21.333,21.333C201.551,384,192,374.449,192,362.667v-128c0-11.782,9.551-21.333,21.333-21.333c11.782,0,21.333,9.551,21.333,21.333V362.667zM320,362.667c0,11.782-9.551,21.333-21.333,21.333c-11.782,0-21.333-9.551-21.333-21.333v-128c0-11.782,9.551-21.333,21.333-21.333c11.782,0,21.333,9.551,21.333,21.333V362.667zM174.315,85.333c9.074-25.551,33.238-42.634,60.352-42.667h42.667c27.114,0.033,51.278,17.116,60.352,42.667H174.315z"/>
                                </g>
                            </svg>
                        </button>
                    </div>
                `;
                addedExpenses.appendChild(expenseDiv);
            }
        });
        console.log(" ")

        // Render user-defined added expenses
        addedExpenseList.forEach(expense => {
            const expenseDiv = document.createElement('div');
            expenseDiv.classList.add('col-md-6');
            expenseDiv.innerHTML = `
                <div class="d-flex justify-content-between grey-div wordbreak my-1 align-items-center">
                    <div class='wordbreak'>${expense}</div>
                    <button type="button" class="btn remove-expense-btn">
                        <svg xmlns="http://www.w3.org/2000/svg" style='fill:grey;'  class='svg' xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Capa_1" x="0px" y="0px" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve" width="512" height="512">
                            <g>
                                <path d="M448,85.333h-66.133C371.66,35.703,328.002,0.064,277.333,0h-42.667c-50.669,0.064-94.327,35.703-104.533,85.333H64c-11.782,0-21.333,9.551-21.333,21.333S52.218,128,64,128h21.333v277.333C85.404,464.214,133.119,511.93,192,512h128c58.881-0.07,106.596-47.786,106.667-106.667V128H448c11.782,0,21.333-9.551,21.333-21.333S459.782,85.333,448,85.333zM234.667,362.667c0,11.782-9.551,21.333-21.333,21.333C201.551,384,192,374.449,192,362.667v-128c0-11.782,9.551-21.333,21.333-21.333c11.782,0,21.333,9.551,21.333,21.333V362.667zM320,362.667c0,11.782-9.551,21.333-21.333,21.333c-11.782,0-21.333-9.551-21.333-21.333v-128c0-11.782,9.551-21.333,21.333-21.333c11.782,0,21.333,9.551,21.333,21.333V362.667zM174.315,85.333c9.074-25.551,33.238-42.634,60.352-42.667h42.667c27.114,0.033,51.278,17.116,60.352,42.667H174.315z"/>
                            </g>
                        </svg>
                    </button>
                </div>
            `;
            addedExpenses.appendChild(expenseDiv);
        });

        // Render removed expenses
        removedExpenseList.forEach(expense => {
            const expenseDiv = document.createElement('div');
            expenseDiv.innerHTML = `
                <div class="d-flex justify-content-between grey-div wordbreak my-1 align-items-center">
                    <div>${expense}</div>
                    <button type="button" class="btn add-expense-btn">+</button>
                </div>
            `;
            removedExpenses.appendChild(expenseDiv);
        });
    }

    addExpenseButton.addEventListener('click', () => {
        const expenseName = expenseInput.value.trim();
        if (expenseName && !predefinedExpenses.has(expenseName) && !addedExpenseList.has(expenseName)) {
            addedExpenseList.add(expenseName);
            // expenses.push({ head: expenseName });
            justAdded.push(expenseName);
            expenseInput.value = '';
            renderExpenses();
        }
    });

    addedExpenses.addEventListener('click', (e) => {
        if (e.target.closest('.remove-expense-btn')) {
            const expenseDiv = e.target.closest('.d-flex');
            const expenseHead = expenseDiv.querySelector('div').textContent;
            // check if its just added
            if (justAdded.includes(expenseHead)) {
                addedExpenseList.delete(expenseHead);
                justAdded = justAdded.filter(e => e !== expenseHead);
            } else {
                removedExpenseList.add(expenseHead);
            }
            addedExpenseList.delete(expenseHead);
            expenses = expenses.filter(e => e.head !== expenseHead);
            renderExpenses();
        }
    });

    removedExpenses.addEventListener('click', (e) => {
        if (e.target.closest('.add-expense-btn')) {
            const expenseDiv = e.target.closest('.d-flex');
            const expenseHead = expenseDiv.querySelector('div').textContent;
            if (removedExpenseList.has(expenseHead)) {
                removedExpenseList.delete(expenseHead);
                if (!predefinedExpenses.has(expenseHead)){
                    addedExpenseList.add(expenseHead);
                }
                else{
                    expenses.push({ head: expenseHead });
                }
                renderExpenses();
            }
        }
    });

    form.addEventListener('submit', (e) => {
        e.preventDefault();
        const addedExpensesArray = Array.from(addedExpenseList);
        const removedExpensesArray = Array.from(removedExpenseList);

        const addedExpensesInput = document.createElement('input');
        addedExpensesInput.type = 'hidden';
        addedExpensesInput.name = 'added_expenses';
        addedExpensesInput.value = JSON.stringify(addedExpensesArray);

        const removedExpensesInput = document.createElement('input');
        removedExpensesInput.type = 'hidden';
        removedExpensesInput.name = 'removed_expenses';
        removedExpensesInput.value = JSON.stringify(removedExpensesArray);

        form.appendChild(addedExpensesInput);
        form.appendChild(removedExpensesInput);

        form.submit();
    });

    renderExpenses();
});


document.addEventListener('DOMContentLoaded', () => {
    const addExpenseButton = document.getElementById('addExpense');
    const expenseInput = document.getElementById('expense');
    const form = document.getElementById('expenseForm');

    // Add Enter key listener to the expense input field to trigger the Add Expense button
    expenseInput.addEventListener('keyup', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault(); // Prevent the default action (form submission)
            addExpenseButton.click();
        }
    });

    // Ensure the Enter key does not submit the form when pressed elsewhere in the form
    form.addEventListener('keydown', (e) => {
        if (e.key === 'Enter') {
            e.preventDefault(); // Prevent form submission on Enter key press
        }
    });
});


    </script>
</body>

</html>
