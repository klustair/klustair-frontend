<?php

use Illuminate\Support\Facades\Route;
use Jenssegers\Mongodb\Query\Builder;

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
    return view('home');
});

Route::get('/images', function () {

/*

    $klustair_all = DB::collection('pods')
        ->get();
    print_r($klustair_all);

    $klustair_all = DB::collection('pods')
        ->first();
    print_r($klustair_all);

    $klustair_namespace = DB::collection('pods')
        ->where('metadata.namespace', 'cms-search')
        ->first();
    print_r($klustair_namespace);

    $data['images'] = DB::collection('pods')
        ->select("metadata.namespace","containers.image")
        ->get();
    print_r($data);
*/
    $data['pods'] = DB::collection('pods')
        ->distinct("metadata.name")
        ->get();

    $data['images'] = DB::collection('pods')
        ->distinct("containers.0.image")
        ->get();

    $data['reports'] = DB::collection('pods')
        ->distinct("checktime")
        ->get();
    

    return view('images', $data);
});

Route::get('/latestreport', function () {

    $data['checktime'] = DB::collection('pods')
        ->distinct("checktime")
        ->first();
    $data['pods'] = DB::collection('pods')
        //->select("metadata.namespace","containers.image")
        ->where('checktime', $data['checktime'])
        ->get();
        //->first();
        //print_r($data);

    $vulnseverity = array(
                    "High" => 'bg-danger text-white',
                    "Critical" => 'bg-warning text-dark',
                    "Medium" => 'bg-info text-white',
                    "Low" => 'bg-secondary text-white',
                    "Negligible" => 'bg-dark text-white',
                    "Unknown" => 'bg-white text-dark'
                );

    $error = array(
            "danger",
            "success",
        );
        
    $data['vulnseverity'] = $vulnseverity;
    $data['error'] = $error;

    return view('reports', $data);
});



Route::get('/pod/{podid}', function ($podid) {

    $vulnseverity = array(
                    "High" => 'bg-danger text-white',
                    "Critical" => 'bg-warning text-dark',
                    "Medium" => 'bg-info text-white',
                    "Low" => 'bg-secondary text-white',
                    "Negligible" => 'bg-dark text-white',
                    "Unknown" => 'bg-white text-dark'
                );

    $error = array(
            "danger",
            "success",
        );
        
    $data['vulnseverity'] = $vulnseverity;
    $data['error'] = $error;

    $data['pod'] = DB::collection('pods')
        ->where('metadata.uid', $podid)
        ->first();
    $data['podid'] = $podid;
    return view('pod', $data);
});