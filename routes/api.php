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

$middlewares = array();
if (config('klustair.auth.enabled')) {
    $middlewares[] = 'auth:sanctum';
}

# Optional auth routes 
Route::group(['prefix' => 'v1', 'middleware' => $middlewares], function(){
    Route::post('/vulnwhitelist/update/{wl_image_b64}', 'VulnerabilitiesController@apiVulnwhitelist');
});

# Forced auth routes 
Route::group(['prefix' => 'v1', 'middleware' => ['auth:sanctum']], function(){

    Route::get('/report/delete/{uid}', 'ReportController@apiDeleteReport');

    Route::get('/config/user/delete/{uid}', 'ConfigController@apiDeleteUser');
    
    Route::post('/config/token/create', 'ConfigController@apiCreateToken');
    Route::get('/config/token/delete/{uid}', 'ConfigController@apiDeleteToken');

    Route::post('/config/runner/create', 'ConfigController@apiCreateConfigRunner');
    Route::get('/config/runner/delete/{uid}', 'ConfigController@apiDeleteConfigRunner');

    Route::get('/test', function () {
        return [1, 2, 3];
    });
    
});
