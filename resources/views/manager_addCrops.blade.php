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
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet-draw/dist/leaflet.draw.css" />
    <script src="{{ asset('js/alert.js') }}"></script>
</head>

    <script>
        const derasData =  @json($deras);
        const farm_id = @json($farm_id);
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
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>
                    <div class="col-md-10 offset-md-1 ">

                    <div class="row d-none page2">
                        <div class="col-md-6" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
                            <div class="box-cont">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h6 class="modal-title light" id="cropModalLabel">Enter Crop Details</h6>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">

                                        <div class="text-center my-3">
                                            <div id="cropNameModal"></div>
                                        </div>

                                        
                                        <div id="sugarcane" class="d-none">
                                            <p class="fsmall mx-3 light">If sugarcane is being planted with the same seed, then check the tick box and select the previous crop where sugarcane is to be planted.</p>
                                            <!-- add a checkbox -->
                                            <div class="form-check d-flex my-4 justify-content-start">
                                                <input class="form-check form-check-input d-flex justify-content-start" type="checkbox" id="sameSeed" value="1">
                                                <label class="form-check form-check-label mt-1 d-flex justify-content-start" for="sameSeed">Same Seed</label>
                                            </div>
                                            <div class="d-flex sugracanePrevCropCont d-none mb-3">
                                                    <label for="sugarcanePreviousCrop" class="form-label w-50">Previous Sugarcane</label>
                                                    <select class="form-select" id="sugarcanePreviousCrop">
                                                        <option value="" disabled >Select Sugarcane Crop  </option>
                                                    </select>
                                                </div>
                                            </div>

                                            <script>
                                                if (document.getElementById('sugarcane')) {
                                                    document.getElementById('sameSeed').addEventListener('change', function(){
                                                        if (this.checked){
                                                            document.querySelector('.sugracanePrevCropCont').classList.remove('d-none');
                                                        }
                                                        else{
                                                            document.querySelector('.sugracanePrevCropCont').classList.add('d-none');
                                                        }
                                                    });
                                                }
                                            </script>
                                            
                                        <div class="d-flex mb-3">
                                            <label for="cropYear" class="w-50 form-label">Year<span class='required'> *</span></label>
                                            <select class="form-select" id="cropYear" required>
                                                <option value='' disabled selected >Select Year</option>
                                                <!--use php to render years from 1970 onward to current year  -->
                                                @php
                                                    $currentYear = date('Y');
                                                    for($i =  $currentYear; $i >= 1970; $i--){
                                                        echo "<option value='$i'>$i</option>";
                                                    }
                                                @endphp
                                            </select>
                                        </div>
                                        <div class="mb-3 d-flex">
                                            <label for="cropAcres" class="w-50 form-label">Number of Acres<span class='required'> *</span></label>
                                            <input step="any" required type="number" class="form-control" id="cropAcres" required>
                                        </div>

                                        <!-- radio button for active passive -->
                                        <div class="d-flex justify-content-start">
                                            <div class="w-50">
                                                <p class="mt-4">Crop Status<span class='required'> *</span></p>
                                            </div>
                                            <div class=" d-flex">
                                                <div class="form-check mx-2 display-inline">
                                                    <input class="form-check-input" type="radio" name="cropStatus" id="cropStatusActive" value="1" checked>
                                                    <label style='cursor:pointer;' class="form-check-label" for="cropStatusActive">Active</label>
                                                </div>
                                                <div class="form-check mx-2 display-inline">
                                                    <input class="form-check-input" type="radio" name="cropStatus" id="cropStatusPassive" value="0">
                                                    <label style='cursor:pointer;' class="form-check-label" for="cropStatusPassive">Passive</label>
                                                </div>
                                            </div>
                                        </div>

                                        <script>
                                            document.getElementById('cropYear').addEventListener('change', function(){
                                                const currentYear = new Date().getFullYear();
                                                const selectedYear = parseInt(this.value);
                                                if (selectedYear != currentYear){
                                                    document.getElementById('cropStatusPassive').checked = true;
                                                }
                                                else{
                                                    document.getElementById('cropStatusActive').checked = true;
                                                }

                                                function restrictDateToYear(year) {
                                                    const sowDateInput = document.getElementById('cropSowingDate');
                                                    sowDateInput.min = `${year}-01-01`;;
                                                    sowDateInput.max = `${year}-12-31`;

                                                    const harvestDateInput = document.getElementById('cropHarvestingDate');
                                                    harvestDateInput.min = `${year}-01-01`;;

                                                }
                                                restrictDateToYear(selectedYear);
                                            });
                                        </script>


                                        <!-- sowing and harvesting date -->
                                        <div class="mb-3 labelcontainer mt-2">
                                            <label for="cropSowingDate" class="form-label w-50">Sowing Date<span class='required'> *</span></label>
                                            <input type="date" class="form-control" id="cropSowingDate" required>
                                        </div>
                                        <div class="mb-3 labelcontainer">
                                            <label for="cropHarvestingDate" class="form-label w-50">Harvesting Date</label>
                                            <input type="date" class="form-control" id="cropHarvestingDate" required>
                                        </div>

                                        <div id="derasContainer"></div>                


                                        <div class=" labelcontainer">
                                            <label for="variety" class="form-label w-50">Variety</label>
                                            <input class="form-control" id="variety"/>
                                        </div>

                                        <div class="mt-2 labelcontainer">
                                            <label for="description" class="form-label w-50">Description</label>
                                            <textarea class="form-control" id="description" rows="3"></textarea>
                                        </div>
                                    
                                    </div>
                                    <div class=" text-center my-3">
                                        <button type="button" class="btn mx-1 btn-orange2 or-width" id="saveCropButton">Save</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="box-cont">
                                <div id="map" style='height:70vh;'></div>
                                <button class='btn btn-orange or-width my-2' id="draw-button">Draw</button>
                            </div>
                        </div>
                    </div>



                <form id="cropDetailsForm" class='page1' action = "{{ route('manager.configurationForm') }}" method="POST">
                    @csrf
                    <input hidden name="farm_id" value="{{ $farm_id }}">
                    <input hidden name="polygonData" id="polygonData">
                    <div class="container">
                        
                        <div class="d-flex justify-content-between align-items-center my-3">
                            <a href="{{ route('manager.farmdetails', ['farm_id' => $farm_id]) }}"
                            class="back-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="512"  class='svg' height="512"><path d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z"/></svg>
                                </a>
                                <h3 class="flex-grow-1 text-center mb-0">Add Crops</h3>
                                <div style='visibility:hidden;' class="invisible"></div>
                            </div>

                            <div class="row px-3">
                                <div class=" mt-3 px-4">
                                    <p class='fsmall light'>Select crops to add them</p>
                                    
                                <div class="dropdown" id="cropDropdown">
                                    <input
                                    class="form-control"
                                    type="text"
                                    id="searchTerm"
                                    placeholder="Type to search crops..."
                                    />
                                    <div class="dropdown-box" hidden id="dropdownBox">
                                        <!-- Filtered crops will be appended here -->
                                </div>
                            </div>
                        </div>


                            <div class="col-md-9 p-4">
                                <p class='light mx-2'>Selected Crops</p>
                                <div class="box-co container selected-crops my-4 p-3" >
                                    <div class="row" id="selectedCropsContainer">
                                        <!-- Selected crops will be appended here -->
                                    </div>
                                </div>
                                <div class="text-center my-3 ">
                                    <button class='mx-2 btn btn-brown text-light d-none' type="button" id="nextButton">Submit</button>
                                </div>
                            </div>
                            <div class="col-md-3 my-4 p-3">
                                <p class='light'>Popular Crops</p>
                                <div id="popularCropsContainer">
                                    <!-- Popular crops will be appended here -->
                                </div>
                            </div>
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

</body>
<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script src="https://unpkg.com/leaflet-draw/dist/leaflet.draw.js"></script>
<script src="https://unpkg.com/leaflet-geometryutil/src/leaflet.geometryutil.js"></script>

<script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet/0.0.1-beta.5/esri-leaflet.js"></script>
<script src="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.js"></script>
<link rel="stylesheet" type="text/css" href="https://cdn-geoweb.s3.amazonaws.com/esri-leaflet-geocoder/0.0.1-beta.5/esri-leaflet-geocoder.css">
<script>
    cropname = null;
</script>
    <script src="{{ asset('js/data/cropdata.js') }}"></script>
    <script src="{{ asset('js/crop_configuration.js') }}"></script>
<script src="{{ asset('js/cropmap.js') }}"></script>


</html>