<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NamespaceController extends Controller
{
    /**
     * Show imagedetailes of an image in specific Report
     *
     * @param  string  $report_uid
     * @param  string  $namespace_uid
     * @return View
     */
    public function show($report_uid, $namespace_uid) 
    {

        $audits_stats = DB::table('k_audits')
                ->select('severity_level', DB::raw('count(*) as total'))
                ->where('report_uid', $report_uid)
                ->where('namespace_uid', $namespace_uid)
                ->groupBy('severity_level')
                ->get();
        $data['stats'] = array(
            'error' => 0,
            'warning' => 0,
            'info' => 0
        );
        foreach ($audits_stats as $s) {
            $data['stats'][$s->severity_level] = $s->total;
        };

        $namespace = DB::table('k_namespaces')
            ->where('report_uid', $report_uid)
            ->where('uid', $namespace_uid)
            ->get();
        $data['namespace'] = json_decode(json_encode($namespace), true)[0];

        $pods_list = DB::table('k_pods')
            ->where('report_uid', $report_uid)
            ->where('namespace_uid', $namespace_uid)
            ->get();
        
                
        $namespace_audits_list = DB::table('k_audits')
            ->where('report_uid', $report_uid)
            ->where('namespace_uid', $namespace_uid)
            ->where('audit_type', 'namespace')
            ->get();

        $data['namespace']['audits'] = [];

        foreach ($namespace_audits_list as $a) {
            $data['namespace']['audits'][$a->uid] = json_decode(json_encode($a), true);
        }

        $pod_audits_list = DB::table('k_audits')
            ->where('report_uid', $report_uid)
            ->where('namespace_uid', $namespace_uid)
            ->where('audit_type', 'pod')
            ->get();

        $data['pods'] = [];
        foreach ($pod_audits_list as $a) {
            $data['pods'][$a->resource_name]['audits'][$a->uid] = json_decode(json_encode($a), true);
        }

        $pod_audits_list = DB::table('k_audits')
            ->where('report_uid', $report_uid)
            ->where('namespace_uid', $namespace_uid)
            ->where('audit_type', 'container')
            ->where('container', '!=', '')
            ->get();

        $data['containers'] = [];
        foreach ($pod_audits_list as $a) {
            $data['containers'][$a->container]['audits'][$a->uid] = json_decode(json_encode($a), true);
        }
        
        /*
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        */

        return view('namespace', $data);
    }
}