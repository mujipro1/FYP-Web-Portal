<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\User;
use App\Models\Farm;
use App\Models\FarmWorker;
use App\Models\Crop;


class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        // use Hashing to check for password
        $user = User::where('email', $credentials['email'])->first();
        if (!($user && password_verify($credentials['password'], $user->password))) {
                return redirect()->route('home')->with('error', 'Invalid email or password');
            }
            
            // // check without auth
            // if (!($user && $credentials['password'] == $user->password)) {
            //     return redirect()->route('home')->with('error', 'Invalid email or password');
            // }
            
            else{
                if ($user) {
                    if ($user->role == 'manager') {
                        Session::put('manager', $user);
                        return redirect()->route('manager.farms');
                    } 
                    else if ($user->role == 'superadmin') {
                        Session::put('superadmin', $user);
                        return  redirect()->route('superadmin');
                    }
                    else if ($user->role == 'expense_farmer') {
                        $worker = FarmWorker::where('user_id', $user->id)->first();
                        if ($worker->access == 0) {
                            return redirect()->route('home')->with('error', 'You do not have access to this page');
                        }
                        Session::put('expense_farmer', $user);
                        return  redirect()->route('expense_farmer');
                    }
                    // else if ($user->role == 'sales_farmer') {
                        // $worker = FarmWorker::where('user_id', $user->id)->first();
                        // if ($worker->access == 0) {
                        //     return redirect()->route('home')->with('error', 'You do not have access to this page');
                        // }
                        // Session::put('sales_farmer', $user);
                        // return  redirect()->route('sales_farmer');
                    // }
                }   
        }
        
    }

  

}

