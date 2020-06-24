@extends('adminlte::page')

@section('title', 'Pod details')

@section('content_header')
    <h1>Reports from {{ $pod['metadata']['name'] }}</h1>
@stop


@section('content')
<!-- {{print_r($pod)}} -->
<div class="row">
    <div class="col-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pod Checks</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-3">
            @foreach ($pod['containers'] as $container)
                        <table>
                            <tbody>
                            <tr>
                                <th>image :</th>
                                <td>{{ $container['image'] }}</td>
                            </tr>
                            <tr>
                                <th>namespace :</th>
                                <td>{{ $pod['metadata']['namespace'] }}</td>
                            </tr>
                            <tr>
                                <th>creation :</th>
                                <td>{{ $pod['metadata']['creationTimestamp'] }}</td>
                            </tr>
                            <tr>
                                <th>started :</th>
                                <td>{{ $container['state']['running']['startedAt'] }}</td>
                            </tr>
                            <tr>
                                <th>imagePullPolicy :</th>
                                <td>{{ $container['imagePullPolicy'] }}</td>
                            </tr>
                            </tbody>
                        </table>
            @endforeach
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <div class="col-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Vulnerabilty Summary</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-3">
            @foreach ($pod['containers'] as $container)
                            @foreach ($container['vulnsum'] as $severity => $vulnsum)
                            <div class="p-1 mb-1 {{$vulnseverity[$severity]}} rounded">{{$severity}} : {{$vulnsum['total']}}/{{$vulnsum['fixed']}}</div>
                            @endforeach
            @endforeach
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>



<div class="row">
    <div class="col-9">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pod Checks</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-3">
                    @foreach ($container['checkresults'] as $checkresult)
                        <span class="badge badge-pill badge-{{$error[$checkresult['result']]}}" style="width:60px">{{$error[$checkresult['result']]}}</span> {{$checkresult['description']}} </br>
                    @endforeach
            </div>
            <!-- /.card-body -->
        </div>
    </div>
    <div class="col-3">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Capabilities</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-3">
            @foreach ($pod['containers'] as $container)
                <dl>
                    <dt>ADD</dt>
                @foreach ($container['securityContext']['capabilities']['add'] as $capability)
                    <dd>- {{ $capability }}</dd>
                @endforeach
                    <dt>DROP</dt>
                @foreach ($container['securityContext']['capabilities']['drop'] as $capability)
                    <dd>- {{ $capability }}</dd>
                @endforeach
                </dl>
            @endforeach
            </div>
            <!-- /.card-body -->
        </div>
    </div>
<!-- /.card -->
</div>

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Vulnerabilties</h3>
                <div class="card-tools">
                    <span class="badge badge-pill bg-purple">base</span>
                    <span class="badge badge-pill bg-fuchsia">exploitability</span>
                    <span class="badge badge-pill badge-info">impact</span>
                    <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i></button>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">

            <table class="table table-condensed">
                <thead>
                <tr>
                    <th>CVE</th>
                    <th>Package</th>
                    <th>Feed</th>
                    <th>Score</th>
                    <th style="width: 40px">Severity</th>
                    <th style="width: 40px">Fixed</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($pod['containers'] as $container)
                    @foreach ($container['vulnerabilies']['vulnerabilities'] as $vulnerabily)
                    <tr>
                        <td><a target="_blank" href="{{$vulnerabily['url']}}">{{$vulnerabily['vuln']}}</a></td>
                        <td>{{$vulnerabily['package']}}</td>
                        <td>{{$vulnerabily['feed_group']}}</td>
                        <td>
                            @foreach ($vulnerabily['nvd_data'] as $nvd)
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-purple" style="width: {{ $nvd['cvss_v3']['base_score']*10}}%"></div>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-fuchsia" style="width: {{ $nvd['cvss_v3']['exploitability_score']*10}}%"></div>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-info" style="width: {{ $nvd['cvss_v3']['impact_score']*10}}%"></div>
                            </div>
                            @endforeach
                        </td>
                        <td><span class="badge {{$vulnseverity[$vulnerabily['severity']]}}">{{$vulnerabily['severity']}}</span></td>
                        <td>
                            @if ($vulnerabily['fix'] != "None")
                            <span class="badge badge-pill badge-success">yes</span>
                            @endif
                        </td>
                    </tr>
                    <p>
                    @endforeach
                @endforeach
                </tbody>
            </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
<!-- /.card -->
</div>


@stop