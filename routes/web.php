<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

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

Route::get('/', function () {return view('index');});


Auth::routes();
//Controllers
Route::group(['namespace' => 'App\Http\Controllers'], function (){
    Route::post('/auth',['as'=> 'auth.user', 'uses' => 'UserController@Auth']);
    Route::post('/validate-transaction', ['as' => 'validate.transaction', 'uses' => 'TransactionsController@validateTransaction']);
    Route::get('/transaction', ['as' => 'transaction', 'uses' => 'TransactionsController@index']);
});
