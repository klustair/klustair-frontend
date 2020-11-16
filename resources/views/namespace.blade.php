@extends('adminlte::page')

@section('title', 'Pod details')

@section('content_header')
    <h1>Namespace {{ $namespace['name'] }}</h1>
@stop


@section('content')
<div class="row">
    <div class="col-3">

            <div class="info-box">
              <span class="info-box-icon bg-danger p-1"><i class="fas fa-exclamation"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Errors</span>
                <span class="info-box-number">{{ $stats['error'] }}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->

            <div class="info-box">
              <span class="info-box-icon bg-warning"><i class="fas fa-times"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Warning</span>
                <span class="info-box-number">{{ $stats['warning'] }}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->

            <div class="info-box">
              <span class="info-box-icon bg-info"><i class="fas fa-info"></i></span>
              <div class="info-box-content">
                <span class="info-box-text">Info</span>
                <span class="info-box-number">{{ $stats['info'] }}</span>
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->




            <div class="info-box-content">
              </div>
              <div class="info-box-content">
              </div>
    </div>
    <div class="col-9">
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Namespace Audit</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-3">

                @foreach( $namespace['audits'] as $audit )
                <div class="post clearfix">
                  <div class="user-block">
                  @switch($audit['severity_level'])
                    @case('error')
                      <span class="info-box-icon bg-danger p-1"><i class="fas fa-exclamation fa-2x"></i></span>
                      @break
                    @case('warning')
                    <span class="info-box-icon bg-warning p-1"><i class="fas fa-times fa-2x"></i></span>
                      @break
                    @case('info')
                    <span class="info-box-icon bg-info p-1"><i class="fas fa-info fa-2x"></i></span>
                      @break
                  @endswitch
                    <span class="audittitle">
                    {{$audit['audit_name']}}
                    </span>
                    <span class="description">{{$audit['audit_time']}}</span>
                  </div>
                  <!-- /.user-block -->
                  <p>
                  {{$audit['msg']}}
                  </p>
                  <!--
                  <p>
                    <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 2</a>
                  </p>
                  -->
                </div>
                @endforeach

                @if( count($namespace['audits']) == 0)
                  <h1>All Good</h1>
                @endif

            </div>
            <!-- /.card-body -->
        </div>

        @foreach( $pods as $podname => $pod )
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Pod {{$podname}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-3">

                @foreach( $pod['audits'] as $audit )
                <div class="post clearfix">
                  <div class="user-block">
                  @switch($audit['severity_level'])
                    @case('error')
                      <span class="info-box-icon bg-danger p-1"><i class="fas fa-exclamation fa-2x"></i></span>
                      @break
                    @case('warning')
                    <span class="info-box-icon bg-warning p-1"><i class="fas fa-times fa-2x"></i></span>
                      @break
                    @case('info')
                    <span class="info-box-icon bg-info p-1"><i class="fas fa-info fa-2x"></i></span>
                      @break
                  @endswitch
                    <span class="audittitle">
                    {{$audit['audit_name']}}
                    </span>
                    <span class="description">{{$audit['audit_time']}}</span>
                  </div>
                  <!-- /.user-block -->
                  <p>
                  {{$audit['msg']}}
                  </p>
                  @if( $audit['missing_annotation'] != '')
                  <p>
                    <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> {{$audit['missing_annotation']}}</a>
                  </p>
                  @endif
                  <!--
                  <p>
                    <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 2</a>
                  </p>
                  -->
                </div>
                @endforeach

            </div>
            <!-- /.card-body -->
        </div>
        @endforeach



        @foreach( $containers as $containername => $container )
        <div class="card card-primary card-outline">
            <div class="card-header">
                <h3 class="card-title">Container {{$containername}}</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-3">

                @foreach( $container['audits'] as $audit )
                <div class="post clearfix">
                  <div class="user-block">
                  @switch($audit['severity_level'])
                    @case('error')
                      <span class="info-box-icon bg-danger p-1"><i class="fas fa-exclamation fa-2x"></i></span>
                      @break
                    @case('warning')
                    <span class="info-box-icon bg-warning p-1"><i class="fas fa-times fa-2x"></i></span>
                      @break
                    @case('info')
                    <span class="info-box-icon bg-info p-1"><i class="fas fa-info fa-2x"></i></span>
                      @break
                  @endswitch
                    <span class="audittitle">
                    {{$audit['audit_name']}}
                    </span>
                    <span class="description">{{$audit['audit_time']}}</span>
                  </div>
                  <!-- /.user-block -->
                  <p>
                  {{$audit['msg']}}
                  </p>
                  @if( $audit['missing_annotation'] != '')
                  <p>
                    <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> {{$audit['missing_annotation']}}</a>
                  </p>
                  @endif
                  @if( $audit['capability'] != '')
                  <p>
                    <a href="#" class="link-black text-sm"><i class="far fa-lightbulb mr-1"></i></i> {{$audit['capability']}}</a>
                  </p>
                  @endif
                  <!--
                  <p>
                    <a href="#" class="link-black text-sm"><i class="fas fa-link mr-1"></i> Demo File 2</a>
                  </p>
                  -->
                </div>
                @endforeach

            </div>
            <!-- /.card-body -->
        </div>
        @endforeach

    </div>



</div>

@stop


@section('css')
<style type="text/css">

.user-block .info-box-icon {
    margin-top: 8px;
    float: left;
    width: 42px;
}
.info-box-icon {
    border-radius: 50%;
}
.img-bordered-sm {
    border: 2px solid #adb5bd;
    padding: 2px;
}
.info-box-icon {
    text-align-last: center;
    border-style: none;
}

.post .user-block {
    margin-bottom: 15px;
    width: 100%;
}
.user-block {
    float: left;
}

.user-block .audittitle {
    font-size: 26px;
    font-weight: 600;
    margin-top: -1px;
}

.user-block .audittitle {
    display: block;
    margin-left: 50px;
}

.user-block .description {
    color: #6c757d;
    font-size: 13px;
    margin-top: -3px;
}
.user-block .comment, .user-block .description, .user-block .audittitle {
    display: block;
    margin-left: 50px;
}
</style>
@stop

@include('footer')