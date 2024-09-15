<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
    <script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.css') }}">
    <link rel="stylesheet" href="{{ asset('bootstrap/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/navBar.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/questionaire.css') }}">
    <script src="{{ asset('js/alert.js') }}"></script>
    </head>

<body>
    <div class="container mb-4">
        <div id="navbar">
            @include('components.nav2')
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

    <div class="container my-5">
        <div class="text-center">
            <h4>Sign Up Confirmation</h4>
            <p class='fsmall light'>Preview and edit your details</p>
        </div>

        <form id="confirmation-form" class="mt-4" action="{{ route('save_preview_changes') }}" method="POST">
            @csrf
            <div class="row ">
                <div class="col-md-12">

                    <div class="box-cont mx-1 row p-5">
                        <h4 class='light'>Form</h4>
                 
                        <div class="col-md-6">
                            <div class="labelcontainer">
                                <label for='name' class='form-label w-50'>Name</label>
                                <input type='text' class='form-control' id='name' name='farmerName'
                                    value="{{ $data['farmerName'] }}" disabled>
                            </div>
                            <div class="labelcontainer">
                                <label for='email' class='form-label w-50'>Email</label>
                                <input type='email' class='form-control' id='email' name='email'
                                    value="{{ $data['email'] }}" disabled>
                            </div>
                            <div class="labelcontainer">
                                <label for='phone' class='form-label w-50'>Phone</label>
                                <input type='text' class='form-control' id='phone' name='phone'
                                    value="{{ $data['phone'] }}" disabled>
                            </div>
                            <div class="labelcontainer">
                                <label for='farmName' class='form-label w-50'>Farm Name</label>
                                <input type='text' class='form-control' id='farmName' name='farmName'
                                    value="{{ $data['farmName'] }}" disabled>
                            </div>
                            <div class="labelcontainer">
                                <label for='farmCity' class='form-label w-50'>Farm City</label>
                                <select class='form-control' id='farmCity' name='farmCity' disabled></select>
                            </div>
                            <div class="labelcontainer">
                                <label for='farmAddress' class='form-label w-50'>Farm Address</label>
                                <input type='text' class='form-control' id='farmAddress' name='farmAddress'
                                    value="{{ $data['farmAddress'] }}" disabled>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="labelcontainer">
                                <label for='acres' class='form-label w-50'>Farm Area</label>
                                <input type='number' step='any' class='form-control' id='acres' name='acres'
                                    value="{{ $data['acres'] }}" disabled>
                            </div>
                            <div class="labelcontainer">
                                <label for='has_deras' class='form-label w-50'>Has Deras</label>
                                <select class='form-control' id='has_deras' name='has_deras' disabled>
                                    <option value="0" {{ $data['has_deras'] == 0 ? 'selected' : '' }}>No</option>
                                    <option value="1" {{ $data['has_deras'] == 1 ? 'selected' : '' }}>Yes</option>
                                </select>
                            </div>
                            <div class="labelcontainer deras-info" id="deras-info">
                                <label for='deras' class='form-label w-50'>No of Deras</label>
                                <input type='number' class='form-control' id='deras' name='deras'
                                    value="{{ $data['deras'] }}" disabled min='0' max="20">
                            </div>
                            <div id="dera-acres-container" class="deras-info">
                                <!-- Dynamic Dera Acres Fields will be inserted here -->
                            </div>
                            <div class="labelcontainer">
                                <label for='remarks' class='form-label w-50'>Remarks</label>
                                <input type='text' class='form-control' id='remarks' name='remarks'
                                    value="{{ $data['remarks'] }}" disabled>
                            </div>
                        </div>
                        <div class="text-center mt-4">
                            <button type="button" id="edit-btn" class="btn btn-orange  or-width mt-3">Edit</button>
                            <button type="submit" id="save-btn" class="btn btn-orange2 or-width  mt-3">Save</button>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <div id="footer">
        @include('components.footer')
    </div>

    
    <script src="{{ asset('js/data/citydata.js') }}"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const editBtn = document.getElementById('edit-btn');
        const saveBtn = document.getElementById('save-btn');
        const form = document.getElementById('confirmation-form');
        const hasDerasSelect = document.getElementById('has_deras');
        const derasInfo = document.querySelectorAll('.deras-info');
        const derasInput = document.getElementById('deras');
        const deraAcresContainer = document.getElementById('dera-acres-container');
        const citySelect = document.getElementById('farmCity');
        if (hasDerasSelect.value === '1') {
            deraAcresData = @json($data['deraAcres']).split(',').map(Number);
        }

        function populateCities() {
            citydata.forEach(city => {
                const option = document.createElement('option');
                option.value = city;
                option.textContent = city;
                citySelect.appendChild(option);
            });
            citySelect.value = "{{ $data['farmCity'] }}"; // Set the current value
        }

        function toggleEdit(enable) {
            form.querySelectorAll('input, select').forEach(input => {
                input.disabled = !enable;
            });
        }

        function toggleDerasInfo() {
            const hasDeras = hasDerasSelect.value === '1';
            derasInfo.forEach(field => {
                field.style.display = hasDeras ? '' : 'none';
            });
            if (hasDeras) {
                createDeraAcresFields(derasInput.value);
            } else {
                deraAcresContainer.innerHTML = '';
            }
        }

        function createDeraAcresFields(number) {
            if (number < 1) {
                deraAcresContainer.innerHTML = '';
                return;
            }

            if (number > 20) {
                alert('Number of Deras cannot exceed 20');
                derasInput.value = deraAcresData.length;
                return;
            }
            deraAcresContainer.innerHTML = '';
            for (let i = 0; i < number; i++) {
                const div = document.createElement('div');
                div.className = 'labelcontainer';
                const label = document.createElement('label');
                label.className = 'form-label w-50';
                label.htmlFor = `deraAcres${i}`;
                label.textContent = `Dera ${i + 1} Acres`;
                const input = document.createElement('input');
                input.type = 'number';
                input.className = 'form-control';
                input.id = `deraAcres${i}`;
                input.name = `deraAcres[]`;
                input.value = deraAcresData[i] || '';
                input.disabled = true;
                div.appendChild(label);
                div.appendChild(input);
                deraAcresContainer.appendChild(div);
            }
        }

        function validateDeraAcres() {
            const deraAcresInputs = document.querySelectorAll('#dera-acres-container input');
            for (let input of deraAcresInputs) {
                if (!input.value) {
                    alert('Please fill in all Dera Acres fields.');
                    input.focus();
                    return false;
                }
            }
            return true;
        }

        form.addEventListener('submit', function(event) {
            form.querySelectorAll('input, select').forEach(input => {
                input.disabled = false; // Enable inputs to include their values in the form submission
            });
            console.log(hasDerasSelect.value);
            if (hasDerasSelect.value === '1' && !validateDeraAcres()) {
                event.preventDefault(); // Prevent form submission if validation fails
            }
        });

        editBtn.addEventListener('click', function() {
            toggleEdit(true);
        });

        derasInput.addEventListener('input', function() {
            if (hasDerasSelect.value === '1') {
                createDeraAcresFields(derasInput.value);
            }
        });

        hasDerasSelect.addEventListener('change', toggleDerasInfo);

        populateCities();
        toggleDerasInfo();
    });
    </script>
</body>

</html>
