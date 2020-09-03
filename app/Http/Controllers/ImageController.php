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
    }
}
