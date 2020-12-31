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

Auth::routes([
    'register' => config('klustair.auth.register'), 
    'reset' => config('klustair.auth.reset'), 
    'verify' => config('klustair.auth.verify')
    ]);

$middlewares = array();
if (config('klustair.auth.enabled')) {
    $middlewares[] = 'auth';
}

Route::middleware($middlewares)->group(function () {
    Route::get('/', 'HomeController@home');

    Route::get('/lists', 'DebugController@list' );

    Route::get('/report/{report_uid?}', 'ReportController@overview');

    Route::get('/image/{report_uid}/{image_uid}', 'ImageController@show' );

    Route::get('/vulnerabilities', 'VulnerabilitiesController@list' );

    Route::get('/vulnerability/{vuln_uid}', 'VulnerabilitiesController@details' );

    Route::get('/namespace/{report_uid}/{namespace_uid}', 'NamespaceController@show' );

    Route::get('/anchore/images', 'Anchore\ImagesController@list' );

    Route::get('/anchore/feeds', 'Anchore\FeedsController@list' );

    Route::get('/anchore/registries', 'Anchore\RegistriesController@list' );

    Route::get('/anchore/system', 'Anchore\SystemController@list' );

    Route::get('/anchore/policies', 'Anchore\PolicyController@list' );

    Route::get('/anchore/subscriptions', 'Anchore\SubscriptionsController@list' );
});