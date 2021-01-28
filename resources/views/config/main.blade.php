@extends('adminlte::page')

@section('title', 'Config')

@section('content_header')
    <h1>Config</h1>
@stop

@section('plugins.jqueryValidate', true)

@section('content')
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Runner configurations</h3>
                <!--
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                </div>
                -->
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                @include('config.runnersconfiglist', ['configRunner' => $configRunner])
            </div>
            <!-- /.card-body -->
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">API Tokens</h3>
                <!--
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                </div>
                -->
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                    @include('config.tokenslist')
            </div>
            <!-- /.card-body -->
        </div>
        @if(!env('LDAP'))
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Users</h3>
                <!--
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                </div>
                -->
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                    @include('config.userslist')
            </div>
            <!-- /.card-body -->
        </div>
        @endif
    </div>
<!-- /.card -->
</div>
@stop

@include('footer')