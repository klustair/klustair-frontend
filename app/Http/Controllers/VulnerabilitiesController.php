<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class VulnerabilitiesController extends Controller
{
    /**
     * Return the View
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function apiListVulnerabilities(Request $request)
    {
        $data=array();

        $vulnseverity = array(
            "0" => 'bg-danger text-dark',
            "1" => 'bg-warning text-white',
            "2" => 'bg-info text-white',
            "3" => 'bg-secondary text-white',
            "4" => 'bg-light text-dark'
        );

        $request->validate([
            'draw' => 'integer|min:1',
            'start' => 'integer|min:0',
            'length' => 'integer|min:10|max:100',
            /*
            'sort_by' => 'string|in:id,name,description,created_at,updated_at',
            'sort_dir' => 'string|in:asc,desc',
            'search' => 'string',
            */
        ]);

        $search = "%".$request->search['value']."%";

        $vuln_trivy_count = DB::table('k_vuln_details_view')
            ->leftJoin('k_vulnwhitelist', function ($join) {
                $join->on('k_vulnwhitelist.wl_vuln', '=', 'vulnerability_id');
            });
        
        if($search!="%%") $vuln_trivy_count = $vuln_trivy_count->where('title', 'LIKE', $search);

        if ($request->ack && $request->ack == 'true') {
            $vuln_trivy_count = $vuln_trivy_count->where('k_vulnwhitelist.uid', '!=', null);
        } elseif ($request->ack && $request->ack == 'false') {
            $vuln_trivy_count = $vuln_trivy_count->where('k_vulnwhitelist.uid', '=', null);
        }

        if ($request->fix && $request->fix == 'true') {
            $vuln_trivy_count = $vuln_trivy_count->where('k_vuln_details_view.fixed_version', '!=', '');
        } elseif ($request->fix && $request->fix == 'false') {
            $vuln_trivy_count = $vuln_trivy_count->where('k_vuln_details_view.fixed_version', '=', '');
        }

        $vuln_trivy_count = $vuln_trivy_count->count(DB::raw('DISTINCT vulnerability_id'));

        $vuln_trivy_list = DB::table('k_vuln_details_view')
            ->leftJoin('k_vulnwhitelist', function ($join) {
                $join->on('k_vulnwhitelist.wl_vuln', '=', 'vulnerability_id');
            })
            ->distinct('vulnerability_id', 'severity')
            ->select('k_vuln_details_view.vulnerability_id', 
            'k_vuln_details_view.title',
            'k_vuln_details_view.pkg_name',
            'k_vuln_details_view.cvss',
            'k_vuln_details_view.severity',
            'k_vuln_details_view.uid',
            'k_vuln_details_view.fixed_version',
            'k_vulnwhitelist.uid as images_vuln_whitelist_uid');
        
        if ($request->ack && $request->ack == 'true') {
            $vuln_trivy_list = $vuln_trivy_list->where('k_vulnwhitelist.uid', '!=', null);
        } elseif ($request->ack && $request->ack == 'false') {
            $vuln_trivy_list = $vuln_trivy_list->where('k_vulnwhitelist.uid', '=', null);
        }

        if ($request->fix && $request->fix == 'true') {
            $vuln_trivy_list = $vuln_trivy_list->where('k_vuln_details_view.fixed_version', '!=', '');
        } elseif ($request->fix && $request->fix == 'false') {
            $vuln_trivy_list = $vuln_trivy_list->where('k_vuln_details_view.fixed_version', '=', '');
        }
        
            //->where('report_uid', '=', $report_uid)
        
        if($search!="%%") $vuln_trivy_list = $vuln_trivy_list->where('title', 'LIKE', $search);

        $vuln_trivy_list = $vuln_trivy_list->orderBy('severity', 'ASC')
            ->offset(intval($request->start))
            ->limit(intval($request->length))
            ->get();

        foreach ($vuln_trivy_list as $vu) {
            $vulnerability =  json_decode(json_encode($vu), true);

            $vulnerability['cvss'] = json_decode($vulnerability['cvss'], true);
            /*
            if ($vulnerability['cvss'] != ''){
                $vulnerability['cvss'] = current($vulnerability['cvss']);
            }
            */

            if (isset($vulnerability['cvss']['version']) && $vulnerability['cvss']['version'] >= 3) {
                $vulnerability['cvss_base_score'] = $vulnerability['cvss']['v3']['scores']['base'];
            } elseif (isset($vulnerability['cvss']['version']) && $vulnerability['cvss']['version'] == 2) {
                $vulnerability['cvss_base_score'] = $vulnerability['cvss']['v2']['scores']['base'];
            } else {
                $vulnerability['cvss_base_score'] = '?';
            }

            $images_count =  DB::table('k_vuln_details_view')
                ->leftJoin('k_images', 'k_images.uid', '=', 'k_vuln_details_view.image_uid')
                ->leftJoin('k_containers', 'k_containers.image', '=', 'k_images.fulltag')
                ->leftJoin('k_namespaces', 'k_namespaces.uid', '=', 'k_containers.namespace_uid')
                ->distinct('k_images.fulltag',)
                ->select('k_images.fulltag', 'k_images.uid', 'k_images.report_uid', 'k_namespaces.name')
                ->where('k_vuln_details_view.vulnerability_id', $vu->vulnerability_id)
                ->count();

            $vulnerability['imagecount'] = $images_count;
            $vulnerability['title'] = $vulnerability['title'];

            $vulnerability['severity'] = substr($vulnseverity[$vu->severity], 0, 150);

            $data[] = $vulnerability;
        }

        $return = array(
            'draw' => intval($request->draw),
            'data' => $data,
            'recordsTotal' => $vuln_trivy_count,
            'recordsFiltered' => $vuln_trivy_count
        );
        
        return $return;
    }

    public function apiVulnwhitelistBulk (Request $request) 
    {
        $postdata = $request->post();
        $insertdata = array();
        $deletedata = array();

        $now = date(DATE_ATOM);
        if (isset($postdata['vuln_list'])) {
            $vulnlist = json_decode(base64_decode($postdata['vuln_list']), true);
            foreach ($vulnlist as $vuln) {
                
                if (isset($vuln['state']) && $vuln['state']=='true') {
                    $insertdata[] = ['uid'=>uniqid('', true), 'wl_vuln'=> $vuln['vuln'], 'whitelisttime'=>$now ];
                } elseif (isset($vuln['state']) && $vuln['state']=='false') {
                    $deletedata[] = ['wl_vuln'=> $vuln['vuln']];
                }
            }
            DB::table('k_vulnwhitelist')->whereIn('wl_vuln', $deletedata)->delete();
            DB::table('k_vulnwhitelist')->insert($insertdata);
        }
        return ['success'=>'true', 'vuln_list'=>$vulnlist];

    }

    /**
     * Whitelist a list of vulnerabilities
     *
     * @return View
     */
    public function apiVulnwhitelist (Request $request, $wl_image_b64) 
    {
        $postdata = $request->post();
        $insertdata = array();

        $now = date(DATE_ATOM);
        if (isset($postdata['vuln_list'])) {
            $vulnlist = json_decode(base64_decode($postdata['vuln_list']), true);
            foreach ($vulnlist as $vuln) {
                
                if (isset($vuln['state']) && $vuln['state']=='true') {
                    $insertdata[] = ['uid'=>uniqid('', true), 'wl_image_b64'=>$wl_image_b64, 'wl_vuln'=> $vuln['vuln'], 'whitelisttime'=>$now ];
                }
            }
        }
        DB::table('k_vulnwhitelist')->where('wl_image_b64', '=', $wl_image_b64)->delete();
        DB::table('k_vulnwhitelist')->insert($insertdata);
        return ['success'=>'true', 'vuln_list'=>$insertdata, 'wl_image_b64'=>$wl_image_b64];
    }
    /**
     * Show a overview of Reports
     *
     * @return View
     */
    public function list() 
    {


        $error = array(
            "danger",
            "success",
        );

        $data['error'] = $error;

        /*
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        */
        return view('vulnerabilities.list', $data);
    }


    /**
     * Show a overview of Reports
     *
     * @return View
     */
     public function details($vulnerability_id) 
     {

        $vulnseverity = array(
            "Critical" => 'bg-danger text-dark',
            "High" => 'bg-warning text-white',
            "Medium" => 'bg-info text-white',
            "Low" => 'bg-secondary text-white',
            "Unknown" => 'bg-light text-dark',
            "0" => array(
                'name' => 'Critical',
                'css' => 'bg-danger text-dark'
            ),
            "1" => array(
                'name' => 'High',
                'css' => 'bg-warning text-white'
            ),
            "2" => array(
                'name' => 'Medium',
                'css' => 'bg-info text-white'
            ),
            "3" => array(
                'name' => 'Low',
                'css' => 'bg-secondary text-white'
            ),
            "4" => array(
                'name' => 'Unknown',
                'css' => 'bg-light text-dark'
            )
        );

        $error = array(
            "danger",
            "success",
        );

        $data['vulnseverity'] = $vulnseverity;
        $data['error'] = $error;


        $vulnerability = DB::table('k_vuln_details_view')
            ->leftJoin('k_vulnwhitelist', function ($join) {
                $join->on('k_vulnwhitelist.wl_vuln', '=', 'vulnerability_id');
            })
            ->select('k_vuln_details_view.*', 'k_vulnwhitelist.uid as images_vuln_whitelist_uid')
            ->where('k_vuln_details_view.vulnerability_id', $vulnerability_id)
            ->first();

        $vulnerability = json_decode(json_encode($vulnerability), true);;

        $vulnerability['links'] = json_decode($vulnerability['links'] , true);

        $vulnerability['cvss'] = json_decode($vulnerability['cvss'], true);

        /*
        if ($vulnerability['cvss'] != ''){
            $vulnerability['cvss'] = current($vulnerability['cvss']);
        }
        */

        if (isset($vulnerability['cvss']['version']) && $vulnerability['cvss']['version'] >= 3) {
            $vulnerability['cvss_base_score'] = $vulnerability['cvss']['v3']['scores']['base'];
        } elseif (isset($vulnerability['cvss']['version']) && $vulnerability['cvss']['version'] == 2) {
            $vulnerability['cvss_base_score'] = $vulnerability['cvss']['v2']['scores']['base'];
        } else {
            $vulnerability['cvss_base_score'] = '?';
        }

        $cwe_id_arr = json_decode($vulnerability['cwe_ids'], true);
        
        $vulnerability['cwe'] = array();
        if (is_array($cwe_id_arr)){

            foreach ($cwe_id_arr as $cwe_id){
                $cwe_arr =  (array) DB::table('k_cwe')
                    ->where('k_cwe.cwe_id', $cwe_id)
                    ->first();
                
                if (isset($cwe_arr['common_consequences'])){
                    $cwe_arr['common_consequences'] = json_decode($cwe_arr['common_consequences']);
                    $vulnerability['cwe'][] = $cwe_arr;
                } 
            }
        } 
        

        $data['vulnerability'] = json_decode(json_encode($vulnerability), true);
        
        $packages_list =  DB::table('k_vuln_details_view')
            ->distinct('k_vuln_details_view.pkg_name', 'k_vuln_details_view.installed_version', 'k_vuln_details_view.fixed_version',)
            ->select('k_vuln_details_view.pkg_name', 'k_vuln_details_view.installed_version', 'k_vuln_details_view.fixed_version')
            ->where('k_vuln_details_view.vulnerability_id', $vulnerability_id)
            ->get();
        $data['packages'] = json_decode(json_encode($packages_list), true);
        
        $images_list =  DB::table('k_vuln_details_view')
            ->leftJoin('k_images', 'k_images.uid', '=', 'k_vuln_details_view.image_uid')
            ->leftJoin('k_containers', 'k_containers.image', '=', 'k_images.fulltag')
            ->leftJoin('k_namespaces', 'k_namespaces.uid', '=', 'k_containers.namespace_uid')
            ->distinct('k_images.fulltag',)
            ->select('k_images.fulltag', 'k_images.uid', 'k_images.report_uid', 'k_namespaces.name')
            ->where('k_vuln_details_view.vulnerability_id', $vulnerability_id)
            ->get();

        $data['images'] = json_decode(json_encode($images_list), true);
            
        /*
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        */
        return view('vulnerabilities.details', $data);
    }
 
}
