
<div class="row" style="margin-top:30px">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Reports</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-0">
                <table class="table table-condensed">
                    <thead>
                    <tr>
                        <th width="50px"></th>
                        <th width="150px">Date</th>
                        <th>Title</th>
                        <th width="120px">Namespaces</th>
                        <th width="390px">Vulnerabilities</th>
                        <th width="75px">No Ack</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach( $reports as $report  )
                        <!-- {{ $vuln_noack = $report->vuln_total - $report->vuln_acknowledged }} -->
                        <tr>
                            <td>
                            @auth
                                <ul class="list-inline m-0">
                                    <li class="list-inline-item">
                                        <button class="btn btn-danger btn-xs btnDeleteToken" data-reportuid="{{ $report->reports_uid}}" type="button" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="fa fa-trash-alt"></i></button>
                                    </li>
                                </ul>
                            @endauth
                            </td>
                            <td><a href="report/{{ $report->report_uid}}">{{ $report->checktime}}</a></td>
                            <td>{{ $report->title}}</td>
                            <td>{{ $report->namespaces_total }}/{{ $report->namespaces_checked}}</td>
                            <td>
                                <div style="width: 65px" class="float-left p-1 m-1 text-center bg-danger rounded">{{ $report->vuln_critical}}</div>
                                <div style="width: 65px" class="float-left p-1 m-1 text-center bg-warning rounded">{{ $report->vuln_high}}</div>
                                <div style="width: 65px" class="float-left p-1 m-1 text-center bg-info rounded">{{ $report->vuln_medium}}</div>
                                <div style="width: 65px" class="float-left p-1 m-1 text-center bg-secondary rounded">{{ $report->vuln_low}}</div>
                                <div style="width: 65px" class="float-left p-1 m-1 text-center bg-light rounded">{{ $report->vuln_unknown}}</div>
                            </td>
                            <td>
                                @if ($vuln_noack > 0)
                                <div style="width: 65px" class="float-left p-1 m-1 text-center bg-danger rounded">{{ $vuln_noack }}</div>
                                @else
                                <div style="width: 65px" class="float-left p-1 m-1 text-center bg-success rounded">{{ $vuln_noack }}</div>
                                @endif
                            </td>
                        </tr>
                        <p>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- /.card-body -->
        </div>
    </div>
<!-- /.card -->
</div>

<!--<pre>{{ print_r($reports) }}</pre>-->

@section('js')
@parent
<script>
$(".btnDeleteToken").click(function(){
    console.log('Delete: '+$(this).data('reportuid'))
    
    $.get( "/api/v1/report/delete/"+$(this).data('reportuid'), function( data ) {
        location.reload();
    });
});
 </script>
@stop
