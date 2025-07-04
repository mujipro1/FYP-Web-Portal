<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Map;
use App\Models\Farm;
use App\Models\CropMap;

class ManagerMapController extends Controller
{
    public function render_map_page($farm_id){

        $map_info = Map::where('farm_id', $farm_id)->get();
        $farm = Farm::find($farm_id);
        $has_deras = $farm->has_deras;
        $no_of_deras = $farm->deras->count();
        
        if($map_info->isEmpty()){
            $map_info = 'EMPTY';
        }
        else{
            $map_info = $map_info->toArray();
            $map_info = $map_info[0]['coords'];
        }

        $cropMaps = CropMap::where('farm_id', $farm_id)->get(); 
        if($cropMaps->isEmpty()){
            $cropMaps = 'EMPTY';
        }
        else{
            // for all crops, get the coords in json format
            $cropMaps = $cropMaps->toArray();
            $cropMaps = array_map(function($cropMap) {
                return json_decode($cropMap['coords'], true);
            }, $cropMaps);
            $cropMaps = json_encode($cropMaps);
        }
        
        return view('manager_maps',['map_info'=>$map_info, 'farm_id'=>$farm_id, 'has_deras'=>$has_deras, 'no_of_deras'=>$no_of_deras, 'cropMaps'=>$cropMaps]);
    }

    public function map_save(Request $request){
        $deraDetails = $request->deraDetails;
      
        // check from Map table if record already exosts, then update, else create new

        $map_info = Map::where('farm_id', $request->farm_id)->get();
        if($map_info->isEmpty()){
            $map = new Map;
            $map->farm_id = $request->farm_id;
            $map->coords = json_encode($deraDetails);
            $map->save();
        }
        else{
            $map = Map::find($map_info[0]->id);
            $map->coords = json_encode($deraDetails);
            $map->save();
        }
        return redirect()->back()->with('success', 'Map saved successfully');

    }
}
