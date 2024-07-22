<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Session;

class isManagerandExpenseFarmer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if (Session::has('manager') || Session::has('expense_farmer')) {
            return $next($request);
        }

        // If not a manager, redirect to a different route or show an error
        return redirect()->route('home')->with('error', 'You do not have manager or expense farmer access.');
    }
}
