<!--<pre>{{ print_r($tokens) }}</pre>--->
<table class="table table-condensed">
    <thead>
    <tr>
        <th></th>
        <th>Name</th>
        <th>Token</th>
        <th>Abilities</th>
        <th>last used at</th>
        <th>created at</th>
        <th>updated at</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($tokens as $token)
        <tr>
            <td class="pl-3">
                <ul class="list-inline m-0">
                    <li class="list-inline-item">
                        <button class="btn btn-danger btn-xs" class="btnDeleteToken" type="button" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="fa fa-trash-alt"></i></button>
                    </li>
                </ul>
            </td>
            <td>{{ $user->name}}</td>
            <td>{{ $user->token}}</td>
            <td>{{ $user->abilities}}</td>
            <td>{{ $user->last_used_at}}</td>
            <td>{{ $user->created_at}}</td>
            <td>{{ $user->updated_at}}</td>
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
        <form role="form">
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
                    <input type="label" class="form-control" id="tokenName" placeholder="Enter label">
                </div>

                <div class="form-group">
                    Abilities
                    <div class="form-check">
                        <input class="form-check-input" type="checkbox">
                        <label class="form-check-label">Checkbox</label>
                    </div>
                </div>
            </div>
            <div class="modal-footer justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save changes</button>
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
});
$(".btnDeleteToken").click(function(){
    $('#TokenModal').modal('show')
});
 </script>
@stop
