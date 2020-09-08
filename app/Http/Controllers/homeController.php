<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\DB;

class homeController extends Controller
{
    /**
     * Load Overview data
     *
     * @return View
     */
    public function home() 
    {
        $anchore_api_user = env('ANCHORE_CLI_USER', 'admin');
        $anchore_api_pass = env('ANCHORE_CLI_PASS', 'foobar');
        $anchore_api_url = env('ANCHORE_API_URL', 'http://host.docker.internal:8228');

        $anchore_api = Http::withBasicAuth($anchore_api_user, $anchore_api_pass);
        
        $status = $anchore_api->get("${anchore_api_url}/v1/system");
        $data['anchore_status'] = $status->json();

        $data['reports_count'] = DB::table('k_reports')
            ->distinct('uid')
            ->count();

        $status = $anchore_api->get("${anchore_api_url}/v1/images");
        $data['anchore_images'] = $status->json();

        $status = $anchore_api->get("${anchore_api_url}/v1/policies");
        $data['anchore_policies'] = $status->json();

        $data['anchore_feeds'] = $anchore_api->get("${anchore_api_url}/v1/system/feeds")->json();
        return view('home', $data);
    }
}
