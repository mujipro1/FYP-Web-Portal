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

        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $credentials['email'])->first();

        if (!($user && password_verify($credentials['password'], $user->password))) {
                return redirect()->route('home')->with('error', 'Invalid email or password');
            }
            
            else{
                if ($user) {
                    Session::put('user', $user);
                    if ($user->role == 'manager') {
                        Session::put('manager', $user);
                        Session::put('worker', 0);
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
                        Session::put('worker', 1);
                        return  redirect()->route('expense_farmer');
                    }
                    else if ($user->role == 'sales_farmer') {
                        $worker = FarmWorker::where('user_id', $user->id)->first();
                        if ($worker->access == 0) {
                            return redirect()->route('home')->with('error', 'You do not have access to this page');
                        }
                        Session::put('sales_farmer', $user);
                        Session::put('worker', 1);
                        return  redirect()->route('sales_farmer');
                    }
                }   
        }
        
    }

    public function logout(){
        if (Session::has('manager')) {
            Session::forget('manager');
            Session::forget('worker');
        } 
        else if (Session::has('superadmin')) {
            Session::forget('superadmin');
        }
        else if (Session::has('expense_farmer')) {
            Session::forget('worker');
            Session::forget('expense_farmer');
        }
        else if (Session::has('sales_farmer')) {
            Session::forget('worker');
            Session::forget('sales_farmer');
        }
        return redirect()->route('home')->with('success', 'Logged out successfully');
    }

   
}

