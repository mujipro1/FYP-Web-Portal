<?php

namespace App\Http\Controllers;
use Session;
use App\Models\Farm;
use App\Models\FarmWorker;
use App\Models\Reconciliation;
use App\Models\Crop;
use App\Models\Expense;
use App\Models\FarmExpense;
use App\Models\ExpenseConfiguration;
use App\Models\Worker;
use App\Models\User;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ManagerExpenseController extends Controller
{

    private function route_security($farm_id){
        $login_user = Session::get('manager') ?: Session::get('expense_farmer');

        $farm = Farm::find($farm_id);
        if (!$farm || $farm->user_id !== $login_user->id || !$login_user) {
            return redirect()->back()->with('error', "You do not have access to the requested page");
        }
        return null;
    }

    public function reconciliation($farm_id){

        $security_check = $this->route_security($farm_id);
        if ($security_check) {return $security_check;}
        

        $workers = FarmWorker::where('farm_id', $farm_id)
        ->with('user')
        ->get();
    
        // Get the latest reconcile with spent = 0 for each worker
        $reconciles = Reconciliation::where('spent', 0)
            ->whereIn('user_id', $workers->pluck('user_id'))
            ->orderBy('created_at', 'desc') // or 'updated_at'
            ->get()
            ->groupBy('user_id');
        
        // Attach the latest reconcile to each worker
        foreach ($workers as $x) {
            $latestReconcile = $reconciles->get($x->user_id)?->first();
            if ($latestReconcile) {
                $x->reconcile = $latestReconcile;
            }
        }

        $worker = Session::get('worker');
        
        return view('manager_reconciliation', ['farm_id' => $farm_id, 'worker' => $worker, 'workers' => $workers]);
    }

    public function view_farmexpense_details($farm_id, $expense_id){
        
        $security_check = $this->route_security($farm_id);
        if ($security_check) {return $security_check;}
        
        
        $expense = FarmExpense::find($expense_id);
        $worker = Session::get('worker');
        return view('manager_viewRowexpense', ['farm_id' => $farm_id, 'expense' => $expense, 'worker' => $worker]);
    }

    public function render_farmexpense($farm_id){
        
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
        $worker = Session::get('worker');

        $latest_expense = FarmExpense::where('farm_id', $farm_id)->orderBy('id', 'desc')->first(); 
        
        if ($latest_expense) {
            $latest_expense_date = $latest_expense->date;
        } else {
            $latest_expense_date = date('Y-m-d');
        }

        return view('manager_farmexpense', ['farm_id' => $farm_id, 'added_expenses' => $added_expenses, 'removed_expenses' => $removed_expenses, 'worker' => $worker, 'latest_expense_date'=>$latest_expense_date]);
    }

    public function view_cropexpense($farm_id){
        
        $security_check = $this->route_security($farm_id);
        if ($security_check) {return $security_check;}
        
        
        $crops = Crop::where('farm_id', $farm_id)->where('active', 1)->get();
        $expenses = Expense::whereIn('crop_id', $crops->where('active', 1)->pluck('id'))->orderBy('date', 'desc')->get();
        $worker = Session::get('worker');

        return view('manager_viewCropexpense', ['farm_id' => $farm_id, 'crops' => $crops, 'expenses' => $expenses, 'worker' => $worker]);
    }

    public function view_cropexpense_details($farm_id,$expense_id){
        
        $security_check = $this->route_security($farm_id);
        if ($security_check) {return $security_check;}
        
        
        $expense = Expense::find($expense_id);
        $worker = Session::get('worker');
        return view('manager_viewRowexpense', ['farm_id' => $farm_id, 'expense' => $expense, 'worker' => $worker]);
    }
   
    public function add_cropexpense(Request $request){

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

        $expense->save();

        $added_by = $details['addedBy'];
        $worker = Session::get('worker');
        
        if ($worker == 0){
            // manager added expenses
            $reconcile = new Reconciliation();
            $reconcile->user_id = $added_by;
            $reconcile->amount =   $total;
            $reconcile->spent = 1;
            $reconcile->expense_id = $expense->id;
            $reconcile->date = $request->input('date');
            $reconcile->save();
        }
        else{
            // worker added expenses
            $paidByOwner = $request->input('paidbyowner');

            $reconcile = new Reconciliation();
            $reconcile->user_id = $added_by;
            $reconcile->amount =   $total;
            $reconcile->spent = 1;
            $reconcile->date = $request->input('date');
            $reconcile->expense_id = $expense->id;
            $reconcile->save();

            if ($paidByOwner == null){
                $worker = FarmWorker::where('user_id', $added_by)->first();
                $worker->wallet = $worker->wallet - $total;
                $worker->save();
            }

        }

        $worker = Session::get('worker');

        return redirect()->route('manager.render_cropexpense', ['farm_id' => $farm_id, 'worker'=>$worker])->with('success', 'Expense added successfully');
    }
    
    public function manager_applyExpenseSearch(Request $request){ 
   
        $active_passive = $request->input('active_passive');
        $farm_id = $request->input('farm_id');

        if ($active_passive == '0') {
            $expenses = Expense::whereHas('crop', function($query) use ($farm_id) {
                        $query->where('active', 1)->where('farm_id', $farm_id);})->orderBy('date', 'desc')->get();
            $crops = Crop::where('farm_id', $farm_id)->where('active', 1)->get();
        } elseif ($active_passive == '1') {
            $expenses = Expense::whereHas('crop', function($query) use ($farm_id) {
                        $query->where('active', 0)->where('farm_id', $farm_id); })->orderBy('date', 'desc')->get();
                        $crops = Crop::where('farm_id', $farm_id)->where('active', 0)->get();
        } elseif ($active_passive == '2') {
            $expenses = Expense::whereHas('crop', function($query) use ($farm_id) {
                    $query->where('farm_id', $farm_id);})->orderBy('date', 'desc')->get();
                    $crops = Crop::where('farm_id', $farm_id)->get();
        }


        $worker = Session::get('worker');

        if ($active_passive == null) {
           return redirect()->route('manager.view_cropexpense', ['farm_id' => $farm_id, 'worker' => $worker, 'active_passive'=>$active_passive]);
        }


        $totalAmount = $expenses->sum('total');
        $totalExpenses = $expenses->count();

        return view('manager_viewCropexpense', ['farm_id' => $farm_id, 'crops' => $crops, 'expenses' => $expenses, 'worker' => $worker, 'active_passive'=>$active_passive]);

    }

    public function add_farmexpense(Request $request){

        $details = $request->except(['_token', 'date', 'farm_id', 'head','total', 'subhead']);
        
        $total = $request->input('total');
        if(!$total){
            $total = $request->input('amount');
        }
        $farm_id = $request->input('farm_id');
        $user_id = Farm::find($farm_id)->user_id;

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
        
        $added_by = $details['addedBy'];
        $worker = Session::get('worker');

        
        if ($worker == 0){
            // manager added expenses
            $reconcile = new Reconciliation();
            $reconcile->user_id = $added_by;
            $reconcile->amount =   $total;
            $reconcile->spent = 1;
            $reconcile->date = $request->input('date');
            $reconcile->farm_expense_id = $expense->id;
            $reconcile->save();
        }
        else{
            // worker added expenses
            $paidByOwner = $details['paidbyowner'];

            $reconcile = new Reconciliation();
            $reconcile->user_id = $added_by;
            $reconcile->amount =   $total;
            $reconcile->spent = 1;
            $reconcile->date = $request->input('date');
            $reconcile->farm_expense_id = $expense->id;
            $reconcile->save();

            $worker = FarmWorker::where('user_id', $added_by)->first();
            $worker->wallet = $worker->wallet - $total;
            $worker->save();

        }
        $worker = Session::get('worker');
        
        return redirect()->route('manager.render_farmexpense', ['farm_id' => $farm_id, 'worker'=>$worker])->with('success', 'Expense added successfully');
    }

    public function manager_applyExpenseSearchfarm(Request $request){

        $farm_id = $request->input('farm_id');

        $active_passive = $request->input('active_passive');
        $farm_id = $request->input('farm_id');

        if ($active_passive == '0') {
            $activeCrops = Crop::where('farm_id', $farm_id)->where('active', 1)->get();
            if ($activeCrops->isNotEmpty()) {
                $oldestActiveCropExpense = Expense::whereIn('crop_id', $activeCrops->pluck('id'))->orderBy('date', 'asc')->first();
                if ($oldestActiveCropExpense) {
                    $expenses = FarmExpense::where('farm_id', $farm_id)->where('date', '>=', $oldestActiveCropExpense->date) // Get expenses after the oldest active crop expense date
                        ->orderBy('date', 'desc')->get();
                } else {
                    $expenses = collect();
                }
            } else {
                $expenses = collect();
            }
        } elseif ($active_passive == '1') {
            $activeCrops = Crop::where('farm_id', $farm_id)->where('active', 1)->get();
            if ($activeCrops->isNotEmpty()) {
                $oldestActiveCropExpense = Expense::whereIn('crop_id', $activeCrops->pluck('id'))->orderBy('date', 'asc')->first();
                if ($oldestActiveCropExpense) {
                    $expenses = FarmExpense::where('farm_id', $farm_id)->where('date', '<=', $oldestActiveCropExpense->date) // Get expenses after the oldest active crop expense date
                        ->orderBy('date', 'desc')->get();
                } else {
                    $expenses = collect();
                }
            } else {
                $expenses = collect();
            }
        
        } elseif ($active_passive == '2') {
            $expenses = FarmExpense::where('farm_id', $farm_id)->orderBy('date', 'desc')->get();
        }
        

        
        $worker = Session::get('worker');
        
        if ($active_passive == null) {
              return redirect()->route('manager.view_farmexpense', ['farm_id' => $farm_id, 'worker'=>$worker]);
        }


        return view('manager_viewFarmexpense', ['farm_id' => $farm_id, 'expenses' => $expenses, 'worker'=>$worker, 'active_passive'=>$active_passive]);
        
    }        

    public function view_farmexpense($farm_id){
        
        $security_check = $this->route_security($farm_id);
        if ($security_check) {return $security_check;}
        


        $activeCrops = Crop::where('farm_id', $farm_id)->where('active', 1)->get();
        if ($activeCrops->isNotEmpty()) {
            $oldestActiveCropExpense = Expense::whereIn('crop_id', $activeCrops->pluck('id'))->orderBy('date', 'asc')->first();
            if ($oldestActiveCropExpense) {
                $expenses = FarmExpense::where('farm_id', $farm_id)->where('date', '>=', $oldestActiveCropExpense->date) // Get expenses after the oldest active crop expense date
                    ->orderBy('date', 'desc')->get();
            } else {
                $expenses = collect();
            }
        } else {
            $expenses = collect();
        }

        $worker = Session::get('worker');
        return view('manager_viewFarmexpense', ['farm_id' => $farm_id, 'expenses' => $expenses, 'worker' => $worker]);
    }

    public function render_cropexpense($farm_id){
        
        $security_check = $this->route_security($farm_id);
        if ($security_check) {return $security_check;}
        
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


        $user_id = Farm::where('id', $farm_id)->value('user_id');
        $latest_expense = Expense::where('user_id', $user_id)->orderBy('id', 'desc')->first(); 

        if ($latest_expense) {
            $latest_expense_date = $latest_expense->date;
        } else {
            $latest_expense_date = date('Y-m-d');
        }

        $worker = Session::get('worker');
        return view('manager_cropexpense', ['farm_id' => $farm_id, 'crops' => $crops, 'added_expenses' => $added_expenses, 'removed_expenses' => $removed_expenses, 'worker' => $worker, 'latest_expense_date' => $latest_expense_date]);
    }


    public function saveEditExpenses(Request $req){
        

        $expense_id = $req->input('expense_id');
        $farm_id = $req->input('farm_id');
        $expense = Expense::find($expense_id);

        if (!$expense || $expense->crop->farm_id != $farm_id) {
            return redirect()->back()->with('error', "You do not have access to the requested page");
        }
        $expense->date = $req->input('date');
        $details = json_decode($expense->details, true);
        $details['description'] = $req->input('description');
        $expense->details = json_encode($details);

        $expense->save();

        return redirect()->back()->with('success', 'Expense updated successfully');

    }

    public function deleteExpense(Request $request)
    {
        if($request->crop_farm_id == '1'){
            $expense = Expense::find($request->expense_id);
            if ($expense && $expense->crop->farm_id == $request->farm_id) {
                $expense->delete();
                return redirect()->route('manager.view_cropexpense' , ['farm_id' => $request->farm_id])->with('success', 'Expense deleted successfully');
            }
            return redirect()->route('manager.view_cropexpense' , ['farm_id' => $request->farm_id])->with('error', 'Expense Not Found');
        }   
        else{
            $expense = FarmExpense::find($request->expense_id);
            if ($expense && $expense->farm_id == $request->farm_id) {
                $expense->delete();
                return redirect()->route('manager.view_farmexpense' , ['farm_id' => $request->farm_id])->with('success', 'Expense deleted successfully');
            }
            return redirect()->route('manager.view_farmexpense' , ['farm_id' => $request->farm_id])->with('error', 'Expense Not Found');
        }
    }

    public function deleteFarmExpenses(Request $request)
{
    $expenseIds = $request->input('expense_ids');

    if (is_array($expenseIds) && count($expenseIds) > 0) {
        try {
            // Delete the selected expenses
            FarmExpense::whereIn('id', $expenseIds)->delete();

            return response()->json([
                'success' => true,
                'message' => count($expenseIds) . ' expense(s) deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting expenses.'
            ]);
        }
    }

    return response()->json([
        'success' => false,
        'message' => 'No expenses selected for deletion.'
    ]);
}

public function deleteCropExpenses(Request $request)
{
    $expenseIds = $request->input('expense_ids');

    if (is_array($expenseIds) && count($expenseIds) > 0) {
        try {
            // Delete the selected expenses
            Expense::whereIn('id', $expenseIds)->delete();

            return response()->json([
                'success' => true,
                'message' => count($expenseIds) . ' expense(s) deleted successfully.'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred while deleting expenses.'
            ]);
        }
    }

    return response()->json([
        'success' => false,
        'message' => 'No expenses selected for deletion.'
    ]);
}

public function costsaver($farm_id){
    // fetch unique crop names and send to frontend
    $security_check = $this->route_security($farm_id);
    if ($security_check) { return $security_check; }

    $crops = Crop::where('farm_id', $farm_id)
                ->where('active', 1)
                 ->get();

    return view("manager_costsaver", ['farm_id' => $farm_id, 'crops' => $crops]);
}

    public function costsaverPost(Request $req){

        // if no crop or cost-saver-expense then return error msg
        if (!$req->input('crop') || !$req->input('cost-saver-expense')) {
            return redirect()->back()->with('error', 'Please select a crop and Expense Type.');
        }

        list($cropId, $selectedCrop) = explode('|', $req->input('crop'));
        $selectedExpense = $req->input('cost-saver-expense');
        $selectedSubtype = $req->input('cost-saver-subtype');
    
        // Step 2: Read CSV file
        try {
            $fileContent = Storage::get('data/monthly_percentage_data.csv');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Expense data file not found.');
        }
    
        $rows = array_map('str_getcsv', explode("\n", trim($fileContent)));
        $headers = array_map('trim', $rows[0]);
        $dataRows = array_slice($rows, 1);
    
        $currentMonth = date('n');
        $currentYear = date('Y');
    
        $filtered = [];
    
        foreach ($dataRows as $row) {
            $rowData = array_combine($headers, $row);
    
            if (
                $rowData['crop_name'] === $selectedCrop &&
                $rowData['expense_type'] === $selectedExpense &&
                (empty($selectedSubtype) || $rowData['expense_subtype'] === $selectedSubtype) &&
                (int)$rowData['month'] === (int)$currentMonth 
            ) {
                $filtered[] = $rowData;
            }
        }

        $yearly_historic_average = 0;
        foreach ($filtered as $row) {
            $yearly_historic_average += $row['total_yearly'];
        }
        $yearly_historic_average /= count($filtered);
        $yearly_historic_average = round($yearly_historic_average, 2);

        $monthly_historic_average = 0;
        foreach ($filtered as $row) {
            $monthly_historic_average += $row['total_monthly'];
        }
        $monthly_historic_average /= count($filtered);
        $monthly_historic_average = round($monthly_historic_average, 2);



        $crop = Crop::find($cropId);

        if ($selectedSubtype) {
            $expense = Expense::where('crop_id', $cropId)
                ->where('expense_subtype', $selectedSubtype)
                ->where('expense_type', $selectedExpense)
                ->whereMonth('date', date('m'))
                ->whereYear('date', date('Y'))
                ->get();
        } else {
            $expense = Expense::where('crop_id', $cropId)
                ->where('expense_type', $selectedExpense)
                ->whereMonth('date', date('m'))
                ->whereYear('date', date('Y'))
                ->get();
        }
        if (!$expense) {
            $totalExpenseMonthly = 0;
        }
        else{
            $totalExpenseMonthly = 0;
            foreach ($expense as $exp) {
                $totalExpenseMonthly += $exp->total;
            }
        }

        $totalExpenseYearly = 0;
        
        if ($selectedSubtype) {
            $expense = Expense::where('crop_id', $cropId)
                ->where('expense_subtype', $selectedSubtype)
                ->where('expense_type', $selectedExpense)
                ->whereYear('date', date('Y'))
                ->get();
        } else {
            $expense = Expense::where('crop_id', $cropId)
                ->where('expense_type', $selectedExpense)
                ->whereYear('date', date('Y'))
                ->get();
        }

        if ($expense) {
            foreach ($expense as $exp) {
                $totalExpenseYearly += $exp->total;
            }
        }
        else{
            $totalExpenseYearly = 0;
        }

        // Step 3: Handle result
        if (empty($filtered)) {
            return redirect()->back()->with('error', 'No matching data found for the current month.');
        }

        $farm_id = $req->input('farm_id');

        $crops = Crop::where('farm_id', $farm_id)
                ->where('active', 1)
                 ->get();
        return view('manager_costsaver', [
            'filtered' => $filtered,
            'selectedCrop' => $selectedCrop,
            'selectedExpense' => $selectedExpense,
            'selectedSubtype' => $selectedSubtype,
            'farm_id' => $req->input('farm_id'),
            'crops' => $crops,
            'totalExpenseMonthly' => $totalExpenseMonthly,
            'totalExpenseYearly' => $totalExpenseYearly,
            'yearly_historic_average' => $yearly_historic_average,
            'monthly_historic_average' => $monthly_historic_average,
        ]);
}

    public function download_expenses($farm_id){

        $crops = Crop::orderBy('year', 'desc')->get();
        return view("manager_downloadExpenses", ['farm_id'=>$farm_id, 'crops'=>$crops, 'data'=>[]]);
    }

    public function downloadExpenses(Request $request)
{
    $validated = $request->validate([
        'farm_id' => 'required|integer',
        'crops' => 'nullable|array',
        'status' => 'nullable|string',
        'year' => 'nullable|string',
        'includeFarmExpenses' => 'nullable',
        'expenseTypeFilter' => 'nullable|string',
        'farmTypeFilter' => 'nullable|string',
        'from_date' => 'nullable|date',
        'to_date' => 'nullable|date',
    ]);

    $farmId = $validated['farm_id'];

    // Get crop IDs
    $cropIds = Crop::where('farm_id', $farmId)->pluck('id');

    $selectedCropIds = !empty($validated['crops']) ? $validated['crops'] : $cropIds;
    $selectedCrops = Crop::whereIn('id', $selectedCropIds)->get();

    // Get date range from selected crops if not provided
    $autoFromDate = null;
    $autoToDate = null;
    if (empty($validated['from_date']) && empty($validated['to_date']) && !empty($validated['includeFarmExpenses']) && $validated['includeFarmExpenses'] === 'on') {
        $autoFromDate = $selectedCrops->min('sow_date');
        $autoToDate = $selectedCrops->max('harvest_date');
    }

    // Crop Expenses
    $cropQuery = Expense::with('crop')->whereIn('crop_id', $selectedCropIds);

    if (!empty($validated['status'])) {
        $cropQuery->where('active', $validated['status'] === 'active' ? 1 : 0);
    }

    if (!empty($validated['year'])) {
        $cropQuery->where('year', $validated['year']);
    }

    if (!empty($validated['expenseTypeFilter']) && $validated['expenseTypeFilter'] !== 'all') {
        $cropQuery->where('expense_type', $validated['expenseTypeFilter']);
    }

    if (!empty($validated['from_date'])) {
        $cropQuery->where('date', '>=', $validated['from_date']);
    }

    if (!empty($validated['to_date'])) {
        $cropQuery->where('date', '<=', $validated['to_date']);
    }

    $cropExpenses = $cropQuery->get()->map(function ($item) {
        return [
            'type' => 'crop',
            'id' => $item->id,
            'crop_identifier' => optional($item->crop)->identifier,
            'expense_type' => $item->expense_type,
            'expense_subtype' => $item->expense_subtype,
            'total' => $item->total,
            'date' => $item->date,
            'status' => optional($item->crop)->active == 1 ? 'Active Crop' : 'Passive Crop',
            'details' => $item->details ?? '',
        ];
    });

    // Farm Expenses
    $farmExpenses = collect();
    if (!empty($validated['includeFarmExpenses']) && $validated['includeFarmExpenses'] === 'on') {
        $farmQuery = FarmExpense::where('farm_id', $farmId);

        if (!empty($validated['status'])) {
            $farmQuery->where('status', $validated['status'] === 'active' ? 1 : 0);
        }

        if (!empty($validated['farmTypeFilter']) && $validated['farmTypeFilter'] !== 'all') {
            $farmQuery->where('expense_type', $validated['farmTypeFilter']);
        }

        if (!empty($validated['year'])) {
            $farmQuery->whereYear('date', $validated['year']);
        }

        // Apply date range — use manual filter if available, otherwise auto-calculated
        $fromDate = $validated['from_date'] ?? $autoFromDate;
        $toDate = $validated['to_date'] ?? $autoToDate;

        if (!empty($fromDate)) {
            $farmQuery->where('date', '>=', $fromDate);
        }

        if (!empty($toDate)) {
            $farmQuery->where('date', '<=', $toDate);
        }

        $farmExpenses = $farmQuery->get()->map(function ($item) {
            return [
                'type' => 'farm',
                'id' => $item->id,
                'crop_identifier' => null,
                'expense_type' => $item->expense_type,
                'expense_subtype' => $item->expense_subtype,
                'total' => $item->total,
                'date' => $item->date,
                'status' => null,
                'details' => $item->details ?? '',
            ];  
        });
    }

    $combined = $cropExpenses->merge($farmExpenses)->sortByDesc('date')->values();

    return view("manager_downloadExpenses", [
        'farm_id' => $farmId,
        'crops' => Crop::orderBy('year', 'desc')->get(),
        'data' => $combined,
    ]);
}

    
}