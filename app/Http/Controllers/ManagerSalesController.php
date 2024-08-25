<?php

namespace App\Http\Controllers;
use App\Models\Crop;
use App\Models\Sale;
use App\Models\Farm;
use Session;
use Illuminate\Http\Request;

class ManagerSalesController extends Controller
{
    public function render_sales_page($farm_id)
    {

        $crops = Crop::where('farm_id',$farm_id)
        ->where('active', 1)
        ->get();

        $worker = Session::get('worker');

        return view('manager_sales',['farm_id'=>$farm_id, 'crops'=>$crops, 'worker'=>$worker]);
    }

    public function add_sales(Request $request)
    {
        
        $farm_id = $request->input('farm_id');
        $crop_id = $request->input('crop');
        $crop_name = Crop::find($crop_id)->name;
        // get all except token and ids, into json format

        $amount = 'Amount';
        if ($crop_name == 'Sugarcane'){
            $amount = 'Bank Credited Amount';
        }
        $data = $request->except(['_token','farm_id','crop',$amount, 'Date']);
        $data['added_by'] = Session::get('worker');
        
        $sales = new Sale();
        $sales->crop_id = $crop_id;
        $sales->user_id = Farm::find($farm_id)->user_id;
        $sales->date = $request->input('Date');
        $sales->amount = $request->input($amount);
        $sales->details = json_encode($data);
        $sales->save();

        return redirect()->back()->with('success',"Sales Added Successfully!");

    }

    public function view_sales($farm_id){
        $crops = Crop::where('farm_id', $farm_id)->orderBy('year', 'desc')->get();
        $sales = Sale::whereIn('crop_id', $crops->pluck('id'))->get();
       
        $worker = Session::get('worker');
        return view('manager_viewSales', ['sales'=>$sales, 'crops'=>$crops,'farm_id'=> $farm_id, 'worker'=>$worker]);
    }
    
    public function apply_salesSearch(Request $request){
        $crop_id = $request->input('crop_id');
        $date = $request->input('date');
        $farm_id = $request->input('farm_id');
        
        $query = Sale::query();
        
        if ($crop_id) {
            $query->where('crop_id', $crop_id);
        }
        
        if ($date) {
            $query->whereDate('date', $date);
        }
        
 
        if ($crop_id == null && $date == null) {
            return redirect()->route('manager.view_sales', ['farm_id' => $farm_id]);
        }
        
        $sales = $query->get();
        $crops = Crop::where('farm_id', $farm_id)
        ->orderBy('year', 'desc')->get();
        return view('manager_viewSales', ['sales'=>$sales, 'crops'=>$crops,'farm_id'=> $farm_id]);
        
    }

    public function viewSalesRow($farm_id, $sale_id){
        $sale = Sale::where('id', $sale_id)->first();
        if (!($sale)){
            return redirect()->back()->with('error', 'Sales Data not Found!');
        }
        if($sale->user_id == Farm::find($farm_id)->user_id){
            $worker = Session::get('worker');
            return view('manager_viewSalesRow', ['sale'=>$sale,'farm_id'=>$farm_id, 'worker'=>$worker]);
        }
    }
}
