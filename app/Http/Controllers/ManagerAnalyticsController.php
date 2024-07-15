<?php

namespace App\Http\Controllers;

use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;
use App\Models\Farm;
use App\Models\FarmExpense;
use App\Models\Expense;
use Illuminate\Http\Request;

class ManagerAnalyticsController extends Controller
{
    public function analytics($farm_id)
    {    
    
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

  
    return view('manager_analytics', ['chart' => $chart, 'farm_id' => $farm_id, 'chart2' => $chart2]);
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
            ->setHorizontal(true) // Set the chart to horizontal
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
            }
            $perAcreExpenseNames = array_keys($perAcreExpenseData);
            $perAcreExpenseAmounts = array_values($perAcreExpenseData);
    
            // Create the second chart (per acre expenses)
            $expenseChartPerAcre = LarapexChart::setType('bar')
                ->setTitle('Per Acre Crop Expenses')
                ->setXAxis($perAcreExpenseNames) // Set labels as X-axis
                ->setHorizontal(true) // Set the chart to horizontal
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

        return view('manager_singlecrop', ['farm_id' => $farm_id, 'crops' => $crops, 'expenseChart' => $expenseChart,
         'id'=>1, 'expenseChartPerAcre' => $expenseChartPerAcre, 'totalExpenses' => $totalExpenses, 'crop'=>$crop]);

    }

    public function comparecrop($farm_id){
        $farm = Farm::findOrFail($farm_id);
        $crops = $farm->crops;

        return view('manager_comparecrop', ['farm_id' => $farm_id, 'crops' => $crops, 'id'=>0]);
    }
    
}
