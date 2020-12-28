@extends('adminlte::page')

@section('title', 'vulnerabilities')

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
                <thead>
                <tr>
                    <th>CVE</th>
                    <th>Title</th>
                    <th>Package</th>
                    <th>IMG</th>
                    <th>Score</th>
                    <th style="width: 40px">CVSS</th>
                    <th style="width: 40px">Fixed</th>
                    <th style="width: 20px"><input type="checkbox" id="checkAll"></th>
                </tr>
                </thead>
                <tbody>
                    @include('vulnerabilities.vulnlisttrivy', ['vulnerabilities' => $vulnerabilities])
                </tbody>
            </table>
            <div class="p-2" style="float:right;">
                <button type="button" id="UpdateWhitelist" class="btn btn-block bg-gradient-success swalDefaultSuccess" style="width:200px;">Add to Whitelist</button>
            </div>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
<!-- /.card -->
</div>

@stop