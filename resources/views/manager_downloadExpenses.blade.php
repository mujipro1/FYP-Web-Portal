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

                    <a href="{{ route('manager.render_cropexpense', ['farm_id' => $farm_id]) }}" class="back-button">
                        <svg xmlns="http://www.w3.org/2000/svg" class='svg' viewBox="0 0 24 24" width="512"
                            height="512">
                            <path
                                d="M19,10.5H10.207l2.439-2.439a1.5,1.5,0,0,0-2.121-2.122L6.939,9.525a3.505,3.505,0,0,0,0,4.95l3.586,3.586a1.5,1.5,0,0,0,2.121-2.122L10.207,13.5H19a1.5,1.5,0,0,0,0-3Z" />
                        </svg>
                    </a>

                    <div class="my-4 d-flex justify-content-center">
                        <h3>Download Expenses</h3>
                    </div>

                    <form method="POST" action="{{ route('download.expenses') }}">
                        @csrf
                        <input type="hidden" name="farm_id" value="{{ $farm_id }}">

                        <div class="row">

                            <div class="col-md-3">
                                <label for="statusFilter">Filter by Status:</label>
                                <select id="statusFilter" class="form-select" onchange="filterCrops()">
                                    <option value="all">All</option>
                                    <option value="active">Active</option>
                                    <option value="passive">Passive</option>
                                </select>
                            </div>

                            <div class="col-md-3">
                                <label for="yearFilter">Filter by Year:</label>
                                <select id="yearFilter" class="form-select" onchange="filterByYear()">
                                    <option value="all">All</option>
                                </select>
                            </div>
                        </div>

                        <h4 class="my-3 mt-5">Crops</h4>
                            <div id="cropCheckboxes" class="row">
                                @foreach($crops as $crop)
                                <div class="col-md-3 col-sm-4 my-2 crop-checkbox"  data-year="{{ $crop->year }}" data-status="{{ $crop->active }}">
                                        <input class="form-check-input" type="checkbox" name="crops[]" value="{{ $crop->id }}" id="crop_{{ $crop->id }}" />
                                        <label for="crop_{{ $crop->id }}">{{ $crop->identifier }}</label>
                                </div>
                                @endforeach
                            </div>


                        <div class="d-flex justify-content-center">
                            <p class="my-3 text-secondary">Data Export Options</p>
                        </div>
                        <div class="row mt-4">
                            <!-- Include Farm Expenses Checkbox -->
                            <div class="col-md-3">
                                <label>
                                    <input type="checkbox" id="includeFarmExpenses" name="includeFarmExpenses"  class="form-check-input" />
                                    Include Farm Expenses
                                </label>
                            </div>
                        </div>
                        <div class="row mt-4">
                            <div class="row mt-3">
                                <!-- Expense Type Filter -->
                                <div class="col-md-3">
                                    <label class="my-2" for="expenseTypeFilter">Expense Types:</label>
                                    <select id="expenseTypeFilter" name="expenseTypeFilter"  class="form-select">
                                        <option value="all">All</option>

                                    </select>
                                </div>

                                <div class="col-md-3" id="farmTypeFilterMain">
                                    <label class="my-2" for="expenseTypeFilter">Farm Expense Types:</label>
                                    <select id="farmTypeFilter" name="farmTypeFilter" class="form-select">
                                        <option value="all">All</option>

                                    </select>
                                </div>
                            </div>

                            <!-- Expenses Date Range Filter -->
                            <div class="col-md-4 my-3">
                                <label class="my-2" for="from_date">Expenses from:</label>
                                <input type="date" name="from_date" id="from_date" class="form-control" />
                            </div>
                            <div class="col-md-4 my-3 ">
                                <label class="my-2" for="to_date">to:</label>
                                <input type="date" name="to_date" id="to_date" class="form-control" />
                            </div>
                        </div>

                        <div class="d-flex justify-content-center my-3 align-items-center">
                            <button type="submit" class=" mx-2 btn btn-brown">Submit</button>
                        </div>
                    </form>
                    
                    <button type="button" class="btn btn-brown my-3" onclick="handleDownload()">Download</button>

                    @if(count($data) > 0)
                        <div class="d-flex justify-content-center">
                            <div class="table-responsive" style="max-height: 400px; overflow-y: auto; width: 100%;">
                                <table class="table table-hover download-table ">
                                    <thead class="table-dark sticky-top">
                                        <tr>
                                            <th>ID</th>
                                            <th>Type</th>
                                            <th>Crop</th>
                                            <th>Expense Type</th>
                                            <th>Expense SubType</th>
                                            <th>Total</th>
                                            <th>Date</th>
                                            <th>Description</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($data as $row)
                                        @php
                                            $details = json_decode($row['details'], true);
                                            $description = $details['description'] ?? 'N/A';
                                        @endphp
                                        <tr style="height:10px;max-height: 100px;cursor:pointer;"  onclick="handleExpenseDownloadClick({{$row['id']}}, '{{$row['type']}}')">
                                            <td>{{ $row['id'] }}</td>
                                            <td>{{ $row['type'] }}</td>
                                            <td>{{ $row['crop_identifier'] }}</td>
                                            <td>{{ $row['expense_type'] }}</td>
                                            <td>{{ $row['expense_subtype'] }}</td>
                                            <td>{{ $row['total'] }}</td>
                                            <td>{{ \Carbon\Carbon::parse($row['date'])->format('d M Y') }}</td>
                                            <td>{{ $description }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>  
                        </div>
                        @endif



                </div>
            </div>


            <div id="footer">
                @include('components.footer')
            </div>

        </div>
    </div>

</body>

<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('js/data/cropExpenseData.js') }}"></script>
<script src="{{ asset('js/data/farmExpenseData.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>


<script>

function handleExpenseDownloadClick(expense_id, type) {
    console.log(type);
    if (type == 'crop'){
        window.location.href = `/manager/view_cropexpense_details/{{$farm_id}}/${expense_id}`;
    }
    else{
        window.location.href = `/manager/view_farmexpense_details/{{$farm_id}}/${expense_id}`;
    }
}

expenseData = cropExpenseData;

document.getElementById('farmTypeFilterMain').style.display = 'none';


filter = document.getElementById('expenseTypeFilter');
html = ''
expenseData.forEach(data => {
    html += `<option value"${data.head}"=>${data.head}</option>`
});

filter.innerHTML += html;

expenseData = farmExpenseData;

filter = document.getElementById('farmTypeFilter');
html = ''
expenseData.forEach(data => {
    html += `<option value"${data.head}"=>${data.head}</option>`
});

filter.innerHTML += html;


document.getElementById('includeFarmExpenses').addEventListener('change', () => {
    if (!document.getElementById('includeFarmExpenses').checked) {
        document.getElementById('farmTypeFilterMain').style.display = 'none';
    } else {
        document.getElementById('farmTypeFilterMain').style.display = 'block';
    }
})
</script>

<script>
function applyFilters() {
    const selectedStatus = document.getElementById('statusFilter').value;
    const selectedYear = document.getElementById('yearFilter').value;

    const cropCheckboxes = document.querySelectorAll('#cropCheckboxes .crop-checkbox');

    cropCheckboxes.forEach(div => {
        const status = div.getAttribute('data-status');
        const year = div.getAttribute('data-year');

        const statusMatch = (selectedStatus === 'all') ||
            (selectedStatus === 'active' && status === '1') ||
            (selectedStatus === 'passive' && status === '0');

        const yearMatch = (selectedYear === 'all') || (selectedYear === year);

        if (statusMatch && yearMatch) {
            div.style.display = 'block'; // Show the crop
        } else {
            div.style.display = 'none'; // Hide the crop and remove it from the flow
        }
    });
}


// Bind the new unified filter to both dropdowns
document.getElementById('statusFilter').addEventListener('change', applyFilters);
document.getElementById('yearFilter').addEventListener('change', applyFilters);

document.addEventListener('DOMContentLoaded', () => {
    const yearSelect = document.getElementById('yearFilter');
    const currentYear = new Date().getFullYear();
    for (let year = currentYear; year >= 1970; year--) {
        const option = document.createElement('option');
        option.value = year;
        option.text = year;
        yearSelect.appendChild(option);
    }

    applyFilters();
}); 

    function handleDownload () {
        const data = @json($data);

        if (data && data.length > 0) {
            // Convert to CSV
            const csvRows = [];
            const headers = Object.keys(data[0]);
            csvRows.push(headers.join(","));

            for (const row of data) {
                const values = headers.map(header => {
                    let val = row[header] ?? '';
                    // Escape double quotes
                    if (typeof val === 'string') {
                        val = `"${val.replace(/"/g, '""')}"`;
                    }
                    return val;
                });
                csvRows.push(values.join(","));
            }

            const csvContent = csvRows.join("\n");
            const blob = new Blob([csvContent], { type: "text/csv;charset=utf-8;" });
            const url = URL.createObjectURL(blob);

            // Create temporary link and trigger download
            const link = document.createElement("a");
            link.href = url;
            link.download = "expenses_data.csv";
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
    };
</script>


</html>