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
                                    <button class="deselect" onclick="handlefarmExpenseClick()">Farm Expense</button>
                                    <button class="tab-button" onclick="handleReconClick()">Reconciliation</button>

                                </div>


                                <div class="box-cont p-5">
                                    <p class="mb-3 light">Manage your worker's wallets here</p>


                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="selected-crop">
                                                <div class="my-2 mb-4 popular-crop w-100 d-flex justify-content-start align-items-center px-2 py-2">
                                                    <img src="{{asset('images/profile.jpg')}}" id='profile-image'
                                                        class='mx-2' />
                                                    <div class='mx-3'>
                                                        <div class="">Hassan Ali</div>
                                                        <div class=" light fsmall">Expense Farmer</div>
                                                    </div>
                                                </div>

                                                <div class="d-flex px-4">
                                                    <label class='w-50 light' for="wallet">Reconcile Date</label>
                                                    <label class='w-50' for="wallet">2021-09-09</label>
                                                </div>
                                                <div class="d-flex px-4">
                                                    <label class='w-50 light' for="wallet">Reconcile Amount</label>
                                                    <label class='w-50' for="wallet">30000</label>
                                                </div>
                                                <div class="d-flex px-4">
                                                    <label class='w-50 light' for="wallet">Wallet</label>
                                                    <label class='w-50' for="wallet">20000</label>
                                                </div>
                                                <div class="d-flex px-4 mb-2">
                                                    <label class='w-50 light' for="wallet">Spent</label>
                                                    <label class='w-50 fw-bold' for="wallet">10000</label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="col-md-6"></div>
                                    </div>

                                    <hr class='my-5' />
                                    <!-- drop down to select workers -->
                                    <div class="row mt-5">
                                        <div class="col-md-7 my-2 d-flex justify-content-start">
                                            <label class='w-50' for="date">Date</label>
                                            <label class='w-50' for="date">{{ date('Y-m-d') }}</label>
                                        </div>

                                        <div class="d-flex col-md-7 my-2">
                                            <label class='w-50' for="workerSelect">Select Worker</label>
                                            <select class="form-select" id="workerSelect"
                                                aria-label="Default select example">
                                                <option selected>Select Worker</option>
                                                <option>Worker A</option>
                                                <option>Worker B</option>
                                            </select>
                                        </div>
                                        <!-- add cash -->
                                        <div class="d-flex col-md-7 my-2">
                                            <label class='w-50' for="cash">Add Cash</label>
                                            <input type="number" class="form-control" id="cash" name="cash">
                                        </div>

                                        <div class="col-md-7 mt-4 text-center">
                                            <button class="btn btn-brown" id="addCash">Add Cash</button>
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
<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>

<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
function handlefarmExpenseClick() {
    window.location.href = "{{ route('manager.render_farmexpense' , ['farm_id' => $farm_id, 'worker'=>$worker]) }}"
}

function handlecropExpenseClick() {
    window.location.href = "{{ route('manager.render_cropexpense' , ['farm_id' => $farm_id, 'worker'=>$worker]) }}"
}
</script>

</html>