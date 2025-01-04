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

}
