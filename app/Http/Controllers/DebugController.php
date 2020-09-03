<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\DB;

use Illuminate\Http\Request;

class DebugController extends Controller
{
    /**
     * Show imagedetailes of an image in specific Report
     *
     * @param  string  $report_uid
     * @param  string  $image_uid
     * @return View
     */
    public function list() 
    {

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
    }
}
