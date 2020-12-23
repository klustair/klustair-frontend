<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VulnerabilitiesController extends Controller
{
    /**
     * Show a overview of Reports
     *
     * @return View
     */
    public function list() 
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

        $vuln_trivy_list = DB::table('k_vuln_trivy')
            ->leftJoin('k_vulnwhitelist', function ($join) {
                $join->on('k_vulnwhitelist.wl_vuln', '=', 'vulnerability_id');
            })
            ->select('k_vuln_trivy.*', 'k_vulnwhitelist.uid as images_vuln_whitelist_uid')
            ->orderBy('severity', 'ASC')
            ->get();

        foreach ($vuln_trivy_list as $vu) {
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
            
            $data['vulnerabilities'][$vu->uid] = $vulnerability;
        }

        /*
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        */
        return view('vulnerabilities.list', $data);
    }


    /**
     * Show a overview of Reports
     *
     * @return View
     */
     public function details($vuln_uid) 
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


        $vulnerability = DB::table('k_vuln_trivy')
            ->leftJoin('k_vulnwhitelist', function ($join) {
                $join->on('k_vulnwhitelist.wl_vuln', '=', 'vulnerability_id');
            })
            ->select('k_vuln_trivy.*', 'k_vulnwhitelist.uid as images_vuln_whitelist_uid')
            ->where('k_vuln_trivy.uid', $vuln_uid)
            ->first();

        $vulnerability = json_decode(json_encode($vulnerability), true);;

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
            

        $data['vulnerability'] = json_decode(json_encode($vulnerability), true);;
        /*
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        */
        return view('vulnerabilities.details', $data);
    }
 
}
