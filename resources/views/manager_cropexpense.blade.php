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
        added_expenses = @json($added_expenses);
        removed_expenses = @json($removed_expenses);
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
                                    <button class="tab-button">Crop Expense</button>
                                    <button class="deselect" onclick="handleExpenseClick()">Farm Expense</button>
                                    <button class="deselect" onclick="handleReconClick()">Reconciliation</button>
                                </div>


                                 @if (count($crops) != 0)
                                <div class="box-cont p-5">
                                    <div class="d-flex justify-content-between align-items-center">
                                        <p class="light">Add your crop expenses here</p>
                                        <button class="btn btn-brown" onclick = "handleViewExpenseClick()"
                                        id="addexpense">View Expenses</button>
                                    </div>
                                    <h5 class='mt-5'> Crops</h5>
                                    <div class='light'>Select Crop</div>

                                    <div class="col-md-5">
                                        <select class='form-select my-3' id='crop-select'>
                                            <option value="0">Active Crops</option>
                                            <option value="1">Passive Crops</option>
                                        </select>
                                    </div>
                                        

                                    <div class="row">
                                        @foreach($crops as $crop)
                                        <div class="col-md-4 crops-container my-2"  data-active="{{$crop['active']}}">
                                            <div class="selected-crop" data-id="{{$crop['id']}}" data-identifier="{{$crop['identifier']}}" 
                                            style="backgroundColor:#f4f4f4;cursor:pointer;">
                                                <img src="{{asset('images/crops/'. str_replace(' ', '', $crop['name']) .'.jpg')}}" class="selected-crop-image" />
                                                <h5 class="m-2 mt-3">{{$crop['identifier']}}</h5>
                                                <p class="mx-2  fsmall light pt-1">{{$crop['variety'] ? $crop['variety'] : 'No Variety Found' }}</p>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>


                                    <div class="expense-box py-4 my-4 row">
                                        <form id="farmexpense-form" action="{{route('manager.add_cropexpense')}}" method="POST">
                                            @csrf
                                            <input type="hidden" name="farm_id" value="{{$farm_id}}">
                                            <input type="hidden" name="selected_crop" id="selected_crop">
                                            <div class="col-md-7">
                                                <div class="labelcontainer">
                                                    <label class='w-50' for="date">Date</label>
                                                    <input type="date" id="date" name="date" class="form-control"
                                                        value="{{$latest_expense_date}}" style='margin:0px;'>
                                                </div>
                                            </div>
                                            <div class='col-md-7'>
                                                <div class="labelcontainer" id="farmdera-container">
                                                    <label class='w-50' for="head">Dera</label>
                                                    <select class='form-select' id="dera" name="dera">
                                                        <option value="">Select Dera</option>
                                                    </select>
                                                </div>
                                            </div>

                                            <div class='col-md-7'>
                                                <div class="labelcontainer">
                                                    <label class='w-50' for="head">Expense Type</label>
                                                    <select class='form-select' id="head" name="head" required>
                                                        <option value="">Select Expense Type</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class='col-md-7 hidden' id="subhead-container">
                                                <div class="labelcontainer">

                                                    <label class='w-50' for="subhead">Subtype</label>
                                                    <select class='form-select' id="subhead" name="subhead">
                                                        <option value="">Select Subtype</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class='col-md-7 hidden' id="fields-container">
                                                <div class="labelcontainer">

                                                </div>
                                            </div>

                                            <!-- checkbox -->

                                            @if($worker == 1)
                                            <div class='col-md-7 m-2 mt-4 hidden ' id='paidByOwner'>
                                                <input type="checkbox" id="paidbyowner" class='form-check-input' style='cursor:pointer;' name="paidbyowner" value="1">
                                                <label class='w-50 mx-3' for="paidbyowner" style='cursor:pointer;'>Paid By Owner</label>
                                            </div>
                                            @endif

                                            <div id='submitdiv' class="hidden text-center mt-4">
                                                <button class='btn btn-brown' type="submit">Submit</button>
                                            </div>

                                            @php 
                                            if($worker == 0){
                                                $manager = Session::get('manager');
                                            }
                                            else{
                                                $manager = Session::get('expense_farmer');
                                            }
                                            $addedBy = $manager['id'];
                                            @endphp
                                            
                                            <input hidden name='addedBy' value="{{$addedBy}}">
                                        </form>

                                    </div>
                                </div>
                                @else
                                <div class="box-cont p-5">
                                    <p class="light">No crops found</p>
                                </div>
                                @endif
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
<script src="{{ asset('js/handleExpenseGeneration.js') }}"></script>
<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
    function handleExpenseClick() {
        window.location.href = "{{ route('manager.render_farmexpense' , ['farm_id' => $farm_id])}}"
    }

    function handleViewExpenseClick() {

        window.location.href = "{{ route('manager.view_cropexpense', ['farm_id' => $farm_id])}}"
    }

    function handleReconClick() {
        window.location.href = "{{ route('manager.reconciliation' , ['farm_id' => $farm_id])}}"
    }
    
    farm_Id = {{$farm_id}};


    document.querySelectorAll('.crops-container').forEach(function(crop) {
        if (crop.getAttribute('data-active') == 1) {
            crop.style.display = 'block';
        } else {
            crop.style.display = 'none';
        }
    });

    document.getElementById('crop-select').addEventListener('change', function() {
        if (this.value == 0) {
            document.querySelectorAll('.crops-container').forEach(function(crop) {
                if (crop.getAttribute('data-active') == 1) {
                    crop.style.display = 'block';
                } else {
                    crop.style.display = 'none';
                }
            });
        } else {
            document.querySelectorAll('.crops-container').forEach(function(crop) {
                if (crop.getAttribute('data-active') == 0) {
                    crop.style.display = 'block';
                } else {
                    crop.style.display = 'none';
                }
            });
        }
    });


</script>
</html>