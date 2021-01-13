
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
                        <th>title</th>
                        <th>namespaces</th>
                        <th width="430px">Vulnerabilities</th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach( $reports as $report  )
                        <tr>
                            <td>
                            @auth
                                <ul class="list-inline m-0">
                                    <li class="list-inline-item">
                                        <button class="btn btn-danger btn-xs btnDeleteToken" data-tokenid="{{ $report->uid}}" type="button" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="fa fa-trash-alt"></i></button>
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