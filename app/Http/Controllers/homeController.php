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
        $anchore_api = Http::withBasicAuth('admin', 'foobar');
        
        $status = $anchore_api->get('http://host.docker.internal:8228/v1/system');
        $data['anchore_status'] = $status->json();

        $data['reports_count'] = DB::table('k_reports')
            ->distinct('uid')
            ->count();

        $status = $anchore_api->get('http://host.docker.internal:8228/v1/images');
        $data['anchore_images'] = $status->json();

        $status = $anchore_api->get('http://host.docker.internal:8228/v1/policies');
        $data['anchore_policies'] = $status->json();

        $data['anchore_feeds'] = $anchore_api->get('http://host.docker.internal:8228/v1/system/feeds')->json();
        return view('home', $data);
    }
}
