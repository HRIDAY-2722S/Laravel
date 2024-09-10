@extends('layouts.admindefault')

@section('title', 'Admin || Users')

@section('content')

<link rel="stylesheet" href="{{ asset('Datatables/datatables.css') }}">
<link rel="stylesheet" href="{{ asset('Datatables/datatables.min.css') }}">
<meta name="csrf-token" content="{{ csrf_token() }}">


<div style="display: flex; margin-top: 30px;">
    <div style="width: 50%;">
        <h1 class="mt-4">Users Table</h1>
    </div>
    <div style="width: 48%;">
        <div class="dropdown" style="float: right;">
            <button class="btn btn-primary dropdown-toggle" type="button" id="pdfDropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                Download User Data
            </button>
            <div class="dropdown-menu" aria-labelledby="pdfDropdown">
                <a class="dropdown-item" target="__blank" href="{{ route('generate_user_pdf') }}">Download Pdf</a>
                <a class="dropdown-item" href="{{ route('export_csv') }}">Download Data in Csv Format</a>
                <a class="dropdown-item" href="{{ route('export_excel') }}">Download Data in Excel Format</a>
                <a class="dropdown-item" href="{{ route('import_csv') }}">Import User From Files</a>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-body">
        <table id="employeesTable" class="table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Profile</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Status</th>
                    <th>E-veri</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>

            </tbody>
        </table>
    </div>
</div>

<script src="{{ asset('Datatables/datatables.min.js') }}"></script>
<script>
    $(document).ready(function() {
        var table = $("#employeesTable").DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": "{{ route('getUsers') }}"
            },
            "columns": [
                { "data": 'id' },
                { 
                    "data": 'profile_picture',
                    "render": function(data, type, row) {
                        return '<img src="{{ asset('profile_picture') }}/' + data + '" width="50" height="50" style="border-radius: 50%;">';
                    }
                },
                { "data": 'name' },
                { "data": 'email' },
                {
                    "data": 'status',
                    "render": function (data, type, row) {
                        var statusText = data == 1 ? 'Active' : 'Inactive';
                        var badgeClass = data == 1 ? 'success' : 'danger';

                        return '<div class="dropdown">' +
                            '<button class="btn btn-sm btn-' + badgeClass + ' dropdown-toggle" type="button" id="statusDropdown-' + row.id + '" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">' + statusText + '</button>' +
                            '<div class="dropdown-menu" aria-labelledby="statusDropdown-' + row.id + '">' +
                            '<a class="dropdown-item status-change" data-id="' + row.id + '" data-status="1" href="#">Active</a>' +
                            '<a class="dropdown-item status-change" data-id="' + row.id + '" data-status="0" href="#">Inactive</a>' +
                            '</div>' +
                            '</div>';
                    }
                },
                { 
                    data: 'email_verify',
                    render: function(data, type, row) {
                        if (data == 1) {
                            return '<span class="badge badge-success">Verified</span>';
                        } else {
                            return '<span class="badge badge-danger">Unverified</span>';
                        }
                    }
                },
                {
                    "data": null,
                    "render": function (data, type, row) {
                        return '<button class="btn btn-success edit-btn">Edit</button> ' +
                                '<button class="btn btn-danger delete-btn" data-id="' + row.id + '">Delete</button> ';
                    }
                }
            ],
            "order": [[0, 'desc']],
            "pageLength": 10,
            "columnDefs": [
                {
                    "targets": 2,
                    "render": function(data, type, row) {
                        return '<div class="editable" data-name="name" data-pk="' + row.id + '">' + data + '</div>';
                    }
                },
                {
                    "targets": 3,
                    "render": function(data, type, row) {
                        return '<div class="editable" data-name="email" data-pk="' + row.id + '">' + data + '</div>';
                    }
                }
            ]
        });


        $('#employeesTable').on('click', '.edit-btn', function() {
            var $row = $(this).closest('tr');
            var data = table.row($row).data();
            var $editable = $row.find('.editable[data-name="name"]');
            var currentValue = $editable.text().trim();
            $editable.addClass('editing');
            $editable.html('<input type="text" class="form-control" value="' + currentValue + '">');
            $editable.find('input').focus();
        });

        $('#employeesTable').on('dblclick', '.editable[data-name="name"]', function() {
            var $editable = $(this);
            var currentValue = $editable.text().trim();
            $editable.addClass('editing');
            $editable.html('<input type="text" class="form-control" value="' + currentValue + '">');
            $editable.find('input').focus();
        });

        $('#employeesTable').on('dblclick', '.editable[data-name="email"]', function() {
            var $editable = $(this);
            var currentValue = $editable.text().trim();
            $editable.data('originalValue', currentValue);
            $editable.addClass('editing');
            $editable.html('<input type="email" class="form-control" value="' + currentValue + '">');
            $editable.find('input').focus();
        });

        $('#employeesTable').on('keyup', '.editable[data-name="name"] input', function(event) {
            if (event.keyCode === 13) {
                var $input = $(this);
                var $editable = $input.closest('.editable');
                var pk = $editable.data('pk');
                var value = $input.val();

                $.ajax({
                    url: '{{ route('updateuser') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: pk,
                        name: value
                    },
                    success: function(response) {
                        console.log(response);
                        $editable.removeClass('editing');
                        $editable.text(value);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        });

        $('#employeesTable').on('keyup', '.editable[data-name="email"] input', function(event) {
            if (event.keyCode === 13) {
                var $input = $(this);
                var $editable = $input.closest('.editable');
                var pk = $editable.data('pk');
                var newValue = $input.val();

                $.ajax({
                    url: '{{ route('updateuseremail') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: pk,
                        email: newValue
                    },
                    success: function(response) {
                        console.log(response);
                        $editable.removeClass('editing');
                        $editable.text(newValue);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            }
        });

        $('#employeesTable').on('focusout', '.editable', function() {
            var $editable = $(this);
            var pk = $editable.data('pk');
            var value = $editable.find('input').val();
            var originalValue = $editable.data('originalValue');

            if ($editable.data('name') === 'name') {

                $.ajax({
                    url: '{{ route('updateuser') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: pk,
                        name: value,
                    },
                    success: function(response) {
                        console.log(response);
                        $editable.removeClass('editing');
                        $editable.text(value);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            } else if ($editable.data('name') === 'email') {
                
                $.ajax({
                    url: '{{ route('updateuseremail') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: pk,
                        email: value,
                    },
                    success: function(response) {
                        console.log(response);
                        $editable.removeClass('editing');
                        $editable.text(value);
                    },
                    error: function(error) {
                        console.log(error);
                    }
                });
            } else {
                $editable.removeClass('editing');
            }
        });
        function updateUserStatus(userId, newStatus) {

            $.ajax({
                url: '{{ route('update_user_status') }}',
                type: 'POST',
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                },
                data: {
                    id: userId,
                    status: newStatus
                },
                success: function (response) {
                    console.log(response);

                    var statusText = newStatus == 1 ? 'Active' : 'Inactive';
                    var badgeClass = newStatus == 1 ? 'success' : 'danger';

                    $('#statusDropdown-' + userId)
                        .removeClass('btn-success btn-danger')
                        .addClass('btn-' + badgeClass)
                        .text(statusText);
                },
                error: function (error) {
                    console.error(error);
                }
            });
        }

        $('#employeesTable').on('click', '.status-change', function (event) {
            event.preventDefault();
            var userId = $(this).data('id');
            var newStatus = $(this).data('status');
            updateUserStatus(userId, newStatus);
        });

        $('#employeesTable').on('click', '.delete-btn', function () {
            var userId = $(this).data('id');
            var $row = $(this).closest('tr');

            if (confirm("Are you sure you want to delete this user?")) {
                $.ajax({
                    url: '{{ route('delete_user_fromtable') }}',
                    type: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    data: {
                        id: userId
                    },
                    success: function (response) {
                        console.log(response);
                        $row.remove();
                    },
                    error: function (error) {
                        console.error(error);
                    }
                });
            }
        });
    });
</script>
@endsection
