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
use Illuminate\Support\Str;

class DailyTaskController extends Controller
{
    public function executeTask(Request $req)
{
    $data = $req->validate([
        'farm_id' => 'required|integer',
        'data' => 'required', // Accept both string and array
    ]);                

    if ($req->has('farm_id')) {
        $farm_id = $req->farm_id;

        // Ensure data is a string before decoding
        $data = is_string($req->data) ? json_decode($req->data, true) : $req->data;

        // Convert to string if it's an array
        $dataString = is_array($data) ? implode('', $data) : $data; 

        $recommendation = Str::between($dataString, '<recommendation>', '</recommendation>') ?? 'Recommendation not found';
        $funFact = Str::between($dataString, '<fun_fact>', '</fun_fact>') ?? 'Fun fact not found';

        if ($recommendation && $funFact) {
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
            ]);
        } else {
            \Log::error("Failed to extract recommendation or fun fact.", [
                'dataString' => $dataString,
                'recommendationMatch' => $recommendationMatch,
                'funFactMatch' => $funFactMatch
            ]);

            return response()->json([
                'message' => 'Failed to extract recommendation or fun fact',
            ], 400);
        }
    }

    return response()->json([
        'message' => 'Missing farm_id in request',
    ], 400);
}


      
}
