<?php

namespace App\Http\Controllers\Anchore;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class SubscriptionsController extends Controller
{
    public function list() 
    {
        $anchore_api_user = env('ANCHORE_CLI_USER', 'admin');
        $anchore_api_pass = env('ANCHORE_CLI_PASS', 'foobar');
        $anchore_api_url = env('ANCHORE_API_URL', 'http://host.docker.internal:8228');

        $anchore_api = Http::withBasicAuth($anchore_api_user, $anchore_api_pass);

        $subscriptions = $anchore_api->get("${anchore_api_url}/v1/subscriptions")->json();

        $subscriptions_list = array();
        foreach ($subscriptions as $subscription) {
            
            $subscriptions_list[$subscription['subscription_key']][$subscription['subscription_type']] = $subscription;
            
        }

        $data['subscriptions'] = $subscriptions_list;
        /*
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        */
        
        return view('anchore/subscriptions', $data);
    }
}
