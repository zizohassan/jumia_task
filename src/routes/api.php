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

Route::namespace('\\App\\Http\\Controllers\\Apis\\')->group(function () {
    Route::namespace('Auth\\')->group(function () {
        Route::Post('login', 'AuthController@login');
    });
    Route::middleware('auth:sanctum')->group(function(){
        Route::get('jobs', 'JobsController@list');
        Route::Post('jobs', 'JobsController@store');
    });
});

