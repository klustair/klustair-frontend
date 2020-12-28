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
        $data['anchore_enabled'] = env('ANCHORE_ENABLED', false);

        if ($data['anchore_enabled']) {

            $anchore_api_user = env('ANCHORE_CLI_USER', 'admin');
            $anchore_api_pass = env('ANCHORE_CLI_PASS', 'foobar');
            $anchore_api_url = env('ANCHORE_API_URL', 'http://host.docker.internal:8228');
    
            $anchore_api = Http::withBasicAuth($anchore_api_user, $anchore_api_pass);
            try {
                
                $data['anchore_status'] = $anchore_api->get("${anchore_api_url}/v1/system")->json();
    
                $data['anchore_images'] = $anchore_api->get("${anchore_api_url}/v1/images")->json();
                
                $data['anchore_policies'] = $anchore_api->get("${anchore_api_url}/v1/policies")->json();
    
                $data['anchore_feeds'] = $anchore_api->get("${anchore_api_url}/v1/system/feeds")->json();
                
            } catch (ConnectionException $e) {
                $data['anchore_status'] = array(
                    'service_states' => array()
                );
                $data['anchore_images'] = array();
                $data['anchore_policies'] = array();
                $data['anchore_feeds'] = array();
            } 
        }


        $reports_summaries = DB::table('k_reports_summaries')
            ->leftJoin('k_reports', 'k_reports.uid', '=', 'k_reports_summaries.report_uid')
            ->selectRaw("to_char(k_reports.checktime, 'DD.MM.YYYY HH24:MI') as checktime, title, k_reports_summaries.*, vuln_total/images as vuln_avg")
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
    

        return view('home', $data);
    }
}
