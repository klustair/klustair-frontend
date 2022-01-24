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
    Route::post('/vulnwhitelist/bulkupdate', 'VulnerabilitiesController@apiVulnwhitelistBulk');

    Route::get('/vulnerabilities', 'VulnerabilitiesController@apiListVulnerabilities');
});

# Forced auth routes 
Route::group(['prefix' => 'v1', 'middleware' => ['auth:sanctum']], function(){

    Route::get('/report/delete/{uid}', 'ReportController@apiDeleteReport');

    Route::get('/config/user/delete/{uid}', 'ConfigController@apiDeleteUser');
    
    Route::post('/config/token/create', 'ConfigController@apiCreateToken');
    Route::get('/config/token/delete/{uid}', 'ConfigController@apiDeleteToken');

    Route::post('/config/runner/create', 'ConfigController@apiCreateConfigRunner');
    Route::get('/config/runner/delete/{uid}', 'ConfigController@apiDeleteConfigRunner');

});

// Auth with personal Access Token
Route::group(['prefix' => 'v1/pac', 'middleware' => ['auth:api']], function(){

    Route::get('/config/runner/get/{uid}', 'ConfigController@apiGetConfigRunner');
    Route::post('/report/create', 'ReportController@apiCreateReport');
    Route::post('/report/{report_uid}/namespace/create', 'ReportController@apiCreateNamespace');
    Route::post('/report/{report_uid}/audit/create', 'ReportController@apiCreateAudit');
    Route::post('/report/{report_uid}/pod/create', 'ReportController@apiCreatePod');
    Route::post('/report/{report_uid}/container/create', 'ReportController@apiCreateContainer');
    Route::post('/report/{report_uid}/image/create', 'ReportController@apiCreateImage');
    Route::post('/report/{report_uid}/{image_uid}/vuln/create', 'ReportController@apiCreateVuln');
    Route::post('/report/{report_uid}/vuln/summary/create', 'ReportController@apiVulnsummary');
    Route::post('/report/{report_uid}/containerhasimage/create', 'ReportController@apiContainerHasImage');
    Route::post('/report/{report_uid}/summary/create', 'ReportController@apiReportsSummary');

    Route::post('/report/cleanup', 'ReportController@apiReportsCleanup');
});