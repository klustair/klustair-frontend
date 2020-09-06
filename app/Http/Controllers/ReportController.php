<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Show a overview of Reports
     *
     * @param  string  $report_uid
     * @return View
     */
    public function overview($report_uid=null) 
    {
        if ($report_uid == null) {
            $report = DB::table('k_reports')
                ->orderBy('checktime', 'DESC')
                ->first();
            $report_uid = $report->uid;
        } else {
            $report = DB::table('k_reports')
                ->where('uid', $report_uid)
                ->first();
        }
        
        if($report == null) {
            $report_uid = 0;
        }
    
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
        
        $namespaces = [];
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
    }
}