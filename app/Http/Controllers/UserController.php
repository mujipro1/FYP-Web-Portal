<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;
use App\Models\User;
use App\Models\Farm;
use App\Models\Crop;


class UserController extends Controller
{
    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');
        
        // use Hashing to check for password
        $user = User::where('email', $credentials['email'])->first();
        // if (!($user && password_verify($credentials['password'], $user->password))) {
        //     return redirect()->route('home');
        // }

        // // check without auth
        if (!($user && $credentials['password'] == $user->password)) {
            return redirect()->route('home');
        }

        else{

            
            if ($user) {
                
                
                if ($user->role == 'manager') {
                    Session::put('manager', $user);
                    return redirect()->route('manager.farms');
                    
                } else if ($user->role == 'superadmin') {
                    Session::put('superadmin', $user);
                    return  redirect()->route('superadmin');
                }
                else if ($user->role == 'expense_farmer') {
                    Session::put('expense_farmer', $user);
                    return  redirect()->route('expense_farmer');
                }
            }
        }
        
    }

    public function render_superadmin_page()
    {
        $user = Session::get('superadmin');
        if (!$user) {
            return redirect()->route('home');
        }
        return view('superadmin', ['user' => $user]);
    }
    
    public function render_manager_page()
    {
        $user = Session::get('manager');
        if (!$user) {
            return redirect()->route('home');
        }
        return view('manager', ['user' => $user]);
    }

}

