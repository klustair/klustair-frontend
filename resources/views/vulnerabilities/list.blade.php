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
            <table id="vulnlist" class="table table-condensed">
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
                    @auth<th style="width: 20px"><input type="checkbox" id="checkAll"></th>@endauth
                </tr>
                </thead>
                <tbody>
                    @include('vulnerabilities.vulnlisttrivy', ['vulnerabilities' => $vulnerabilities])
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
    $('#vulnlist').DataTable();
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