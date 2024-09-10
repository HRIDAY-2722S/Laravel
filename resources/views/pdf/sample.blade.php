<!DOCTYPE html>
<html>
<head>
    <title>User || Pdf</title>
    <style>
        /* Define your styles here */
        body {
            font-family: Arial, sans-serif;
        }
        .container {
            width: 100%;
            margin: 0 auto;
            padding: 20px;
        }
        .user-table {
            border-collapse: collapse;
            width: 100%;
        }
        .user-table th, .user-table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        .user-table th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1 style="text-align: center;">Users Data</h1>
        <div class="table-responsive">
            <table class="user-table">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>E-mail verification</th>
                        <th>Created_at</th>
                        <th>Updated_at</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $user)
                    <tr>
                        <td>{{ $user->id }}</td>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>
                            @if($user->status == 0)
                            <span class="badge badge-danger" style="color: red;">Inactive</span>
                            @else
                            <span class="badge badge-success" style="color: green;">Active</span>
                            @endif
                        </td>
                        <td>
                            @if($user->email_verify == 0)
                                <span class="badge badge-danger" style="color: red;">Not Verified</span>
                            @else
                                <span class="badge badge-success" style="color: green;">Verified</span>
                            @endif
                        </td>
                        <td>{{ $user->created_at->format('d M, Y') }}</td>
                        <td>{{ $user->updated_at->format('d M, Y') }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</body>
</html>