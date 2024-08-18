<?php

namespace App\Http\Controllers;
use Session;
use Illuminate\Http\Request;
use ArielMejiaDev\LarapexCharts\Facades\LarapexChart;
use Mail;
use App\Mail\FarmCreated;

use App\Models\User;
use App\Models\Requests;
use App\Models\Dera;
use App\Models\Farm;
use App\Models\Map;

class SuperAdminController extends Controller{


    public function render_request_page(){
        $user = Session::get('superadmin');
        $requests = Requests::all();
        // convert the strings of json to array
        foreach ($requests as $request) {
            $request->user_info = json_decode($request->user_info, true);
            $request->farm_info = json_decode($request->farm_info, true);
        }

        $requests = $requests->sortByDesc('created_at');
        
        return view('superadmin_requests', ['requests' => $requests, 'user' => $user]);
    }
    
    public function render_createFarm(Request $request) {

        $requestId = $request->input('request_Id');
        $request = Requests::find($requestId);
        $request->user_info = json_decode($request->user_info, true);
        $request->farm_info = json_decode($request->farm_info, true);


        return view('superadmin_createfarm', ['request' => $request]);

    }

    public function submit_createfarm(Request $request){

        $user_id = $request->input('user_id');
        $farmName = $request->input('farmName');
        $farmCity = $request->input('farmCity');
        $no_of_acres = $request->input('acres');
        $address = $request->input('address');
        $hasDeras = $request->input('has_Deras');

        if ($hasDeras == null) {
            $hasDeras = 1;
        }
        $no_of_deras = $request->input('numberOfDeras');
        $deraAcres = $request->input('deraAcres');

        $farm = new Farm();
        $farm->user_id = $user_id;
        $farm->name = $farmName;
        $farm->city = $farmCity;
        $farm->number_of_acres = $no_of_acres;
        $farm->address = $address;
        $farm->has_deras = $hasDeras;
        $farm->save();

        $or_request = Requests::find($request->input('request_id'));
        $farm_info = json_decode($or_request->farm_info, true);
        $map_data = json_decode($farm_info['map'], true);        

        $map = new Map();
        $map->farm_id = $farm->id;

        $map->coords = json_encode($map_data);
        $map->save();
        

        //create deras
        if ($hasDeras == 1) {
            for ($i=0; $i < $no_of_deras; $i++) { 
                $dera = new Dera();
                $dera->name = 'Dera '.($i+1);
                $dera->farm_id = $farm->id;
                $dera->number_of_acres = $deraAcres[$i];
                $dera->save();
            }
        }

        //update request
        $request = Requests::find($request->input('request_id'));
        $request->status = 'approved';
        $request->save();

        $user_name = User::find($user_id)->name;
        $email = User::find($user_id)->email;

        $farmData = [
            'name' => $user_name, 
            'email' => $email
        ];


        Mail::to($email)->send(new FarmCreated($farmData));

        return redirect()->route('superadmin.requests')->with('success', 'Farm created successfully');
    }


    public function render_superadmin_page()
    {
        $user = Session::get('superadmin');
        if (!$user) {
            return redirect()->route('home');
        }
        
        // fetch total farms
        $farms = Farm::all();
        $totalFarms = count($farms);
        
        // fetch total users
        $users = User::where('role', 'manager')->get();
        $totalUsers = count($users);
        
        // fetch the number of farms with deras
        $farmsWithDeras = Farm::where('has_deras', 1)->get();
        $totalFarmsWithDeras = count($farmsWithDeras);
        
        // fetch the number of farms without deras
        $farmsWithoutDeras = Farm::where('has_deras', 0)->get();
        $totalFarmsWithoutDeras = count($farmsWithoutDeras);

        // fetch total requests
        $requests = Requests::all();
        $totalRequests = count($requests);

        // fetch pending requests
        $pendingRequests = Requests::where('status', 'pending')->get();
        $totalPendingRequests = count($pendingRequests);

        
        // create larapex hollow pie chart
        $chart = LarapexChart::PieChart()
        ->setTitle('Farms')
        ->addData([$totalFarmsWithDeras, $totalFarmsWithoutDeras])
        ->setLabels(['Farms with Deras', 'Farms without Deras']);

            
            return view('superadmin', ['user' => $user, 'totalFarms' => $totalFarms, 'totalUsers' => $totalUsers, 'chart' => $chart, 'totalRequests' => $totalRequests, 'totalPendingRequests' => $totalPendingRequests]);
        }

        public function submit_answers(Request $request){
            
            $farmerName = $request->input('farmerName');
            $email = $request->input('email');
            $phone = $request->input('phone');
            $farmName = $request->input('farmName');
            $farmCity = $request->input('farmCity');
            $farmAddress = $request->input('farmAddress');
            $acres = $request->input('farmArea');
            $has_deras = $request->input('has_deras');
            $deras = $request->input('deras');
            $deraAcres = $request->input('deraAcres');
            $remarks = $request->input('remarks');

            $data = [
                'farmerName' => $farmerName,
                'email' => $email,
                'phone' => $phone,
                'farmName' => $farmName,
                'farmCity' => $farmCity,
                'farmAddress' => $farmAddress,
                'acres' => $acres,
                'has_deras' => $has_deras,
                'deras' => $deras,
                'deraAcres' => $deraAcres,
                'remarks' => $remarks,
            ];

            // store data in session
            Session::put('Preview_data', $data);
            return redirect()->route('render-preview-answers');

            
        }

        public function render_preview_answers(){
            // fetch data from session
            $data = Session::get('Preview_data');
            if (!$data) {
                return redirect()->route('signup');
            }
            return view('signup_confirm', ['data' => $data]);
        }

        public function save_preview_changes(Request $request){
            $data = $request->all();
            Session::forget('Preview_data');
            Session::put('Preview_data', $data);
            return redirect()->route('render-signupmap');            
        }
        public function render_signupmap(){
            $data = Session::get('Preview_data');
            if ($data['has_deras'] == 0){
                $data['deras'] = 0;
                $data['deraAcres'] = [];
            }
            if (!$data) {
                return redirect()->route('signup');
            }
            return view('signup_map', ['data' => $data]);
        }

        public function map_save(Request $request){

            $data = json_decode($request->input('data'), true);
            $map = $request->input('deraDetails');

            $user = User::where('email', $data['email'])->first();
            if (!$user) {
                $user = new User();
                $user->name = $data['farmerName'];
                $user->email = $data['email'];
                $user->phone = $data['phone'];
                $user->role = 'manager';
                $user->password = bcrypt('password');
                $user->save();
            }

            $request = new Requests();
            
            $user_info = [
                'farmerName' => $data['farmerName'],
                'email' => $data['email'],
                'phone' => $data['phone'],
            ];

            $farm_info = [
                'farmName' => $data['farmName'],
                'farmCity' => $data['farmCity'],
                'farmAddress' => $data['farmAddress'],
                'acres' => $data['acres'],
                'has_deras' => $data['has_deras'],
                'deras' => $data['deras'],
                'deraAcres' => $data['deraAcres'],
                'map' => json_encode($map),
            ];

            $request->user_info = json_encode($user_info);
            $request->farm_info = json_encode($farm_info);
            $request->details = $data['remarks'];
            $request->status = 'pending';
            $request->user_id = $user->id;
            $request->save();

            return view('thankyou');
        }
}
