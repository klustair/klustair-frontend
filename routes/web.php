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

Route::get('/lists', function () {

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

Route::get('/report/{report_uid?}', function ($report_uid=null) {
    
    if ($report_uid == null) {
        $report = DB::table('k_reports')
            ->orderBy('checktime', 'DESC')
            ->first();
    } else {
        $report = DB::table('k_reports')
            ->where('uid', $report_uid)
            ->first();
    }
    $report_uid = $report->uid;
    $data['report_data'] = $report;

    $data['reports'] = DB::table('k_reports')
        ->distinct('uid')
        ->get()
        ->toArray();
    
    $data['stats']['namespaces'] = 0;
    $data['stats']['pods'] = 0;
    $data['stats']['containers'] = 0;
    $data['stats']['images'] = 0;
    
    $namespaces_list = DB::table('k_namespaces')
        ->where('report_uid', $report_uid)
        ->get();
    
    foreach ($namespaces_list as $n) {
        $data['stats']['namespaces']++;
        $namespaces[$n->uid] = json_decode(json_encode($n), true);
        $pods_list = DB::table('k_pods')
            ->where('report_uid', $report_uid)
            ->where('namespace_uid', $n->uid)
            ->get();
        
        foreach ($pods_list as $p) {
            $data['stats']['pods']++;
            $namespaces[$n->uid]['pods'][$p->uid] = json_decode(json_encode($p), true);
            $containers_list = DB::table('k_containers')
                ->where('report_uid', $report_uid)
                ->where('namespace_uid', $n->uid)
                ->where('pod_uid', $p->uid)
                ->get();
            
            foreach ($containers_list as $c) {
                $data['stats']['containers']++;
                $namespaces[$n->uid]['pods'][$p->uid]['containers'][$c->uid] = json_decode(json_encode($c), true);
                
                $images_list = DB::table('k_container_has_images')
                    ->join('k_images', 'k_container_has_images.image_uid', '=', 'k_images.uid')
                    ->where('k_container_has_images.container_uid', $c->uid)
                    ->where('k_container_has_images.report_uid', $report_uid)
                    ->get();
                
                foreach ($images_list as $i) {
                    $data['stats']['images']++;
                    $namespaces[$n->uid]['pods'][$p->uid]['containers'][$c->uid]['imagedetails']= json_decode(json_encode($i), true);

                    $vulnsummary_list = DB::table('k_images_vulnsummary')
                        ->where('image_uid', $i->uid)
                        ->where('report_uid', $report_uid)
                        ->get();
                }

                foreach ($vulnsummary_list as $v) {
                    $namespaces[$n->uid]['pods'][$p->uid]['containers'][$c->uid]['imagedetails']['vulnsummary'][$v->uid] = json_decode(json_encode($v), true);
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
    
    $data['namespaces'] = $namespaces;
    /*
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    */
    return view('reports', $data);
});


Route::get('/image/{report_uid}/{image_uid}', function ($report_uid, $image_uid) {

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

    $image = DB::table('k_images')
        ->where('uid', $image_uid)
        ->where('report_uid', $report_uid)
        ->first();

    $data['image']= json_decode(json_encode($image), true);

    $vulnsummary_list = DB::table('k_images_vulnsummary')
        ->where('image_uid', $image_uid)
        ->where('report_uid', $report_uid)
        ->get();

    foreach ($vulnsummary_list as $v) {
        $data['image']['vulnsummary'][$v->uid] = json_decode(json_encode($v), true);
    }

    $vuln_list = DB::table('k_images_vuln')
        ->where('image_uid', $image_uid)
        ->where('report_uid', $report_uid)
        ->get();

    foreach ($vuln_list as $vu) {
        $data['image']['vulnerabilities'][$vu->uid] = json_decode(json_encode($vu), true);
    }
    /*
    echo "<pre>";
    print_r($data);
    echo "</pre>";
    */
    return view('image', $data);
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