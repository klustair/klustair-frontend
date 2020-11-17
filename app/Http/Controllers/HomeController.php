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

        $data['reports_count'] = DB::table('k_reports')
            ->distinct('uid')
            ->count();

        $data['container_count'] = DB::table('k_containers')
            ->distinct('name')
            ->count();

        $data['namespaces_count'] = DB::table('k_namespaces')
            ->distinct('name')
            ->count();

        $data['vuln_count'] = DB::table('k_images_trivyvuln')
            ->distinct('vulnerability_id')
            ->count();
    

        return view('home', $data);
    }
}
