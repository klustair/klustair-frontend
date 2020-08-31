@extends('adminlte::page')

@section('title', 'Pod details')

@section('content_header')
    <h1>Image {{ $image['fulltag'] }}</h1>
@stop


@section('content')
<!-- {{print_r($image)}} -->
<div class="row">
    <div class="col-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Pod Checks</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-3">
                <table>
                    <tbody>
                    <tr>
                        <th>Image :</th>
                        <td>{{ $image['fulltag'] }}</td>
                    </tr>
                    <tr>
                        <th>Registy :</th>
                        <td>{{ $image['registry'] }}</td>
                    </tr>
                    <tr>
                        <th>Distro</th>
                        <td>{{ $image['distro'] }} {{ $image['distro_version'] }}</td>
                    </tr>
                    <tr>
                        <th>Layer Count</th>
                        <td>{{ $image['layer_count'] }}</td>
                    </tr>
                    <tr>
                        <th>Size</th>
                        <td>{{ $image['image_size'] }}</td>
                    </tr>
                    </tbody>
                </table>
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
                @foreach ($image['vulnsummary'] as $vulnsum)
                <div class="p-1 mb-1 {{$vulnseverity[$vulnsum['severity']]}} rounded">{{$vulnsum['severity']}} : {{$vulnsum['total']}}/{{$vulnsum['fixed']}}</div>
                @endforeach
            </div>
            <!-- /.card-body -->
        </div>
    </div>
</div>


<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
            Dockerfile
            </div>
            <!-- /.card-body -->
            <div class="card-body table-responsive p-0">
            <pre>
                {{ base64_decode($image['dockerfile']) }}
            </pre>
            </div>
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
                    @foreach ($image['vulnerabilities'] as $vulnerabily)
                    <tr>
                        <td><a target="_blank" href="{{$vulnerabily['url']}}">{{$vulnerabily['vuln']}}</a></td>
                        <td>{{$vulnerabily['package_fullname']}}</td>
                        <td>{{$vulnerabily['feed_group']}}</td>
                        <td>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-purple" style="width: {{ $vulnerabily['nvd_data_base_score']*10}}%"></div>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-fuchsia" style="width: {{ $vulnerabily['nvd_data_exploitability_score']*10}}%"></div>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-info" style="width: {{ $vulnerabily['nvd_data_impact_score']*10}}%"></div>
                            </div>
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
                </tbody>
            </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
<!-- /.card -->
</div>


@stop