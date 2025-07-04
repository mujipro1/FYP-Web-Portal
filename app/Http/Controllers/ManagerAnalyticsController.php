<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Response;

use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;
use App\Models\Farm;
use App\Models\FarmExpense;
use App\Models\Crop;
use App\Models\Expense;
use Illuminate\Http\Request;

class ManagerAnalyticsController extends Controller
{
    public function analytics(Request $request)
    {  

        $farm_id = $request->input('farm_id');
        $from_date = $request->input('from_date');
        $to_date = $request->input('to_date');

        $farm = Farm::findOrFail($farm_id);
        $crops = $farm->crops->where('active', 1);


        $cropAcres = $crops->pluck('acres')->toArray();
        $cropNames = $crops->pluck('name')->toArray();
        
        // Combine crop names and acres for the labels
        $cropLabels = array_map(function($name, $acres) {
            return "{$name} : {$acres} acres";
        }, $cropNames, $cropAcres);
        
        $chart = LarapexChart::pieChart()
            ->setTitle('Active Crops')
            ->setLabels($cropLabels)  // Labels now show 'Crop Name : Acres acres'
            ->addData($cropAcres);   // Acres data for chart display
        

            
            
            $farm_expenses = FarmExpense::where('farm_id', $farm_id)
            ->whereBetween('date', [$from_date, $to_date])
            ->get();
            
            $expenseData = [];
            foreach ($farm_expenses as $expense) {
                $expenseType = $expense->expense_type;
                $expenseAmount = intval($expense->total);
                
                if (isset($expenseData[$expenseType])) {
                    $expenseData[$expenseType] += $expenseAmount;
                } else {
                    $expenseData[$expenseType] = $expenseAmount;
                }
            }
            $expenseNames = array_keys($expenseData);
            $expenseAmounts = array_values($expenseData);

            
            $chart2 = LarapexChart::PieChart()
            ->setTitle('Farm Expenses')
            ->addData($expenseAmounts)
            ->setLabels($expenseNames);

            if ($expenseData == null){
                $chart2 = [];
            }
            
            
        $expenseTypes = ['Electricity Bills','Labor', 'Machinery Maintenance', 'Fuel', 'Maintenance & Repairs', 'Tubewells', 'Livestock'];
        
        $chartsSubtype = [];
        foreach ($expenseTypes as $expenseType) {
            $chartsSubtype[$expenseType] = $this->generateExpenseSubtypeChart($farm_id, $expenseType, 0, $from_date, $to_date);
        }
        
        
        return view('manager_analytics', ['chart' => $chart, 'farm_id' => $farm_id, 'chart2' => $chart2, 'charts' => $chartsSubtype, 'from_date' => $from_date, 'to_date' => $to_date]);
    } 


    private function generateExpenseSubtypeChart($farm_id, $expenseType, $id, $from_date, $to_date, $crop_id = 0)
{
    if ($id == 0) {
        $expenses = FarmExpense::where('farm_id', $farm_id)
            ->where('expense_type', $expenseType)
            ->whereBetween('date', [$from_date, $to_date])
            ->get();

        $acres = Farm::where('id', $farm_id)->value('number_of_acres');
    } elseif ($id > 0) {
        $expenses = Expense::where('crop_id', $crop_id)
            ->where('expense_type', $expenseType)
            ->get();

        $acres = Crop::where('id', $crop_id)->value('acres');
    } else {
        throw new InvalidArgumentException('Invalid ID value provided.');
    }

    // Initialize the data array for subtypes
    $subtypeData = [];

    foreach ($expenses as $expense) {
        $subtype = $expense->expense_subtype ?? 'Unknown Subtype';
        $expenseAmount = intval($expense->total);

        // Decode the JSON details column to extract quantity
        $details = json_decode($expense->details, true);
        $quantity = (is_array($details) && isset($details['quantity'])) ? intval($details['quantity']) : 0;

        if (!isset($subtypeData[$subtype])) {
            $subtypeData[$subtype] = [
                'amount' => 0,
                'quantity' => 0,
            ];
        }

        $subtypeData[$subtype]['amount'] += $expenseAmount;
        $subtypeData[$subtype]['quantity'] += $quantity;
    }

    $subtypeNames = array_keys($subtypeData);
    $subtypeAmounts = array_column($subtypeData, 'amount');
    $subtypeQuantities = array_column($subtypeData, 'quantity');

    // Calculate per-acre values
    $amountsPerAcre = [];
    $quantitiesPerAcre = [];

    foreach ($subtypeData as $subtype => $data) {
        $amountsPerAcre[] = $acres > 0 ? round($data['amount'] / $acres, 2) : 0;
        $quantitiesPerAcre[] = $acres > 0 ? round($data['quantity'] / $acres, 2) : 0;
    }

    // Original charts
    $chart = LarapexChart::barChart()
        ->setTitle("Expenses for $expenseType")
        ->setDataset([
            [
                'name' => 'Amount',
                'data' => $subtypeAmounts,
            ],
        ])
        ->setXAxis($subtypeNames);

    $chart2 = LarapexChart::barChart()
        ->setTitle("Quantity for $expenseType")
        ->setDataset([
            [
                'name' => 'Quantity',
                'data' => $subtypeQuantities,
            ],
        ])
        ->setXAxis($subtypeNames);

    // New chart: Amount per Acre
    $chart3 = LarapexChart::barChart()
        ->setTitle("Amount per Acre for $expenseType")
        ->setDataset([
            [
                'name' => 'Amount per Acre',
                'data' => $amountsPerAcre,
            ],
        ])
        ->setXAxis($subtypeNames);

    // New chart: Quantity per Acre
    $chart4 = LarapexChart::barChart()
        ->setTitle("Quantity per Acre for $expenseType")
        ->setDataset([
            [
                'name' => 'Quantity per Acre',
                'data' => $quantitiesPerAcre,
            ],
        ])
        ->setXAxis($subtypeNames);

    return [
        'amountChart' => $chart,
        'qChart' => $chart2,
        'amountPerAcreChart' => $chart3,
        'quantityPerAcreChart' => $chart4,
    ];
}


    public function singlecrop($farm_id){
        $farm = Farm::findOrFail($farm_id);
        $crops = $farm->crops;

        return view('manager_singleCrop', ['farm_id' => $farm_id, 'crops' => $crops, 'id'=>0]);
    }   

    public function singlecropPost(Request $req){
        $farm_id = $req->input('farm_id');
        $farm = Farm::findOrFail($farm_id);
        $crops = $farm->crops;
        $crop_id = $req->input('crop');

        $crop_expenses = Expense::where('crop_id', $crop_id)->get();
        $expenseData = [];
        foreach ($crop_expenses as $expense) {
            $expenseType = $expense->expense_type;
            $expenseAmount = intval($expense->total);

            if (isset($expenseData[$expenseType])) {
                $expenseData[$expenseType] += $expenseAmount;
            } else {
                $expenseData[$expenseType] = $expenseAmount;
            }
        }
        $expenseNames = array_keys($expenseData);
        $expenseAmounts = array_values($expenseData);

        $expenseChart = LarapexChart::setType('bar')
            ->setTitle('Crop Expenses')
            ->setXAxis($expenseNames) // Set labels as X-axis
            ->setDataset([
                [
                    'name'  => 'Amount',
                    'data'  => $expenseAmounts
                ]
            ]);

            $crop = $farm->crops->where('id', $crop_id)->first();
            $cropAcres = $crop->acres;
    
            $perAcreExpenseData = [];
            foreach ($expenseData as $type => $amount) {
                $perAcreExpenseData[$type] = $amount / $cropAcres;
                // upto 3
                $perAcreExpenseData[$type] = round($perAcreExpenseData[$type], 3);
            }
            $perAcreExpenseNames = array_keys($perAcreExpenseData);
            $perAcreExpenseAmounts = array_values($perAcreExpenseData);
    
            // Create the second chart (per acre expenses)
            $expenseChartPerAcre = LarapexChart::setType('bar')
                ->setTitle('Per Acre Crop Expenses')
                ->setXAxis($perAcreExpenseNames) // Set labels as X-axis
                ->setDataset([
                    [
                        'name'  => 'Per Acre Amount',
                        'data'  => $perAcreExpenseAmounts
                    ]
                ]);

        $totalExpenses = 0;
        foreach ($expenseAmounts as $amount) {
            $totalExpenses += $amount;
        }


        
        $expenseTypes = ['Labour','Machinery','Fertilizer','Fuel','Electricity Bills','Pesticides', 'Poisons']; 
        
        $chartsSubtype = [];
        foreach ($expenseTypes as $expenseType) {
            $chartsSubtype[$expenseType] = $this->generateExpenseSubtypeChart($farm_id, $expenseType, 1, null , null, $crop_id);

        }

// ---------------------------

        $crop_expenses = Expense::where('crop_id', $crop_id)->get();
        $expenseData = [];
        foreach ($crop_expenses as $expense) {
            $expenseType = $expense->expense_type;
            $expenseAmount = intval($expense->total);

            if (isset($expenseData[$expenseType])) {
                $expenseData[$expenseType] += $expenseAmount;
            } else {
                $expenseData[$expenseType] = $expenseAmount;
            }
        }
        $expenseNames = array_keys($expenseData);
        $expenseAmounts = array_values($expenseData);

        $expenseChart = LarapexChart::setType('bar')
            ->setTitle('Crop Expenses')
            ->setXAxis($expenseNames) // Set labels as X-axis
            ->setDataset([
                [
                    'name'  => 'Amount',
                    'data'  => $expenseAmounts
                ]
            ]);

// ----------------------------

        $quantityKeys = ['quantity', 'quantity_(litres)', 'no_of_units'];
        $quantityChart = $this->fetchCropExpenseQuantities($crop_id, $quantityKeys);

        if ($quantityChart == null) {
            $quantityChart = 'empty';
        }

        return view('manager_singleCrop', ['farm_id' => $farm_id, 'crops' => $crops, 'expenseChart' => $expenseChart,
         'id'=>1, 'expenseChartPerAcre' => $expenseChartPerAcre, 'totalExpenses' => $totalExpenses, 'crop'=>$crop, 'charts' => $chartsSubtype
          , 'quantityChart' => $quantityChart]);

    }

    private function fetchCropExpenseQuantities($crop_id, $quantityKeys)
{
    $crop_expenses = Expense::where('crop_id', $crop_id)->get();
    $quantityData = [];
    $expenseData = [];
    

    foreach ($crop_expenses as $expense) {
        $expenseType = $expense->expense_type;

        // Decode the details JSON object
        $details = json_decode($expense->details, true);
        $quantity = 0;
        

        // Check for quantity keys in the details
        foreach ($quantityKeys as $key) {

            if (isset($details[$key])) {
                $quantity += intval($details[$key]);
            }
        }        
        // Only add to the arrays if there is a quantity
        if ($quantity > 0) {
            // Add quantity
            if (isset($quantityData[$expenseType])) {
                $quantityData[$expenseType] += $quantity;
            } else {
                $quantityData[$expenseType] = $quantity;
            }

            // Add expense amount
            if (isset($expenseData[$expenseType])) {
                $expenseData[$expenseType] += intval($expense->total);
            } else {
                $expenseData[$expenseType] = intval($expense->total);
            }
        }
    }
    

    // Only proceed if there are quantities
    if (!empty($quantityData)) {
        $expenseNames = array_keys($quantityData);
        $quantityAmounts = array_values($quantityData);

        $expenseChart = LarapexChart::barChart()
            ->setTitle('Quantities (Bags, Litres, Units, etc.)')
            ->addData('Quantity', $quantityAmounts)
            ->setXAxis($expenseNames);

        return $expenseChart;
    }

    return null;
}


    public function comparecrop($farm_id){
        $farm = Farm::findOrFail($farm_id);
        $crops = $farm->crops;

        return view('manager_compareCrop', ['farm_id' => $farm_id, 'crops' => $crops, 'id'=>0]);
    }


    public function comparecropPost(Request $req)
    {
        $farm_id = $req->input('farm_id');
        $farm = Farm::findOrFail($farm_id);

        $crop_id1 = $req->input('crop1');
        $crop_id2 = $req->input('crop2');

        if ($crop_id1 == null || $crop_id2 == null) {
            return redirect()->back()->with('error', 'Please select two crops to compare');
        }

        $crops = $farm->crops;
        $crop1 = $farm->crops->where('id', $crop_id1)->first();
        $crop2 = $farm->crops->where('id', $crop_id2)->first();

        $crop1_name = $crop1->identifier;
        $crop2_name = $crop2->identifier;

        $crop1_expenses = Expense::where('crop_id', $crop_id1)->get();
        $crop2_expenses = Expense::where('crop_id', $crop_id2)->get();

        $expenseData1 = $this->calculateExpenses($crop1_expenses);
        $expenseData2 = $this->calculateExpenses($crop2_expenses);

       
        $expenseNames = array_unique(array_merge(array_keys($expenseData1), array_keys($expenseData2)));
        sort($expenseNames); 


        $expenseAmounts1 = $this->getExpenseAmounts($expenseNames, $expenseData1);
        $expenseAmounts2 = $this->getExpenseAmounts($expenseNames, $expenseData2);

        $expenseChart = LarapexChart::setType('bar')
            ->setTitle('Crop Expenses')
            ->setXAxis($expenseNames)
            ->setDataset([
                [
                    'name' => $crop1_name . ' Amount',
                    'data' => $expenseAmounts1
                ],
                [
                    'name' => $crop2_name . ' Amount',
                    'data' => $expenseAmounts2
                ]
            ]);

        // dd($expenseChart);

        $perAcreExpenseData1 = $this->calculatePerAcreExpenses($expenseData1, $crop1->acres);
        $perAcreExpenseData2 = $this->calculatePerAcreExpenses($expenseData2, $crop2->acres);

        $perAcreExpenseAmounts1 = $this->getExpenseAmounts($expenseNames, $perAcreExpenseData1);
        $perAcreExpenseAmounts2 = $this->getExpenseAmounts($expenseNames, $perAcreExpenseData2);

        $expenseChartPerAcre = LarapexChart::setType('bar')
            ->setTitle('Per Acre Crop Expenses')
            ->setXAxis($expenseNames)
            ->setDataset([
                [
                    'name' => $crop1_name . ' Per Acre Amount',
                    'data' => $perAcreExpenseAmounts1
                ],
                [
                    'name' => $crop2_name . ' Per Acre Amount',
                    'data' => $perAcreExpenseAmounts2
                ]
            ]);

        $totalExpenses1 = array_sum($expenseAmounts1);
        $totalExpenses2 = array_sum($expenseAmounts2);

        $SubExpenseCharts = [];
        $expenseTypes = ['Labour','Machinery','Fertilizer','Fuel','Electricity Bills','Pesticides', 'Poisons', 'Seed'];
        foreach ($expenseTypes as $expenseType) {
            $SubExpenseCharts[$expenseType] = $this->generateExpenseSubtypeChartCompare($farm_id, $expenseType, $crop_id1, $crop_id2);
        }

        $quantityKeys = ['quantity', 'quantity_(litres)', 'no_of_units'];
        $quantityChart = $this->fetchCropExpenseQuantitiesCompare($crop_id1, $crop_id2,$quantityKeys, $farm_id);

        return view('manager_compareCrop', [
            'farm_id' => $farm_id,
            'crops' => $crops,
            'expenseChart' => $expenseChart,
            'id' => 1,
            'expenseChartPerAcre' => $expenseChartPerAcre,
            'totalExpenses1' => $totalExpenses1,
            'totalExpenses2' => $totalExpenses2,
            'crop1' => $crop1,
            'crop2' => $crop2,
            'charts' => $SubExpenseCharts,
            'quantityChart' => $quantityChart
        ]);
    }

    private function calculateExpenses($expenses)
    {
        $expenseData = [];
        foreach ($expenses as $expense) {
            $expenseType = $expense->expense_type;
            $expenseAmount = intval($expense->total);

            if (isset($expenseData[$expenseType])) {
                $expenseData[$expenseType] += $expenseAmount;
            } else {
                $expenseData[$expenseType] = $expenseAmount;
            }
        }
        return $expenseData;
    }

    private function calculatePerAcreExpenses($expenseData, $acres)
    {
        $perAcreExpenseData = [];
        foreach ($expenseData as $type => $amount) {
            $perAcreExpenseData[$type] = $amount / $acres;
            // upto 3
            $perAcreExpenseData[$type] = round($perAcreExpenseData[$type], 3);
        }
        return $perAcreExpenseData;
    }

    private function getExpenseAmounts($expenseNames, $expenseData)
    {
        $amounts = [];
        foreach ($expenseNames as $name) {
            $amounts[] = $expenseData[$name] ?? 0;
        }
        return $amounts;
    }


    public function fetchCropExpenseQuantitiesCompare($crop_id1, $crop_id2, $quantityKeys, $farm_id)
    {
        $crop_expenses1 = Expense::where('crop_id', $crop_id1)->get();
        $crop_expenses2 = Expense::where('crop_id', $crop_id2)->get();

        $quantityData1 = [];
        $quantityData2 = [];
        $expenseData1 = [];
        $expenseData2 = [];

        foreach ($crop_expenses1 as $expense) {
            $expenseType = $expense->expense_type;

            // Decode the details JSON object
            $details = json_decode($expense->details, true);
            $quantity = 0;

            // Check for quantity keys in the details
            foreach ($quantityKeys as $key) {
                if (isset($details[$key])) {
                    $quantity += intval($details[$key]);
                }
            }

            // Only add to the arrays if there is a quantity
            if ($quantity > 0) {
                // Add quantity
                if (isset($quantityData1[$expenseType])) {
                    $quantityData1[$expenseType] += $quantity;
                } else {
                    $quantityData1[$expenseType] = $quantity;
                }

                // Add expense amount
                if (isset($expenseData1[$expenseType])) {
                    $expenseData1[$expenseType] += intval($expense->total);
                } else {
                    $expenseData1[$expenseType] = intval($expense->total);
                }
            }
        }

        foreach ($crop_expenses2 as $expense) {
            $expenseType = $expense->expense_type;

            // Decode the details JSON object
            $details = json_decode($expense->details, true);
            $quantity = 0;

            // Check for quantity keys in the details
            foreach ($quantityKeys as $key) {
                if (isset($details[$key])) {
                    $quantity += intval($details[$key]);
                }
            }

            // Only add to the arrays if there is a quantity
            if ($quantity > 0) {
                // Add quantity
                if (isset($quantityData2[$expenseType])) {
                    $quantityData2[$expenseType] += $quantity;
                } else {
                    $quantityData2[$expenseType] = $quantity;
                }

                // Add expense amount
                if (isset($expenseData2[$expenseType]))
                {
                    $expenseData2[$expenseType] += intval($expense->total);
                } else {
                    $expenseData2[$expenseType] = intval($expense->total);
                }

            }
        }

        $crop1_name = Farm::findOrFail($farm_id)->crops->where('id', $crop_id1)->first()->identifier;
        $crop2_name = Farm::findOrFail($farm_id)->crops->where('id', $crop_id2)->first()->identifier;

        // Only proceed if there are quantities
        if (!empty($quantityData1) && !empty($quantityData2)) {
            $expenseNames = array_keys($quantityData1);
            $quantityAmounts1 = array_values($quantityData1);
            $quantityAmounts2 = array_values($quantityData2);

            $expenseChart = LarapexChart::barChart()
                ->setTitle('Quantities (Bags, Litres, Units, etc.)')
                ->addData($crop1_name, $quantityAmounts1)
                ->addData($crop2_name, $quantityAmounts2)
                ->setXAxis($expenseNames);

            return $expenseChart;
        }
    }


    private function generateExpenseSubtypeChartCompare($farm_id, $expenseType, $crop_id1, $crop_id2)
    {
        $expenses1 = Expense::where('crop_id', $crop_id1)
            ->where('expense_type', $expenseType)
            ->get();
    
        $expenses2 = Expense::where('crop_id', $crop_id2)
            ->where('expense_type', $expenseType)
            ->get();
    
        $subtypeData1 = $this->calculateSubtypeData($expenses1);
        $subtypeData2 = $this->calculateSubtypeData($expenses2);
    
        $subtypeQData1 = $this->calculateSubtypeQData($expenses1);
        $subtypeQData2 = $this->calculateSubtypeQData($expenses2);
    
        $crop1 = Farm::findOrFail($farm_id)->crops->where('id', $crop_id1)->first();
        $crop2 = Farm::findOrFail($farm_id)->crops->where('id', $crop_id2)->first();
    
        $crop1_name = $crop1->identifier;
        $crop2_name = $crop2->identifier;
    
        $acres1 = $crop1->acres ?? 1;
        $acres2 = $crop2->acres ?? 1;
    
        $subtypeNames = array_unique(array_merge(array_keys($subtypeData1), array_keys($subtypeData2)));
    
        $subtypeAmounts1 = $this->getExpenseAmounts($subtypeNames, $subtypeData1);
        $subtypeAmounts2 = $this->getExpenseAmounts($subtypeNames, $subtypeData2);
    
        $subtypeQuantity1 = $this->getExpenseAmounts($subtypeNames, $subtypeQData1);
        $subtypeQuantity2 = $this->getExpenseAmounts($subtypeNames, $subtypeQData2);
    
        // Calculate per-acre values
        $amountsPerAcre1 = array_map(fn($amt) => round($acres1 > 0 ? $amt / $acres1 : 0, 2), $subtypeAmounts1);
        $amountsPerAcre2 = array_map(fn($amt) => round($acres2 > 0 ? $amt / $acres2 : 0, 2), $subtypeAmounts2);
    
        $quantitiesPerAcre1 = array_map(fn($qty) => round($acres1 > 0 ? $qty / $acres1 : 0, 2), $subtypeQuantity1);
        $quantitiesPerAcre2 = array_map(fn($qty) => round($acres2 > 0 ? $qty / $acres2 : 0, 2), $subtypeQuantity2);
    
        // Original charts
        $expenseChartAmount = LarapexChart::setType('bar')
            ->setXAxis($subtypeNames)
            ->setDataset([
                [
                    'name' => $crop1_name . ' Expense',
                    'data' => $subtypeAmounts1
                ],
                [
                    'name' => $crop2_name . ' Expense',
                    'data' => $subtypeAmounts2
                ]
            ]);
    
        $expenseChartQ = LarapexChart::setType('bar')
            ->setXAxis($subtypeNames)
            ->setDataset([
                [
                    'name' => $crop1_name . ' Quantity',
                    'data' => $subtypeQuantity1
                ],
                [
                    'name' => $crop2_name . ' Quantity',
                    'data' => $subtypeQuantity2
                ]
            ]);
    
        // New: Amount per Acre Chart
        $amountPerAcreChart = LarapexChart::setType('bar')
            ->setXAxis($subtypeNames)
            ->setDataset([
                [
                    'name' => $crop1_name . ' Amount per Acre',
                    'data' => $amountsPerAcre1
                ],
                [
                    'name' => $crop2_name . ' Amount per Acre',
                    'data' => $amountsPerAcre2
                ]
            ]);
    
        // New: Quantity per Acre Chart
        $quantityPerAcreChart = LarapexChart::setType('bar')
            ->setXAxis($subtypeNames)
            ->setDataset([
                [
                    'name' => $crop1_name . ' Quantity per Acre',
                    'data' => $quantitiesPerAcre1
                ],
                [
                    'name' => $crop2_name . ' Quantity per Acre',
                    'data' => $quantitiesPerAcre2
                ]
            ]);
    
        return [
            'amountChart' => $expenseChartAmount,
            'qChart' => $expenseChartQ,
            'amountPerAcreChart' => $amountPerAcreChart,
            'quantityPerAcreChart' => $quantityPerAcreChart,
        ];
    }
    

    private function calculateSubtypeQData($expenses){
        $subtypeData = [];
        foreach ($expenses as $expense) {
            $subtype = $expense->expense_subtype;
            $details = json_decode($expense->details, true);
            $quantity = (is_array($details) && isset($details['quantity'])) ? intval($details['quantity']) : 0;

            if (isset($subtypeData[$subtype])) {
                $subtypeData[$subtype] += $quantity;
            } else {
                $subtypeData[$subtype] = $quantity;
            }
        }
        return $subtypeData;
    }

    private function calculateSubtypeData($expenses)
    {
        $subtypeData = [];
        foreach ($expenses as $expense) {
            $subtype = $expense->expense_subtype;
            $expenseAmount = intval($expense->total);

            if (isset($subtypeData[$subtype])) {
                $subtypeData[$subtype] += $expenseAmount;
            } else {
                $subtypeData[$subtype] = $expenseAmount;
            }
        }
        return $subtypeData;
    }
    
    public function exportCsvCompare($crop_id1, $crop_id2)
{
    $crop1 = Crop::find($crop_id1);
    $crop2 = Crop::find($crop_id2);

    if (!$crop1 || !$crop2) {
        return response()->json(['error' => 'One or both crops not found'], 404);
    }

    $expenses1 = Expense::where('crop_id', $crop_id1)->get();
    $expenses2 = Expense::where('crop_id', $crop_id2)->get();

    if ($expenses1->isEmpty() && $expenses2->isEmpty()) {
        return response()->json(['error' => 'No expenses found for either crop'], 404);
    }

    $total1 = $expenses1->sum('total');
    $total2 = $expenses2->sum('total');

    $count1 = $expenses1->count();
    $count2 = $expenses2->count();

    $perAcre1 = $crop1->acres > 0 ? $total1 / $crop1->acres : 0;
    $perAcre2 = $crop2->acres > 0 ? $total2 / $crop2->acres : 0;

    // Group and map data
    $groupData = function ($expenses) {
        return $expenses->groupBy('expense_type')->map(function ($group) {
            return [
                'total' => $group->sum('total'),
                'subtypes' => $group->groupBy('expense_subtype')->map(function ($subgroup) {
                    return [
                        'amount' => $subgroup->sum('total'),
                        'quantity' => $subgroup->sum(function ($expense) {
                            $details = json_decode($expense->details, true);
                            return $details['quantity'] ?? 0;
                        }),
                    ];
                }),
            ];
        });
    };

    $data1 = $groupData($expenses1);
    $data2 = $groupData($expenses2);

    $allTypes = array_unique(array_merge($data1->keys()->toArray(), $data2->keys()->toArray()));

    $csvData = [
        ['Crop Comparison'],
        ['Crop Name', $crop1->identifier, $crop2->identifier],
        ['Total Expense', $total1, $total2],
        ['Number of Expenses', $count1, $count2],
        ['Per Acre Expense', $perAcre1, $perAcre2],
        [''],
        ['Expense Type', 'Subtype', $crop1->identifier . ' Amount', $crop2->identifier . ' Amount', $crop1->identifier . ' Quantity', $crop2->identifier . ' Quantity'],
    ];

    foreach ($allTypes as $type) {
        $subtypes1 = $data1[$type]['subtypes'] ?? collect();
        $subtypes2 = $data2[$type]['subtypes'] ?? collect();
        $allSubtypes = array_unique(array_merge(array_keys($subtypes1->toArray()), array_keys($subtypes2->toArray())));

        foreach ($allSubtypes as $subtype) {
            $row = [
                $type,
                $subtype ?? 'No Subtype Found',
                $subtypes1[$subtype]['amount'] ?? 0,
                $subtypes2[$subtype]['amount'] ?? 0,
                $subtypes1[$subtype]['quantity'] ?? 0,
                $subtypes2[$subtype]['quantity'] ?? 0,
            ];
            $csvData[] = $row;
        }

        $csvData[] = ['', 'Total for ' . $type, $data1[$type]['total'] ?? 0, $data2[$type]['total'] ?? 0, '', ''];
        $csvData[] = ['']; // spacer
    }

    // Generate and return CSV
    $filename = 'Comparison_' . $crop1->identifier . '_vs_' . $crop2->identifier . '.csv';
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $filename . '"',
    ];

    return Response::stream(function () use ($csvData) {
        $file = fopen('php://output', 'w');
        foreach ($csvData as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
    }, 200, $headers);
}

    public function exportCsv($crop_id)
{
    $crop = Crop::where('id', $crop_id)->first();

    if (!$crop) {
        return response()->json(['error' => 'Crop not found'], 404);
    }

    $crop_name = $crop->identifier;
    $acres = $crop->acres;
    $expenses = Expense::where('crop_id', $crop_id)->get();

    if ($expenses->isEmpty()) {
        return response()->json(['error' => 'No expenses found for the crop'], 404);
    }

    $total_expense_amount = $expenses->sum('total');
    $number_of_expenses = $expenses->count();
    $per_acre_expense = $acres > 0 ? $total_expense_amount / $acres : 0;

    // Group by expense type and then by subtype
    $type_and_subtype_expenses = $expenses->groupBy('expense_type')->map(function ($group) {
        return [
            'total' => $group->sum('total'),
            'subtypes' => $group->groupBy('expense_subtype')->map(function ($subgroup) {
                return $subgroup->sum('total');
            }),
        ];
    });

    // Prepare CSV data
    $data = [
        ['Crop Name', 'Total Expense', 'Number of Expenses', 'Per Acre Expense'],
        [$crop_name, $total_expense_amount, $number_of_expenses, $per_acre_expense],
        [''], // Spacer row
        ['Expense Type', 'Subtype', 'Total', 'Quantity'], // Header for expense details
    ];

    foreach ($type_and_subtype_expenses as $type => $details) {
        foreach ($details['subtypes'] as $subtype => $amount) {
            $quantities = $expenses
                ->where('expense_type', $type)
                ->where('expense_subtype', $subtype)
                ->sum(function ($expense) {
                    $details = json_decode($expense->details, true);
                    return $details['quantity'] ?? 0;
                });

            $data[] = [
                $type, 
                $subtype ? $subtype : "No Subtype Found", 
                $amount, 
                $quantities
            ]; // Add subtype details
        }
        $data[] = ['', 'Total', $details['total'], '']; // Add total for the type
        $data[] = ['']; // Spacer row between types
    }

    // Generate and return CSV
    $headers = [
        'Content-Type' => 'text/csv',
        'Content-Disposition' => 'attachment; filename="' . $crop_name . '.csv"',
    ];

    return Response::stream(function () use ($data) {
        $file = fopen('php://output', 'w');
        foreach ($data as $row) {
            fputcsv($file, $row);
        }
        fclose($file);
    }, 200, $headers);
}

}    