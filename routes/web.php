<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});


Route::fallback(function() {
    return view('welcome');
});


Route::get('/superadmin', function () {
    return view('SuperAdmin');
});


Route::get('/expensefarmer', function () {
    return view('expensefarmer');
});


Route::get('/manager', function () {
    return view('manager');
});

