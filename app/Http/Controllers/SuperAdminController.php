<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;

use App\Models\User;
use App\Models\Requests;
use App\Models\Farm;

class SuperAdminController extends Controller{


    public function render_request_page(){
        $user = Session::get('superadmin');
        $requests = Requests::all();
        return view('superadmin_requests', ['requests' => $requests, 'user' => $user]);
    }
    
    public function render_createFarm(Request $request) {

        $requestId = $request->input('request_Id');
        $request = Requests::find($requestId);


        return view('superadmin_createfarm', ['request' => $request]);

    }

    public function submit_createfarm(Request $request){

        $user_id = $request->input('user_id');
        $farmName = $request->input('farmName');
        $farmCity = $request->input('farmCity');
        $no_of_acres = $request->input('acres');
        $address = $request->input('address');
        $hasDeras = $request->input('hasDeras');
        // $no_of_deras = $request->input('numberOfDeras');

        //create farm
        $farm = new Farm();
        $farm->user_id = $user_id;
        $farm->name = $farmName;
        $farm->city = $farmCity;
        $farm->number_of_acres = $no_of_acres;
        $farm->address = $address;
        $farm->has_deras = $hasDeras;
        $farm->save();

        //update request
        $request = Requests::find($request->input('request_id'));
        $request->status = 'approved';
        $request->save();

        return redirect()->route('superadmin.requests')->with('success', 'Farm created successfully');

    }
}
