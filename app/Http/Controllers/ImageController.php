<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ImageController extends Controller
{
    /**
     * Show imagedetailes of an image in specific Report
     *
     * @param  string  $report_uid
     * @param  string  $image_uid
     * @return View
     */
    public function show($report_uid, $image_uid) 
    {

        $vulnseverity = array(
                        "Critical" => 'bg-danger text-dark',
                        "High" => 'bg-warning text-white',
                        "Medium" => 'bg-info text-white',
                        "Low" => 'bg-secondary text-white',
                        "Unknown" => 'bg-light text-dark',
                        "0" => 'bg-danger text-dark',
                        "1" => 'bg-warning text-white',
                        "2" => 'bg-info text-white',
                        "3" => 'bg-secondary text-white',
                        "4" => 'bg-light text-dark',
                        "5" => 'bg-light text-dark', ## Caused by a bug in runner
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

        $vulnhistory_sql = <<<SQL
            to_char(k_reports.checktime, 'DD.MM HH24:MI') as checktime,
            (SELECT 
                COUNT(k_vuln_trivy.uid) AS total 
                FROM k_vuln_trivy 
                LEFT JOIN k_images ON image_uid = k_images.uid
                WHERE fulltag='$image->fulltag' and 
                k_vuln_trivy.report_uid=k_reports.uid
            )
        SQL;

        $vulnhistory_list = DB::table('k_reports')
        ->selectRaw($vulnhistory_sql)
        ->get();

        foreach ($vulnhistory_list as $v) {
            $data['vulnhistory']['data'][] = $v->total;
            $data['vulnhistory']['labels'][] = "'".$v->checktime."'";
        }
        
        $vulnsummary_list = DB::table('k_vulnsummary')
            ->where('image_uid', $image_uid)
            ->where('report_uid', $report_uid)
            ->get();
    
        foreach ($vulnsummary_list as $v) {
            $data['image']['vulnsummary'][$v->uid] = json_decode(json_encode($v), true);
            $data['image']['vulnsummary_list'][$v->severity] = $v->total;
        }

        $targets_list = DB::table('k_target_trivy')
            ->where('image_uid', $image_uid)
            ->where('report_uid', $report_uid)
            ->get();
        
        foreach ($targets_list as $t) {
            $data['image']['targets'][$t->uid]['metadata'] = json_decode(json_encode($t), true);

            $data['image']['targets'][$t->uid]['vulnerabilities'] = [];

            $vuln_list = DB::table('k_vuln_trivy')
                ->leftJoin('k_images', 'k_images.uid', '=', 'k_vuln_trivy.image_uid')
                ->leftJoin('k_vulnwhitelist', function ($join) {
                    $join->on('k_vulnwhitelist.wl_image_b64', '=', 'image_b64')
                        ->on('k_vulnwhitelist.wl_vuln', '=', 'vulnerability_id');
                })
                ->where('k_vuln_trivy.image_uid', $image_uid)
                ->where('k_vuln_trivy.report_uid', $report_uid)
                ->where('k_vuln_trivy.target_uid', $t->uid)
                ->select('k_vuln_trivy.*', 'k_images.image_b64 as image_b64', 'k_vulnwhitelist.uid as images_vuln_whitelist_uid')
                ->orderBy('severity', 'ASC')
                ->get();

            foreach ($vuln_list as $vu) {
                $vulnerability =  json_decode(json_encode($vu), true);
                $vulnerability['links'] = json_decode($vulnerability['links'] , true);

                $vulnerability['cvss'] = json_decode($vulnerability['cvss'], true);

                if ($vulnerability['cvss'] != ''){
                    $vulnerability['cvss'] = current($vulnerability['cvss']);

                }

                if (isset($vulnerability['cvss']['V3Vector_base_score'])) {
                    $vulnerability['cvss_base_score'] = $vulnerability['cvss']['V3Vector_base_score'];
                } elseif (isset($vulnerability['cvss']['V2Vector_base_score']) && !isset($vulnerability['cvss']['V3Vector_base_score']) ) {
                    $vulnerability['cvss_base_score'] = $vulnerability['cvss']['V2Vector_base_score'];
                } else {
                    $vulnerability['cvss_base_score'] = '?';
                }
                
                $data['image']['targets'][$t->uid]['vulnerabilities'][$vu->uid] = $vulnerability;
            }

        }
        /*
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        */
        return view('image', $data);
    }
}
