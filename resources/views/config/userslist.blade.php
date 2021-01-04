<!--<pre>{{ print_r($users) }}</pre>-->
<table class="table table-condensed">
    <thead>
    <tr>
        <th></th>
        <th>Name</th>
        <th>Email</th>
        <th>created at</th>
        <th>updated at</th>
    </tr>
    </thead>
    <tbody>
    @foreach ($users as $user)
        <tr>
            <td class="pl-3">
                <ul class="list-inline m-0">
                    <li class="list-inline-item">
                        <button class="btn btn-danger btn-xs" type="button" data-toggle="tooltip" data-placement="top" title="" data-original-title="Delete"><i class="fa fa-trash-alt"></i></button>
                    </li>
                </ul>
            </td>
            <td>{{ $user->name}}</td>
            <td>{{ $user->email}}</td>
            <td>{{ $user->created_at}}</td>
            <td>{{ $user->updated_at}}</td>
        </tr>
        <p>
    @endforeach
    </tbody>
</table>
