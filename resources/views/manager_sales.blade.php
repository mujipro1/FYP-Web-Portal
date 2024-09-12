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
    </head>

<script>
    farm_id = @json($farm_id)
</script>

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
            @if($worker == 0)
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>
            @endif
                <div class="col-md-10 offset-md-1 ">


                    <div class="container section">
                        <div class="row">
                        <div class="d-flex justify-content-between align-items-center my-3">
                            @if($worker == 0)
                            <a href="{{ route('manager.farmdetails', ['farm_id' => $farm_id]) }}" class="back-button">
                                <svg xmlns="http://www.w3.org/2000/svg"  class='svg' viewBox="0 0 24 24" width="512" height="512">
                                    <path
                                        d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                                </svg>
                            </a>
                            @else
                            <a href="{{ route('sales_farmer') }}" class="back-button">
                                <svg xmlns="http://www.w3.org/2000/svg"  class='svg' viewBox="0 0 24 24" width="512" height="512">
                                    <path
                                        d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                                </svg>
                            </a>
                            @endif
                            <h3 class="flex-grow-1 text-center mb-0">Sales</h3>
                        </div>

                        <div class="d-flex justify-content-between mx-3">
                            <p class='light'>Select a crop to add sales</p>
                            <button class='btn-orange2 or-width p-1' onclick="handleViewSales()">View Sales</button>
                        </div>


                        <div class="col-md-12">

                            <div class="row">
                                @if($crops == null)
                                <div class="d-flex align-items-center justify-content-center">
                                    <h5 class='light'>No crops added</h5>
                                </div>
                                @endif
                                @foreach($crops as $crop)
                                <div class="col-md-3">
                                    <div data-tooltip='Active crop' class="selected-crop crops" id="{{$crop['name']}}" data-id="{{$crop['id']}}" data-identifier="{{$crop['identifier']}}"
                                        onclick="handleCropClick(this)">
                                        <img
                                            src="{{asset('images/crops/'. str_replace(' ', '', $crop['name']) .'.jpg')}}">
                                        <div class='mx-2 fw-bold mt-1'>{{$crop['identifier']}}</div>
                                        @if ($crop['variety'] != null)
                                        <div class='mx-2 fsmall light'>{{$crop['variety']}}</div>
                                        @else
                                        <div class='mx-2 fsmall light'>No variety</div>
                                        @endif
                                    </div>
                                </div>
                                @endforeach
                            </div>


                            <div class="sales-cont">
                                <form class='row' id="cropForm" action="{{route('manager.add_sales')}}" method='post'>
                                  
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
<script src="{{ asset('js/data/salesdata.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
    function handleCropClick(e){


        const cropForm = document.getElementById("cropForm");
        cropForm.classList.add('box-cont', 'p-4', 'my-4');
        const crops = document.querySelectorAll('.crops');
        


            // Deselect all crops first
            crops.forEach(c => c.classList.remove('selecteds'));
            crops.forEach(c => c.classList.add('selected-crop'));

            e.classList.remove('selected-crop');
            e.classList.add('selecteds');

            selectedCrop = e.id;            
            selectedCrop = selectedCrop.replace(/\s/g, '');
            cropId = e.getAttribute('data-id');
            if (!salesData[selectedCrop]) {
                selectedCrop = "All";
            }

            cropForm.innerHTML = `
            @csrf
            <input type="hidden" name="farm_id" value="${farm_id}">
            <input type="hidden" name="crop" value="${cropId}">
            <p class='light'>${e.getAttribute('data-identifier')}</p>`; // Clear existing form content
        
            if (selectedCrop && salesData[selectedCrop]) {
                const formData = salesData[selectedCrop];

                formData.forEach(field => {
                    
                    const div = document.createElement("div");
                    div.classList.add("col-md-6", "mb-2", "px-2", "labelcontainer");
                    
                    const label = document.createElement("label");
                    label.textContent = field.label;
                    div.appendChild(label);
                    label.classList.add("form-label", "w-50");

                    let input;
                    
                    if (field.label == "Date"){
                        input = document.createElement("input");
                        input.type = "date";
                        input.id = field.id || "";
                        // default todays
                        today = new Date();
                        const dd = String(today.getDate()).padStart(2, '0');
                        const mm = String(today.getMonth() + 1).padStart(2, '0'); //January is 0!
                        const yyyy = today.getFullYear();
                        today = yyyy + '-' + mm + '-' + dd;
                        input.value = today;

                    }
                    else if (field.label == 'Dera'){
                        cropId = e.getAttribute('data-id');
                        input = document.createElement("select");

                        fetch(`/get-deras/${cropId}`)
                            .then(response => response.json())
                            .then(data => {
                                input.innerHTML = '<option value="">Select Dera</option>';
                                data.forEach(dera => {
                                    const option = document.createElement('option');
                                    option.value = dera.name;
                                    option.textContent = dera.name;
                                    input.appendChild(option);
                                });
                            })
                            .catch(error => console.error('Error fetching Deras:', error));
                    }
                    else if (field.type === "num") {
                        input = document.createElement("input");
                        input.type = "number";
                        input.id = field.id || "";
                    } else if (field.type === "text") {
                        input = document.createElement("input");
                        input.type = "text";
                    } else if (field.type === "dropdown") {
                        input = document.createElement("select");
                        field.options.forEach(optionText => {
                            const option = document.createElement("option");
                            option.value = optionText;
                            option.textContent = optionText;
                            input.appendChild(option);
                        });
                    }

                    input.name  = field.label;
                    input.classList.add("form-control");
                    input.required = true;

                    if (field.label == 'Remarks'){
                        input.required = false;
                    }

                    if (field.readonly) {
                        input.readOnly = true;
                    }

                    div.appendChild(input);
                    cropForm.appendChild(div);
                });


                // submit button
                const div2 = document.createElement("div");
                div2.classList.add("my-3", "text-center");
                const submitButton = document.createElement("button");
                submitButton.type = "submit";
                submitButton.textContent = "Submit";
                submitButton.classList.add("btn-orange2", "or-width", "mt-3");
                div2.appendChild(submitButton);
                cropForm.appendChild(div2);

                setupAutoCalculation(selectedCrop);
            }
        }
        
        function setupAutoCalculation(crop) {
            // Wheat Calculation
            if (crop === "Wheat") {
                const weightInput = document.getElementById("totalWeight");
                const unitPriceInput = document.getElementById("unitPrice");
                const amountInput = document.getElementById("amount");

                if (weightInput && unitPriceInput && amountInput) {
                    weightInput.addEventListener("input", calculateAmountWheat);
                    unitPriceInput.addEventListener("input", calculateAmountWheat);

                    function calculateAmountWheat() {
                        const weight = parseFloat(weightInput.value) || 0;
                        const unitPrice = parseFloat(unitPriceInput.value) || 0;
                        amountInput.value = weight * unitPrice;
                    }
                }
            }

            // All Crop Calculation
            if (crop === "All") {
                const weightInput = document.getElementById("totalWeightAll");
                const unitPriceInput = document.getElementById("unitPriceAll");
                const amountInput = document.getElementById("amountAll");

                if (weightInput && unitPriceInput && amountInput) {
                    weightInput.addEventListener("input", calculateAmountAll);
                    unitPriceInput.addEventListener("input", calculateAmountAll);

                    function calculateAmountAll() {
                        const weight = parseFloat(weightInput.value) || 0;
                        const unitPrice = parseFloat(unitPriceInput.value) || 0;
                        amountInput.value = weight * unitPrice;
                    }
                }
            }

            // Rice Calculation
            if (crop === "Rice") {
                const weightInput = document.getElementById("totalWeightRice");
                const unitPriceInput = document.getElementById("unitPriceRice");
                const amountInput = document.getElementById("amountRice");

                if (weightInput && unitPriceInput && amountInput) {
                    weightInput.addEventListener("input", calculateAmountRice);
                    unitPriceInput.addEventListener("input", calculateAmountRice);

                    function calculateAmountRice() {
                        const weight = parseFloat(weightInput.value) || 0;
                        const unitPrice = parseFloat(unitPriceInput.value) || 0;
                        amountInput.value = weight * unitPrice;
                    }
                }
            }

            // Corn Calculation
            if (crop === "Corn") {
                const weightInput = document.getElementById("totalWeightCorn");
                const unitPriceInput = document.getElementById("unitPriceCorn");
                const amountInput = document.getElementById("amountCorn");

                if (weightInput && unitPriceInput && amountInput) {
                    weightInput.addEventListener("input", calculateAmountCorn);
                    unitPriceInput.addEventListener("input", calculateAmountCorn);

                    function calculateAmountCorn() {
                        const weight = parseFloat(weightInput.value) || 0;
                        const unitPrice = parseFloat(unitPriceInput.value) || 0;
                        amountInput.value = weight * unitPrice;
                    }
                }
            }

            // Sugarcane Calculation
            if (crop === "Sugarcane") {
                const weightInput = document.getElementById("totalWeightSugarcane");
                const unitPriceInput = document.getElementById("unitPriceSugarcane");
                const amountInput = document.getElementById("bankCreditedAmountSugarcane");

                if (weightInput && unitPriceInput && amountInput) {
                    weightInput.addEventListener("input", calculateAmountSugarcane);
                    unitPriceInput.addEventListener("input", calculateAmountSugarcane);
                    
                    function calculateAmountSugarcane() {
                        const weight = parseFloat(weightInput.value) || 0;
                        const unitPrice = parseFloat(unitPriceInput.value) || 0;
                        amountInput.value = weight * unitPrice;
                    }
                }
            }
            
            weight = document.getElementById('totalWeightAll') || document.getElementById('totalWeightRice') || document.getElementById('totalWeightCorn') || document.getElementById('totalWeightSugarcane') || document.getElementById('totalWeight');
            const weightMun = document.getElementById("weightMuns");

            if (weight && weightMun) {
                weight.addEventListener("input", calculateMun);
                function calculateMun() {
                    weightVal = parseFloat(weight.value) || 0;
                    weightMun.value = weightVal / 40;
                }
            }
    }


    function handleViewSales(){
        window.location.href="{{route('manager.view_sales', ['farm_id' => $farm_id])}}"
    }



</script>
</html>