<!--<pre>{{ print_r($users) }}</pre>-->
<table class="table table-condensed">
    <thead>
    <tr>
        <th>label</th>
        <th>kubeaudit</th>
        <th>namespacesblacklist</th>
        <th>namespaces</th>
        <th>scanner</th>
        <th>trivycredentialspath</th>
        <th>limit_date</th>
        <th>limit_nr</th>
        <th>verbosity</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($configRunner as $runner)
        <tr>
            <td>{{ $runner->runner_label}}</td>
            <td>{{ $runner->kubeaudit}}</td>
            <td>{{ $runner->namespacesblacklist}}</td>
            <td>{{ $runner->namespaces}}</td>
            <td>{{ $runner->trivy}}</td>
            <td>{{ $runner->trivycredentialspath}}</td>
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
        id="UpdateWhitelist" 
        class="btn btn-block bg-gradient-success swalDefaultSuccess" 
        style="width:200px;">Add new Runner Config
    </button>
</div>



@section('js')
<script>
$.get( "/api/v1/test", function( data ) {
  $( ".result" ).html( data );
  alert( "Load was performed." + data);
});
 </script>
@stop