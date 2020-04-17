<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group([
    'prefix' => 'customer'
], function () {
    Route::post('', 'CustomerController@store');
    Route::put('{customerId}', 'CustomerController@update');

    Route::post('withdraw/{customerId}', 'CustomerController@withdraw');
    Route::post('deposit/{customerId}', 'CustomerController@deposit');
});

Route::get('list', 'ReportsController@list');
Route::get('generate', 'ReportsController@generateExcel');