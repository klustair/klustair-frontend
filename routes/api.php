<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});


Route::prefix('v1')->group(function () {
    Route::post('/vulnwhitelist/update/{wl_anchore_imageid}', function (Request $request, $wl_anchore_imageid) {
        $postdata = $request->post();
        $insertdata = array();

        $now = date(DATE_ATOM);
        if (isset($postdata['vuln_list'])) {
            $vulnlist = json_decode(base64_decode($postdata['vuln_list']), true);
            foreach ($vulnlist as $vuln) {
                
                if (isset($vuln['state']) && $vuln['state']=='true') {
                    $insertdata[] = ['uid'=>uniqid('', true), 'wl_anchore_imageid'=>$wl_anchore_imageid, 'wl_vuln'=> $vuln['vuln'], 'whitelisttime'=>$now ];
                }
            }
        }
        DB::table('k_images_vuln_whitelist')->where('wl_anchore_imageid', '=', $wl_anchore_imageid)->delete();
        DB::table('k_images_vuln_whitelist')->insert($insertdata);
        return ['success'=>'true', 'vuln_list'=>$insertdata, 'wl_anchore_imageid'=>$wl_anchore_imageid];
    });
});



/*
Route::get('/test', function () {
    return [1, 2, 3];
});
*/