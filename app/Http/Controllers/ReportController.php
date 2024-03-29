<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;

use App\Models\Report;
use App\Models\Nspace;
use App\Models\Audit;
use App\Models\Pod;
use App\Models\Container;
use App\Models\Image;
use App\Models\TargetTrivy;
use App\Models\Vuln;
use App\Models\VulnDetails;
use App\Models\Vulnsummary;
use App\Models\ContainerHasImage;
use App\Models\ReportsSummaries;

class ReportController extends Controller
{

    
    public function apiDeleteReport($uid)
    {
        Report::destroy($uid);
    }

    public function apiCreateReport(Request $request)
    {
        
        $report = new Report;
        $report->title      = $request->title;
        $report->uid        = $request->uid; 
        $report->checktime  = date('Y-m-d H:i:s');
        $report->save();
        Log::info('ReportController::apiCreateReport: ' . $report->uid);
        
        return $report;
    }

    public function apiCreateNamespace($report_uid, Request $request)
    {
        foreach ($request->all() as $namespace) {
            $ns = new Nspace;
            $ns->uid                        = $namespace['uid']; 
            $ns->report_uid                 = $report_uid; 
            $ns->name                       = $namespace['name'];
            $ns->creation_timestamp         = $namespace['creation_timestamp'];
            $ns->kubernetes_namespace_uid   = $namespace['kubernetes_namespace_uid'];
            $ns->save();
            Log::debug('ReportController::apiCreateNamespace: ' . $ns->uid);
        }
        return $request;
    }

    public function apiCreateAudit($report_uid, Request $request)
    {
        foreach ($request->all() as $a) {
            $audit = new Audit;
            $audit->uid                     = $a['uid']; 
            $audit->report_uid              = $report_uid; 
            $audit->namespace_uid           = $a['namespace_uid'];
            $audit->audit_type              = $a['audit_type'];
            $audit->audit_name              = $a['AuditResultName'];
            $audit->msg                     = $a['msg'];
            $audit->severity_level          = $a['level'];
            $audit->audit_time              = $a['time'];
            $audit->resource_name           = $a['ResourceName'];
            $audit->capability              = @$a['Capability'] ?: '';
            $audit->container               = @$a['Container'] ?: '';
            $audit->missing_annotation      = $a['AuditResultName'];
            $audit->missing_annotation      = @$a['MissingAnnotation'] ?: '';
            $audit->resource_namespace      = @$a['ResourceNamespace'] ?: '';
            $audit->resource_api_version    = $a['ResourceApiVersion'];
            $audit->save();
            Log::debug('ReportController::apiCreateAudit: ' . $audit->uid);
        }
        return $request;
    }

    public function apiCreatePod($report_uid, Request $request)
    {
        foreach ($request->all() as $pod) {
            $p = new Pod;
            $p->uid                 = $pod['uid']; 
            $p->report_uid          = $report_uid; 
            $p->namespace_uid       = $pod['namespace_uid'];
            $p->podname             = $pod['podname'];
            $p->creation_timestamp  = $pod['creation_timestamp'];
            $p->kubernetes_pod_uid  = $pod['kubernetes_pod_uid'];
            $p->age                 = @$pod['age'] ?: 0;
            $p->save();
            Log::debug('ReportController::apiCreatePod: ' . $p->uid);
        }
        return $request;
    }

    public function apiCreateContainer($report_uid, Request $request)
    {
        foreach ($request->all() as $container) {
            //Log::debug('ReportController::aaaaaaaa: ' . var_export($container, true));
            $c = new Container;
            $c->uid                 = $container['uid']; 
            $c->report_uid          = $report_uid; 
            $c->namespace_uid       = $container['namespace_uid'];
            $c->pod_uid             = $container['pod_uid'];
            $c->name                = $container['name'];
            $c->image               = $container['image'];
            $c->image_pull_policy   = $container['image_pull_policy'];
            $c->security_context    = $container['security_context'];
            $c->init_container      = @$container['init_container'] ?: false;
            $c->ready               = @$container['ready'] ?: false;
            $c->started             = @$container['started'] ?: false;
            $c->restart_count       = @$container['restartCount'] ?: 0;
            $c->started_at          = @$container['startedAt'] ?: '';
            $c->image_id            = @$container['imageID'] ?: '';
            $c->actual              = @$container['actual'] ?: false;
            $c->save();
            Log::debug('ReportController::apiCreateContainer: ' . $c->name);
        }
        return $request;
    }

    public function apiCreateImage($report_uid, Request $request)
    {
        foreach ($request->all() as $image) {
            //Log::debug('apiCreateImage::image: ' . var_export($image, true));
            $i = new Image;
            $i->uid             = $image['uid']; 
            $i->report_uid      = $report_uid; 
            $i->image_b64       = base64_encode($image['fulltag']);
            $i->analyzed_at     = @$image['analyzed_at'] ?: '01.01.1970';
            $i->created_at      = @$image['created_at'] ?: '01.01.1970';
            $i->fulltag         = $image['fulltag'];
            $i->image_digest    = @$image['image_digest'] ?: '';
            $i->arch            = @$image['arch'] ?: '';
            $i->distro          = @$image['distro'] ?: '';
            $i->distro_version  = @$image['distro_version'] ?: '';
            $i->image_size      = @$image['image_size'] ?: 0;
            $i->layer_count     = @$image['layer_count'] ?: 0;
            $i->registry        = @$image['registry'] ?: '';
            $i->repo            = @$image['repo'] ?: '';
            $i->dockerfile      = @$image['dockerfile'] ?: '';
            $i->config          = json_encode(@$image['config'] ?: ''); 
            $i->history         = json_encode(@$image['history'] ?: ''); 
            $i->age             = @$image['age'] ?: 0;
            $i->save();
            Log::debug('ReportController::apiCreateImage: ' . $i->uid);


            foreach ($image['summary']['severity'] as $severity => $value) {
                $v = new Vulnsummary;
                $v->uid         = Str::uuid();
                $v->report_uid  = $report_uid; 
                $v->image_uid   = $image['uid'];
                $v->severity    = $severity;
                $v->total       = $value['total'];
                $v->fixed       = $value['fixed'];
                $v->save();
                Log::debug('ReportController::apiCreateImage::Vulnsummary: ' . $severity);
            }

        }
        return $request;
    }

    public function apiCreateVuln($report_uid, $image_uid, Request $request)
    {
        foreach ($request->all() as $target) {
            //Log::debug('apiCreateVuln::apiCreateVuln: ' . var_export($target, true));
            $i = new TargetTrivy;
            $i->uid         = $target['uid']; 
            $i->report_uid  = $report_uid; 
            $i->image_uid   = $image_uid; 
            $i->target      = $target['Target'];
            $i->target_type = $target['Type'];
            $i->is_os       = filter_var($target['isOS'], FILTER_VALIDATE_BOOLEAN);
            $i->save();
            Log::debug('ReportController::apiCreateVuln: ' . $i->uid);
            if (isset($target['Vulnerabilities'])) {
                foreach ($target['Vulnerabilities'] as $vuln) {
                    //Log::debug('ReportController::apiCreateVuln::VulnerabilityID : ' . $vuln['VulnerabilityID']);

                    if (
                        !isset($vuln['VulnerabilityID']) || $vuln['VulnerabilityID'] == '' ||
                        !isset($vuln['PkgName']) || $vuln['PkgName'] == '' ||
                        !isset($vuln['InstalledVersion']) || $vuln['InstalledVersion'] == '') {
                        // skip if vulnerability has no CVE yet
                        continue;
                    }
                    $v = new Vuln;
                    $v->uid                 = $vuln['uid'];
                    $v->report_uid          = $report_uid;
                    $v->image_uid           = $image_uid;
                    $v->target_uid          = $target['uid'];
                    $v->vulnerability_id    = $vuln['VulnerabilityID'];
                    $v->pkg_name            = $vuln['PkgName'];
                    $v->installed_version   = $vuln['InstalledVersion']; 
                    $v->save();


                    $vulndetails = VulnDetails::updateOrCreate(
                        [
                            'vulnerability_id'      => $vuln['VulnerabilityID'], 
                            'pkg_name'              => $vuln['PkgName'],
                            'installed_version'     => $vuln['InstalledVersion'],
                        ],
                        [
                            'vulnerability_id'      => $vuln['VulnerabilityID'], 
                            'pkg_name'              => $vuln['PkgName'],
                            'installed_version'     => $vuln['InstalledVersion'],
                            'title'                 => @$vuln['Title'] ?: '', 
                            'descr'                 => @$vuln['Description'] ?: '', 
                            'fixed_version'         => @$vuln['FixedVersion'] ?: '', 
                            'severity_source'       => @$vuln['SeveritySource'] ?: '', 
                            'severity'              => @$vuln['SeverityInt'] ?: 0,  
                            'last_modified_date'    => @$vuln['LastModifiedDate'] ?: '',
                            'published_date'        => @$vuln['PublishedDate'] ?: '',
                            'links'                 => json_encode(@$vuln['References'] ?: ''), 
                            'cvss'                  => json_encode(@$vuln['CVSS'] ?: ''),
                            'cwe_ids'               => json_encode(@$vuln['CweIDs'] ?: '')
                        ]
                    );
                }
                //Log::debug('ReportController::apiCreateVuln::vulnerabilities : ' . count($target['Vulnerabilities']));

            }
        }
        return $request;
    }
    
    // TODO : Not used anymore since data is inserted with image 
    public function apiVulnsummary($report_uid, Request $request)
    {
        foreach ($request->all() as $image_uid => $image) {

            foreach ($image as $severity => $value) {
                $i = new Vulnsummary;
                $i->uid         = Str::uuid();
                $i->report_uid  = $report_uid; 
                $i->image_uid   = $image_uid;
                $i->severity    = $severity;
                $i->total       = $value['total'];
                $i->fixed       = $value['fixed'];
                $i->save();
            }
            Log::debug('ReportController::apiVulnsummary::count ' . count($image));
        }
        return $request;
    }

    public function apiContainerHasImage($report_uid, Request $request)
    {
        foreach ($request->all() as $item) {
            $i = new ContainerHasImage;
            $i->container_uid   = $item['container_uid']; 
            $i->report_uid      = $report_uid; 
            $i->image_uid       = $item['image_uid'];;
            $i->save();
            Log::debug('ReportController::apiContainerHasImage: ' . $i->container_uid . '->' . $i->image_uid);
        }
        return $request;
    }

    public function apiReportsSummary($report_uid, Request $request)
    {
        // Update per image summary
        $images = DB::table('k_images')
            ->where('report_uid', $report_uid)
            ->get();
        
        $severityStr = array(
            'Critical',
            'High',
            'Medium',
            'Low',
            'Unknown', 
        );

        foreach ($images as $image) {

            for ($i = 0; $i < 5; $i++) {
                $severity = $i;
                
                $vuln_ack_count = DB::table('k_vuln_details_view')
                ->leftJoin('k_images', 'k_images.uid', '=', 'k_vuln_details_view.image_uid')
                ->leftJoin('k_vulnwhitelist', function ($join) {
                    $join->on('k_vulnwhitelist.wl_image_b64', '=', 'image_b64')
                         ->on('k_vulnwhitelist.wl_vuln', '=', 'vulnerability_id');
                })
                ->where('k_vuln_details_view.image_uid', $image->uid)
                ->where('k_vuln_details_view.report_uid', $report_uid)
                ->where('severity', $severity)
                //->where('k_vulnwhitelist.uid', null) // to get only the vulns that are not whitelisted
                ->whereNotNull('k_vulnwhitelist.uid')
                ->distinct('k_vuln_details_view.uid')
                ->select('k_vulnwhitelist.uid as images_vuln_whitelist_uid')
                //->toSql();
                ->count();

                Vulnsummary::where('report_uid', $report_uid)
                    ->where('image_uid', $image->uid)
                    ->where('severity', $severityStr[$severity])
                    ->update(['acknowledged' => $vuln_ack_count]);
            }
        }

        $acknowledged_sum = DB::table('k_vuln_details_view')
            ->leftJoin('k_images', 'k_images.uid', '=', 'k_vuln_details_view.image_uid')
            ->rightJoin('k_vulnwhitelist', function ($join) {
                $join->on('k_vulnwhitelist.wl_image_b64', '=', 'image_b64')
                     ->on('k_vulnwhitelist.wl_vuln', '=', 'vulnerability_id');
            })
            ->where('k_vuln_details_view.report_uid', $report_uid)
            //->distinct('k_vulnwhitelist.uid')
            ->select('k_vuln_details_view.uid as vuln_trivy_uid')
            ->count();
        
        $not_acknowledged_sum = DB::table('k_vuln_details_view')
            ->leftJoin('k_images', 'k_images.uid', '=', 'k_vuln_details_view.image_uid')
            ->leftJoin('k_vulnwhitelist', function ($join) {
                $join->on('k_vulnwhitelist.wl_image_b64', '=', 'image_b64')
                     ->on('k_vulnwhitelist.wl_vuln', '=', 'vulnerability_id');
            })
            ->where('k_vuln_details_view.report_uid', $report_uid)
            ->where('k_vulnwhitelist.uid', null)
            //->distinct('k_vulnwhitelist.uid')
            ->select('k_vuln_details_view.uid as vuln_trivy_uid')
            //->toSql();
            ->count();

        $i = new ReportsSummaries;
        $i->uid                   = Str::uuid();
        $i->report_uid            = $report_uid; 
        $i->namespaces_checked    = @$request['namespaces_checked'] ?: 0;
        $i->namespaces_total      = @$request['namespaces_total'] ?: 0;
        $i->vuln_total            = @$request['vuln_total'] ?: 0;
        $i->vuln_critical         = @$request['vuln_critical'] ?: 0;
        $i->vuln_medium           = @$request['vuln_medium'] ?: 0;
        $i->vuln_high             = @$request['vuln_high'] ?: 0;
        $i->vuln_low              = @$request['vuln_low'] ?: 0;
        $i->vuln_unknown          = @$request['vuln_unknown'] ?: 0;
        $i->vuln_fixed            = @$request['vuln_fixed'] ?: 0;
        $i->vuln_acknowledged     = $acknowledged_sum;
        $i->vuln_not_acknowledged = $not_acknowledged_sum;
        $i->pods                  = @$request['pods'] ?: 0;
        $i->images                = @$request['images'] ?: 0;
        $i->save();
        Log::debug('ReportController::apiReportsSummary: ' . $request);
        return $request;
    }

    public function apiReportsCleanup(Request $request)
    {
        Log::debug('ReportController::apiReportsCleanup: ' . $request);
        $return['limit_nr'] = false;
        $return['limit_date'] = false;

        if ($request['limit_nr'] != false) {
            $limitNr = (int)$request['limit_nr'];
            $result = DB::table('k_reports')
                ->whereRaw("uid NOT IN (select uid from k_reports ORDER BY checktime DESC LIMIT $limitNr)")
                ->delete();
                $return['limit_nr'] = $result;
        } 

        if ($request['limit_date'] != false) {
            $limitDate = (int)$request['limit_date'];
            $date = date("Y-m-d H:i", strtotime("-$limitDate day"));
            $result = DB::table('k_reports')
                ->where('checktime', '<', "$date")
                ->delete();
                $return['limit_date'] = $result;
        } 
        return $return;
        
    }

    /**
     * Show a overview of Reports
     *
     * @param  string  $report_uid
     * @return View
     */
    public function overview($report_uid=null) 
    {
        if ($report_uid == null) {
            // select the latest report
            $report = DB::table('k_reports')
                ->select('uid', 'id', 'title')
                ->selectRaw("to_char(k_reports.checktime, 'DD.MM.YYYY HH24:MI') as checktime")
                ->orderBy('id', 'DESC')
                ->first();
                
            if ($report == null) {
                ## Cheapest solution. Go back to home, if there are no reports yet
                return redirect('/');
            }
            $report_uid = $report->uid;
        } else {
            // select a specific report
            $report = DB::table('k_reports')
            ->select('uid', 'title')
            ->selectRaw("to_char(k_reports.checktime, 'DD.MM.YYYY HH24:MI') as checktime")
                ->where('uid', $report_uid)
                ->first();
        }
        
        if($report == null) {
            $report_uid = 0;
        }
    
        $data['report_data'] = $report;
    
        $data['reports'] = DB::table('k_reports')
            ->select('uid', 'id')
            ->selectRaw("to_char(k_reports.checktime, 'DD. Mon, HH24:MI') as checktime, title")
            ->orderBy('id', 'DESC')
            ->get()
            ->toArray();
        
        $data['stats']['namespaces'] = 0;
        $data['stats']['pods'] = 0;
        $data['stats']['containers'] = 0;
        $data['stats']['images'] = 0;
        
        $namespaces_list = DB::table('k_namespaces')
            ->where('report_uid', $report_uid)
            ->get();
        
        $pods_list = DB::table('k_pods')
            ->where('report_uid', $report_uid)
            ->get();


        $vulnsummary_list = DB::table('k_vulnsummary')
            ->where('report_uid', $report_uid)
            ->orderBy('severity', 'DESC')
            ->get();

        
        $containers_list = DB::table('k_containers')
            ->where('report_uid', $report_uid)
            ->distinct('name', 'image')
            ->get();
        
        $audits_stats = DB::table('k_audits')
            ->select('namespace_uid', 'severity_level', DB::raw('count(*) as total'))
            ->where('report_uid', $report_uid)
            ->groupBy('namespace_uid','severity_level')
            ->get();
        
        $containers_list = DB::table('k_containers')
            ->where('report_uid', $report_uid)
            ->distinct('name', 'image')
            ->get();
        
        $images_list = DB::table('k_container_has_images')
            ->join('k_images', 'k_container_has_images.image_uid', '=', 'k_images.uid')
            ->where('k_container_has_images.report_uid', $report_uid)
            ->get();
        
        $vuln_ack_count_list = DB::table('count_no_ack_view')
            ->where('report_uid', $report_uid)
            ->get();

        $namespaces = [];
        foreach ($namespaces_list as $n) {
            $data['stats']['namespaces']++;
            $namespaces[$n->uid] = json_decode(json_encode($n), true);

            $namespaces[$n->uid]['stats'] = array(
                'error' => 0,
                'warning' => 0,
                'info' => 0
            );
            
            foreach ($audits_stats as $s) {
                if ($s->namespace_uid == $n->uid) {
                    $namespaces[$n->uid]['stats'][$s->severity_level] = $s->total;
                }
            };

            foreach ($pods_list as $p) {
                if ($p->namespace_uid == $n->uid) {
                    $data['stats']['pods']++;
                    $namespaces[$n->uid]['pods'][$p->uid] = json_decode(json_encode($p), true);
                }
            }

            foreach ($containers_list as $c) {
                if ($c->namespace_uid == $n->uid) {
                    $data['stats']['containers']++;
                    $namespaces[$n->uid]['pods'][$c->pod_uid]['containers'][$c->uid] = json_decode(json_encode($c), true);

                    foreach ($images_list as $i) {
                        if ($i->container_uid == $c->uid) {
                            $data['stats']['images']++;
                            $namespaces[$n->uid]['pods'][$c->pod_uid]['containers'][$c->uid]['imagedetails'] = json_decode(json_encode($i), true);
                            if ($i->distro == '') {
                                $namespaces[$n->uid]['pods'][$c->pod_uid]['containers'][$c->uid]['imagedetails']['distro'] = 'unknown';
                            }
                            
                            $namespaces[$n->uid]['pods'][$c->pod_uid]['containers'][$c->uid]['imagedetails']['vuln_ack_count'] = "0";
                            foreach ($vuln_ack_count_list as $cl) {
                                if ($cl->image_uid == $i->uid) {
                                    $namespaces[$n->uid]['pods'][$c->pod_uid]['containers'][$c->uid]['imagedetails']['vuln_ack_count'] = $cl->vuln_ack_count;
                                }
                            }
                            
                            foreach ($vulnsummary_list as $v) {
                                if ($v->image_uid == $i->uid) {
                                    $namespaces[$n->uid]['pods'][$c->pod_uid]['containers'][$c->uid]['imagedetails']['vulnsummary'][$v->uid] = json_decode(json_encode($v), true);
                                }
                            }
                        }
                    }
                }
            }
        }
    
        $vulnseverity = array(
                        "Critical" => 'bg-danger text-dark',
                        "High" => 'bg-warning text-white',
                        "Medium" => 'bg-info text-white',
                        "Low" => 'bg-secondary text-white',
                        "Negligible" => 'bg-dark text-white',
                        "Unknown" => 'bg-light text-dark'
                    );
    
        $error = array(
                "danger",
                "success",
                "unknown",
            );
            
        $data['vulnseverity'] = $vulnseverity;
        $data['error'] = $error;
        
        $data['namespaces'] = $namespaces;
        /*
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        */
        
        return view('reportsshort', $data);
    }
}
