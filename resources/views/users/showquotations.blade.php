@extends('layouts.default')

@section('title', 'Quotations')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                @if($quotationproducts->isEmpty())
                    <div class="alert alert-warning">
                        Your quotation appears to be empty. Please consider adding products from the <u><a href="{{ route('usersproducts') }}">products page</a></u>.
                    </div>
                @else
                    @php
                        $quotation = DB::table('quotations')->where('id', $quotationproducts->first()->quotation_id)->first();
                    @endphp
                    <h2>{{ $quotation ? $quotation->name : 'No Quotation' }}</h2>
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th style="width: 30%;">Product</th>
                                <th>Price</th>
                                <th>Sub_total</th>
                                <th>Total</th>
                                <th>Quantity</th>
                                <th>Created At</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                            $id = 0;
                            @endphp
                            @foreach ($quotationproducts as $quotationproduct)
                                @php $id++; @endphp
                                <tr class="quotations">
                                    <td>{{ $id }}</td>
                                    <td>
                                        @php
                                        $product = DB::table('products')->where('id', $quotationproduct->product_id)->first();
                                        @endphp
                                        <span class="quotation-name"><a href="{{ route('single_products', ['id' => $quotationproduct->product_id]) }}">{{ $product->name }}</a></span>
                                    </td>
                                    <td>${{ $product->price }}</td>
                                    <td>
                                        $<span class="subtotal">{{ $product->price * $quotationproduct->quantity }}.00</span>
                                    </td>
                                    <td>
                                        $<span class="total">{{ $quotationproduct->total }}</span>
                                    </td>
                                    <td>
                                        <div class="input-group">
                                            <button class="btn btn-outline-secondary quantity-btn decrease-btn" type="button">-</button>
                                            <input type="number" class="form-control quantity-input" value="{{ $quotationproduct->quantity }}" data-id="{{ $quotationproduct->id }}" style="width: 70px;" readonly>
                                            <button class="btn btn-outline-secondary quantity-btn increase-btn" type="button">+</button>
                                        </div>
                                    </td>
                                    <td>{{ $quotationproduct->created_at ? $quotationproduct->created_at->format('d M Y') : 'N/A' }}</td>
                                    <td>
                                        <form action="{{ route('delete_quotation_product', ['id' => $quotationproduct->id]) }}" method="POST" style="display:inline;">
                                            @csrf
                                            @method('DELETE')
                                            <button type="button" class="btn btn-danger delete-btn">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div style="display: flex;">
                        <div class="text-left mt-3">
                            <button id="update-btn" class="btn btn-primary">Update Quotations</button>
                            <button id="add-to-cart-btn" class="gradient-button" data-quotation-id="{{ $quotation ? $quotation->id : '' }}" style="margin-left: 10px;">Add products to cart</button>
                        </div>
                        <div id="summary-cart" class="summary-cart" style="margin-top: 15px; margin-left: 33%;">
                            <h4>Quotation Summary</h4>
                            <p>Total Items: <span id="total-items" style="float: right;">0</span></p>
                            <p>Subtotal: <span id="total-subtotal" style="float: right;">0</span></p>
                            <p>Total Amount: <span id="total-amount" style="float: right;">0.00</span></p>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    
    <div id="loader-container" class="loader-container" style="display: none;">
        <div id="loader" class="loader"></div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        $(document).ready(function() {
            function calculateTotals() {
                var totalQuantity = 0;
                var subtotalAmount = 0;
                var totalAmount = 0;

                $('.quotations').each(function() {
                    var quantity = parseInt($(this).find('.quantity-input').val());
                    var price = parseFloat($(this).find('td:eq(2)').text().replace('$', ''));
                    var subtotal = parseFloat($(this).find('.subtotal').text());
                    var total = parseFloat($(this).find('.total').text());

                    totalQuantity += quantity;
                    subtotalAmount += subtotal;
                    totalAmount += total;
                });
                console.log(subtotalAmount);

                $('#total-items').text(totalQuantity);
                $('#total-subtotal').text('$' + subtotalAmount.toFixed(2));
                $('#total-amount').text('$'  + totalAmount.toFixed(2));
            }

            // Function to update totals and cart summary
            function updateTotals(input) {
                var row = input.closest('tr');
                var price = parseFloat(row.find('td:eq(2)').text().replace('$', ''));
                var quantity = parseInt(input.val());
                var subtotal = price * quantity;

                var discount = 0;
                if (quantity >= 9 && quantity <= 14) {
                    discount = 0.025;
                } else if (quantity >= 15 && quantity <= 20) {
                    discount = 0.035;
                } else if (quantity > 20) {
                    discount = 0.05;
                }
                var total = subtotal - (subtotal * discount);

                row.find('.subtotal').text(subtotal.toFixed(2));
                row.find('.total').text(total.toFixed(2));

                // Recalculate cart summary totals
                calculateTotals();
            }

            // Calculate initial totals when the page loads
            calculateTotals();

            $('.delete-btn').on('click', function(e) {
                e.preventDefault();

                var form = $(this).closest('form');
                
                if (confirm("Please confirm if you wish to delete the quotation. Once deleted, it cannot be retrieved along with the associated products.")) {
                    form.submit(); 
                }
            });

            $('.increase-btn').on('click', function() {
                var input = $(this).siblings('.quantity-input');
                var currentValue = parseInt(input.val());
                if(currentValue < 30){
                    input.val(currentValue + 1);
                    updateTotals(input);
                }
            });

            $('.decrease-btn').on('click', function() {
                var input = $(this).siblings('.quantity-input');
                var currentValue = parseInt(input.val());
                if (currentValue > 1) {
                    input.val(currentValue - 1);
                    updateTotals(input);
                }
            });

            $('#update-btn').on('click', function() {
                $('#loader-container').show();

                var updatedQuantities = [];
                $('tr').each(function() {
                    var row = $(this);
                    var id = row.find('.quantity-input').data('id');
                    var quantity = row.find('.quantity-input').val();
                    if(id !== undefined && quantity !== undefined) {
                        updatedQuantities.push({ id: id, quantity: quantity });
                    }
                });

                $.ajax({
                    url: '{{ route('update_quotations_products_quantity') }}', 
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        quantities: updatedQuantities
                    },
                    success: function(response) {
                        $('#loader-container').hide();
                    },
                    error: function(xhr) {
                        $('#loader-container').hide();
                        alert('An error occurred while updating quantities.');
                    }
                });
            });

            $('#add-to-cart-btn').on('click', function() {
                $('#loader-container').show();
                
                var quotationId = $(this).data('quotation-id');

                $.ajax({
                    url: '{{ route('add_products_to_cart') }}',
                    type: 'POST',
                    data: {
                        _token: $('meta[name="csrf-token"]').attr('content'),
                        quotation_id: quotationId
                    },
                    success: function(response) {
                        $('#loader-container').hide();
                        alert('Products added to cart successfully.');
                    },
                    error: function(xhr) {
                        $('#loader-container').hide();
                        alert('An error occurred while adding products to the cart.');
                    }
                });
            });
        });

    </script>
    <style>
        .quantity-input {
            width: 80px;
            text-align: center;
        }
        .quantity-btn {
            width: 35px;
            height: 100%;
        }
        .input-group {
            display: flex;
        }

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

        .table td {
            white-space: nowrap;
        }
        .quotation-name {
            text-align: center;
            display: block;
            width: 200px;
            word-wrap: break-word;
            white-space: normal;
        }
        .gradient-button {
            background: linear-gradient(to right, #ff758c, #ff7eb3);
            border: none;
            color: white;
            padding: 6.5px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
            transition: background 0.3s;
        }
        .gradient-button:hover {
            background: linear-gradient(to right, #ff7eb3, #ff758c);
        }
        .summary-cart {
            /* position: absolute; */
            bottom: 20px;
            right: 20px;
            background: white;
            border: 1px solid #ddd;
            border-radius: 5px;
            padding: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            width: 300px;
        }
        .summary-cart h4 {
            margin-top: 0;
        }
    </style>
@endsection
