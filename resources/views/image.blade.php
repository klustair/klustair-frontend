@extends('adminlte::page')

@section('title', 'Pod details')

@section('content_header')
    <h1 id='image' data-imageuid='{{ $image['uid'] }}'>Image {{ $image['fulltag'] }}</h1>
@stop


@section('content')
<!-- {{print_r($image)}} -->
<div class="row">
    <div class="col-7">
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
            <!-- /.card-body -->
        </div>
    </div>
    <div class="col-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Vulnerabilty Summary</h3>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive p-3">
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
                            <input type="checkbox" id="{{ $vulnerabily['uid'] }}" class="whitelistItem" name="whitelist" value="{{ $vulnerabily['uid'] }}" @if ($vulnerabily['images_vuln_whitelist_uid'] != "") checked @endif>
                        </td>
                    </tr>
                    <p>
                    @endforeach
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
            'uid' : $(this).attr('id'),
            'state' :  $(this).prop('checked')
        }
        vuln_uid_list.push(vuln)
    });

    console.log(vuln_uid_list)

    var data = {
        vuln_uid_list: vuln_uid_list
    }
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