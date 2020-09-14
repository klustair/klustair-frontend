<?php

namespace App\Http\Controllers\Anchore;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ImagesController extends Controller
{
    public function list() 
    {   
        $anchore_api_user = env('ANCHORE_CLI_USER', 'admin');
        $anchore_api_pass = env('ANCHORE_CLI_PASS', 'foobar');
        $anchore_api_url = env('ANCHORE_API_URL', 'http://host.docker.internal:8228');

        $anchore_api = Http::withBasicAuth($anchore_api_user, $anchore_api_pass);

        $images = $anchore_api->get("${anchore_api_url}/v1/images")->json();

        # this is the PHP implementation of this anchore_cli Code to show only newest version of image
        # https://github.com/anchore/anchore-cli/blob/ef2108b20a5895c6ebdb26ec47ce53f39ef52adc/anchorecli/cli/utils.py#L251
        $latest_tag_details = array();
        $latest_records = array();
        foreach ($images as $image_record) {
            foreach ($image_record['image_detail'] as $image_detail) {
                $fulltag = $image_detail['fulltag'];
                $tagts = strtotime ( $image_detail['created_at'] );
                if ( !array_key_exists($fulltag, $latest_tag_details) ){
                    $latest_tag_details[$fulltag] = $image_detail;
                    $latest_records[$fulltag] = $image_record;
                } else {
                    $lasttagts = strtotime($latest_tag_details[$fulltag]['created_at']);
                    if ($tagts >= $lasttagts) {
                        $latest_tag_details[$fulltag] = $image_detail;
                        $latest_records[$fulltag] = $image_record;
                    }
                }
            }
        }

        $data['images'] = $latest_records;

        /*
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        */
        return view('anchore/images', $data);
    }
}
