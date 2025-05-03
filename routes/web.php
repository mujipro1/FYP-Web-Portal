<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Session;

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
Route::get('lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'ur'])) {
        abort(400);
    }
    Session::put('locale', $locale);
    return redirect()->back();
});


Route::get('/', function () {
    return view('home');
});


Route::get('/home', function () {
    return view('home');
})->name('home');

Route::get('/signup', function () {
    return view('signup');
});

Route::get('/logout', 'App\Http\Controllers\UserController@logout')->name('logout');


Route::post("login", "App\Http\Controllers\UserController@login")->name('login');

Route::middleware('is_manager')->group(function () {
    Route::get('/manager/farms', 'App\Http\Controllers\ManagerController@render_farms_page')->name('manager.farms');
    Route::get('/manager/farmDetails/{farm_id}', 'App\Http\Controllers\ManagerController@render_get_farm_details_page')->name('manager.farmdetails');
    Route::get('/manager/configuration/{farm_id}', "App\Http\Controllers\ManagerController@render_configuration_page")->name('manager.configuration');
    Route::get('/manager/addCrop/{farm_id}', 'App\Http\Controllers\ManagerController@addCrop')->name('manager.addCrop');
    Route::get('/manager/editDeras/{farm_id}', 'App\Http\Controllers\ManagerController@editDeras')->name('manager.editDeras');
    Route::get('/manager/editCrops/{farm_id}', 'App\Http\Controllers\ManagerController@editCrops')->name('manager.editCrops');
    Route::post('/manager/configuration_form', 'App\Http\Controllers\ManagerController@configurationForm_submit')->name('manager.configurationForm');
    Route::post('/manager/editDerasPost', 'App\Http\Controllers\ManagerController@editDerasPost')->name('manager.editDerasPost');
    Route::post('/manager/addDerasPost', 'App\Http\Controllers\ManagerController@addDerasPost')->name('manager.addDerasPost');
    Route::post('/manager/editCropsPost', 'App\Http\Controllers\ManagerController@editCropsPost')->name('manager.editCropsPost');
    Route::get('/manager/configureExpenses/{farm_id}', 'App\Http\Controllers\ManagerController@configureExpenses')->name('manager.configureExpenses');
    Route::get('/manager/configureFarmExpense/{farm_id}', 'App\Http\Controllers\ManagerController@configureFarmExpense')->name('manager.configureFarmExpense');
    Route::get('/manager/configureCropExpense/{farm_id}', 'App\Http\Controllers\ManagerController@configureCropExpense')->name('manager.configureCropExpense');
    Route::post('/manager/saveExpenses/{farm_id}/{id}', 'App\Http\Controllers\ManagerController@saveExpenses')->name('manager.saveExpenses');

    Route::get('/manager/cropdetails/{farm_id}/{crop_id}/{route_id}', 'App\Http\Controllers\ManagerController@cropdetails')->name('manager.cropdetails');

    Route::get('/manager/render_workers/{farm_id}', 'App\Http\Controllers\ManagerController@render_workers')->name('manager.render_workers');
    Route::post('/manager/addworker', 'App\Http\Controllers\ManagerController@addworker')->name('manager.addworker');
    Route::post('/manager/delete', 'App\Http\Controllers\ManagerController@workerDelete')->name('workers.delete');
    Route::post('/manager/revoke', 'App\Http\Controllers\ManagerController@workerRevoke')->name('workers.revoke');
    
    
    Route::get('/get-deras/{crop_id}', 'App\Http\Controllers\ManagerController@getDerasForCrop');
    Route::get('/get-all-deras/{farmId}', 'App\Http\Controllers\ManagerController@getAllDeras')->name('get_all_deras');
    
    Route::post('/manager/analytics', 'App\Http\Controllers\ManagerAnalyticsController@analytics')->name('manager.analytics');
    
    Route::get('/manager/singlecrop/{farm_id}', 'App\Http\Controllers\ManagerAnalyticsController@singlecrop')->name('manager.singlecrop');
    Route::post('/manager/singlecropPost', 'App\Http\Controllers\ManagerAnalyticsController@singlecropPost')->name('manager.singlecropPost');
    
    Route::get('/manager/comparecrop/{farm_id}', 'App\Http\Controllers\ManagerAnalyticsController@comparecrop')->name('manager.comparecrop');
    Route::post('/manager/comparecropPost', 'App\Http\Controllers\ManagerAnalyticsController@comparecropPost')->name('manager.comparecropPost');
    
    
    Route::get('/manager/farm_history/{farm_id}', 'App\Http\Controllers\ManagerController@farm_history')->name('manager.farm_history');
    Route::post('/manager/add_cash', 'App\Http\Controllers\ManagerController@add_cash')->name('manager.add_cash');
    
    Route::get('/manager/reconciliationHistory/{farm_id}', 'App\Http\Controllers\ManagerController@reconciliationHistory')->name('manager.reconciliationHistory');
    
    Route::post('/harvest_crop', 'App\Http\Controllers\ManagerController@harvest_crop')->name('manager.harvest_crop');
    
    // maps___________________________
    
    Route::get('/manager/maps/{farm_id}', 'App\Http\Controllers\ManagerMapController@render_map_page')->name("manager.maps");
    Route::post('/manager/mapsave', 'App\Http\Controllers\ManagerMapController@map_save')->name('manager.map.save');

    Route::get('/manager/smartspend/{farm_id}', 'App\Http\Controllers\ManagerExpenseController@costsaver')->name("manager.costsaver");
    Route::post('/manager/cost-saver', 'App\Http\Controllers\ManagerExpenseController@costsaverPost')->name("manager.costsaverPost");

    Route::get('/manager/kisaanlink/{farm_id}', 'App\Http\Controllers\ManagerController@kisaanlink')->name("manager.kisaanlink");
    Route::post('/kisaan-link/send-message', 'App\Http\Controllers\ManagerController@sendMessage');
    Route::get('/kisaan-link/get-message/{id}', 'App\Http\Controllers\ManagerController@getMessage');

    // sales

    Route::get('/fetch-sugarcane/{farm_id}', 'App\Http\Controllers\ManagerController@fetch_sugarcane')->name('fetch_sugarcane');
});


Route::middleware('is_manager_and_expense_farmer')->group(function () {
    Route::get('manager/reconciliation/{farm_id}', 'App\Http\Controllers\ManagerExpenseController@reconciliation')->name('manager.reconciliation');
    Route::get('/manager/view_farmexpense_details/{farm_id}/{expense_id}', 'App\Http\Controllers\ManagerExpenseController@view_farmexpense_details')->name('manager.view_farmexpense_details');
    Route::get('/manager/render_farmexpense/{farm_id}', 'App\Http\Controllers\ManagerExpenseController@render_farmexpense')->name('manager.render_farmexpense');
    Route::get('/manager/view_cropexpense/{farm_id}', 'App\Http\Controllers\ManagerExpenseController@view_cropexpense')->name('manager.view_cropexpense');
    Route::post('/manager/add_cropexpense', 'App\Http\Controllers\ManagerExpenseController@add_cropexpense')->name('manager.add_cropexpense');
    Route::post('manager/manager_applyExpenseSearch', 'App\Http\Controllers\ManagerExpenseController@manager_applyExpenseSearch')->name('manager.manager_applyExpenseSearch');
    Route::post('/manager/add_farmexpense', 'App\Http\Controllers\ManagerExpenseController@add_farmexpense')->name('manager.add_farmexpense');
    Route::post('manager/manager_applyExpenseSearchfarm', 'App\Http\Controllers\ManagerExpenseController@manager_applyExpenseSearchfarm')->name('manager.manager_applyExpenseSearchfarm');
    Route::get('/manager/view_farmexpense/{farm_id}/', 'App\Http\Controllers\ManagerExpenseController@view_farmexpense')->name('manager.view_farmexpense');
    Route::get('/manager/view_cropexpense_details/{farm_id}/{expense_id}', 'App\Http\Controllers\ManagerExpenseController@view_cropexpense_details')->name('manager.view_cropexpense_details');
    Route::get('/manager/render_cropexpense/{farm_id}', 'App\Http\Controllers\ManagerExpenseController@render_cropexpense')->name('manager.render_cropexpense');
    Route::post('/manager/saveEditExpenses', 'App\Http\Controllers\ManagerExpenseController@saveEditExpenses')->name('manager.saveEditExpenses');
    Route::post('/manager/deleteExpense', 'App\Http\Controllers\ManagerExpenseController@deleteExpense')->name('manager.deleteExpense');
    
    Route::get('/manager/download_expenses/{farm_id}', 'App\Http\Controllers\ManagerExpenseController@download_expenses')->name('manager.download_expenses');
    Route::post('/download-expenses', 'App\Http\Controllers\ManagerExpenseController@downloadExpenses')->name('download.expenses');

    Route::get('/manager/lucifer/{farm_id}', 'App\Http\Controllers\ManagerController@lucifer')->name('lucifer');
});

Route::get('/export-csv/{crop_id}', 'App\Http\Controllers\ManagerAnalyticsController@exportCsv');


Route::middleware('is_superadmin')->group(function () {
    Route::get('/superadmin', 'App\Http\Controllers\SuperAdminController@render_superadmin_page')->name('superadmin');
    Route::get('/superadmin/requests', 'App\Http\Controllers\SuperAdminController@render_request_page')->name('superadmin.requests');
    Route::post('/superadmin/createfarm', 'App\Http\Controllers\SuperAdminController@render_createfarm')->name('superadmin.render_createfarm');
    Route::post('/superadmin/submit_createfarm', 'App\Http\Controllers\SuperAdminController@submit_createfarm')->name('superadmin.submit_createfarm');
});
Route::post('/submit-answers', 'App\Http\Controllers\SuperAdminController@submit_answers')->name('submit_answers');
Route::post('/submit-preview-answers', 'App\Http\Controllers\SuperAdminController@save_preview_changes')->name('save_preview_changes');
Route::get('/render-preview-answers', 'App\Http\Controllers\SuperAdminController@render_preview_answers')->name('render-preview-answers');
Route::get('/render-signupmap', 'App\Http\Controllers\SuperAdminController@render_signupmap')->name('render-signupmap');
Route::post('/superadmin.map_save', 'App\Http\Controllers\SuperAdminController@map_save')->name('superadmin.map_save');

Route::middleware('is_expense_farmer')->group(function () {
    Route::get('/expense_farmer', 'App\Http\Controllers\ManagerController@render_expense_farmer')->name('expense_farmer');
});

Route::middleware('is_sales_farmer')->group(function () {
    Route::get('/sales_farmer', 'App\Http\Controllers\ManagerController@render_sales_farmer')->name('sales_farmer');
});


Route::get('/manager/sales/{farm_id}', 'App\Http\Controllers\ManagerSalesController@render_sales_page')->name('manager.render_sales_page');
Route::post('/manager/add_sales', 'App\Http\Controllers\ManagerSalesController@add_sales')->name('manager.add_sales');
Route::get('/manager/view_sales/{farm_id}', 'App\Http\Controllers\ManagerSalesController@view_sales')->name('manager.view_sales');
Route::post('/manager/apply_salesSearch', 'App\Http\Controllers\ManagerSalesController@apply_salesSearch')->name('manager.apply_salesSearch');
Route::get('/manager/viewSalesRow/{farm_id}/{sale_id}', 'App\Http\Controllers\ManagerSalesController@viewSalesRow')->name('manager.viewSalesRow');

Route::post('/daily-task', 'App\Http\Controllers\DailyTaskController@executeTask')->name('daily.task');

Route::post('/manager/delete-farm-expenses', 'App\Http\Controllers\ManagerExpenseController@deleteFarmExpenses')->name('manager.deleteFarmExpenses');
Route::post('/manager/delete-farm-expenses', 'App\Http\Controllers\ManagerExpenseController@deleteCropExpenses')->name('manager.deleteCropExpenses');