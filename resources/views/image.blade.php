@extends('adminlte::page')

@section('title', 'Pod details')

@section('content_header')
    <h1 id='image' data-imageuid='{{ $image['anchore_imageid'] }}'>Image {{ $image['fulltag'] }}</h1>
@stop


@section('plugins.Chartjs', true)

@section('content')
<!-- {{print_r($image)}} -->
<div class="row">
    <div class="col-8">
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
        </div>

            <!-- STACKED BAR CHART -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Vulnerability history</h3>
            </div>
            <div class="card-body">
                <div class="chart">
                    <canvas id="barChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
            </div>
        </div>

    </div>
    <div class="col-4">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Vulnerabilty Summary</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-3">
                <div class="chart">
                    <canvas id="pieChart" style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%;"></canvas>
                </div>
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
        <div class="card collapsed-card">
            <div class="card-header">
            Dockerfile
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse"><i class="fas fa-plus"></i></button>
            </div>
            </div>
            <!-- /.card-body -->
            <div class="card-body table-responsive p-0">
            <pre style="background-color: #e1e1e1">
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
                    <th style="width: 20px"><input type="checkbox" id="checkAll"></th>
                </tr>
                </thead>
                <tbody>
                    @isset ($image['vulnerabilities'])
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
                        <td>
                            <input type="checkbox" id="{{ $vulnerabily['vuln'] }}" class="whitelistItem" name="whitelist" value="{{ $vulnerabily['uid'] }}" @if ($vulnerabily['images_vuln_whitelist_uid'] != "") checked @endif>
                        </td>
                    </tr>
                    <p>
                    @endforeach
                    @endisset
                </tbody>
                <tr>
                <td colspan="3"></td>
                <td colspan="4"><button type="button" id="UpdateWhitelist" class="btn btn-block bg-gradient-success swalDefaultSuccess">Add to Whitelist</button></td>
                <tr>
            </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
<!-- /.card -->
</div>

<div class="modal fade" id="aaamyModal" tabindex="-1" role="dialog">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
        <h4 class="modal-title">Modal title</h4>
      </div>
      <div class="modal-body">
        <p>One fine body&hellip;</p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
        <button type="button" class="btn btn-primary">Save changes</button>
      </div>
    </div><!-- /.modal-content -->
  </div><!-- /.modal-dialog -->
</div><!-- /.modal -->


<div class="modal fade" id="myModal" tabindex="-1"  role="dialog">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
        <div class="modal-header">
            <h4 class="modal-title">Large Modal</h4>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">×</span>
            </button>
        </div>
        <div class="modal-body">
            <p>One fine body…</p>
        </div>
        <div class="modal-footer justify-content-between">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            <button type="button" class="btn btn-primary">Save changes</button>
        </div>
        </div>
        <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>

@stop

@section('plugins.Sweetalert2', true)
@section('js')
<script> 

$(function () {

    //-------------
    //- VULNHIST CHART -
    //-------------


    var vulnhistChartData = {
      labels  : [{!! implode(',', $vulnhistory['labels']) !!}],
      datasets: [
        {
          label               : 'Vulnerabilities',
          backgroundColor     : 'rgba(210, 214, 222, 1)',
          borderColor         : 'rgba(210, 214, 222, 1)',
          pointRadius         : false,
          pointColor          : 'rgba(210, 214, 222, 1)',
          pointStrokeColor    : '#c1c7d1',
          pointHighlightFill  : '#fff',
          pointHighlightStroke: 'rgba(220,220,220,1)',
          data                : [{{ implode(",", $vulnhistory['data']) }}]
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
                                    yAxes: [{
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

    //-------------
    //- PIE CHART -
    //-------------
    var donutData        = {
      labels: [
          'Critical',
          'High',
          'Medium',
          'Low',
          'Negligible',
          'Unknown',
      ],
      datasets: [
        {
          data: [{{ $image['vulnsummary_list']['Critical'] }}, {{ $image['vulnsummary_list']['High'] }}, {{ $image['vulnsummary_list']['Medium'] }}, {{ $image['vulnsummary_list']['Low'] }}, {{ $image['vulnsummary_list']['Negligible'] }}, {{ $image['vulnsummary_list']['Unknown'] }}, ],
          backgroundColor : ['#DC3545', '#FFC108', '#17A2B8', '#6C757D', '#343A40','#F8F9FA' ],
        }
      ]
    }

    // Get context with jQuery - using jQuery's .get() method.
    var pieChartCanvas = $('#pieChart').get(0).getContext('2d')
    var pieData        = donutData;
    var pieOptions     = {
      maintainAspectRatio : false,
      responsive : true,
    }
    //Create pie or douhnut chart
    // You can switch between pie and douhnut using the method below.
    var pieChart = new Chart(pieChartCanvas, {
      type: 'pie',
      data: pieData,
      options: pieOptions
    })

})

$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
});

$("#aaaaaaUpdateWhitelist").click(function(){
    console.log('openModal')
    $('.whitelistItem').each(function( index ) {
        console.log( index + ": " + $( this ).text() );
    });
    $('#myModal').modal('show')
});

const Toast = Swal.mixin({
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000
});

$('.swalDefaultSuccess').click(function() {
    vuln_uid_list = []

    $('.whitelistItem').each(function( index ) {
        //console.log( index + ": " + $(this).prop('checked') );
        vuln = {
            'vuln' : $(this).attr('id'),
            'state' :  $(this).prop('checked')
        }
        if ($(this).prop('checked') == true) {
            vuln_uid_list.push(vuln)
        }
    });

    // Encode and Stringify fields to avoid hitting the POST Max 
    // field setting on images woth more than 500 vulnerabilities
    var encodedString = btoa(JSON.stringify(vuln_uid_list));

    var data = {
        vuln_list: encodedString
    }
    console.log(data)
    $.post( '/api/v1/vulnwhitelist/update/'+$('#image').data('imageuid'), data, function( data ) {
        $( '.result' ).html( data );
    })
    
    Toast.fire({
    type: 'success',
    title: 'Updated Whitelist'
    })
});

</script>
@stop