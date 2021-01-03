<!--<pre>{{ print_r($users) }}</pre>-->
<table class="table table-condensed">
    <thead>
    <tr>
        <th>Name</th>
        <th>Email</th>
        <th>created at</th>
        <th>updated at</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)
        <tr>
            <td>{{ $user->name}}</td>
            <td>{{ $user->email}}</td>
            <td>{{ $user->created_at}}</td>
            <td>{{ $user->updated_at}}</td>
        </tr>
        <p>
    @endforeach
    </tbody>
</table>
