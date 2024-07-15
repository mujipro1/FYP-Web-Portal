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


        <!-- Modal -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropModalLabel">Enter Crop Details</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">

                <div class="text-center my-3">
                    <div id="cropNameModal"></div>
                </div>

                <div class="mb-3">
                    <label for="cropYear" class="form-label">Year</label>
                    <select class="form-select" id="cropYear" required>
                        <!--use php to render years from 1970 onward to current year  -->
                        @php
                            $currentYear = date('Y');
                            for($i =  $currentYear; $i >= 1970; $i--){
                                if ($i == $currentYear){
                                    echo "<option value='$i' selected>$i</option>";
                                }
                                else{
                                    echo "<option value='$i'>$i</option>";
                                }
                            }
                        @endphp
                    </select>
                </div>
                <div id="derasContainer"></div>                
                <div class="mb-3">
                    <label for="cropAcres" class="form-label">Number of Acres</label>
                    <input required type="number" class="form-control" id="cropAcres" required>
                </div>

                <!-- radio button for active passive -->
                <p class="light mt-5 mx-2 fsmall">Crop Status</p>
                <div class="d-flex justify-content-start">
                    <div class="form-check mx-2 display-inline">
                        <input class="form-check-input" type="radio" name="cropStatus" id="cropStatusActive" value="1" checked>
                        <label class="form-check-label" for="cropStatusActive">Active</label>
                    </div>
                    <div class="form-check mx-2 display-inline">
                        <input class="form-check-input" type="radio" name="cropStatus" id="cropStatusPassive" value="0">
                        <label class="form-check-label" for="cropStatusPassive">Passive</label>
                    </div>
                </div>


                <!-- sowing and harvesting date -->
                <div class="mb-3 labelcontainer mt-4">
                    <label for="cropSowingDate" class="form-label w-50">Sowing Date</label>
                    <input type="date" class="form-control" id="cropSowingDate" required>
                </div>
                <div class="mb-3 labelcontainer">
                    <label for="cropHarvestingDate" class="form-label w-50">Harvesting Date</label>
                    <input type="date" class="form-control" id="cropHarvestingDate" required>
                </div>


                <div class="mt-4 labelcontainer">
                    <label for="cropStage" class="form-label w-50">Crop Stage</label>
                    <select class="form-select" id="cropStage" required>
                        <option value="">Select Crop Stage</option>
                        <option value="Planted">Planted</option>
                        <option value="Established">Established</option>
                        <option value="Harvested">Harvested</option>
                        <option value="Sold">Sold</option>
                    </select>
                </div>


                 <div class="mt-4 labelcontainer">
                    <label for="description" class="form-label w-50">Description</label>
                    <textarea class="form-control" id="description" rows="3"></textarea>
                </div>
                
                <div id="noDeraAlert" class="alert alert-danger d-none" role="alert">
                    Please select at least one Dera or enter acres if no Deras are present.
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary" id="saveCropButton">Save Crop</button>
            </div>
        </div>
    </div>
</div>



        <div class="container-fluid">
            <div class="row">
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>
                    <div class="col-md-10 offset-md-1 ">


                <form id="cropDetailsForm" action = "{{ route('manager.configurationForm') }}" method="POST">
                    @csrf
                    <input hidden name="farm_id" value="{{ $farm_id }}">
                    <div class="container">
                        
                        <div class="d-flex justify-content-between align-items-center my-3">
                            <a href="{{ route('manager.configuration', ['farm_id' => $farm_id]) }}"
                            class="back-button">
                                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="512"  class='svg' height="512"><path d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z"/></svg>
                                </a>
                                <h3 class="flex-grow-1 text-center mb-0">Select Crops</h3>
                                <div style='visibility:hidden;' class="invisible"></div>
                            </div>

                            <div class="row px-3">
                            <div class=" mt-3 px-4">

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
                                    <button class='mx-2 btn btn-brown text-light' type="button" id="nextButton">Submit</button>
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
<script src="{{ asset('js/alert.js') }}"></script>
<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script src="{{ asset('js/data/cropdata.js') }}"></script>
<script src="{{ asset('js/crop_configuration.js') }}"></script>
</html>