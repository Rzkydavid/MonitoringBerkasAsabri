<!DOCTYPE html>
<html>
<head>
    <title>List of Privileges</title>
</head>
<body>
    <h1>Available Privileges</h1>

    <table border="1" cellpadding="10">
        <tr>
            <th>ID</th>
            <th>Role ID</th>
            <th>Privilege Name</th>
        </tr>

        @foreach ($privileges as $p)
        <tr>
            <td>{{ $p->id }}</td>
            <td>{{ $p->role_id }}</td>
            <td>{{ $p->privilege_name }}</td>
        </tr>
        @endforeach
    </table>

</body>
</html>
