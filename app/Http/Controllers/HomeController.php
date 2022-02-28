<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    /**
     * Load Overview data
     *
     * @return View
     */
    public function home() 
    {

        $reports_summaries = DB::table('k_reports_summaries')
            ->leftJoin('k_reports', 'k_reports.uid', '=', 'k_reports_summaries.report_uid')
            ->selectRaw("to_char(k_reports.checktime, 'DD.MM.YYYY HH24:MI') as checktime, k_reports.uid as reports_uid, title, k_reports_summaries.*, vuln_total/NULLIF(images,0) as vuln_avg")
            ->orderBy('id', 'DESC')
            ->get()
            ->toArray();
        
        $data['reports_summaries']['full'] = $reports_summaries;
        $data['reports_summaries']['pods'] = array_column($reports_summaries, 'pods');
        $data['reports_summaries']['namespaces_checked'] = array_column($reports_summaries, 'namespaces_checked');
        $data['reports_summaries']['vuln_avg'] = array_column($reports_summaries, 'vuln_avg');
        
        $data['reports_summaries']['checktime'] = array_column($reports_summaries, 'checktime');

        $data['reports_count'] = DB::table('k_reports')
            ->distinct('uid')
            ->count();

        $data['pods_count'] = DB::table('k_pods')
            ->distinct('podname')
            ->count();

        $data['namespaces_count'] = DB::table('k_namespaces')
            ->distinct('name')
            ->count();

        $data['vuln_count'] = DB::table('k_vuln_trivy')
            ->distinct('vulnerability_id')
            ->count();
    
        /*
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        */
        return view('home', $data);
    }
}
