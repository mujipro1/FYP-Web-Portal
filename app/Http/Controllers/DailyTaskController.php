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
    public function executeTask(Request $req)
    {


            
        $data = $req->validate([
            'farm_id' => 'required|integer',
            'data' => 'required|array',
        ]);                
        if ($req->has('farm_id')) {
            $farm_id = $req->farm_id;

            $data = $req->data;
        
                // Extract recommendation and fun_fact
                preg_match('/<recommendation>(.*?)<\/recommendation>/s', $data, $recommendationMatch);
                preg_match('/<fun_fact>(.*?)<\/fun_fact>/s', $data, $funFactMatch);
        
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
    }        
}
