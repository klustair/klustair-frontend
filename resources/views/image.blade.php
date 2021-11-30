@extends('adminlte::page')

@section('title', 'Pod details')

@section('content_header')
    <h1 id='image' data-imageb64='{{ $image['image_b64'] }}'>Image {{ $image['fulltag'] }}</h1>
@stop


@section('plugins.Chartjs', true)

@section('content')
<!-- {{print_r($image)}} -->
@csrf
<div class="row">
    <div class="col-8">
        @if ($image['layer_count'] != "")
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Image</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-3">
                <table>
                    <tbody>
                    <tr>
                        <th>Tag</th>
                        <td>{{ $image['fulltag'] }}</td>
                    </tr>
<!--
                    <tr>
                        <th>Registy :</th>
                        <td>{{ $image['registry'] }}</td>
                    </tr>
-->
                    <tr>
                        <th>Distro</th>
                        <td>{{ $image['distro'] }}:{{ $image['distro_version'] }}</td>
                    </tr>
                    <tr>
                        <th>Layers</th>
                        <td>{{ $image['layer_count'] }}</td>
                    </tr>
                    <tr>
                        <th>Created at</th>
                        <td><b>{{ $image['age'] }} days </b> ({{ $image['created_at'] }})</td>
                    </tr>
                    <tr>
                        <th>Architecture</th>
                        <td>{{ $image['arch'] }}</td>
                    </tr>
<!--
                    <tr>
                        <th>Size</th>
                        <td>{{ $image['image_size'] }}</td>
                    </tr>
-->
                    </tbody>
                </table>
            </div>
        </div>
        @endif

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

@if ($image['history'] != "")
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
@foreach (json_decode($image['history']) as $history)
{{ $history->created_by }}
{{ Str::replaceArray('\t', ["\n"], $history->created_by) }}
@endforeach
            </pre>
            </div>
        </div>
    </div>
<!-- /.card -->
</div>
@endif

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Vulnerabilties</h3>
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
                    <th style="width: 50px"></th>
                    <th>Title</th>
                    <th>CVE</th>
                    <th>Package</th>
                    <th>Score</th>
                    <th style="width: 40px">CVSS</th>
                    <th style="width: 40px">Fixed</th>
                    @auth<th style="width: 20px"><input type="checkbox" id="checkAll"></th>@endauth
                </tr>
                </thead>
                <tbody>
                    @foreach ($image['targets'] as $target)
                    <tr><td colspan="8" style="background-color: gainsboro;" class="p-1 pl-3"><b>{{$target ['metadata']['target_type'] }}</b> ({{$target ['metadata']['target'] }})</td></tr>
                        @isset ($target['vulnerabilities'])
                            @include('vulnlisttrivy', ['vulnerabilities' => $target['vulnerabilities']])
                        @endisset
                    <tr><td colspan="8" class="p-1">&nbsp;</td></tr>
                    @endforeach
                </tbody>
            </table>
            @auth
            <div class="p-2" style="float:right;">
                <button type="button" id="UpdateWhitelist" class="btn btn-block bg-gradient-success swalDefaultSuccess" style="width:200px;">Add to Whitelist</button>
            </div>
            @endauth

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
          'Unknown',
      ],
      datasets: [
        {
          data: [{{ $image['vulnsummary_list']['Critical'] }}, {{ $image['vulnsummary_list']['High'] }}, {{ $image['vulnsummary_list']['Medium'] }}, {{ $image['vulnsummary_list']['Low'] }}, {{ $image['vulnsummary_list']['Unknown'] }}, ],
          backgroundColor : ['#DC3545', '#FFC108', '#17A2B8', '#6C757D' ,'#F8F9FA' ],
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


$(".collapseDetails").click(function(){
    var tr = $(this).closest('tr').next('tr');
    tr.toggle()
});

$("#aaaaaaUpdateWhitelist").click(function(){
    console.log('openModal')
    $('.whitelistItem').each(function( index ) {
        console.log( index + ": " + $( this ).text() );
    });
    $('#myModal').modal('show')
});

const Toast = Swal.mixin({
    //toast: true,
    //position: 'top-end',
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
        _token: $('input[name="_token"]').val(),
        vuln_list: encodedString
    }
    console.log(data)
    $.post( '/api/v1/vulnwhitelist/update/'+$('#image').data('imageb64'), data, function( data ) {
        Toast.fire({
            icon: 'success',
            title: 'Updated Whitelist'
        })
    })
});

</script>
@stop

@include('footer')