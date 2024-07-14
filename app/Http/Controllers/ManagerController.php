<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

use App\Models\Farm;
use App\Models\User;
use App\Models\CropDera;
use App\Models\Crop;
use App\Models\Dera;
use App\Models\Expense;
use App\Models\FarmExpense;
use App\Models\ExpenseConfiguration;
use App\Models\FarmWorker;

use App\Charts\MonthlyUsersChart;


class ManagerController extends Controller
{
    public function render_farms_page()
    {
        $user = Session::get('manager');
        $farms = Farm::where('user_id', $user->id)->get();
        return view('manager_farms', ['farms' => $farms]);
    }

    public function render_configuration_page($farm_id)
    {      

        return view('manager_configuration', ['farm_id' => $farm_id]);    
    }

    public function render_get_farm_details_page($farm_id)
    {
        $farm = Farm::with(['crops.deras'])->find($farm_id);
        // fetch workers from farmworker table and user table
        $workers = FarmWorker::where('farm_id', $farm_id)->get();
        $users = User::whereIn('id', $workers->pluck('user_id'))->get();

        return view('manager_farmDetails', ['farm' => $farm, 'workers' => $users]);
    }


    public function configurationForm_submit(Request $request)
    {

        $farm_id = $request->input('farm_id');
        $cropsData = json_decode($request->input('cropDetails'), true);

        foreach ($cropsData as $cropData) {
            
            $crop = new Crop();
            $crop->name = $cropData['name'];
            $crop->year = $cropData['year'];
            $crop->farm_id = $farm_id;
            $crop->acres = $cropData['acres'];
            $crop->identifier = $cropData['name'] . " " . $cropData['year'];
            $crop->sow_date = $cropData['sowingDate'];
            $crop->harvest_date = $cropData['harvestDate'];
            $crop->active = $cropData['status'];
            $crop->status = $cropData['stage'];
            $crop->description = $cropData['desc'];
            $crop->save();

            
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

    public function render_cropexpense($farm_id, $worker){
        $crops = Crop::where('farm_id', $farm_id)->get();
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

        return view('manager_cropexpense', ['farm_id' => $farm_id, 'crops' => $crops, 'added_expenses' => $added_expenses, 'removed_expenses' => $removed_expenses, 'worker' => $worker]);
    }

    public function view_cropexpense($farm_id, $worker){
        $crops = Crop::where('farm_id', $farm_id)->get();
        $expenses = Expense::whereIn('crop_id', $crops->pluck('id'))->get();

        $totalAmount = $expenses->sum('total');
        $totalExpenses = $expenses->count();

         return view('manager_viewCropexpense', ['farm_id' => $farm_id, 'crops' => $crops, 'expenses' => $expenses, 'totalAmount' => $totalAmount, 'totalExpenses' => $totalExpenses, 'worker' => $worker]);
        }

    public function view_cropexpense_details($farm_id, $worker,$expense_id){
        $expense = Expense::find($expense_id);
        return view('manager_viewRowexpense', ['farm_id' => $farm_id, 'expense' => $expense, 'worker' => $worker]);
    }

    public function view_farmexpense_details($farm_id, $worker,$expense_id){
        $expense = FarmExpense::find($expense_id);
        return view('manager_viewRowexpense', ['farm_id' => $farm_id, 'expense' => $expense, 'worker' => $worker]);
    }

    public function manager_applyExpenseSearch(Request $request)
    { 
        $crop_id = $request->input('crop_id');
        $expense_type = $request->input('expense_type');
        $date = $request->input('date');

        $query = Expense::query();

        if ($crop_id) {
            $query->where('crop_id', $crop_id);
        }

        if ($expense_type) {
            $query->where('expense_type', $expense_type);
        }

        if ($date) {
            $query->whereDate('date', $date);
        }
        $farm_id = $request->input('farm_id');
        
        if ($crop_id == null && $expense_type == null && $date == null) {
           return redirect()->route('manager.view_cropexpense', ['farm_id' => $farm_id]);
        }

        $expenses = $query->get();

        $totalAmount = $expenses->sum('total');
        $totalExpenses = $expenses->count();

        $expenses = $query->get();
        $crops = Crop::where('farm_id', $farm_id)->get();

        return view('manager_viewCropexpense', ['farm_id' => $farm_id, 'crops' => $crops, 'expenses' => $expenses, 'totalAmount' => $totalAmount, 'totalExpenses' => $totalExpenses]);

    }


    
    public function manager_applyExpenseSearchfarm(Request $request)
    {
        $expense_type = $request->input('expense_type');
        $date = $request->input('date');

        $query = FarmExpense::query();

        if ($expense_type) {
            $query->where('expense_type', $expense_type);
        }

        if ($date) {
            $query->whereDate('date', $date);
        }
        $farm_id = $request->input('farm_id');
        
        if ($expense_type == null && $date == null) {
              return redirect()->route('manager.view_farmexpense', ['farm_id' => $farm_id]);
        }



        $expenses = $query->get();
        $totalAmount = $expenses->sum('total');
        $totalExpenses = $expenses->count();
        return view('manager_viewFarmexpense', ['farm_id' => $farm_id, 'expenses' => $expenses, 'totalAmount' => $totalAmount, 'totalExpenses' => $totalExpenses]);
        
    }        

    public function render_farmexpense($farm_id, $worker){
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

        return view('manager_farmexpense', ['farm_id' => $farm_id, 'added_expenses' => $added_expenses, 'removed_expenses' => $removed_expenses, 'worker' => $worker]);
    }
    public function view_farmexpense($farm_id, $worker){
        $expenses = FarmExpense::where('farm_id', $farm_id)->get();
        $totalAmount = $expenses->sum('total');
        $totalExpenses = $expenses->count();
        return view('manager_viewFarmexpense', ['farm_id' => $farm_id, 'expenses' => $expenses, 'totalAmount' => $totalAmount, 'totalExpenses' => $totalExpenses, 'worker' => $worker]);
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


    public function add_cropexpense(Request $request)
    {

        $details = $request->except(['_token', 'date', 'farm_id', 'head','total', 'subhead', 'selected_crop']);

        $crop_id = $request->input('selected_crop');
        $crop = Crop::find($crop_id);

        $total = $request->input('total');
        if(!$total){
            $total = $request->input('amount');
        }

        $farm_id = $request->input('farm_id');
        $user_id = Farm::find($farm_id)->user_id;

        $expense = $crop->expenses()->create([
            'date' => $request->input('date'),
            'expense_type' => $request->input('head'),
            'expense_subtype' => $request->input('subhead'),
            'total' => $total,
            'details' => json_encode($details),
            'user_id' => $user_id
        ]);
        $worker = $request->input('worker');

        $expense->save();

        return redirect()->route('manager.render_cropexpense', ['farm_id' => $farm_id, 'worker'=>$worker])->with('success', 'Expense added successfully');
    }


    public function add_farmexpense(Request $request)
    {

        $details = $request->except(['_token', 'date', 'farm_id', 'head','total', 'subhead']);

        $farm_id = $request->input('farm_id');
        $user_id = Farm::find($farm_id)->user_id;

        $total = $request->input('total');
        if(!$total){
            $total = $request->input('amount');
        }

        // add farm expense
        $expense = new FarmExpense();
        $expense->date = $request->input('date');
        $expense->expense_type = $request->input('head');
        $expense->expense_subtype = $request->input('subhead');
        $expense->total = $total;
        $expense->details = json_encode($details);

        $expense->user_id = $user_id;
        $expense->farm_id = $farm_id;
        $expense->save();

        $worker = $request->input('worker');

        return redirect()->route('manager.render_farmexpense', ['farm_id' => $farm_id, 'worker'=>$worker])->with('success', 'Expense added successfully');
    }

    public function reconciliation($farm_id, $worker){
        return view('manager_reconciliation', ['farm_id' => $farm_id, 'worker' => $worker]);
    }


    public function addCrop($farm_id){
        $farm = Farm::with('deras')->find($farm_id);
        $deras = $farm->deras->pluck('name');
        return view('manager_addCrops', ['farm' => $farm, 'deras' => $deras, 'farm_id' => $farm_id]);
    }

    public function editDeras($farm_id){
        $farm = Farm::with('deras')->find($farm_id);
        $deras = $farm->deras;

        return view('manager_editDeras', ['farm' => $farm, 'deras' => $deras, 'farm_id' => $farm_id]);
    }

    public function editCrops($farm_id){
        $farm = Farm::with('crops')->find($farm_id);
        $crops = $farm->crops;

        return view('manager_editCrops', ['farm' => $farm, 'crops' => $crops, 'farm_id' => $farm_id]);
    }

    public function editCropsPost(Request $request){
        $farm_id = $request->input('farm_id');

        $remove = $request->input('remove');
        if ($remove == '1' && $request->input('selectedCropId') != NULL && $request->input('deras') != NULL)
        {
            $crop = Crop::find($request->input('selectedCropId'));
            $crop->deras()->detach($request->input('deras'));
            return redirect()->back()->with('success', 'Crop removed successfully');
        }

        $crop = Crop::find($request->input('selectedCropId'));

        $status = $request->input('status');
        $stage = $request->input('stage');

        if ($status == NULL){
            $status = $crop->active;
        }
        if ($stage == NULL){
            $stage = $crop->status;
        }

        $crop->status = $stage;
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

        $dera = Dera::find($request->input('deraDropDown'));
        $dera->name = $request->input('deraNameEdit');
        $dera->number_of_acres = $request->input('acres');

        $dera->save();
        return redirect()->back()->with('success', 'Dera updated successfully');
    }

    public function addDerasPost(Request $request){
        $farm_id = $request->input('farm_id');

        $dera = new Dera();
        $dera->name = $request->input('deraName');
        $dera->number_of_acres = $request->input('acres');
        $dera->farm_id = $farm_id;

        $dera->save();
        return redirect()->back()->with('success', 'Dera added successfully');
    }


    public function configureExpenses($farm_id){
        return view('manager_configureExpenses', ['farm_id' => $farm_id]);
    }

    public function configureFarmExpense($farm_id){

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

        // $user->password = bcrypt($request->input('password'));
        $user->password = $request->input('password');

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
        $worker = $req->input('worker_id');

        $worker = FarmWorker::where('user_id', $worker)->first();
        $user = User::find($worker->user_id);
        $user->delete();
        $worker->delete();

        return redirect()->back()->with('success', 'Worker deleted successfully');
    }

    public function workerRevoke(Request $req){
        $farm_id = $req->input('farm_id');
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
        // get farms from farmworker table
        if (!$worker) {
            return redirect()->route('home');
        }
        $farm_ids = FarmWorker::where('user_id', $worker->id)->pluck('farm_id');
        $farms = Farm::whereIn('id', $farm_ids)->get();

        return view('worker_farms', ['farms' => $farms]);
    }

    public function cropdetails($farm_id, $crop_id){
        $crop = Crop::with('deras')->find($crop_id);
        return view('manager_cropDetails', ['crop' => $crop, 'farm_id' => $farm_id]);
    }

}
