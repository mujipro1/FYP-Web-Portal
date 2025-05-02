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
                                                    <option value="{{ $crop->id }}|{{ $crop->name }}">{{ $crop->identifier }}
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

                                <div class="my-5 mx-4 text-light">
                                    <div class="row">
                                        <div class="col-md-3">Expenses For <script>document.write(new Date().toLocaleString('default', { month: 'long', year : 'numeric' }));</script></div>
                                        <div class="col-md-4">Rs. {{ $totalExpenseMonthly }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">Average Expenses For <script>document.write(new Date().toLocaleString('default', { month: 'long' }));</script></div>
                                        <div class="col-md-4">Rs. {{ $monthly_historic_average }}</div>
                                    </div>
                                    <div class="row mt-5">
                                        <div class="col-md-3">Expenses This Year</div>
                                        <div class="col-md-4">Rs. {{ $totalExpenseYearly }}</div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-3">Average Expenses Yearly</div>
                                        <div class="col-md-4">Rs. {{ $yearly_historic_average }}</div>
                                    </div>
                                </div>

                                <div class="my-5">
                                    <div class="d-flex flex-column text-light justify-content-center">
                                        <h3 class="text-light">Smart Spend Insights</h3>
                                            <div id="smart-spend-insights-glass" class="p-4">
                                                <div id="smart-spend-insights"></div>
                                            </div>
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

totalExpenseThisMonth = @json($totalExpenseMonthly);
totalExpenseThisYear = @json($totalExpenseYearly);

const canvas = document.getElementById('cost-saver-chart');
if (canvas) {
    const ctx = canvas.getContext('2d');
    const chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: [],
            datasets: [
                {
                    label: 'Historic Data',
                    data: [],
                    backgroundColor: 'rgba(255, 255, 255, 0.2)',
                    borderColor: 'rgba(75, 192, 192, 1)',
                    borderWidth: 1,
                    fill: true,
                },
                {
                    label: 'This Year',
                    data: [],
                    borderDash: [5, 5],
                    spanGaps: true,
                    backgroundColor: 'rgba(255, 99, 132, 0.2)', // Different color for this year's data
                    borderColor: 'rgba(255, 99, 132, 1)',
                    borderWidth: 2,
                    fill: true,

                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    display: true,
                    labels: {
                        color: 'white'  // ✅ Changes color of legend labels
                    }
                },
                title: {
                    display: true,
                    text: 'Smart Spend: Expense Comparison Over Years for ' + new Date().toLocaleString('default', { month: 'long' }) ,
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

        // Calculate this month's percentage of the current year's total expense
        const currentYear = new Date().getFullYear();
        const thisMonthPercentage = totalExpenseThisYear > 0 ? (totalExpenseThisMonth / totalExpenseThisYear) : 0;

        // Add the current year and this month's percentage to the graph
        if (!years.includes(currentYear)) {
    years.push(currentYear);
    percentages.push(null); // No historic data for this year
}

// 1. Find last non-null historic point
let lastHistoricIndex = percentages.length - 2; // Before the added current year
while (lastHistoricIndex >= 0 && (percentages[lastHistoricIndex] === null || percentages[lastHistoricIndex] === undefined)) {
    lastHistoricIndex--;
}

let lastHistoricValue = (lastHistoricIndex >= 0) ? percentages[lastHistoricIndex] : 0;

// 2. Build current year data: first point is last historic, second is this month's percentage
const currentYearData = percentages.map((_, index) => {
    if (index === lastHistoricIndex) return lastHistoricValue;
    if (index === lastHistoricIndex + 1) return thisMonthPercentage;
    return null;
});

// 3. Set data to chart
chart.data.labels = years;
chart.data.datasets[0].data = percentages; // Historic data
chart.data.datasets[1].data = currentYearData; // This year's line
chart.update();

    }
}

document.addEventListener('DOMContentLoaded', function() {
    // Show different texts every 3 seconds in the smart spend div
    const smartSpendDiv = document.getElementById('smart-spend-insights');
    const messages = [
        "Gathering Data...",
        "Analyzing your expenses...",
        "Generating insights...",
        "Please wait...",
        "Fetching results...",
    ];
    let index = 0;

    // Start the interval to show messages
    const intervalId = setInterval(() => {
        smartSpendDiv.innerHTML = messages[index];
        index = (index + 1) % messages.length;
    }, 2000);

    const fetchResponse = async (userMessage) => {
        try {
            const response = await fetch('https://10.3.16.71:443/smart-spend', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({ query: userMessage }),
            });

            if (!response.ok) {
                throw new Error(`HTTP error! Status: ${response.status}`);
            }

            const data = await response.json();
            return data.response; // Ensure the backend returns a JSON object with a "response" key
        } catch (err) {
            console.error('Fetch Error:', err); // Improved error logging
            return `Sorry, something went wrong. Error: ${err.message}`;
        }
    };

    data = {
        'yearly_historic_average': 'PKR {{ $yearly_historic_average }}',
        'total_expense_this_month': 'PKR {{ $totalExpenseMonthly }}',
        'total_expense_this_year': 'PKR {{ $totalExpenseYearly }}',
        'monthly_historic_average': 'PKR {{ $monthly_historic_average }}',
        'crop': '{{ $crop->name }}',
        'expense_type': '{{ $selectedExpense }}',
        'expense_subtype': '{{ $selectedSubtype }}',
    };

    fetchResponse(data).then(response => {
        // Stop the interval once data is fetched
        clearInterval(intervalId);

        // Display the fetched data
        const insightsDiv = document.getElementById('smart-spend-insights');
        // render markdown if coming
        insightsDiv.innerHTML = response.replace(/\\n/g, '<br>');
        insightsDiv.innerHTML = insightsDiv.innerHTML.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        
    }).catch(err => {
        console.error('Error fetching insights:', err);

        // Stop the interval even if there's an error
        clearInterval(intervalId);

        // Display an error message
        const insightsDiv = document.getElementById('smart-spend-insights');
        insightsDiv.innerHTML = "Failed to fetch insights. Please try again later.";
    });
});
</script>
@endif
</html>