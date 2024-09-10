@extends('layouts.default')

@section('title', 'Quotations')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <h2 class="mb-4">Quotations</h2>
                <!-- <a href="" class="btn btn-primary mb-3">Create New Quotation</a> -->
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
                                    <td>
                                        <span class="quotation-name">{{ $quotation->name }}</span>
                                        <input type="text" class="form-control edit-name" style="display:none;" value="{{ $quotation->name }}">
                                        <input type="hidden" class="form-control quotation-id" value="{{ $quotation->id }}">
                                    </td>
                                    @php
                                    $no_of_items = DB::table('quotation_products')->where('quotation_id', $quotation->id)->count();
                                    @endphp
                                    <td>{{ $no_of_items }}</td>
                                    <td>{{ $quotation->created_at ? $quotation->created_at->format('d M Y') : 'N/A' }}</td>
                                    <td>
                                        <a href="{{ route('show_quotation_products' ,['id' => $quotation->id]) }}" class="btn btn-info view-btn" data-id="{{ $quotation->id }}">View</a>
                                        <button class="btn btn-warning edit-btn" data-id="{{ $quotation->id }}">Edit</button>
                                        <button class="btn btn-success save-btn" data-id="{{ $quotation->id }}" style="display:none;">Save</button>
                                        <form action="{{ route('delete_quotation', ['id' => $quotation->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <!-- <input type="text" class="form-control" id="delete-quotation" value="{{ $quotation->id }}"> -->
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
        var updateQuotationUrl = "{{ route('update_quotation_name', ['id' => '__id__']) }}";
        $(document).ready(function() {
            var editingRow = null;

            $('.edit-btn').on('click', function(e) {
                e.preventDefault();
                
                if (editingRow && editingRow[0] !== $(this).closest('tr')[0]) {
                    $('#loader-container').show();
                    saveRow(editingRow);
                }
                
                var row = $(this).closest('tr');
                row.find('.quotation-name').hide();
                row.find('.edit-name').show();
                row.find('.view-btn').hide();
                row.find('.delete-btn').hide();
                row.find('.save-btn').show();
                $(this).hide();
                
                editingRow = row;
            });

            $('.save-btn').on('click', function() {
                $('#loader-container').show();
                var row = $(this).closest('tr');
                saveRow(row);
            });

            function saveRow(row) {
                var id = row.find('.quotation-id').val();
                var newName = row.find('.edit-name').val();
                var url = updateQuotationUrl.replace('__id__', id); 

                $.ajax({
                    url: url,
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        name: newName
                    },
                    success: function(response) {
                        row.find('.quotation-name').text(newName).show();
                        row.find('.edit-name').hide();
                        row.find('.edit-btn').show();
                        row.find('.save-btn').hide();
                        row.find('.view-btn').show();
                        row.find('.delete-btn').show();
                        editingRow = null;
                        $('#loader-container').hide();
                    },
                    error: function(xhr) {
                        $('#loader-container').hide();
                        alert('A quotation with this name already exists for the current user.');
                    }
                });
            }
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
