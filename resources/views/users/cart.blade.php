@extends('layouts.default')

@section('title', 'Cart || Users')

@section('content')
<i><b><h2 style="text-align: center;">Shopping Cart</h2></b></i>

<div class="cart-page">
    @php
    print_r(session('cart_data'));
    @endphp
    @if(count($carts) > 0)
        @foreach($carts as $cart)
        <div class="cart-item">
            <?php
            $product = DB::table('products')->where('id', $cart->product_id)->first();
            ?>
            <a href="{{ route('single_products',['id' => $product->id]) }}">
                <img src="{{ asset('products_image/'.$product->image) }}" alt="Product Image">
            </a>
            <div class="product-info" style="max-width: 550px;">
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>
            </div>
            <div class="quantity-control">
                <button type="button" class="decrement"><</button>
                <input type="hidden" value="{{ $product->price }}" id="productprice-{{$cart->id}}">
                <input type="text" class="productquantity" value="{{ $cart->quantity }}" min="1" max="5" step="1" id="quantity-{{$cart->id}}" readonly>
                <button type="button" class="increment">></button>
            </div>&nbsp;&nbsp;&nbsp;
            <p class="total-price">$<span id="amount-{{$cart->id}}">{{ $cart->total }}</span></p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <p class="total-price">
                <button type="button" class="delete-item-btn" data-cart-id="{{ $cart->id }}">X</button>
            </p>
        </div>
        @endforeach

        <div class="cart-summary">
            <h3>Cart Summary</h3>
            <hr>
            <div>
                <p style='font-size: 20px; font-weight: 500; font-family: "Times New Roman", Times, serif;'>Subtotal: $<span id="subtotalAmount">0.00</span></p>
                <p style='font-size: 20px; font-weight: 500; font-family: "Times New Roman", Times, serif;'>Tax: $<span id="taxAmount">0.00</span></p>
                <p style='font-size: 20px; font-weight: 500; font-family: "Times New Roman", Times, serif;'>Total: $<u><span id="totalAmount">0.00</span></u></p>
                <button type="button" class="buy-now-btn">Buy Now</button>
            </div>
        </div>

        <button type="button" class="update-cart-btn">Update Cart</button>
        <div class="loader-container" id="loaderContainer">
            <div class="loader"></div>
        </div>
    @elseif(session('cart_data') != "")
        <?php
        foreach(session('cart_data') as $products) { 
        ?>
        <div class="cart-item">
            <?php
            $product = DB::table('products')->where('id', $products["product_id"])->first();
            ?>
            <a href="{{ route('single_products',['id' => $product->id]) }}">
                <img src="{{ asset('products_image/'.$product->image) }}" alt="Product Image">
            </a>
            <div class="product-info">
                <h3>{{ $product->name }}</h3>
                <p>{{ $product->description }}</p>
            </div>
            <div class="quantity-control">
                <button type="button" class="decrement"><</button>
                <input type="hidden" value="{{ $product->price }}" id="productprice-{{$products['product_id']}}">
                <input type="text" class="productquantity" value="{{ $products["quantity"] }}" min="1" max="30" step="1" id="quantity-{{$products["product_id"]}}" readonly>
                <button type="button" class="increment">></button>
            </div>&nbsp;&nbsp;&nbsp;
            <p class="total-price">$<span id="amount-{{$products['product_id']}}" class="total-amount">{{ $product->price*$products["quantity"] }}</span></p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
            <p class="total-price">
                <button type="button" class="delete-item-btn delete" data-cart-id="{{ $products["product_id"] }}">X</button>
            </p>
        </div>
        <?php
            }
        ?>
            <div class="cart-summary">
                <h3>Cart Summary</h3>
                <hr>
                <div>
                    <p style='font-size: 20px; font-weight: 500; font-family: "Times New Roman", Times, serif;'>Subtotal: $<span id="subtotalAmount">0.00</span></p>
                    <p style='font-size: 20px; font-weight: 500; font-family: "Times New Roman", Times, serif;'>Tax: $<span id="taxAmount">0.00</span></p>
                    <p style='font-size: 20px; font-weight: 500; font-family: "Times New Roman", Times, serif;'>Total: $<u><span id="totalAmount">0.00</span></u></p>
                    <button type="button" class="buy-now-btn">Buy Now</button>
                </div>
            </div>

            <button type="button" class="update-cart-btn update">Update Cart</button>
            <div class="loader-container" id="loaderContainer">
                <div class="loader"></div>
            </div>
    @else
        <h2 style="text-align: center;">Your cart is currently empty.</h2>
    @endif
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {

        function updateCartSummary() {
            var subtotal = 0;
            $('.cart-item').each(function() {
                var pricePerItem = parseFloat($(this).find('input[type="hidden"]').val());
                var quantity = parseInt($(this).find('input[type="text"]').val());
                var amount = pricePerItem * quantity;
                subtotal += amount;
            });
            var deliveryCharge = 0;
            if (subtotal <= 400) {
                deliveryCharge = 40;
            }

            var tax = subtotal * 0; // Assuming tax calculation logic goes here
            var total = subtotal + tax + deliveryCharge;

            $('#subtotalAmount').text(subtotal.toFixed(2));
            $('#taxAmount').text(tax.toFixed(2));
            $('#totalAmount').html(total.toFixed(2) + (deliveryCharge > 0 ? ' (Delivery Charge: $' + deliveryCharge.toFixed(2) + ')' : ''));
        }

        updateCartSummary();


        $('.decrement').on('click', function() {
            var $quantityInput = $(this).closest('.cart-item').find('input[type="text"]');
            var $amountSpan = $(this).closest('.cart-item').find('span[id^="amount-"]');
            var newQuantity = parseInt($quantityInput.val()) - 1;
            if (newQuantity < 1) {
                newQuantity = 1;
                $(this).prop('disabled', true);
                $(this).closest('.cart-item').find('.increment').prop('disabled', false);
            } else {
                $(this).prop('disabled', false);
            }
            $quantityInput.val(newQuantity);
            var pricePerItem = $(this).closest('.cart-item').find('input[type="hidden"]').val();
            var amount = newQuantity * pricePerItem;
            $amountSpan.html(amount.toFixed(2));
        });
      
        $('.increment').on('click', function() {
            var $quantityInput = $(this).closest('.cart-item').find('input[type="text"]');
            var $amountSpan = $(this).closest('.cart-item').find('span[id^="amount-"]');
            var newQuantity = parseInt($quantityInput.val()) + 1;
            if (newQuantity > 30) {
                newQuantity = 30;
                $(this).prop('disabled', true);
                $(this).closest('.cart-item').find('.decrement').prop('disabled', false);
            } else {
                $(this).prop('disabled', false);
                if (newQuantity > 1) {
                    $(this).closest('.cart-item').find('.decrement').prop('disabled', false);
                }
            }
            $quantityInput.val(newQuantity);
            var pricePerItem = $(this).closest('.cart-item').find('input[type="hidden"]').val();
            // alert(pricePerItem);
            var amount = newQuantity * pricePerItem;
            $amountSpan.html(amount.toFixed(2));
        });
      
      
        $('.update-cart-btn').on('click', function() {
            $('#loaderContainer').show();
                var cartItems = [];
                $('.cart-item').each(function() {
                    var cartItem = {};
                    cartItem.cart_id = $(this).find('input[type="hidden"]').attr('id').replace('productprice-', '');
                    cartItem.quantity = $(this).find('input[type="text"]').val();
                    cartItems.push(cartItem);
                });

                var url = '{{ route("updatecart") }}';
                var token = '{{ csrf_token() }}';

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    '_token': token,
                    'cart_items': cartItems
                },
                success: function(data) {
                    // console.log(data);
                    // $.each(data.cart_items, function(index, cartItem) {
                    //     $('#amount-' + cartItem.cart_id).html(cartItem.total.toFixed(2));
                    // });
                    $('#loaderContainer').hide();
                    updateCartSummary();
                },
                error: function(xhr, status, error) {
                    console.log(xhr.responseText);
                }
            });
        });

        $('.update').on('click', function() {

            $('#loaderContainer').show();

            var cart = [];
            $('.cart-item').each(function() {
                var cartI = {};
                cartI.cart_id = $(this).find('input[type="hidden"]').attr('id').replace('productprice-', '');
                console.log($(this).find('input[type="hidden"]').attr('id').replace('productprice-', ''));
                cartI.quantity = $(this).find('input[type="text"]').val();
                cart.push(cartI);
            });

            $.ajax({
                type: 'POST',
                url: '{{ route("updatecart") }}', 
                data: {
                    '_token': '{{ csrf_token() }}',
                    'cart_items': cart
                },
                success: function(data) {
                    console.log(data);
                    $('#loaderContainer').hide();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    $('#loaderContainer').hide();
                }
            });
        });

        $('.delete-item-btn').on('click', function() {
            var cartId = $(this).data('cart-id');
            var url = '{{ route("deletecartitem") }}';

            $('#loaderContainer').show();

            if(confirm('Are you sure that you want to remove this product from your cart') == true){
            

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    '_token': '{{ csrf_token() }}',
                    'cart_id': cartId
                },
                success: function(data) {
                    $('#amount-' + cartId).closest('.cart-item').remove();
                    $('#loaderContainer').hide();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    $('#loaderContainer').hide();
                }
            });
            }else{
                $('#loaderContainer').hide();
            }
        });

        
        $('.delete').on('click', function() {
            var cartId = $(this).data('cart-id');
            var url = '{{ route("deletecartitem") }}';

            $('#loaderContainer').show();

            if(confirm('Are you sure that you want to remove this product from your cart') == true){
            

            $.ajax({
                type: 'POST',
                url: url,
                data: {
                    '_token': '{{ csrf_token() }}',
                    'cart_id': cartId
                },
                success: function(data) {
                    $('#amount-' + cartId).closest('.cart-item').remove();
                    $('#loaderContainer').hide();
                },
                error: function(xhr, status, error) {
                    console.error(xhr.responseText);
                    $('#loaderContainer').hide();
                }
            });
            }else{
                $('#loaderContainer').hide();
            }
        });

        
        $('.buy-now-btn').on('click', function() {

            var checkoutUrl = '{{ route("checkout") }}';
            window.location.href = checkoutUrl;
        });
    });
</script>

<style>
.cart-page {
    max-width: 100%;
    margin: 40px auto;
    padding: 20px;
    background-color: #f9f9f9;
    border: 1px solid #ddd;
    box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
}

.cart-item {
    display: flex;
    align-items: center;
    padding: 20px;
    border-bottom: 1px solid #ddd;
}

.cart-item img {
    width: 100px;
    height: 100px;
    margin-right: 20px;
}

.product-info {
    flex: 1;
}

.quantity-control {
    display: flex;
    align-items: center;
    margin-top: -8px;
}

.productquantity{
    border: 1px solid #ddd;
    border-radius: 5px;
    width: 40px;
    height: 30px;
}

.decrement, .increment {
    width: 20px;
    height: 20px;
    border: none;
    border-radius: 50%;
    background-color: #4CAF50;
    color: #fff;
    cursor: pointer;
    padding: -3px;
}

.decrement:hover, .increment:hover {
    background-color: #3e8e41;
}

.quantity-input {
    width: 30px;
    height: 30px;
    text-align: center;
    border: 1px solid #ddd;
    border-radius: 5px;
    padding: 5px;
}

.total-price {
    font-weight: bold;
    font-size: 18px;
    margin-top: 10px;
}
.update-cart-btn {
    display: inline-block;
    padding: 10px 20px;
    background-color: #4CAF50;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    transition: background-color 0.3s ease;
    margin-top: 20px;
}

.update-cart-btn:hover {
    background-color: #45a049;
}

/* Loader */
.loader-container {
    display: none; /* Initially hidden */
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5); /* Semi-transparent black overlay */
    z-index: 9999;
}

.loader {
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    border: 4px solid #f3f3f3;
    border-top: 4px solid #3498db;
    border-radius: 50%;
    width: 30px;
    height: 30px;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}
.cart-summary {
    margin-top: 90px;
    margin-right: -20px;
    float: right;
    width: 300px;
    background-color: #fff;
    padding: 20px;
    border: 1px solid #ddd;
    border-radius: 5px;
    box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
}
cart-summary h3 {
    margin-top: 0;
    font-size: 30px;
    font-weight: 900;
    margin-bottom: 10px;
}
.cart-summary div {
    margin-top: 10px;
}
.buy-now-btn {
    display: block;
    width: 100%;
    padding: 10px;
    background-color: #007bff;
    color: #fff;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    margin-top: 10px;
    transition: background-color 0.3s ease;
}
.buy-now-btn:hover {
    background-color: #0056b3;
}
</style>
@endsection