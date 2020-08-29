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
    $data['pods'] = DB::table('k_pods')
        ->distinct('podname')
        ->get();

    $data['reports'] = DB::table('k_reports')
        ->distinct('uid')
        ->get();

    $data['namespaces'] = DB::table('k_namespaces')
        ->distinct('name')
        ->get();

    $data['pods'] = DB::table('k_pods')
        ->distinct('podname')
        ->get();

    $data['images'] = DB::table('k_images')
        ->distinct('fulltag')
        ->get();
    
    return view('images', $data);
});

Route::get('/report2/{report_uid?}', function ($report_uid=null) {
    
    if ($report_uid == null) {
        $report_uid = DB::table('k_reports')
            ->first()->uid;
    }
    echo $report_uid;

    $data['reports'] = DB::table('k_reports')
        ->distinct('uid')
        ->get()
        ->toArray();

    $namespaces_list = DB::table('k_namespaces')
        ->where('report_uid', $report_uid)
        ->get();
    
    foreach ($namespaces_list as $n) {
        $namespaces[$n->uid] = json_decode(json_encode($n), true);
        $pods_list = DB::table('k_pods')
            ->where('report_uid', $report_uid)
            ->where('namespace_uid', $n->uid)
            ->get();
        
        foreach ($pods_list as $p) {
            $namespaces[$n->uid]['pods'][$p->uid] = json_decode(json_encode($p), true);
            $containers_list = DB::table('k_containers')
                ->where('report_uid', $report_uid)
                ->where('namespace_uid', $n->uid)
                ->where('pod_uid', $p->uid)
                ->get();
            
            foreach ($containers_list as $c) {
                $namespaces[$n->uid]['pods'][$p->uid]['containers'][$c->uid] = json_decode(json_encode($c), true);
                
                $images_list = DB::table('k_container_has_images')
                    ->join('k_images', 'k_container_has_images.image_uid', '=', 'k_images.uid')
                    ->where('k_container_has_images.container_uid', $c->uid)
                    ->where('k_container_has_images.report_uid', $report_uid)
                    ->get();
                foreach ($images_list as $i) {
                    $namespaces[$n->uid]['pods'][$p->uid]['containers'][$c->uid]['images'][$i->uid] = json_decode(json_encode($i), true);
                }
            }
        }
    }

    $vulnseverity = array(
                    "Critical" => 'bg-danger text-dark',
                    "High" => 'bg-warning text-white',
                    "Medium" => 'bg-info text-white',
                    "Low" => 'bg-secondary text-white',
                    "Negligible" => 'bg-dark text-white',
                    "Unknown" => 'bg-white text-dark'
                );

    $error = array(
            "danger",
            "success",
            "unknown",
        );
        
    $data['vulnseverity'] = $vulnseverity;
    $data['error'] = $error;


    $data['report_uid'] = $report_uid;
    
    $data['namespaces'] = $namespaces;
    
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    
    return view('reports', $data);
});

Route::get('/report/{checktime?}', function ($checktime=null) {
    
    if ($checktime == null) {
        $data['checktime'] = DB::collection('pods')
            ->distinct("checktime")
            ->first();
    }else{
        $data['checktime'] = new MongoDB\BSON\UTCDateTime($checktime);
    }

    $data['reports'] = DB::collection('pods')
        ->distinct("checktime")
        ->get();

    $data['stats']['pods'] = DB::collection('pods')
        ->where('checktime', $data['checktime'])
        ->distinct("metadata.name")
        ->get();

    $data['stats']['images'] = DB::collection('pods')
        ->where('checktime', $data['checktime'])
        ->distinct("containers.0.image")
        ->get();

    $data['stats']['namespaces'] = DB::collection('pods')
        ->where('checktime', $data['checktime'])
        ->distinct("metadata.namespace")
        ->get();

    $data['pods'] = DB::collection('pods')
        //->select("metadata.namespace","containers.image")
        ->where('checktime', $data['checktime'])
        ->get();
        //->first();
        //print_r($data);

    $vulnseverity = array(
                    "Critical" => 'bg-danger text-dark',
                    "High" => 'bg-warning text-white',
                    "Medium" => 'bg-info text-white',
                    "Low" => 'bg-secondary text-white',
                    "Negligible" => 'bg-dark text-white',
                    "Unknown" => 'bg-white text-dark'
                );

    $error = array(
            "danger",
            "success",
            "unknown",
        );
        
    $data['vulnseverity'] = $vulnseverity;
    $data['error'] = $error;

    return view('reports', $data);
});



Route::get('/pod/{podid}', function ($podid) {

    $vulnseverity = array(
                    "Critical" => 'bg-danger text-dark',
                    "High" => 'bg-warning text-white',
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