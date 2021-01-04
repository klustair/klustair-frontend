<!--<pre>{{ print_r($users) }}</pre>-->
<table class="table table-condensed">
    <thead>
    <tr>
        <td style="width: 90px;"></td>
        <th>label</th>
        <th>kubeaudit</th>
        <th>namespacesblacklist</th>
        <th>namespaces</th>
        <th>limit_date</th>
        <th>limit_nr</th>
        <th>verbosity</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($configRunner as $runner)
        <tr>
            <td class="pl-3">
                <ul class="list-inline m-0">
                    <li class="list-inline-item">
                        <button class="btn btn-success btn-xs btnEditRunnerConfig" data-userid="{{ $runner->uid}}" type="button" data-toggle="tooltip" data-placement="top" title="" data-original-title="Edit"><i class="fa fa-edit"></i></button>
                    </li>
                    <li class="list-inline-item">
                        <button class="btn btn-danger btn-xs btnDeleteRunnerConfig" data-userid="{{ $runner->uid}}" type="button" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="fa fa-trash-alt"></i></button>
                    </li>
                </ul>
            </td>
            <td>{{ $runner->runner_label}}</td>
            <td>{{ $runner->kubeaudit}}</td>
            <td>{{ $runner->namespacesblacklist}}</td>
            <td>{{ $runner->namespaces}}</td>
            <td>{{ $runner->limit_date}}</td>
            <td>{{ $runner->limit_nr}}</td>
            <td>{{ $runner->verbosity}}</td>
        </tr>
        <p>
    @endforeach
    </tbody>
</table>

<div class="p-2" style="float:right;">
    <button 
        type="button" 
        id="btnNewRunnerConfig" 
        class="btn btn-block bg-gradient-success swalDefaultSuccess" 
        style="width:200px;">Add new Runner Config
    </button>
</div>

<div class="modal fade" id="RunnerConfigModal" tabindex="-1"  role="dialog">
    <div class="modal-dialog modal-lg">
        <form id="RunnerConfigForm">
        @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Runner Config</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="formRunnerLabel">Label</label>
                        <input name="label" type="text" class="form-control" id="formRunnerLabel" placeholder="Enter label">
                    </div>
                    <div class="form-group">
                        <label for="formRunnerKubeaudit">Kubeaudit</label> (Disable with "none")
                        <input name="kubeaudit" type="text" class="form-control" id="formRunnerKubeaudit" placeholder="Enter kubeaudit checks">
                    </div>
                    <div class="form-group">
                        <label for="formRunnerNamespacesblacklist">Namespacesblacklist</label>
                        <input name="namespacesblacklist" type="text" class="form-control" id="formRunnerNamespacesblacklist" placeholder="Enter commaseparated list of ignored namespaces">
                    </div>
                    <div class="form-group">
                        <label for="formRunnerNamespaces">Namespaces</label>
                        <input name="namespaces" type="text" class="form-control" id="formRunnerNamespaces" placeholder="Enter commaseparated list of checked namespaces">
                    </div>
                    <div class="form-group">
                        <label for="formRunnerLimitNr">Limit by Nr</label>
                        <input name="limit_nr" type="number" class="form-control" id="formRunnerLimitNr" placeholder="Limit the reports to keep">
                    </div>
                    <div class="form-group">
                        <label for="formRunnerLimitDate">Limit by Date</label>
                        <input name="limit_date" type="number" class="form-control" id="formRunnerLimitDate" placeholder="Limit the reports to keep by days">
                    </div>
                    <div class="form-group">
                        <div class="custom-control custom-switch">
                        <input name="verbose" type="checkbox" class="custom-control-input" id="formRunnerVerbose">
                        <label class="custom-control-label" for="formRunnerVerbose">Enable Verbose logging</label>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                    <button type="button" id="btnCreateRunnerConfig" class="btn btn-primary">Save changes</button>
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

$.get( "/api/v1/test", function( data ) {
  //alert( "Load was performed." + data);
});

$('#RunnerConfigForm').validate({
    rules: {
      formRunnerLimitNr: {
        required: false,
        number: true,
      },
      formRunnerLimitDate: {
        required: false,
        number: true
      }
    },
    messages: {
      formRunnerLimitNr: {
        number: "Please enter a Number"
      },
      formRunnerLimitDate: {
        number: "Please enter a Number"
      },
    },
    errorElement: 'span',
    errorPlacement: function (error, element) {
      error.addClass('invalid-feedback');
      element.closest('.form-group').append(error);
    },
    highlight: function (element, errorClass, validClass) {
      $(element).addClass('is-invalid');
    },
    unhighlight: function (element, errorClass, validClass) {
      $(element).removeClass('is-invalid');
    }
  });


$("#btnNewRunnerConfig").click(function(){
    $('#RunnerConfigModal').modal('show')
    
    $('#RunnerConfigModal').find("input[type=text], textarea")
        .val("")
        .prop('checked', false)
        .prop('selected', false);
});


$("#btnCreateRunnerConfig").click(function(){
    console.log('Create new Runnerconfig')
    values = $("#RunnerConfigForm").serializeArray();
    console.log(values)

    $.post( "/api/v1/config/runner/create", values);
    //$.post( "/api/v1/config/runner/create", { label: values.value, time: "2pm" } );
    
    //$('#RunnerConfigModal').modal('hide')
});

$(".btnDeleteRunnerConfig").click(function(){
    console.log('Delete: '+$(this).data('userid'))
    
    $.get( "/api/v1/config/runner/delete/"+$(this).data('userid'), function( data ) {
    //alert( "Load was performed." + data);
    });
});
 </script>
@stop
