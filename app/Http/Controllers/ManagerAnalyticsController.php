<?php

namespace App\Http\Controllers;

use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;
use App\Models\Farm;
use App\Models\FarmExpense;
use App\Models\Expense;
use Illuminate\Http\Request;

class ManagerAnalyticsController extends Controller
{
    public function analytics(Request $request)
    {  

        $farm_id = $request->input('farm_id');

    
        $farm = Farm::findOrFail($farm_id);
        $crops = $farm->crops->where('active', 1);
    
        // Prepare crop data for the chart
        $cropNames = $crops->pluck('name')->toArray();
        $cropAcres = $crops->pluck('acres')->toArray();       

        $chart = LarapexChart::setType('bar')
            ->setTitle('Active Crops')
            ->setXAxis($cropNames)

            ->setDataset([
                [
                    'name'  => 'Acres',
                    'data'  => $cropAcres
                ]
            ]);


            $farm_expenses = FarmExpense::where('farm_id', $farm_id)->get();
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


        $expenseTypes = ['Electricity Bills','Labor', 'Machinery Maintenance', 'Fuel', 'Maintenance & Repairs', 'Tubewells', 'Livestock'];
        
        $chartsSubtype = [];
        foreach ($expenseTypes as $expenseType) {
            $chartsSubtype[$expenseType] = $this->generateExpenseSubtypeChart($farm_id, $expenseType, 0);
        }
                

  
    return view('manager_analytics', ['chart' => $chart, 'farm_id' => $farm_id, 'chart2' => $chart2, 'charts' => $chartsSubtype]);
} 


    private function generateExpenseSubtypeChart($farm_id, $expenseType, $id, $crop_id = 0)
    {
        if ($id == 0){
            $expenses = FarmExpense::where('farm_id', $farm_id)
            ->where('expense_type', $expenseType)
            ->get();
        } else {
            $expenses = Expense::where('crop_id', $crop_id)
            ->where('expense_type', $expenseType)
            ->get();
        }

        
        // Initialize the data array for subtypes
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
        
        $subtypeNames = array_keys($subtypeData);

        $subtypeAmounts = array_values($subtypeData);

        // Create the bar chart for subtypes
        $chart = LarapexChart::barChart()
            ->setTitle("Expenses for $expenseType")
            ->addData('Amount', $subtypeAmounts)
            ->setXAxis($subtypeNames);
        return $chart;
    }

    public function singlecrop($farm_id){
        $farm = Farm::findOrFail($farm_id);
        $crops = $farm->crops;

        return view('manager_singlecrop', ['farm_id' => $farm_id, 'crops' => $crops, 'id'=>0]);
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
            $chartsSubtype[$expenseType] = $this->generateExpenseSubtypeChart($farm_id, $expenseType, 1, $crop_id);
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

             
        return view('manager_singlecrop', ['farm_id' => $farm_id, 'crops' => $crops, 'expenseChart' => $expenseChart,
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
            ->setTitle('Crop Quantities')
            ->addData('Quantity', $quantityAmounts)
            ->setXAxis($expenseNames);

        return $expenseChart;
    }

    return null;
}


    public function comparecrop($farm_id){
        $farm = Farm::findOrFail($farm_id);
        $crops = $farm->crops;

        return view('manager_comparecrop', ['farm_id' => $farm_id, 'crops' => $crops, 'id'=>0]);
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
        $expenseTypes = ['Labour','Machinery','Fertilizer','Fuel','Electricity Bills','Pesticides', 'Poisons'];
        foreach ($expenseTypes as $expenseType) {
            $SubExpenseCharts[$expenseType] = $this->generateExpenseSubtypeChartCompare($farm_id, $expenseType, $crop_id1, $crop_id2);
        }



        $quantityKeys = ['quantity', 'quantity_(litres)', 'no_of_units'];
        $quantityChart = $this->fetchCropExpenseQuantitiesCompare($crop_id1, $crop_id2,$quantityKeys, $farm_id);

        return view('manager_comparecrop', [
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
                ->setTitle('Crop Quantities')
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

        $crop1 = Farm::findOrFail($farm_id)->crops->where('id', $crop_id1)->first();
        $crop2 = Farm::findOrFail($farm_id)->crops->where('id', $crop_id2)->first();

        $crop1_name = $crop1->identifier;
        $crop2_name = $crop2->identifier;

        $subtypeNames = array_unique(array_merge(array_keys($subtypeData1), array_keys($subtypeData2)));
        $subtypeAmounts1 = $this->getExpenseAmounts($subtypeNames, $subtypeData1);

        $subtypeAmounts2 = $this->getExpenseAmounts($subtypeNames, $subtypeData2);
        
        $expenseChartPerAcre = LarapexChart::setType('bar')
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
        return $expenseChartPerAcre;
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
    
}
