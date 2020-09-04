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

Route::get('/', 'homeController@home');

Route::get('/lists', 'DebugController@list' );

Route::get('/report/{report_uid?}', 'ReportController@overview');

Route::get('/image/{report_uid}/{image_uid}', 'ImageController@show' );
