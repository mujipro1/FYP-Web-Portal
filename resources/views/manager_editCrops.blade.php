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
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>
                <div class="col-md-10 offset-md-1 ">


                    <div class="d-flex justify-content-between align-items-center my-3">
                        <a href="{{ route('manager.configuration', ['farm_id' => $farm_id]) }}" class="back-button">
                            <svg xmlns="http://www.w3.org/2000/svg" class='svg' viewBox="0 0 24 24" width="512"
                                height="512">
                                <path
                                    d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                            </svg>
                        </a>
                        <h3 class="flex-grow-1 text-center mb-0">Edit Crops</h3>
                        <div style='visibility:hidden;' class="invisible"></div>
                    </div>

                    <div class="row mx-4">
                        <div class="col-md-5">
                            <div class="d-flex justify-content-start">
                                <label class="w-50">Select Crop</label>
                                <select class="form-select" onchange="handleSelectCrop(this)">
                                    <option selected disabled>Select Crop</option>
                                    @foreach($crops as $crop)
                                    <option data-crop-id="{{ $crop->id }}" data-crop-name="{{ $crop->name }}"
                                        data-crop-acres="{{ $crop->acres }}" data-crop-deras='@json($crop->deras)'
                                        data-crop-status='{{ $crop->active }}'
                                        data-crop-identifier="{{ $crop->identifier }}">{{$crop->identifier}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="d-flex justify-content-center">
                        <p class="text-secondary my-3">or choose from carousal</p>
                    </div>


                    <div class="row px-4">

                        <div id="carouselExampleCaptions" class="carousel carousel-dark slide" data-bs-ride="false">
                            <div class="carousel-indicators">
                                @foreach($crops->chunk(3) as $index => $chunk)
                                <button type="button" data-bs-target="#carouselExampleCaptions"
                                    data-bs-slide-to="{{ $index }}" class="{{ $index == 0 ? 'active' : '' }}"
                                    aria-current="true" aria-label="Slide {{ $index + 1 }}"></button>
                                @endforeach
                            </div>
                            <div class="carousel-inner">
                                @foreach($crops->chunk(3) as $index => $chunk)
                                <div class="carousel-item {{ $index == 0 ? 'active' : '' }}">
                                    <div class="card-group">
                                        @foreach($chunk as $crop)
                                        <div class="p-3 col-md-4">
                                            <div class="mycard mycard2">
                                                <img src="{{asset('images/crops/'. str_replace(' ', '', $crop['name']) .'.jpg')}}"
                                                    alt="" class="img-fluid">
                                                <div class="mycardInner">
                                                    <div class="py-3 d-flex justify-content-center">
                                                        <div class="line"></div>
                                                    </div>
                                                    <div class="text-center py-2 ">
                                                        <h4>{{$crop->identifier}}</h4>
                                                        <p>{{$crop->acres}} Acres</p>
                                                    </div>

                                                    <div class="text-center">
                                                        <button class="btn btn-primary" onclick="handleSelectCrop(this)"
                                                            data-crop-id="{{ $crop->id }}"
                                                            data-crop-name="{{ $crop->name }}"
                                                            data-crop-acres="{{ $crop->acres }}"
                                                            data-crop-deras='@json($crop->deras)'
                                                            data-crop-status='{{ $crop->active }}'
                                                            data-crop-identifier="{{ $crop->identifier }}"
                                                            data-identifier="{{ $crop->identifier }}">Select
                                                            Crop</button>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            </div>
                            <button class="carousel-control-prev" type="button"
                                data-bs-target="#carouselExampleCaptions" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Previous</span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                data-bs-target="#carouselExampleCaptions" data-bs-slide="next">
                                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                                <span class="visually-hidden">Next</span>
                            </button>
                        </div>

                        <form id="editCropForm" action="{{ route('manager.editCropsPost', ['farm_id' => $farm_id]) }}"
                            method="POST">
                            @csrf
                            <input type="hidden" name="farm_id" value="{{ $farm_id }}">
                            <div class="row my-2">
                                <div class="col-md-7">
                                    <div class="labelcontainer2 my-2">
                                        <label class='w-25' for="cropname">Crop Name</label>
                                        <label id="cropnameLabel"></label>
                                        <input type="text" hidden id="cropname" name="cropname" class="form-control"
                                            readonly>
                                    </div>

                                    <div class="labelcontainer2 mb-2">
                                        <label class='w-25' for="acres">Total Acres</label>
                                        <label id="acresLabel"></label>
                                        <input hidden type="number" step='any' id="acres" name="acres"
                                            class="form-control">
                                    </div>
                                    <div class="labelcontainer" id="dera-con">
                                        <label class='w-75' for="deras">Deras</label>
                                        <select id="deras" name="deras" class="form-select">
                                            <option value="">Select a dera</option>
                                        </select>

                                    </div>

                                    <div class="labelcontainer"  id="dera-con2">
                                        <label class='w-75' for="deraAcres">Dera Acres</label>
                                        <input type="number" step='any' id="deraAcres" name="deraAcres"
                                            class="form-control">
                                    </div>

                                    <div class="labelcontainer"  id="crop-con" style="display:none">
                                        <label class='w-75' for="cropAcresTotal">Crop Acres</label>
                                        <input type="number" step='any' id="cropAcresTotal" name="cropAcresTotal"
                                            class="form-control">
                                    </div>

                                    <div class=" mt-4 d-flex">
                                        <label class="w-75" for="status">Change Status</label>
                                        <select class="form-select" name="status" id="status">
                                            <option value="">Select Status</option>
                                        </select>
                                    </div>

                                    <input type="hidden" id="selectedCropId" name="selectedCropId" value="">
                                </div>

                                <!-- check count of deras in which crop is plotted
                                  -->

                                <div class="col-md-4 offset-md-1 px-4">
                                    <div class="box-cont">
                                        <div class="text-center">
                                            Remove Crop From Dera
                                            <hr class="hr2">
                                            <button id="removeBtn" type='button' class="btn my-3 btn-danger">Remove
                                            </button>
                                            <input hidden type="text" name="remove" id='remove' value="0">
                                            <p id='removeCropP' class='fsmall'>Click to remove crop from dera</p>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="text-center my-3">
                                <button type='button' id='saveBtn' class="btn btn-primary">Save Changes</button>
                            </div>
                        </form>
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
let selectedCropId = null;
let cropDeras = []; // To hold the Deras information for the selected crop

function handleSelectCrop(element) {
    // For select dropdown, we need to get the selected option
    let selectedOption;
    if (element.tagName === 'SELECT') {
        selectedOption = element.options[element.selectedIndex];
    } else {
        // For buttons, use the element directly
        selectedOption = element;
    }

    if (JSON.parse(selectedOption.getAttribute('data-crop-deras')).length <= 1) {
        document.getElementById('removeBtn').disabled = true;
    } else {
        document.getElementById('removeBtn').disabled = false;
    }

    const cropId = selectedOption.getAttribute('data-crop-id');
    const cropName = selectedOption.getAttribute('data-crop-name');
    const cropIdentifier = selectedOption.getAttribute('data-crop-identifier');
    const cropAcres = selectedOption.getAttribute('data-crop-acres');
    const cropStatus = selectedOption.getAttribute('data-crop-status');
    cropDeras = JSON.parse(selectedOption.getAttribute('data-crop-deras'));

    document.getElementById('cropAcresTotal').value = cropAcres;
    if (cropDeras.length == 0) {
        document.getElementById('dera-con').style.display = 'none';
        document.getElementById('dera-con2').style.display = 'none';
        document.getElementById('crop-con').style.display = 'flex';
    }

    document.getElementById('cropAcresTotal').addEventListener('input', () => {
        if (document.getElementById('cropAcresTotal').value == ''){
            document.getElementById('acresLabel').textContent = cropAcres;
        }
        else{
            document.getElementById('acresLabel').textContent = document.getElementById('cropAcresTotal').value;
        }
    })

    // For buttons, handle the card selection styling
    if (element.tagName === 'BUTTON') {
        const cards = document.querySelectorAll('.mycard');
        const buttons = document.querySelectorAll('.mycard button');

        cards.forEach(card => card.classList.remove('mycard-selected'));
        buttons.forEach(btn => {
            btn.textContent = 'Select Crop';
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-primary');
        });

        if (selectedCropId === cropId) {
            selectedCropId = null;
            clearFormFields();
            return;
        }

        const selectedCard = element.closest('.mycard');
        selectedCard.classList.add('mycard-selected');
        element.textContent = 'Deselect Crop';
        element.classList.remove('btn-primary');
        element.classList.add('btn-danger');
        
        // Update dropdown to match the selected carousel item
        const dropdown = document.querySelector('.form-select');
        for (let i = 0; i < dropdown.options.length; i++) {
            if (dropdown.options[i].getAttribute('data-crop-identifier') === cropIdentifier) {
                dropdown.selectedIndex = i;
                break;
            }
        }
    } else if (element.tagName === 'SELECT') {
        // If selection came from dropdown, find and select corresponding carousel item
        const buttons = document.querySelectorAll('.mycard button');
        const cards = document.querySelectorAll('.mycard');
        
        // First, clear all selections
        cards.forEach(card => card.classList.remove('mycard-selected'));
        buttons.forEach(btn => {
            btn.textContent = 'Select Crop';
            btn.classList.remove('btn-danger');
            btn.classList.add('btn-primary');
        });
        
        // Then find and select the matching carousel item
        buttons.forEach(btn => {
            if (btn.getAttribute('data-crop-identifier') === cropIdentifier) {
                const selectedCard = btn.closest('.mycard');
                selectedCard.classList.add('mycard-selected');
                btn.textContent = 'Deselect Crop';
                btn.classList.remove('btn-primary');
                btn.classList.add('btn-danger');
            }
        });
    }

    selectedCropId = cropId;
    document.getElementById('selectedCropId').value = selectedCropId;

    // Update form fields
    document.getElementById('cropname').value = cropName;
    document.getElementById('acres').value = cropAcres;
    document.getElementById('deraAcres').value = '';
    document.getElementById('cropnameLabel').textContent = cropIdentifier;
    document.getElementById('acresLabel').textContent = cropAcres;

    // Populate Deras select box
    const derasSelect = document.getElementById('deras');
    derasSelect.innerHTML = '<option value="">Select a dera</option>';
    cropDeras.forEach(dera => {
        const option = document.createElement('option');
        option.value = dera.id;
        option.textContent = dera.name;
        derasSelect.appendChild(option);
    });

    const statusSelect = document.getElementById('status');
    statusSelect.innerHTML = '<option value="">Select Status</option>';
    const statusOption1 = document.createElement('option');
    statusOption1.value = '1';
    statusOption1.textContent = 'Active';
    const statusOption2 = document.createElement('option');
    statusOption2.value = '0';
    statusOption2.textContent = 'Passive';
    statusSelect.appendChild(statusOption1);
    statusSelect.appendChild(statusOption2);

    if (cropStatus == 1) {
        statusSelect.value = '1';
    } else {
        statusSelect.value = '0';
    }
}

function clearFormFields() {
    document.getElementById('cropname').value = '';
    document.getElementById('acres').value = '';
    document.getElementById('deras').innerHTML = '<option value="">Select a dera</option>';
    document.getElementById('deraAcres').value = '';
    document.getElementById('acresLabel').textContent = '';
    document.getElementById('cropnameLabel').textContent = '';
    document.getElementById('remove').value = '0';
    document.getElementById('removeBtn').innerText = 'Remove';
    document.getElementById('removeBtn').classList.remove('btn-success');
    document.getElementById('removeBtn').classList.add('btn-danger');
    document.getElementById('removeCropP').innerText = 'Click to remove crop from dera';
    document.getElementById('status').innerHTML = '<option value="">Select Status</option>';
    document.getElementById('selectedCropId').value = '';
    selectedCropId = null;
}

function handleDeraChange() {
    const selectedDeraId = document.getElementById('deras').value;
    const deraAcresInput = document.getElementById('deraAcres');
    const totalAcresInput = document.getElementById('cropAcresTotal');
    
    if (selectedDeraId) {
        const selectedDera = cropDeras.find(dera => dera.id == selectedDeraId);
        if (selectedDera) {
            deraAcresInput.value = selectedDera.pivot.acres;
        } else {
            deraAcresInput.value = '';
        }
    } else {
        deraAcresInput.value = '';
    }

    // Reset the remove field
    document.getElementById('remove').value = '0';
    
    // Add event listener to update total when deraAcres changes
    deraAcresInput.addEventListener('input', updateTotalAcres);
    
    // Initial calculation of total acres
    updateTotalAcres();
}

function updateTotalAcres() {
    const selectedDeraId = document.getElementById('deras').value;
    const deraAcresInput = document.getElementById('deraAcres');
    const totalAcresInput = document.getElementById('cropAcresTotal');
    
    // Calculate total of all other deras (excluding the currently selected one)
    let totalOtherAcres = 0;
    cropDeras.forEach(dera => {
        if (dera.id != selectedDeraId) {
            totalOtherAcres += parseFloat(dera.pivot.acres) || 0;
        }
    });
    
    // Add current dera acres to the total
    const currentDeraAcres = parseFloat(deraAcresInput.value) || 0;
    const totalAcres = totalOtherAcres + currentDeraAcres;
    
    // Update the total acres field
    totalAcresInput.value = totalAcres.toFixed(2);
    document.getElementById('acresLabel').textContent = totalAcres.toFixed(2);

}

function handleRemoveFromDera() {
    if (!selectedCropId) {
        alert('Please select a crop first');
        return;
    }
    // if dera not selected
    if (!document.getElementById('deras').value) {
        alert('Please select a dera first');
        return;
    }
    if (document.getElementById('remove').value == '0') {
        document.getElementById('remove').value = '1';
        document.getElementById('deraAcres').value = '';
        document.getElementById('removeBtn').innerText = 'Removed';
        document.getElementById('removeBtn').classList.add('btn-success');
        document.getElementById('removeBtn').classList.remove('btn-danger');
        document.getElementById('removeCropP').innerText = 'Click again to undo';
    } else {
        document.getElementById('remove').value = '0';
        document.getElementById('removeBtn').innerText = 'Remove';
        document.getElementById('removeBtn').classList.remove('btn-success');
        document.getElementById('removeBtn').classList.add('btn-danger');
        document.getElementById('removeCropP').innerText = 'Click to remove crop from dera';

    }

}
document.getElementById('saveBtn').addEventListener('click', function() {

    if (!selectedCropId) {
        alert('Please select a crop first');
        return;
    }
    // if (document.getElementById('remove').value == '0' && !document.getElementById('deraAcres').value) {
    //     alert('Please enter dera acres');
    //     return;
    // }
    else {
        document.getElementById('editCropForm').submit();
    }
});


document.getElementById('deras').addEventListener('change', handleDeraChange);
document.getElementById('removeBtn').addEventListener('click', handleRemoveFromDera);
</script>


</html>