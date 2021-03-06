<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Report;

class ReportController extends Controller
{

    
    public function apiDeleteReport($uid)
    {
        Report::destroy($uid);
    }


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
                ->select('uid')
                ->selectRaw("to_char(k_reports.checktime, 'DD.MM.YYYY HH24:MI') as checktime, title")
                ->orderBy('checktime', 'DESC')
                ->first();
                
            if ($report == null) {
                ## Cheapest solution. Go back to home, if there are no reports yet
                return redirect('/');
            }
            $report_uid = $report->uid;
        } else {
            $report = DB::table('k_reports')
            ->select('uid')
            ->selectRaw("to_char(k_reports.checktime, 'DD.MM.YYYY HH24:MI') as checktime, title")
                ->where('uid', $report_uid)
                ->first();
        }
        
        if($report == null) {
            $report_uid = 0;
        }
    
        $data['report_data'] = $report;
    
        $data['reports'] = DB::table('k_reports')
            ->select('uid')
            ->selectRaw("to_char(k_reports.checktime, 'DD.MM HH24:MI') as checktime, title")
            ->orderBy('checktime', 'DESC')
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

            $audits_stats = DB::table('k_audits')
                ->select('severity_level', DB::raw('count(*) as total'))
                ->where('report_uid', $report_uid)
                ->where('namespace_uid', $n->uid)
                ->groupBy('severity_level')
                ->get();

            $namespaces[$n->uid]['stats'] = array(
                'error' => 0,
                'warning' => 0,
                'info' => 0
            );
            foreach ($audits_stats as $s) {
                $namespaces[$n->uid]['stats'][$s->severity_level] = $s->total;
            };

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
    
                        $vulnsummary_list = DB::table('k_vulnsummary')
                            ->where('image_uid', $i->uid)
                            ->where('report_uid', $report_uid)
                            ->get();

                        $os = DB::table('k_target_trivy')
                            ->where('image_uid', $i->uid)
                            ->where('report_uid', $report_uid)
                            ->select('k_target_trivy.target_type as distro')
                            ->first();

                        if ($os) {
                            $namespaces[$n->uid]['pods'][$p->uid]['containers'][$c->uid]['imagedetails']['distro'] = $os->distro;
                        } else {
                            $namespaces[$n->uid]['pods'][$p->uid]['containers'][$c->uid]['imagedetails']['distro'] = "unknown";
                        }

                        $vuln_ack_count = DB::table('k_vuln_trivy')
                            ->leftJoin('k_images', 'k_images.uid', '=', 'k_vuln_trivy.image_uid')
                            ->leftJoin('k_vulnwhitelist', function ($join) {
                                $join->on('k_vulnwhitelist.wl_image_b64', '=', 'image_b64')
                                      ->on('k_vulnwhitelist.wl_vuln', '=', 'vulnerability_id');
                            })
                            ->where('k_vuln_trivy.image_uid', $i->uid)
                            ->where('k_vuln_trivy.report_uid', $report_uid)
                            ->where('k_vulnwhitelist.uid', null)
                            ->distinct('k_vuln_trivy.uid')
                            ->select('k_vulnwhitelist.uid as images_vuln_whitelist_uid')
                            //->toSql();
                            ->count();
                        
                        $namespaces[$n->uid]['pods'][$p->uid]['containers'][$c->uid]['imagedetails']['vuln_ack_count'] = $vuln_ack_count;
                        
                        foreach ($vulnsummary_list as $v) {
                            $namespaces[$n->uid]['pods'][$p->uid]['containers'][$c->uid]['imagedetails']['vulnsummary'][$v->uid] = json_decode(json_encode($v), true);
                        }
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
                        "Unknown" => 'bg-light text-dark'
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
        
        return view('reportsshort', $data);
    }
}
