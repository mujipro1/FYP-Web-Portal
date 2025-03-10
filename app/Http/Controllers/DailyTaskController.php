<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Carbon;
use App\Models\Farm;
use App\Models\Crop;
use App\Models\Kleio;
use App\Models\Expense;
use App\Models\FarmExpense;

class DailyTaskController extends Controller
{
    public function executeTask($farm_id)
    {

        $farm = Farm::find($farm_id);
        $crop = Crop::where('farm_id', $farm_id)->get();
        $farmExpense = FarmExpense::where('farm_id', $farm_id)->get();

        $cropData = '';
        foreach ($crop as $c) {
            if ($c->active == '1' && $c->acres > 0) {
                
                $totalExpense = Expense::where('crop_id', $c->id)
                ->sum('total');

                $cropData .= '
                Crop Name: ' . $c->identifier . ', 
                Crop Area: ' . $c->acres . ' acres, 
                Crop Type: ' . $c->type . ', 
                Crop Sowing Date: ' . $c->sowing_date . ', 
                Crop Harvest Date: ' . $c->harvest_date . ',
                Crops Total Expense Till To-date: ' . $totalExpense . ' PKR ,
                Crop Expense Per Acre Normalized: ' . $totalExpense / $c->acres . ' PKR';
            }
        }

        // Fetch farm expenses, filter by farm_id, and group by expense_type
        $farmExpenses = FarmExpense::where('farm_id', $farm_id)
        ->selectRaw('expense_type, SUM(total) as total_expense')
        ->groupBy('expense_type')
        ->get();

        $farmExpenseData = '';
        foreach ($farmExpenses as $expense) {
        $farmExpenseData .= '
        <expense>
            Expense Type: ' . $expense->expense_type . ', 
            Total Amount: ' . $expense->total_expense . ' PKR
        </expense>';
        }

        $queryData = [
            'query' => '
                <farm>
                    Farm Location: '. $farm->address . ',
                    Farm City' . $farm->city . ' ,
                    Farm Area: ' . $farm->number_of_acres. ' acres ,
                </farm>
                <cropsplotted>
                    ' . $cropData . '
                </cropsplotted>
                 <farmexpenses>
                    ' . $farmExpenseData . '
                </farmexpenses>
            ',
        ];

        try {
            $response = Http::withoutVerifying()
                ->timeout(120)
                ->withHeaders([
                    'Accept' => 'application/json'
                ])
                ->post('https://10.3.16.62:443/recommendations', $queryData);
        
            if ($response->successful()) {
                $responseBody = $response->body(); // Get raw response
        
                // Extract recommendation and fun_fact
                preg_match('/<recommendation>(.*?)<\/recommendation>/s', $responseBody, $recommendationMatch);
                preg_match('/<fun_fact>(.*?)<\/fun_fact>/s', $responseBody, $funFactMatch);
        
                $recommendation = $recommendationMatch[1] ?? null;
                $funFact = $funFactMatch[1] ?? null;
        

                if ($recommendation && $funFact) {
                    // Save data into the Kleio table

                    $kleio_data = Kleio::where('farm_id', $farm_id);
                    if ($kleio_data->exists()) {
                        $kleio_data->update([
                            'recommendation' => trim($recommendation),
                            'fun_fact' => trim($funFact),
                            'record_date' => Carbon::today()->toDateString(),
                        ]);
                    } else {
                        Kleio::create([
                            'recommendation' => trim($recommendation),
                            'fun_fact' => trim($funFact),
                            'record_date' => Carbon::today()->toDateString(),
                            'farm_id' => $farm_id,
                        ]);
                    }

                    return response()->json([
                        'message' => 'Task executed and data stored successfully',
                        'api_response' => $response->json(),
                    ]);
                } else {
                    return response()->json([
                        'message' => 'Failed to extract recommendation or fun fact',
                        'api_response' => $response->json(),
                    ], 400);
                }
            } else {
                return response()->json([
                    'message' => 'Failed to fetch recommendations',
                    'error' => $response->body()
                ], $response->status());
            }
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'An error occurred',
                'error' => $e->getMessage()
            ], 500);
        }
    }        
}
