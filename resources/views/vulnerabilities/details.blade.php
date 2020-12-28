@extends('adminlte::page')

@section('title', 'vulnerability')

@section('content_header')
    <h1>{{$vulnerability['vulnerability_id']}}</h1>
@stop

@section('content')
<div class="row">
    <div class="col-8">
        <div class="card">
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
                        </tr>
                        <tr>
                            <td style="border-top: none">
                                <div class="row">
                                    <div class=" bg-light rounded border p-1">
                                    {{ $vulnerability['descr'] }}
                                    </div>
                                </div>
                            </td>
                        </tr>
                    </tbody>
                </table>
            <!-- /.card-body -->
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Affected Packages</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <table class="table table-striped">
                    <tr>
                        <th>Package</th>
                        <th>Installed version</th>
                        <th>Fixed version</th>
                    </tr>
                    @foreach ($packages as $package)
                    <tr>
                        <td>{{ $package['pkg_name'] }}</td>
                        <td>{{ $package['installed_version'] }}</td>
                        <td>{{ $package['fixed_version'] }}</td>
                    </tr>
                    @endforeach
                </table>
            <!-- /.card-body -->
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Affected Images</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body p-0">
                <table class="table table-striped">
                    <tr>
                        <th>Image</th>
                        <th>Namespace</th>
                    </tr>
                    @foreach ($images as $image)
                    <tr>
                        <td><a href="/image/{{ $image['report_uid'] }}/{{ $image['uid'] }}">{{ $image['fulltag'] }}</a></td>
                        <td>{{ $image['name'] }}</td>
                    </tr>
                    @endforeach
                </table>
            <!-- /.card-body -->
            </div>
        </div>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Links</h3>
            </div>
            <div class="card-body table-responsive">
                @if ($vulnerability['links'] != '')
                <ul class="fa-ul">
                    @foreach ($vulnerability['links'] as $link)
                    <li><span class="fa-li" style="color: gray"><i class="fas fa-link fa-xs"></i></span><a href="{{ $link }}" target="_blank">{{ $link }}</a></li>
                    @endforeach
                </ul>
                @endif
            </div>
        </div>
    </div>
    <div class="col-4">
    <!-- not Working now
        <div class="pb-3">
            <button type="button" id="UpdateWhitelist" class="btn btn-block bg-gradient-success swalDefaultSuccess">Add to Whitelist</button>
        </div>
    -->
        <!-- small card -->
        <div class="small-box  {{$vulnseverity[$vulnerability['severity']]['css']}}">
            <div class="inner">
                <h3>{{$vulnerability['cvss_base_score']}}</h3>

                <p>CVSS Base Score</p>
            </div>
            <div class="icon">
                <i class="fas fa-bug"></i>
            </div>
            <a href="#" class="small-box-footer">{{ Str::upper($vulnseverity[$vulnerability['severity']]['name'])}} </a>
        </div>


        <div class="card">
            <div class="card-body table-responsive p-0">
                <div id="chart"></div>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3 class="card-title">CVSS</h3>
            </div>
            <div class="card-body table-responsive">
                        @include('elements.cvss', ['vuln_cvss' => $vulnerability['cvss']])
            </div>
        </div>
    </div>
    <!-- /.card -->
</div>

@stop


@section('js')
<script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>

<script> 
var options = {
  chart: {
    type: 'bar',
    toolbar: {
        show: false,
    }
  },
  colors:['#6f42c1', '#f012be', '#17a2b8'],
  series: [
        {
            name: 'cvss scores',
            data: [{{ $vulnerability['cvss']['V3Vector_base_score']}},{{ $vulnerability['cvss']['V3Vector_modified_esc']}},{{ $vulnerability['cvss']['V3Vector_modified_isc']}}]
        },
  ],
  xaxis: {
    categories: ['base','exploitability','impact'],
    labels: {
          show: false,
    },
  },
  yaxis: {
    max: 10,
  },
  plotOptions: {
    bar: {
        distributed: true
    }
  }
}

var chart = new ApexCharts(document.querySelector("#chart"), options);

chart.render();
</script>

@stop