@extends('adminlte::page')

@section('title', 'vulnerability')

@section('content_header')
    <h1>Vulnerabilities</h1>
@stop

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All</h3>
                <div class="card-tools">
                    <span class="badge badge-pill bg-purple">base</span>
                    <span class="badge badge-pill bg-fuchsia">exploitability</span>
                    <span class="badge badge-pill badge-info">impact</span>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-condensed">
                    <tbody>
                        <tr>
                            <td><b>
                                {{$vulnerability['title']}}
                                @if ($vulnerability['title'] == "")
                                {{ Str::limit($vulnerability['descr'],200) }}
                                @endif</b>
                            </td>
                            <td><nobr>{{$vulnerability['vulnerability_id']}}<nobr></td>
                            <td>{{$vulnerability['pkg_name']}}</td>
                            <td>
                                @isset ($vulnerability['cvss']['V3Score'])
                                <div class="progress progress-xs">
                                    <div class="progress-bar bg-purple" style="width: {{ $vulnerability['cvss']['V3Vector_base_score']*10}}%"></div>
                                </div>
                                <div class="progress progress-xs">
                                    <div class="progress-bar bg-fuchsia" style="width: {{ $vulnerability['cvss']['V3Vector_modified_esc']*10}}%"></div>
                                </div>
                                <div class="progress progress-xs">
                                    <div class="progress-bar bg-info" style="width: {{ $vulnerability['cvss']['V3Vector_modified_isc']*10}}%"></div>
                                </div>
                                @endisset
                                @if (@isset ($vulnerability['cvss']['V2Score']) && !@isset ($vulnerability['cvss']['V3Score']))
                                <div class="progress progress-xs">
                                    <div class="progress-bar bg-purple" style="width: {{ $vulnerability['cvss']['V2Vector_base_score']*10}}%"></div>
                                </div>
                                @endif
                            </td>
                            <td><span class="badge {{$vulnseverity[$vulnerability['severity']]}}">{{$vulnerability['cvss_base_score']}}</span></td>
                            <td>
                                @if ($vulnerability['fixed_version'] != "")
                                <span class="badge badge-pill badge-success">yes</span>
                                @endif
                            </td>
                            <td>
                                <input type="checkbox" id="{{ $vulnerability['vulnerability_id'] }}" class="whitelistItem" name="whitelist" value="{{ $vulnerability['uid'] }}" @if ($vulnerability['images_vuln_whitelist_uid'] != "") checked @endif>
                            </td>
                        </tr>
                        <tr>
                            <td colspan="7" style="border-top: none">
                                <div class="row">
                                    <div class="col-sm-7 p-3 bg-light rounded border">
                                    {{ $vulnerability['descr'] }}
                                    </div>
                                    <div class="col-sm-5">
                                        <table>
                                            <tr>
                                                <th>installed version</th>
                                                <td>{{ $vulnerability['installed_version'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>fixed version</th>
                                                <td>{{ $vulnerability['fixed_version'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>published</th>
                                                <td>{{ $vulnerability['published_date'] }}</td>
                                            </tr>
                                            <tr>
                                                <th>last modified</th>
                                                <td>{{ $vulnerability['last_modified_date'] }}</td>
                                            </tr>
                                        </table>
                                    </div>
                                </div>
                                <div class="row pt-4">
                                    <div class="col-sm-7">
                                        @if ($vulnerability['links'] != '')
                                        <ul class="fa-ul" style="max-width: 800px;">
                                            @foreach ($vulnerability['links'] as $link)
                                            <li><span class="fa-li" style="color: gray"><i class="fas fa-link fa-xs"></i></span><a href="{{ $link }}" target="_blank">{{ $link }}</a></li>
                                            @endforeach
                                        </ul>
                                        @endif
                                    </div>
                                    <div class=col-sm-5>
                                        @include('elements.cvss', ['vuln_cvss' => $vulnerability['cvss']])
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
                <div class="p-2" style="float:right;">
                    <button type="button" id="UpdateWhitelist" class="btn btn-block bg-gradient-success swalDefaultSuccess" style="width:200px;">Add to Whitelist</button>
                </div>
            <!-- /.card-body -->
            </div>
        </div>
    </div>
<!-- /.card -->
</div>

@stop