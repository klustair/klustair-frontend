@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Dashboard</h1>
@stop


@section('plugins.Chartjs', true)

@section('content')

@if($anchore_enabled)
<!--<pre>{{ print_r($anchore_status) }}</pre>-->
<h5 class="mb-2">Anchore status</h5>
<div class="row">

  
@foreach ($anchore_status['service_states'] as $service_state)
  <div class="col-lg-3 col-12">
    <div class="info-box">
      @switch($service_state['status'])
        @case(1)
          <span class="info-box-icon bg-success"><i class="far fa-thumbs-up"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">{{ str_replace("_", " ", $service_state['servicename']) }}</span>
            <span class="info-box-number">{{ $service_state['service_detail']['message']}}</span>
          </div>
          @break
        @case(0)
          <span class="info-box-icon bg-danger"><i class="far fa-thumbs-down"></i></span>
          <div class="info-box-content">
            <span class="info-box-text">{{ str_replace("_", " ", $service_state['servicename']) }}</span>
            <span class="info-box-number">{{ $service_state['service_detail']}}</span>
          </div>
          @break
      @endswitch
      <!-- /.info-box-content -->
    </div>
    <!-- /.info-box -->
  </div>
  <!-- /.col -->
@endforeach

</div>

  <div class="row">
    <div class="col-lg-3 col-12">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ $reports_count }}</h3>

          <p>Reports</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
        <a href="/report" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-12">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h3>{{ count($anchore_feeds) }}</h3>

          <p>Feeds</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <a href="/anchore/feeds" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-12">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>{{ count($anchore_policies) }}</h3>

          <p>Policies</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        <a href="/anchore/policies" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-12">
      <!-- small box -->
      <div class="small-box bg-danger">
        <div class="inner">
          <h3>{{ count($anchore_images) }}</h3>

          <p>Images</p>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>
        </div>
        <a href="/anchore/images" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
  </div>
@endif



<!--<pre>{{ print_r($reports_summaries) }}</pre>-->
<div class="row">
    <div class="col-lg-3 col-12">
      <!-- small box -->
      <div class="small-box bg-info">
        <div class="inner">
          <h3>{{ $reports_count }}</h3>

          <p>Reports</p>
        </div>
        <div class="icon">
          <i class="ion ion-bag"></i>
        </div>
        <a href="/report" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-12">
      <!-- small box -->
      <div class="small-box bg-warning">
        <div class="inner">
          <h3>{{ $namespaces_count }}</h3>

          <p>Namespaces</p>
        </div>
        <div class="icon">
          <i class="ion ion-person-add"></i>
        </div>
        <a href="/report" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-12">
      <!-- small box -->
      <div class="small-box bg-success">
        <div class="inner">
          <h3>{{ $pods_count }}</h3>

          <p>Pods</p>
        </div>
        <div class="icon">
          <i class="ion ion-stats-bars"></i>
        </div>
        <a href="/report" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
    <div class="col-lg-3 col-12">
      <!-- small box -->
      <div class="small-box bg-danger">
        <div class="inner">
          <h3>{{ $vuln_count }}</h3>

          <p>Vulnerabilities</p>
        </div>
        <div class="icon">
          <i class="ion ion-pie-graph"></i>
        </div>
        <a href="/report" class="small-box-footer">More info <i class="fas fa-arrow-circle-right"></i></a>
      </div>
    </div>
    <!-- ./col -->
  </div>

  <div class="chart">
    <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
  </div>

  @include('home.reportslist', ['reports' => $reports_summaries['full']])
@stop

@section('js')
<script> 

$(function () {

    //-------------
    //- VULNHIST CHART -
    //-------------


    var vulnhistChartData = {
      labels  : ['{!! implode("','", $reports_summaries['checktime']) !!}'],
      datasets: [
        {
          label               : 'namespaces',
          backgroundColor     : '#FFC108',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [{{ implode(",", $reports_summaries['namespaces_checked']) }}]
        },
        {
          label               : 'pods',
          backgroundColor     : '#29A745',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [{{ implode(",", $reports_summaries['pods']) }}]
        },
        {
          label               : 'avg vuln/img',
          backgroundColor     : '#DC3545',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [{{ implode(",", $reports_summaries['vuln_avg']) }}]
        },
      ]
    }

    var barChartCanvas = $('#barChart').get(0).getContext('2d')
    var barChartData = $.extend(true, {}, vulnhistChartData)
    var temp0 = vulnhistChartData.datasets[0]
    barChartData.datasets[0] = temp0

    var barChartOptions = {
      responsive              : true,
      maintainAspectRatio     : false,
      datasetFill             : false,
      scales                  : {
                                    xAxes: [{
                                        //stacked: true
                                    }],
                                    yAxes: [{
                                        //stacked: true,
                                        ticks: {
                                            beginAtZero: true
                                        }
                                    }]
                                }
    }

    var barChart = new Chart(barChartCanvas, {
      type: 'bar',
      data: barChartData,
      options: barChartOptions
    })

})
</script>
@stop


@include('footer')