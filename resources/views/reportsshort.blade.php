@extends('adminlte::page')

@section('title', 'Reports')

@section('content_header')
    <h1>Report {{ $report_data->checktime }} {{ $report_data->title }}</h1>
@stop

@section('content')

<p>
  <div class="dropdown show">
    <a class="btn btn-secondary dropdown-toggle" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
      Select Report
    </a>

    <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
    @foreach ($reports as $report)
      <a class="dropdown-item" href="/report/{{ $report->uid }}">{{ $report->checktime }} {{ $report->title }}</a>
    @endforeach
    </div>
  </div>
</p>

<div class="row">
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-success">
              <span class="info-box-icon"><i class="far fa-thumbs-up"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Namespaces Checked</span>
                <span class="info-box-number"><h3>{{ $stats['namespaces'] }}</h3></span>

              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-6 col-12">
            <div class="info-box bg-info">
              <span class="info-box-icon"><i class="far fa-bookmark"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Pods Checked</span>
                <span class="info-box-number"><h3>{{ $stats['pods'] }}</h3></span>
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
          <div class="col-md-3 col-sm-12 col-12">
            <div class="info-box bg-warning">
              <span class="info-box-icon"><i class="far fa-calendar-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Containters Scanned </span>
                <span class="info-box-number"><h3>{{ $stats['containers'] }}</h3></span>

              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
          <div class="col-md-3 col-sm-12 col-12">
            <div class="info-box bg-warning">
              <span class="info-box-icon"><i class="far fa-calendar-alt"></i></span>

              <div class="info-box-content">
                <span class="info-box-text">Images Scanned </span>
                <span class="info-box-number"><h3>{{ $stats['images'] }}</h3></span>

              </div>
              <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
          </div>
          <!-- /.col -->
        </div>

<p>
  <button type="button" class="btn btn-block btn-default" id="toggler">show/hide Namespace details</button>
</p>


@foreach ($namespaces as $namespace)
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title"><a href="/namespace/{{ $report_data->uid }}/{{ $namespace['uid'] }}">{{ $namespace['name'] }}</a></h3>
                <!--
                <div class="card-tools">
                  <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-minus"></i>
                  </button>
                </div>
                -->
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-3">

            <div class="row">

            <a href="/namespace/{{ $report_data->uid }}/{{ $namespace['uid'] }}" class="info-box m-2 bg-light col-2" style="min-height: inherit">
                <span class="info-box-icon bg-danger p-1" style="width: 50px"><i class="fas fa-exclamation"></i></span>
                <div class="info-box-content">
                  <span class="info-box-number" style="font-size: 2.1rem;color: #AFAFAF;">{{ $namespace['stats']['error'] }}</span>
                </div>
                <!-- /.info-box-content -->
              </a>
              <!-- /.info-box -->

              <a href="/namespace/{{ $report_data->uid }}/{{ $namespace['uid'] }}" class="info-box m-2 bg-light col-2" style="min-height: inherit">
                <span class="info-box-icon bg-warning p-1" style="width: 50px"><i class="fas fa-times"></i></span>
                <div class="info-box-content">
                  <span class="info-box-number" style="font-size: 2.1rem;color: #AFAFAF;">{{ $namespace['stats']['warning'] }}</span>
                </div>
                <!-- /.info-box-content -->
              </a>
              <!-- /.info-box -->

              
              <a href="/namespace/{{ $report_data->uid }}/{{ $namespace['uid'] }}" class="info-box m-2 bg-light col-2" style="min-height: inherit">
                <span class="info-box-icon bg-info p-1" style="width: 50px"><i class="fas fa-info"></i></span>
                <div class="info-box-content">
                  <span class="info-box-number" style="font-size: 2.1rem;color: #AFAFAF;">{{ $namespace['stats']['info'] }}</span>
                </div>
                <!-- /.info-box-content -->
              </a>
              <!-- /.info-box -->
            </div>

                    <table class="col-12">
                      <theader>
                          <tr>
                            <th width="35px"></th>
                            <th>Container</th>
                            <th>Build Age</th>
                            <th width="60px">UP</th>
                            <th width="430px">Vulnerabilities</th>
                            <th width="50px">NO ACK</th>
                          </tr>
                      </theader>
                      <tbody>
            @if(isset($namespace['pods']))
            @foreach ($namespace['pods'] as $pod)
            @if(isset($pod['containers']))
            @foreach ($pod['containers'] as $container)
                      <tr>
                          <td><img src="/img/distro/{{ $container['imagedetails']['distro'] }}.png" alt="{{ $container['imagedetails']['distro'] }}" class="img-fluid" width="20px"> </td>
                          <td><b>{{ $container['name'] }} </b>
                            <br>
                          @if(isset($container['imagedetails']['vulnsummary']))
                            <a href="/report/{{ $report_data->uid }}/image/{{ $container['imagedetails']['image_uid'] }}">{{ $container['image'] }}</a>
                          @else
                            {{ $container['image'] }}
                          @endif
                          </td>
                          <td>
                            {{ $container['imagedetails']['age'] }}d
                          </td>
                          <td>
                            {{ $pod['age'] }}d 
                            @if($container['actual'] != '' && $container['actual'] != true)
                            <i class="fas fa-sync text-danger"></i>
                            @endif
                          </td>
                          <td>
                          @isset($container['imagedetails'], $container['imagedetails']['vulnsummary'])
                            @foreach ($container['imagedetails']['vulnsummary'] as $vulnsum)
                            <div style="width: 65px" class="float-left p-1 m-1 text-center {{$vulnseverity[$vulnsum['severity']]}} rounded">{{$vulnsum['total']}}/{{$vulnsum['fixed']}}</div>
                            @endforeach
                          @endisset
                          </td>
                          <td>
                        @if ($container['imagedetails']['vuln_ack_count'] > 0)
                             <div style="width: 45px" class="float-left p-1 m-1 text-center bg-danger rounded">{{ $container['imagedetails']['vuln_ack_count'] }}</div>
                        @else
                             <div style="width: 45px" class="float-left p-1 m-1 text-center bg-success rounded">{{ $container['imagedetails']['vuln_ack_count'] }}</div>
                        @endif
                          </td>
                      </tr>
            @endforeach
            @endif
            @endforeach
            @endif
                      </tbody>
                  </table>
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

@include('footer')
