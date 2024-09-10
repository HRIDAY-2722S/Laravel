@extends('layouts.admindefault')

@section('title', 'Quotations || Admin')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="container">
        <div class="row">
            <div class="col-md-12 mt-4">
                <h2>Quotations</h2>
                @if($quotations->isEmpty())
                    <div class="alert alert-warning">
                        No quotations found.
                    </div>
                @else
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>User Name</th>
                                <th>Name</th>
                                <th>No of items</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $id = 0;
                            @endphp
                            @foreach ($quotations as $quotation)
                                @php $id++; @endphp
                                <tr>
                                    <td>{{ $id }}</td>
                                    @php
                                    $name = DB::table('users')->where('id', $quotation->user_id)->first();
                                    @endphp
                                    <td>
                                        {{ $name->name }}
                                        <input type="hidden" class="user_id" value="{{ $quotation->user_id }}">
                                    </td>
                                    <td>
                                        <span class="quotation-name">{{ $quotation->name }}</span>
                                    </td>
                                    @php
                                    $no_of_items = DB::table('quotation_products')->where('quotation_id', $quotation->id)->count();
                                    @endphp
                                    <td>{{ $no_of_items }}</td>
                                    <td>{{ $quotation->created_at ? $quotation->created_at->format('d M Y') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('showquotationproducts' ,['id' => $quotation->id]) }}" class="btn btn-info view-btn" data-id="{{ $quotation->id }}">View</a>
                                        <button class="btn btn-warning edit-btn" data-id="{{ $quotation->id }}">Edit</button>
                                        <button class="btn btn-success save-btn" data-id="{{ $quotation->id }}" style="display:none;">Save</button>
                                        <form action="{{ route('deletequotation', ['id' => $quotation->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </div>
        </div>
        <div id="loader-container" class="loader-container" style="display: none;">
            <div id="loader" class="loader"></div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            $('.edit-btn').on('click', function() {
                var row = $(this).closest('tr');
                var quotationName = row.find('.quotation-name');
                var quotationId = $(this).data('id');

                var name = quotationName.text();
                quotationName.html('<input type="text" class="form-control edit-input" value="' + name + '">');

                row.find('.view-btn, .edit-btn, .delete-btn').hide();
                row.find('.save-btn').show();
            });

            $('.save-btn').on('click', function() {
                var row = $(this).closest('tr');
                var inputField = row.find('.edit-input');
                var newName = inputField.val();
                var quotationId = $(this).data('id');
                var userid = row.find('.user_id').val();
                $('#loader-container').show();

                $.ajax({
                    url: '{{ route('updatequotationname') }}', 
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        id: quotationId,
                        name: newName,
                        userid: userid
                    },
                    success: function(response) {
                        row.find('.quotation-name').text(newName);
                        row.find('.view-btn, .edit-btn, .delete-btn').show();
                        row.find('.save-btn').hide();
                        $('#loader-container').hide();
                    },
                    error: function(xhr) {
                        alert('A quotation with this name already exists for the current user.');
                        $('#loader-container').hide();
                    }
                });
            });

            $('.delete-btn').on('click', function(e) {
                e.preventDefault();

                var form = $(this).closest('form');
                
                if (confirm("Please confirm if you wish to delete the quotation. Once deleted, it cannot be retrieved along with the associated products.")) {
                    form.submit(); 
                }
            });
        });

    </script>
    <style>
         /* Loader styles */
         .loader-container {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(255, 255, 255, 0.7);
            display: flex;
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .loader {
            border: 8px solid #f3f3f3; /* Light grey */
            border-top: 8px solid #3498db; /* Blue */
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
    </style>
@endsection