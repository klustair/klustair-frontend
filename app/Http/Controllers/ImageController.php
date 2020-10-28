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
                        "4" => 'bg-light text-dark'
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
                COUNT(k_images_trivyvuln.uid) AS total 
                FROM k_images_trivyvuln 
                LEFT JOIN k_images ON image_uid = k_images.uid
                WHERE fulltag='$image->fulltag' and 
                k_images_trivyvuln.report_uid=k_reports.uid
            )
        SQL;

        $vulnhistory_list = DB::table('k_reports')
        ->selectRaw($vulnhistory_sql)
        ->get();

        foreach ($vulnhistory_list as $v) {
            $data['vulnhistory']['data'][] = $v->total;
            $data['vulnhistory']['labels'][] = "'".$v->checktime."'";
        }
        
        $vulnsummary_list = DB::table('k_images_vulnsummary')
            ->where('image_uid', $image_uid)
            ->where('report_uid', $report_uid)
            ->get();
    
        foreach ($vulnsummary_list as $v) {
            $data['image']['vulnsummary'][$v->uid] = json_decode(json_encode($v), true);
            $data['image']['vulnsummary_list'][$v->severity] = $v->total;
        }
/*
        $vuln_list_ancore = DB::table('k_images_vuln')
            ->leftJoin('k_images', 'k_images.uid', '=', 'k_images_vuln.image_uid')
            ->leftJoin('k_images_vuln_whitelist', function ($join) {
                $join->on('k_images_vuln_whitelist.wl_anchore_imageid', '=', 'anchore_imageid')
                      ->on('k_images_vuln_whitelist.wl_vuln', '=', 'vuln');
            })
            ->where('k_images_vuln.image_uid', $image_uid)
            ->where('k_images_vuln.report_uid', $report_uid)
            ->select('k_images_vuln.*', 'k_images.anchore_imageid as anchore_imageid', 'k_images_vuln_whitelist.uid as images_vuln_whitelist_uid')
            ->get();
*/

        $vuln_list = DB::table('k_images_trivyvuln')
            ->leftJoin('k_images', 'k_images.uid', '=', 'k_images_trivyvuln.image_uid')
            ->leftJoin('k_images_vuln_whitelist', function ($join) {
                $join->on('k_images_vuln_whitelist.wl_anchore_imageid', '=', 'anchore_imageid')
                      ->on('k_images_vuln_whitelist.wl_vuln', '=', 'vulnerability_id');
            })
            ->where('k_images_trivyvuln.image_uid', $image_uid)
            ->where('k_images_trivyvuln.report_uid', $report_uid)
            ->select('k_images_trivyvuln.*', 'k_images.anchore_imageid as anchore_imageid', 'k_images_vuln_whitelist.uid as images_vuln_whitelist_uid')
            ->orderBy('severity', 'ASC')
            ->get();

        foreach ($vuln_list as $vu) {
            $data['image']['vulnerabilities'][$vu->uid] = json_decode(json_encode($vu), true);
            $data['image']['vulnerabilities'][$vu->uid]['links'] = json_decode($data['image']['vulnerabilities'][$vu->uid]['links'] , true);

            $data['image']['vulnerabilities'][$vu->uid]['cvss'] = json_decode($data['image']['vulnerabilities'][$vu->uid]['cvss'], true);

            if ($data['image']['vulnerabilities'][$vu->uid]['cvss'] != ''){
                $data['image']['vulnerabilities'][$vu->uid]['cvss'] = current($data['image']['vulnerabilities'][$vu->uid]['cvss']);

            }

            if (isset($data['image']['vulnerabilities'][$vu->uid]['cvss']['V3Vector_base_score'])) {
                $data['image']['vulnerabilities'][$vu->uid]['cvss_base_score'] = $data['image']['vulnerabilities'][$vu->uid]['cvss']['V3Vector_base_score'];
            } elseif (isset($data['image']['vulnerabilities'][$vu->uid]['cvss']['V2Vector_base_score']) && !isset($data['image']['vulnerabilities'][$vu->uid]['cvss']['V3Vector_base_score']) ) {
                $data['image']['vulnerabilities'][$vu->uid]['cvss_base_score'] = $data['image']['vulnerabilities'][$vu->uid]['cvss']['V2Vector_base_score'];
            } else {
                $data['image']['vulnerabilities'][$vu->uid]['cvss_base_score'] = '?';
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
