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
    Route::post('/vulnwhitelist/update/{image_uid}', function (Request $request, $image_uid) {
        $postdata = $request->post();
        $insertdata = array();

        $now = date(DATE_ATOM);
        foreach ($postdata['vuln_uid_list'] as $vuln) {
            if ($vuln['state']==='true') {
                $insertdata[] = ['uid'=>uniqid('', true), 'image_uid'=>$image_uid, 'images_vuln_uid'=> $vuln['uid'], 'whitelisttime'=>$now ];
            }
        }
        DB::table('k_images_vuln_whitelist')->where('image_uid', '=', $image_uid)->delete();
        DB::table('k_images_vuln_whitelist')->insert($insertdata);
        return ['success'=>'true', 'vuln_uid_list'=>$insertdata, 'image_uid'=>$image_uid];
    });
});



/*
Route::get('/test', function () {
    return [1, 2, 3];
});
*/