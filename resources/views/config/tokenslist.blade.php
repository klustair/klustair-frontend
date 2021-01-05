<!--<pre>{{ print_r($tokens) }}</pre>--->
<table class="table table-condensed">
    <thead>
    <tr>
        <th></th>
        <th>Name/Purpose</th>
        <th>Token</th>
        <!--<th>Abilities</th> Not implemented yet-->
        <th>last used at</th>
        <th>created at</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($tokens as $token)
        <tr>
            <td class="pl-3">
                <ul class="list-inline m-0">
                    <li class="list-inline-item">
                        <button class="btn btn-danger btn-xs btnDeleteToken" data-tokenid="{{ $token->id}}" type="button" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="fa fa-trash-alt"></i></button>
                    </li>
                </ul>
            </td>
            <td>{{ $token->name}}</td>
            <td>{{ $token->token}}</td>
            <td>{{ $token->last_used_at}}</td>
            <td>{{ $token->created_at}}</td>
        </tr>
        <p>
    @endforeach
    </tbody>
</table>

<div class="p-2" style="float:right;">
    <button 
        type="button" 
        id="btnNewToken" 
        class="btn btn-block bg-gradient-success swalDefaultSuccess" 
        style="width:200px;">Add new Token
    </button>
</div>

<div class="modal fade" id="TokenModal" tabindex="-1"  role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="TokenForm">
        @csrf
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Token</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">×</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="tokenName">Name/Purpose</label>
                    <input type="text" class="form-control" name="tokenName" id="tokenName" placeholder="Enter label">
                </div>
<!--
                <div class="form-group">
                    Abilities
                    <div class="form-check">
                        <input class="form-check-input" name="ability_full" type="checkbox">
                        <label class="form-check-label">Full</label>
                    </div>
                    <div class="form-check">
                        <input class="form-check-input" name="ability_monitoring" value="monitoring" type="checkbox">
                        <label class="form-check-label">Monitoring</label>
                    </div>
                </div>
-->
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" id="btnCreateToken" class="btn btn-primary">Save changes</button>
            </div>
        </div>
        </form>
        <!-- /.modal-content -->
    </div>
<!-- /.modal-dialog -->
</div>


@section('js')
@parent
<script>
$("#btnNewToken").click(function(){
    $('#TokenModal').modal('show')
    $('#TokenModal').find("input[type=text], input[type=checkbox], textarea")
        .val("")
        .prop('checked', false)
        .prop('selected', false);
});


$("#btnCreateToken").click(function(){
    console.log('Create new Token')
    values = $("#TokenForm").serializeArray();
    console.log(values)

    $.post( "/api/v1/config/token/create", values, function( data ) {
        location.reload();
    });
    
    //$('#RunnerConfigModal').modal('hide')
    
});

$(".btnDeleteToken").click(function(){
    console.log('Delete: '+$(this).data('tokenid'))
    
    $.get( "/api/v1/config/token/delete/"+$(this).data('tokenid'), function( data ) {
        location.reload();
        //$(this).closest("tr").remove();
    });
});
 </script>
@stop
