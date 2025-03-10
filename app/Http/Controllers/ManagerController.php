<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;
use Session;

use App\Models\Farm;
use App\Models\User;
use App\Models\CropDera;
use App\Models\Crop;
use App\Models\Map;
use App\Models\CropMap;
use App\Models\Dera;
use App\Models\Expense;
use App\Models\FarmExpense;
use App\Models\ExpenseConfiguration;
use App\Models\FarmWorker;
use App\Models\Reconciliation;
use App\Models\Kleio;
use Jenssegers\Agent\Agent;

class ManagerController extends Controller
{

    private function route_security($farm_id){
        $login_user = Session::get('manager');
        $farm = Farm::find($farm_id);
        if (!$farm || $farm->user_id !== $login_user->id || !$login_user) {
            return redirect()->back()->with('error', "You do not have access to the requested page");
        }
        return null;
    }


    public function render_farms_page()
    {       
        $user = Session::get('manager');
        $farms = Farm::where('user_id', $user->id)->get();
        
        return view('manager_farms', ['farms' => $farms]);
    }

    public function render_configuration_page($farm_id)
    {      
        $security_check = $this->route_security($farm_id);
        if ($security_check) {return $security_check;}
        return view('manager_configuration', ['farm_id' => $farm_id]);    
    }

    public function render_get_farm_details_page($farm_id)
    {

        $security_check = $this->route_security($farm_id);
        if ($security_check) {return $security_check;}
        
        $farm = Farm::with(['crops.deras'])->find($farm_id);
        $workers = FarmWorker::where('farm_id', $farm_id)->get();
        $users = User::whereIn('id', $workers->pluck('user_id'))->get();

        $map_info = Map::where('farm_id', $farm_id)->get();
        if ($map_info->isEmpty()){
            $map_info = 'EMPTY';
        }
        else{
            $map_info = json_decode($map_info->first()->coords);
        }
        $agent = new Agent();

        $kleio_data = Kleio::where('farm_id', $farm_id)->first();
        if ($kleio_data == null){
            $kleio_data = Kleio::where('farm_id', 0)->first();
        }

        if ($agent->isMobile()) {
            return view('manager_farmDetailsMobile', ['farm' => $farm, 'workers' => $users, 'map_info'=>$map_info]);
        } else {
            return view('manager_farmDetails', ['farm' => $farm, 'workers' => $users, 'map_info'=>$map_info, 'kleio_data' => $kleio_data]);
        }


    }


    public function configurationForm_submit(Request $request)
    {
        $farm_id = $request->input('farm_id');
        $security_check = $this->route_security($farm_id);
        if ($security_check) {return $security_check;}
        $cropsData = json_decode($request->input('cropDetails'), true);
        
        foreach ($cropsData as $cropData) {
            
            $crop_identifier = $cropData['name'] . " " . $cropData['year'];
            $cropData['name'] = str_replace(' ', '', $cropData['name']);

            if ($cropData['name'] == 'Sugarcane') {
                $month = date('M', strtotime($cropData['sowingDate']));
                $cropyear = substr($cropData['year'], -2);
                $crop_identifier = "{$cropData['name']} $month $cropyear";
            
                $sugarcane_id = '';
                
                if ($cropData['sameSeed'] == 1) {
                    $prevCropId = $cropData['sugarcanePreviousCrop'];
                    
                    // Find the root parent crop
                    $rootParentId = $prevCropId;
                    $crop = Crop::find($prevCropId);
                    
                    while ($crop && $crop->sugarcane_id) {
                        list($parentId) = explode('-', $crop->sugarcane_id);
                        $rootParentId = $parentId;
                        $crop = Crop::find($parentId);
                    }
            
                    // If no root parent found, use the original prevCropId
                    $rootParentId = $rootParentId ?: $prevCropId;
            
                    // Find the highest version for this parent
                    $highestVersion = Crop::where('farm_id', $farm_id)
                        ->where('name', 'Sugarcane')
                        ->where(function($query) use ($rootParentId) {
                            $query->where('sugarcane_id', 'like', $rootParentId . '-%')
                                  ->orWhere('id', $rootParentId);
                        })
                        ->max(DB::raw('COALESCE(CAST(SUBSTRING_INDEX(sugarcane_id, "-", -1) AS UNSIGNED), 0)'));
            
                    $version = $highestVersion + 1;
                    $sugarcane_id = "$rootParentId-$version";
                }
            }
            
            
            

            $crop = new Crop();
            $crop->name = $cropData['name'];
            $crop->year = $cropData['year'];
            $crop->variety = $cropData['variety'];
            $crop->farm_id = $farm_id;
            $crop->acres = $cropData['acres'];
            $crop->identifier = $crop_identifier;
            $crop->sow_date = $cropData['sowingDate'];
            if ($cropData['harvestDate'] != null){
                $crop->harvest_date = $cropData['harvestDate'];
            }
            $crop->active = $cropData['status'];
            $crop->description = $cropData['desc'];

            if ($cropData['name'] == 'Sugarcane'){
                $crop->sugarcane_id = $sugarcane_id;
            }

            $crop->save();

            if ($cropData['polygonData'] != null){
                $cropmap = new CropMap();
                $cropmap->farm_id = $farm_id;
                $cropmap->crop_id = $crop->id;
                $cropmap->coords = $cropData['polygonData'];
                $cropmap->save();
            }

            
            foreach ($cropData['deras'] as $deraData) {
                // Check if the dera already exists
                $dera = Dera::where('name', $deraData['name'])->first();
    
                if (!$dera) {
                    // If dera doesn't exist, create it
                    $dera = Dera::create([
                        'name' => $deraData['name'],
                        'acres' => $deraData['acres'],
                    ]);
                }
                $crop->deras()->attach($dera->id, ['acres' => $deraData['acres']]);
            }
        }

        return redirect()->route('manager.farmdetails', ['farm_id' => $farm_id]);
    }

    
    public function getDerasForCrop($crop_id)
    {
        $crop = Crop::with('deras')->find($crop_id);
        if ($crop) {
            return response()->json($crop->deras);
        }
        return response()->json([]);
    }
    public function getAllDeras($farmId)
    {
        // Fetch all Deras for the given farm ID
        $deras = Dera::where('farm_id', $farmId)->get();

        return response()->json($deras);
    }

    public function reconciliationHistory($farm_id){

        // get all reconciliation data of the specific farm's manager and workers associated
        $workers = FarmWorker::where('farm_id', $farm_id);
        $manager = Farm::find($farm_id)->user_id;

        $reconciles = Reconciliation::whereIn('user_id', $workers->pluck('user_id'))
            ->orWhere('user_id', $manager)
            ->orderBy('created_at', 'desc')
            ->get();
        
        return view('manager_reconciliationHistory', ['farm_id' => $farm_id, 'worker'=>0, 'reconciles'=>$reconciles]);
    }

    public function add_cash(Request $request){
        $farm_id = $request->input('farm_id');
        $cash = $request->input('cash');
        $worker_id = $request->input('workerSelect');
        
        $worker = FarmWorker::where('id', $worker_id)->first();
        $worker->wallet = $worker->wallet + $cash;
        $worker->save();
        
        $reconcile = new Reconciliation();
        $reconcile->user_id = $worker->user_id;
        $reconcile->amount =   $cash;
        $reconcile->spent = 0;
        $reconcile->date = $request->input('date');
        $reconcile->save();
        
        $worker = Session::get('worker');
        return redirect()->route('manager.reconciliation', ['farm_id' => $farm_id, 'worker' => $worker])->with('success', 'Cash added successfully');
    }

    public function addCrop($farm_id){
        $security_check = $this->route_security($farm_id);
        if ($security_check) {return $security_check;}
        
        $farm = Farm::with('deras')->find($farm_id);
        $deras = $farm->deras->pluck('name');
        return view('manager_addCrops', ['farm' => $farm, 'deras' => $deras, 'farm_id' => $farm_id]);
    }

    public function editDeras($farm_id){

        $security_check = $this->route_security($farm_id);
           if ($security_check) {return $security_check;}
        $farm = Farm::with('deras')->find($farm_id);
        $deras = $farm->deras;

        return view('manager_editDeras', ['farm' => $farm, 'deras' => $deras, 'farm_id' => $farm_id]);
    }

    public function editCrops($farm_id){
        $security_check = $this->route_security($farm_id);
           if ($security_check) {return $security_check;}
        $farm = Farm::with('crops')->find($farm_id);
        $crops = $farm->crops;
        if (count($crops) == 0){
            return redirect()->back()->with('error', 'No crops in your farm!');
        }

        return view('manager_editCrops', ['farm' => $farm, 'crops' => $crops, 'farm_id' => $farm_id]);
    }

    public function editCropsPost(Request $request){
        $farm_id = $request->input('farm_id');

        $security_check = $this->route_security($farm_id);
           if ($security_check) {return $security_check;}

        $remove = $request->input('remove');
        if ($remove == '1' && $request->input('selectedCropId') != NULL && $request->input('deras') != NULL)
        {
            $crop = Crop::find($request->input('selectedCropId'));
            $crop->deras()->detach($request->input('deras'));
            return redirect()->back()->with('success', 'Crop removed successfully');
        }

        $crop = Crop::find($request->input('selectedCropId'));

        $status = $request->input('status');

        if ($status == NULL){
            $status = $crop->active;
        }

        $crop->active = $status;
        $crop->save();

        $dera_id = $request->input('deras');
        if ($dera_id == NULL){
            return redirect()->back()->with('success', 'Crop updated successfully');
        }
        // cropdera table
        $cropDera = CropDera::where('crop_id', $crop->id)->where('dera_id', $dera_id)->first();

        $cropDera->acres = $request->input('deraAcres');
        $cropDera->updated_at = now();
        $cropDera->save();
        return redirect()->back()->with('success', 'Crop updated successfully');
    }

    public function editDerasPost(Request $request){
        $farm_id = $request->input('farm_id');

        $security_check = $this->route_security($farm_id);
           if ($security_check) {return $security_check;}

        $dera = Dera::find($request->input('deraDropDown'));
        $dera->name = $request->input('deraNameEdit');
        $dera->number_of_acres = $request->input('acres');

        $dera->save();
        return redirect()->back()->with('success', 'Dera updated successfully');
    }

    public function addDerasPost(Request $request){
        $farm_id = $request->input('farm_id');

        $security_check = $this->route_security($farm_id);
           if ($security_check) {return $security_check;}

        $dera = new Dera();
        $dera->name = $request->input('deraName');
        $dera->number_of_acres = $request->input('acres');
        $dera->farm_id = $farm_id;

        $dera->save();
        return redirect()->back()->with('success', 'Dera added successfully');
    }


    public function configureExpenses($farm_id){

        $security_check = $this->route_security($farm_id);
           if ($security_check) {return $security_check;}
        return view('manager_configureExpenses', ['farm_id' => $farm_id]);
    }

    public function configureFarmExpense($farm_id){

        $security_check = $this->route_security($farm_id);
           if ($security_check) {return $security_check;}

        $added_expenses = ExpenseConfiguration::where('farm_id', $farm_id)
                            ->where('crop_id', 0)
                            ->where('include', 1)
                            ->pluck('expense_head')
                            ->toArray();
        $removed_expenses = ExpenseConfiguration::where('farm_id', $farm_id)
                            ->where('crop_id', 0)                    
                            ->where('include', 0)
                            ->pluck('expense_head')
                            ->toArray();

        return view('manager_configureFarmExpense', ['farm_id' => $farm_id, 'id'=> 'FARM', 'added_expenses' => $added_expenses, 'removed_expenses' => $removed_expenses]);
    }

    public function configureCropExpense($farm_id){

         $security_check = $this->route_security($farm_id);
           if ($security_check) {return $security_check;}

        $added_expenses = ExpenseConfiguration::where('farm_id', $farm_id)
                            ->where('crop_id', 1)
                            ->where('include', 1)
                            ->pluck('expense_head')
                            ->toArray();
        $removed_expenses = ExpenseConfiguration::where('farm_id', $farm_id)
                            ->where('crop_id', 1)                    
                            ->where('include', 0)
                            ->pluck('expense_head')
                            ->toArray();

        return view('manager_configureFarmExpense', ['farm_id' => $farm_id, 'id'=> 'CROP', 'added_expenses' => $added_expenses, 'removed_expenses' => $removed_expenses]);
    }

    public function saveExpenses(Request $request, $farm_id, $id){
        if ($id == 'FARM'){
            $crop_id = 0;
        }else{
            $crop_id = 1;
        }

        $security_check = $this->route_security($farm_id);
           if ($security_check) {return $security_check;}
            
            // Extract form data
            $addedExpenses = json_decode($request->input('added_expenses'), true);
            $removedExpenses = json_decode($request->input('removed_expenses'), true);
            // // Get existing expense configurations for the farm and crop
            if ($id == 'FARM'){
                $existingExpenses = ExpenseConfiguration::where('farm_id', $farm_id)
                                    ->where('crop_id', 0)
                                    ->pluck('expense_head')
                                    ->toArray();
            foreach ($addedExpenses as $expense) {
                if (!in_array($expense, $existingExpenses)) {
                    $newExpense = new ExpenseConfiguration();
                    $newExpense->farm_id = $farm_id;
                    $newExpense->crop_id = 0;
                    $newExpense->expense_head = $expense;
                    $newExpense->include = 1;
                    $newExpense->save();
                }
            }
             }else{
                $existingExpenses = ExpenseConfiguration::where('farm_id', $farm_id)
                                    ->where('crop_id', 1)
                                    ->pluck('expense_head')
                                    ->toArray();
                foreach ($addedExpenses as $expense) {
                    if (!in_array($expense, $existingExpenses)) {
                        $newExpense = new ExpenseConfiguration();
                        $newExpense->farm_id = $farm_id;
                        $newExpense->crop_id = 1;
                        $newExpense->expense_head = $expense;
                        $newExpense->include = 1;
                        $newExpense->save();
                    }
                }
            }
    
            // Handle removed expenses
            foreach ($removedExpenses as $expenseName) {
                if ($id == 'FARM'){
                    $existingExpense = ExpenseConfiguration::where('expense_head', $expenseName)
                    ->where('farm_id', $farm_id)
                    ->where('crop_id', 0)
                    ->first();
                }
                else{
                    $existingExpense = ExpenseConfiguration::where('expense_head', $expenseName)
                    ->where('farm_id', $farm_id)
                    ->where('crop_id', 1)
                    ->first();
                }
                if (!$existingExpense) {
                    $newExpense = new ExpenseConfiguration();
                    $newExpense->farm_id = $farm_id;
                    $newExpense->crop_id = $crop_id;
                    $newExpense->expense_head = $expenseName;
                    $newExpense->include = 0;
                    $newExpense->save();
                }
            }


            // fecth all expenses added in db
            if ($id == 'FARM'){
                $expenses = ExpenseConfiguration::where('farm_id', $farm_id)
                        ->where('include', 0)
                        ->where('crop_id', 0)
                        ->pluck('expense_head')
                        ->toArray();
            }else{
            $expenses = ExpenseConfiguration::where('farm_id', $farm_id)
                        ->where('crop_id', 1)            
                        ->where('include', 0)
                        ->pluck('expense_head')
                        ->toArray();
            }

            foreach ($expenses as $expense) {
                if (!in_array($expense, $removedExpenses)) {
                    // find expense and then delete
                    if ($id == 'FARM'){
                    $existingExpense = ExpenseConfiguration::where('expense_head', $expense)
                                ->where('farm_id', $farm_id)
                                ->where('crop_id', 0)
                                ->first();
                    }else{
                    $existingExpense = ExpenseConfiguration::where('expense_head', $expense)
                                    ->where('farm_id', $farm_id) // Replace with actual farm ID check
                                    ->where('crop_id', 1)
                                    ->first();
                    }
                    if ($existingExpense) {
                        $existingExpense->delete();
                    }
                }
            }

            if ($id == 'FARM'){
                $expenses = ExpenseConfiguration::where('farm_id', $farm_id)
                ->where('include', 1)
                ->where('crop_id', 0)
                ->pluck('expense_head')
                ->toArray();
            }else{
                $expenses = ExpenseConfiguration::where('farm_id', $farm_id)
                ->where('include', 1)
                ->where('crop_id', 1)
                ->pluck('expense_head')
                ->toArray();
            }

       


            foreach ($expenses as $expense) {
                if (in_array($expense, $removedExpenses)) {
                    // find expense and then delete
                    if ($id == 'FARM'){
                        $existingExpense = ExpenseConfiguration::where('expense_head', $expense)
                                    ->where('farm_id', $farm_id)
                                    ->where('crop_id', 0)
                                    ->first();
                    }else{
                    $existingExpense = ExpenseConfiguration::where('expense_head', $expense)
                                    ->where('farm_id', $farm_id) // Replace with actual farm ID check
                                    ->where('crop_id', 1)
                                    ->first();
                    }
                    if ($existingExpense) {
                        $existingExpense->delete();
                    }
                }
            }
            return redirect()->back()->with('success', 'Expenses updated successfully');
    }

    public function render_workers($farm_id){
        // fetch all workers for the farm
        
        $security_check = $this->route_security($farm_id);
           if ($security_check) {return $security_check;}
        $workers = FarmWorker::where('farm_id', $farm_id)->get();
        $users = User::whereIn('id', $workers->pluck('user_id'))->get();
        foreach ($users as $user) {
            $worker = FarmWorker::where('user_id', $user->id)->first();
            $user->access = $worker->access;
        }
        return view('manager_workers', ['farm_id' => $farm_id, 'workers' => $users]);
    }

    public function addworker(Request $request){
        $farm_id = $request->input('farm_id');

        $security_check = $this->route_security($farm_id);
           if ($security_check) {return $security_check;}

        $existing = User::where('email',  $request->input('email'))->first();
        if ($existing){
            return redirect()->back()->with('error', 'Email is already registered!');
        }

        $user = new User();
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->phone = $request->input('phone');

        $role = $request->input('role');
        if ($role == 1) {
            $user->role = 'expense_farmer';
        } else {
            $user->role = 'sales_farmer';
        }

        $user->password = bcrypt($request->input('password'));
        // $user->password = $request->input('password');

        $user->save();

        $farmWorker = new FarmWorker();
        $farmWorker->farm_id = $farm_id;
        $farmWorker->user_id = $user->id;
        $farmWorker->access = 1;
        $farmWorker->save();


        return redirect()->back()->with('success', 'Worker added successfully');
    }

    public function workerDelete(Request $req){
        $farm_id = $req->input('farm_id');

       $security_check = $this->route_security($farm_id);
    if ($security_check) {
        return $security_check; // If route_security returns a redirect, return it here
    }
        $worker = $req->input('worker_id');

        $worker = FarmWorker::where('user_id', $worker)->first();
        $user = User::find($worker->user_id);
        $user->delete();
        $worker->delete();

        return redirect()->back()->with('success', 'Worker deleted successfully');
    }

    public function workerRevoke(Request $req){
        $farm_id = $req->input('farm_id');
       $security_check = $this->route_security($farm_id);
    if ($security_check) {
        return $security_check; // If route_security returns a redirect, return it here
    }
        $worker = $req->input('worker_id');

        $worker = FarmWorker::where('user_id', $worker)->first();
        if ($worker->access == 0) {
            $worker->access = 1;
            $worker->save();
            return redirect()->back()->with('success', 'Worker access granted successfully');
        }
        else{
            $worker->access = 0;
            $worker->save();
            return redirect()->back()->with('success', 'Worker access revoked successfully');
        }

    }

    public function render_expense_farmer(){
        $worker = Session::get('expense_farmer');
        
        if (!$worker) {
            return redirect()->route('home');
        }
        $farm_ids = FarmWorker::where('user_id', $worker->id)->pluck('farm_id');
        $farms = Farm::whereIn('id', $farm_ids)->get();

        return view('worker_farms', ['farms' => $farms, 'farmer' => 0]);
    }

    public function cropdetails($farm_id, $crop_id, $route_id){

        $security_check = $this->route_security($farm_id);
        if ($security_check) {return $security_check;}

        $crop = Crop::with('deras')->find($crop_id);
        if ($route_id != 1 && $route_id != 0){
            return redirect()->back()->with('error', 'Route Not Allowed!');
        }
        if ($crop == null){
            return redirect()->back()->with('error', 'Crop not found');
        }

        if ($crop->farm_id != $farm_id){
            return redirect()->back()->with('error', 'Crop not found');
        }

        if ($crop['name'] == 'Sugarcane') {
            $sugarcaneId = $crop->sugarcane_id ?? '';
            $sugarcaneIdParts = explode("-", $sugarcaneId);
            $prevCrop = $sugarcaneIdParts[0];
            $currentVersion = isset($sugarcaneIdParts[1]) ? (int) $sugarcaneIdParts[1] : 0;
        
            $all_versions = [];
            foreach ($crop->farm->crops as $c) {
                $cId = $c->sugarcane_id;
                if ($cId) {
                    $cIdParts = explode("-", $cId);
                    if ($cIdParts[0] == $prevCrop) {
                        $version = isset($cIdParts[1]) ? (int) $cIdParts[1] : 0;
                        if ($version < $currentVersion) {
                            $all_versions[] = $c;
                        }
                    }
                }
            }
            usort($all_versions, function($a, $b) {
                return $b->year - $a->year;
            });
        }

        $cropMap = CropMap::where('crop_id', $crop_id)
        ->where('farm_id', $farm_id)
        ->first();
        if ($cropMap == null){
            $cropMap = 'Empty';
        }
        return view('manager_cropDetails', ['crop' => $crop, 'farm_id' => $farm_id, 'route_id' => $route_id, 'cropMap' => $cropMap, 'all_versions' => $all_versions ?? []]);
    }

    
    public function farm_history($farm_id)
    {

       $security_check = $this->route_security($farm_id);
    if ($security_check) {
        return $security_check; // If route_security returns a redirect, return it here
    }

        $crops = Crop::where('farm_id', $farm_id)->get();
        return view('manager_farmHistory', compact('crops', 'farm_id'));
    }

    public function render_sales_farmer(){
        $worker = Session::get('sales_farmer');
        // get farms from farmworker table
        if (!$worker) {
            return redirect()->route('home');
        }
        $farm_ids = FarmWorker::where('user_id', $worker->id)->pluck('farm_id');
        $farms = Farm::whereIn('id', $farm_ids)->get();

        return view('worker_farms', ['farms' => $farms, 'farmer' => 1]);
    }

    public function fetch_sugarcane($farm_id) {
        $sugarcane = Crop::where('farm_id', $farm_id)
                         ->where('name', 'Sugarcane')
                         ->get();
        return response()->json($sugarcane);
    }
    
    public function harvest_crop(Request $request){

        $crop_id = $request->input('crop_id');
        $date = $request->input('harvest_date');
        $passive = $request->input('passive');

        $crop = Crop::find($crop_id);
        $crop->harvest_date = $date;
        
        if ($request->has('passive')) {
            $crop->active = 0;
        }

        $crop->save();

        return redirect()->back()->with('success', 'Crop harvested successfully');
    }

    public function lucifer($farm_id){
        return view('chatbotseperate', ['farm_id'=>$farm_id]);
    }
}
