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
            <div class="row section">
                <div class="mt-3 sidebarcol">
                    <div class="ManagerSidebar sidebar"></div>
                </div>
                <div class="overlay" id="overlay"></div>
                <div class="col-md-11 offset-md-1" style='padding-right:50px;'>


                    <div class="col-md-11 ">
                        <div class="cost-saver">
                            <div class="cost-save-inner">
                                <div class="d-flex text-white flex-column justify-content-center">
                                    <h3>Smart Spend</h3>
                                    <p style="opacity:.7" class="w-75 text-center">Know your spending before it grows,
                                        compare this season’s expenses with past years and make smarter farming
                                        decisions</p>
                                </div>

                                <!-- three dropdowns input boxes for Crop, expense type and subtype -->
                                <form action="{{ route('manager.costsaverPost') }}" method="POST" id="cost-saver-form">
                                    <div class="row mt-4">
                                        @csrf
                                        <input name = "farm_id" id="farm_id" type="hidden"
                                            value="{{ $farm_id }}">
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="crop" class="text-white py-2">Crop</label>
                                                <select class="form-control" id="crop" name="crop">
                                                    <option disabled selected value="">Select Crop</option>
                                                    @foreach($crops as $crop)
                                                    <option value="{{ $crop->name }}">{{ $crop->identifier }}
                                                        ({{$crop->variety}})</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="crop" class="text-white py-2">Expense Type</label>
                                                <select class="form-control" id="cost-saver-expense"
                                                    name="cost-saver-expense">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-md-3">
                                            <div class="form-group">
                                                <label for="crop" class="text-white py-2">Expense Subtype</label>
                                                <select class="form-control" id="cost-saver-subtype"
                                                    name="cost-saver-subtype">
                                                </select>
                                            </div>
                                        </div>

                                        <div class="d-flex my-3 justify-content-center">
                                            <button class="btn btn-success or-width mt-4" id="cost-saver-btn">Get
                                                Data</button>
                                        </div>

                                    </div>
                                </form>
                                
                                
                            <!-- CHECK IF variable filtered exists from backend -->
                            @if(isset($filtered) && $filtered == true)
                            <div class="col-md-12 mt-4">
                                <div class="row">
                                    <div class="col-md-12">
                                        <canvas id="cost-saver-chart" width="400" height="400"></canvas>
                                    </div>
                                </div>
                            </div>
                            @endif

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
<script src="{{ asset('js/data/cropExpenseData.js') }}"></script>
<script>
cropExpenseData = cropExpenseData;
</script>
<script src="{{ asset('js/ManagerSidebar.js') }}"></script>
<script src="{{ asset('js/costSaver.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.js') }}"></script>
<script src="{{ asset('bootstrap/bootstrap.bundle.min.js') }}"></script>
<script>
</script>
<!-- /chart -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1"></script>

<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-colorschemes@0"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-annotation@1"></script>

@if (isset($filtered) && $filtered == true)
<script>
const canvas = document.getElementById('cost-saver-chart');
if (canvas) {
    const ctx = canvas.getContext('2d');
    const chart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: [],
        datasets: [{
            label: 'Percentage',
            data: [],
            backgroundColor: 'rgba(255, 255, 255, 0.2)',
            borderColor: 'rgba(75, 192, 192, 1)',
            borderWidth: 1,
            fill: true,
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                display: true,
                labels: {
                    color: 'white'  // ✅ Changes color of "Percentage" label in legend
                }
            },
            title: {
                display: true,
                text: 'Smart Spend: Expense Comparison Over Years', // 📝 Set your custom title
                color: 'white',
                font: {
                    size: 18
                }
            },
            tooltip: {
                callbacks: {
                    label: function(context) {
                        return `${(context.parsed.y * 100).toFixed(1)}%`;
                    }
                }
            }
        },
        scales: {
            x: {
                ticks: {
                    color: 'white'
                },
                title: {
                    display: true,
                    text: 'Year',
                    color: 'white'
                }
            },
            y: {
                min: 0,
                max: 1,
                ticks: {
                    color: 'white',
                    callback: function(value) {
                        return (value * 100) + '%';
                    },
                    stepSize: 0.1
                },
                title: {
                    display: true,
                    text: 'Expense % Compared to Yearly Total',
                    color: 'white'
                }
            }
        }
    }
});


    const filtereddata = @json($filtered);
    if (filtereddata) {
        const years = filtereddata.map(item => item.year).sort((a, b) => a - b);
        const percentages = years.map(year => {
            const item = filtereddata.find(data => data.year === year);
            return item ? parseFloat(item.percentage) : null;
        });

        chart.data.labels = years;
        

        chart.data.datasets[0].data = percentages;
        chart.update();
    }
}

// set the crops and subtypes in the dropdowns

const cropSelect = document.getElementById('crop');
const expenseSelect = document.getElementById('cost-saver-expense');
const subtypeSelect = document.getElementById('cost-saver-subtype');

crop = @json($selectedCrop);
expense = @json($selectedExpense);
subtype = @json($selectedSubtype);
console.log(crop, expense, subtype);

if (crop) {
    cropSelect.value = crop;
}
if (expense) {
    expenseSelect.value = expense;
    console.log(expense);
}
if (subtype) {
    subtypeSelect.value = subtype;
}

</script>

@endif


</html>