@extends('adminlte::page')

@section('title', 'Reports')

@section('content_header')
    <h1>Report {{$report_uid}}</h1>
@stop

@section('content')

<p>
  <div class="dropdown show">
    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Select Report
    </a>

    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
    @foreach ($reports as $report)
      <a class="dropdown-item" href="/report/{{ $report->uid }}">{{ $report->checktime }}</a>
    @endforeach
    </div>
  </div>
</p>

<div class="row">
          <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box bg-info">
              <span class="info-box-icon"><i class="far fa-bookmark"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Pods Checked</span>
                <span class="info-box-number"><h3>TODO</h3></span>
<!--
                <div class="progress">
                  <div class="progress-bar" style="width: 70%"></div>
                </div>
                <span class="progress-description">
                  70% Increase in 30 Days
                </span>
-->
              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-4 col-sm-12 col-12">
            <div class="info-box bg-warning">
              <span class="info-box-icon"><i class="far fa-calendar-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Images Scanned </span>
                <span class="info-box-number"><h3>TODO</h3></span>

              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-4 col-sm-6 col-12">
            <div class="info-box bg-success">
              <span class="info-box-icon"><i class="far fa-thumbs-up"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Namespaces Checked</span>
                <span class="info-box-number"><h3>TODO</h3></span>

              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>

<p>
  <button type="button" class="btn btn-block btn-default" id="toggler">show/hide details</button>
</p>


@foreach ($namespaces as $namespace)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><a href="/namespace/{{ $namespace['uid'] }}">{{ $namespace['name'] }}</a></h3>
                <!--
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                </div>
                -->
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-3">
            @foreach ($namespace['pods'] as $pod)
            @foreach ($pod['containers'] as $container)
                <div class="row">
                    <div class="col">
                        <table>
                            <tbody>
                            <tr>
                                <th>image :</th>
                                <td>{{ $container['image'] }}</td>
                            </tr>
                            <tr>
                                <th>pod :</th>
                                <td>{{ $pod['podname'] }}</td>
                            </tr>
                            <tr>
                                <th>namespace :</th>
                                <td>{{ $namespace['name'] }}</td>
                            </tr>
                            <tr>
                                <th>Pod creation :</th>
                                <td>TODO</td>
                            </tr>
                            @if(isset($container['state']) && isset($container['state']['running']) && isset($container['state']['running']['startedAt']))
                            <tr>
                                <th>started :</th>
                                <td>{{ $container['state']['running']['startedAt'] }}</td>
                            </tr>
                            @endif
                            <tr>
                                <th>imagePullPolicy :</th>
                                <td>{{ $container['image_pull_policy'] }}</td>
                            </tr>
                            </tbody>
                        </table>
                    </div>
                    <div  class="col-md-auto">
                    @if(isset($container['checkresults']))
                    @foreach ($container['checkresults'] as $checkresult)
                        <span class="badge badge-pill badge-{{$error[$checkresult['result']]}}" style="width:60px">{{$error[$checkresult['result']]}}</span> {{$checkresult['description']}} </br>
                    @endforeach
                    @endif
                    </div>
                    <div class="col col-lg-3">
                          @isset($container['vulnsum'])
                            @foreach ($container['vulnsum'] as $severity => $vulnsum)
                            <div class="p-1 mb-1 {{$vulnseverity[$severity]}} rounded">{{$severity}} : {{$vulnsum['total']}}/{{$vulnsum['fixed']}}</div>
                            @endforeach
                          @endisset
                    </div>
                </div>
            <hr>
            @endforeach
            @endforeach
            </div>
            <!-- /.card-body -->
        </div>
    </div>
<!-- /.card -->
</div>

    @endforeach
@stop

@section('right-sidebar')
Hello
@stop


@section('js')
<script>
  $('.card-body').collapse('show');
  $('#toggler').on('click', function () {
    $('.card-body').collapse('toggle');
  });
  </script>
@stop