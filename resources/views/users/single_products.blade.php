@extends('layouts.default')

@section('title', 'Dashboard')

@section('content')

    <div class="container">
        @foreach ($products as $product)
        <div class="left-sidebar">
            <div class="main-image">
                <img src="{{ asset('products_image/'.$product->image) }}" alt="Main Image" id="main-image">
            </div>
            <?php $product_images = DB::table('products_images')->where('product_id', $product->id)->get(); ?>
            @if(!$product_images->isEmpty())
                <div class="more-images">
                    @foreach($product_images as $image)
                        <?php $images = explode(',', $image->images); ?>
                        @foreach($images as $img)
                            <img src="{{ asset('products_image/'.$img) }}" alt="Product Image" onmouseover="changeMainImage(this.src)">
                        @endforeach
                    @endforeach
                </div>
            @endif
        </div>
        <div class="main-content1">
            <div class="product-info">
                <h1>{{ $product->name }}</h1>
                <p>{{ $product->description }}</p>
            </div>
            <div class="pricing-and-offers">
                <div class="pricing">
                    <h2>Pricing</h2>
                    <p>${{ $product->price }}</p>
                    <?php $dicsount = $product->price * 10/100; ?>
                    <p class="discounted-price">${{ round($product->price + $dicsount) }}</p>
                </div>
                <div class="offers">
                    <h2>Available Offers</h2>
                    <ul>
                        <li>10% off</li>
                        <li>Bank Offer: Get ₹50 Instant Discount on first UPI transaction on order of $200 and above</li>
                        <li>Bank Offer: 5% Cashback on Axis Bank Card</li>
                        <li>Special Price: Get extra $150 off (price inclusive of cashback/coupon)</li>
                    </ul>
                </div>
                <div class="call-to-action">
                    <!-- <button onclick="redirectToCheckout({{ $product->id }}, {{ $product->price }})">BUY NOW</button> -->
                    <button id="add-to-cart">ADD TO CART</button>
                    @php
                    if(session('role') != 'guest'){
                    @endphp
                        <button id="create-quotation">CREATE QUOTATION</button>
                    @php
                    }
                    @endphp
                    


                    <input type="hidden" id="product-id" value="{{ $product->id }}">
                    <input type="hidden" id="price" value="{{ $product->price }}">
                    <div id="quotation-options-container" class="quotation-options-container" style="display:none;"></div>

                </div>
            </div>
        </div>
        @endforeach
    </div>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            $('#add-to-cart').on('click', function() {
                var productId = $('#product-id').val();
                var price = $('#price').val();
                var url = '{{ route('userscart') }}';

                $.ajax({
                    type: 'POST',
                    url: '{{ route('add_to_cart') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        product_id: productId,
                        price: price
                    },
                    success: function(response) {
                        console.log(response);
                        window.location.href = url;
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });

            $('#create-quotation').on('click', function() {
                $.ajax({
                    type: 'GET',
                    url: '{{ route('check_existing_quotation') }}',
                    success: function(response) {
                        if (response.quotations.length > 0) {
                            displayQuotationOptions(response.quotations);
                        } else {
                            var quotationName = prompt("Enter a name for the new quotation:");
                            if (quotationName) {
                                createNewQuotation(quotationName);
                            }
                        }
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            });

            function displayQuotationOptions(quotations) {
                var optionsHtml = '<div id="quotation-options"><h3>Select a Quotation or Create New:</h3>';
                optionsHtml += '<select id="quotation-select">';
                optionsHtml += '<option value="">Select Existing Quotation</option>';
                $.each(quotations, function(index, quotation) {
                    optionsHtml += '<option value="' + quotation.id + '">' + quotation.name + '</option>';
                });
                optionsHtml += '</select>';
                optionsHtml += '<button id="add-to-existing-quotation">Add to Selected Quotation</button>';
                optionsHtml += '<button id="create-new-quotation">Create New Quotation</button>';
                optionsHtml += '</div>';

                $('#quotation-options-container').show();
                $('#quotation-options-container').html(optionsHtml);

                $('#add-to-existing-quotation').on('click', function() {
                    var selectedQuotationId = $('#quotation-select').val();
                    if (selectedQuotationId) {
                        addToExistingQuotation(selectedQuotationId);
                    } else {
                        alert("Please select a quotation.");
                    }
                });

                $('#create-new-quotation').on('click', function() {
                    var quotationName = prompt("Enter a name for the new quotation:");
                    if (quotationName) {
                        createNewQuotation(quotationName);
                    }
                });
            }

            function createNewQuotation(name) {
                var productId = $('#product-id').val();
                var price = $('#price').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('create_quotation') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        name: name,
                        product_id: productId,
                        price: price
                    },
                    success: function(response) {
                        alert('New quotation created successfully.');
                        console.log(response);
                        $('#quotation-options-container').empty();
                        $('#quotation-options-container').hide();
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                        alert('A quotation with this name already exists for the current user.');
                    }
                });
            }

            function addToExistingQuotation(quotationId) {
                var productId = $('#product-id').val();
                var price = $('#price').val();
                $.ajax({
                    type: 'POST',
                    url: '{{ route('add_to_quotation') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        quotation_id: quotationId,
                        product_id: productId,
                        price: price
                    },
                    success: function(response) {
                        alert('Product added to existing quotation successfully.');
                        console.log(response);
                        $('#quotation-options-container').empty();
                        $('#quotation-options-container').hide();
                    },
                    error: function(xhr, status, error) {
                        console.log(error);
                    }
                });
            }
        });

        function changeMainImage(src) {
            document.getElementById("main-image").src = src;
        }
    </script>
    <style>
        .quotation-options-container {
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            max-width: 500px;
            margin: 20px auto;
        }

        .quotation-options-container h3 {
            margin-bottom: 20px;
            font-size: 24px;
            color: #333;
            text-align: center;
        }

        .quotation-options-container select {
            width: 100%;
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
        }

        .quotation-options-container button {
            width: calc(50% - 10px);
            padding: 10px;
            margin: 10px 5px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
        }

        .quotation-options-container button#add-to-existing-quotation {
            background-color: #337ab7;
            color: #fff;
        }

        .quotation-options-container button#add-to-existing-quotation:hover {
            background-color: #23527c;
        }

        .quotation-options-container button#create-new-quotation {
            background-color: #5cb85c;
            color: #fff;
        }

        .quotation-options-container button#create-new-quotation:hover {
            background-color: #4cae4c;
        }

        .container {
            max-width: 100%;
            /* margin: 40px auto; */
            padding: 20px;
            background-color: #f9f9f9;
            border: 1px solid #ddd;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            display: flex;
        }
        .left-sidebar {
            overflow: hidden;
            width: 40%;
            margin-right: 20px;
            flex-shrink: 0;
        }
        .main-image {
            margin-bottom: 20px;
            margin: 5px;
        }
        .main-image img {
            width: 100%;
            height: 300px;
            object-fit: cover;
            border-radius: 10px;
        }
        .more-images {
            display: flex;
            overflow-x: auto;
            padding: 10px;
        }
        .more-images img {
            width: 100px;
            height: 100px;
            object-fit: cover;
            border-radius: 10px;
            margin: 10px;
            cursor: pointer;
        }
        .more-images img:hover {
            border: 2px solid #337ab7;
        }
        .swipe-button {
            position: absolute;
            top: 50%;
            transform: translateY(-50%);
            background-color: #337ab7;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
        }
        .swipe-button:hover {
            background-color: #23527c;
        }
        .swipe-left {
            left: 10px;
        }
        .swipe-right {
            right: 10px;
        }
        .main-content1 {
            width: 100%!important;
            padding: 20px;
        }
        .product-info {
            margin-bottom: 20px;
        }
        .product-info h1 {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .product-info p {
            font-size: 18px;
            color: #666;
        }
        .pricing-and-offers {
            margin-top: 20px;
        }
        .pricing {
            margin-bottom: 20px;
        }
        .pricing h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .pricing p {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }
        .discounted-price {
            font-size: 18px;
            color: #666;
            text-decoration: line-through;
        }
        .offers {
            margin-bottom: 20px;
        }
        .offers h2 {
            font-size: 18px;
            margin-bottom: 10px;
        }
        .offers ul {
            list-style: none;
            padding: 0;
            margin: 0;
        }
        .offers li {
            margin-bottom: 10px;
        }
        .offers li:before {
            content: "•";
            margin-right: 10px;
            color: #666;
        }
        .call-to-action {
            margin-top: 20px;
            text-align: center;
        }
        .call-to-action button {
            background-color: #337ab7;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-size: 18px;
            cursor: pointer;
        }
        .call-to-action button:hover {
            background-color: #23527c;
        }
    </style>

@endsection