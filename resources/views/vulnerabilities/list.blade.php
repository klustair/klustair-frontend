@extends('adminlte::page')

@section('title', 'vulnerabilities')

@section('content_header')
    <h1>Vulnerabilities</h1>
@stop

@section('content')
@csrf

<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">All</h3>
                <div class="card-tools">
                    <span class="badge badge-pill bg-purple">base</span>
                    <span class="badge badge-pill bg-fuchsia">exploitability</span>
                    <span class="badge badge-pill badge-info">impact</span>
                </div>
            </div>
            <!-- /.card-header -->
            <div class="card-body table-responsive">
            <table id="dyn-vulnlist" class="table table-condensed"  style="width:100%">
                <thead>
                <tr>
                    <th style="display:none">Severity</th>
                    <th>CVE</th>
                    <th>Title</th>
                    <th>Package</th>
                    <th>IMG</th>
                    <th>Score</th>
                    <th style="width: 40px">CVSS</th>
                    <th style="width: 40px">Fixed</th>
                    <th style="width: 20px">@auth<input type="checkbox" id="checkAll">@endauth</th>
                </tr>
                </thead>
                <tbody>
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

@stop

@section('plugins.Sweetalert2', true)
@section('plugins.Datatables', true)
@section('js')
<script> 


$(document).ready(function() {
    $('#dyn-vulnlist').DataTable({
        ordering: false,
        searching: false,
        stateSave: true,
        serverSide: true,
        ajax: {
            url: '/api/v1/vulnerabilities',
            dataSrc: 'data'
        },
        pageLength: 10,
        lengthMenu: [[10, 25, 50, -1], [10, 25, 50, "All"]],
        columns: [
            {   data: 'vulnerability_id',
                orderable: false,
                render: function ( data, type, row ) {
                    //console.log(row);
                    return '<a href="/vulnerability/'+data+'"><nobr>'+data+'</nobr></a>';
                }
            },
            {   data: 'title',
                orderable: false,
                render: function ( data, type, row ) {
                    return '<b>'+data+'</b>';
                }
            },
            {   data: 'pkg_name',
                orderable: false,
            },
            {   data: 'imagecount' },
            {   data: 'cvss',
                orderable: false,
                render: function ( data, type, row ) {

                    if (data.V3Score == null) {
                        return '';
                    } else {
                        return `
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-purple" style="width: ${data.V3Vector_base_score*10}%"></div>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-fuchsia" style="width: ${data.V3Vector_modified_esc*10}%"></div>
                            </div>
                            <div class="progress progress-xs">
                                <div class="progress-bar bg-info" style="width: ${data.V3Vector_modified_isc*10}%"></div>
                            </div>`;
                    }
                }
            },
            {   data: 'cvss_base_score',
                render: function ( data, type, row ) {
                    return '<span class="badge '+ row.severity +'">'+ data +'</span>';
                }
            },
            {   data: 'fixed_version',
                render: function ( data, type, row ) {
                    if (data != "") {
                        return '<span class="badge badge-pill badge-success">yes</span>';
                    } else {
                        return '';
                    }
                }
            },
            {   data: 'vulnerability_id',
                render: function ( data, type, row ) {
                    let checkbox = ''
                    if (row.images_vuln_whitelist_uid) {
                        checkbox = 'checked'
                    }
                    return '<input type="checkbox" id="'+ row.vulnerability_id+'" class="whitelistItem" name="whitelist" value="'+row.uid+'" '+checkbox+'/>';
                }
            }
        ]
    });
} );

$("#checkAll").click(function(){
    $('input:checkbox').not(this).prop('checked', this.checked);
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