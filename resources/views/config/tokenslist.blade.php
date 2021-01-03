<!--<pre>{{ print_r($tokens) }}</pre>--->
<table class="table table-condensed">
    <thead>
    <tr>
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
        id="UpdateWhitelist" 
        class="btn btn-block bg-gradient-success swalDefaultSuccess" 
        style="width:200px;">Add new Token
    </button>
</div>
