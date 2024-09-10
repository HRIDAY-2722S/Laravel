<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Checkout || User</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/5.0.0/normalize.min.css">
  <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
  <link rel='stylesheet' href='https://fonts.googleapis.com/css?family=Josefin+Sans:300,400,600,700|Open+Sans:400,600'>
  <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/simple-line-icons/2.4.1/css/simple-line-icons.min.css'>
  <link rel="stylesheet" href="{{ asset('css/checkout.css')}}">
  <script src="https://js.stripe.com/v3/"></script>
</head>
<body>
    <header>
        <!-- Loader -->
        <div id="loader" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 9999;">
            <div style="position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%);">
                <div class="spinner-border text-light" role="status">
                    <span class="sr-only">Loading...</span>
                </div>
            </div>
        </div>
        <!-- loader end -->
        <div class="container">
            <div class="navigation">
                <div class="logo">
                    <i class="icon icon-basket"></i>Laravel
                </div>
                <div class="secure">
                    <i class="icon icon-shield"></i>
                    <span>Secure Checkout</span>
                </div>
            </div>
            <div class="notification">
                Complete Your Purchase
            </div>
        </div>
    </header>
    @if (session('status'))
        <div class="fade-out" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; background-color: #34C759; padding: 15px; border-radius: 10px;">{{ session('status') }}</div>
        <style>
            .fade-out {
                animation: fadeOut 2s forwards;
                animation-delay: 2s;
            }
            @keyframes fadeOut {
                to {
                    opacity: 0;
                    visibility: hidden;
                }
            }
        </style>
        <script>
            $(document).ready(function() {
                setTimeout(function() {
                    $('.alert-success').fadeOut(200, function() {
                        $(this).remove();
                    });
                }, 2000);
            });
        </script>
    @endif
    <section class="content">
        <div class="details shadow" style="height: auto !important; border: 2px solid red;">
            @if(session('role') != "guest")
                @foreach($products as $product)
                    <?php
                    $item = DB::table('products')->where('id', $product->product_id)->first();
                    ?>
                    <div class="details__item" data-product-id="{{ $item->id }}" data-quantity="{{ $product->quantity }}">
                        <div class="item__details">
                            <input type="hidden" id="productid" value="{{ $item->id }}">
                            <div class="item__title"> {{ $item->name }} ({{ $product->quantity }}) </div>
                            <?php
                            $discount = 0;
                            if ($product->quantity >= 9 && $product->quantity <= 14) {
                                $discount = 0.025;
                            } elseif ($product->quantity >= 15 && $product->quantity <= 20) {
                                $discount = 0.035;
                            } elseif ($product->quantity > 20) {
                                $discount = 0.05;
                            }
                            $subtotal = $product->price * $product->quantity;
                            ?>
                            <div class="item__price"> $ {{ $product->total }} ($ {{ $item->price }})</div>
                        </div>
                    </div>
                @endforeach
                <p id="show-total-amount" style="margin-left: 30px;">Total: </p>
            @else
                <?php
                foreach(session('cart_data') as $products) { 
                $item = DB::table('products')->where('id', $products["product_id"])->first();
                ?>
                <div class="details__item" data-product-id="{{ $item->id }}" data-quantity="{{ $products["quantity"] }}">
                    <div class="item__details">
                        <input type="hidden" id="productid" value="{{ $item->id }}">
                        <div class="item__title"> {{ $item->name }} ({{ $products["quantity"] }}) </div>
                        <div class="item__price"> $ {{ $item->price*$products["quantity"] }} </div>
                    </div>
                </div>
                <?php } ?>
                <p id="show-total-amount" style="margin-left: 30px;">Total: </p>
            @endif
        </div>

        <div class="container">
            <div class="payment">
                <div class="payment__title"> Payment Method </div>
                <div class="payment__types">
                    <div class="payment__type payment__type--cc active">
                        <i class="icon icon-credit-card"></i>Credit Card
                    </div>
                </div>

                <div class="payment__shipping">
                    <div class="payment__title">
                        <i class="icon icon-plane"></i> Shipping Information
                    </div>
                    <div class="details__user">
                        @if(session('role') != "guest")
                            <?php
                            $id = session('id');
                            $add = DB::table('addresses')->where('user_id', $id)->first();
                            ?>
                            @if($add)
                                <a href="#" class="change-link" style="float: right;">Change</a>
                                <div class="user__name">{{ $add->name }}</div>
                                <div class="user__address">{{ $add->landmark }}, {{ $add->address_lane_1 }}, {{ $add->area }}, {{ $add->town }}, {{ $add->state }}, {{ $add->country}} ({{ $add->mobile;}})({{ $add->email }})
                                <br></div>
                            @else
                                <a href="#" class="change-link" style="float: right;">Add a new address</a>
                                <p>No address found.</p>
                            @endif
                        @else
                         <!-- for guest users -->
                            @if(session('address_id') != "")
                                <?php
                                $add = DB::table('addresses')->where('id', session('address_id'))->first();
                                ?>
                                <a href="#" class="change-link" style="float: right;">Change</a>
                                <div class="user__name">{{ $add->name }}</div>
                                <div class="user__address">{{ $add->landmark }}, {{ $add->address_lane_1 }}, {{ $add->area }}, {{ $add->town }}, {{ $add->state }}, {{ $add->country}} ({{ $add->mobile}})({{ $add->email }})
                                    <br></div>
                            @else
                                <a href="#" class="change-link" style="float: right;">Add a new address</a>
                                <p>When logging in as a guest, you'll need to add a new address.</p>
                            @endif
                        @endif
                        <div class="shipping-form" style="display: none;">
                            @if(session('role') != 'guest')
                                <?php
                                $id = session('id');
                                $addresses = DB::table('addresses')->where('user_id', $id)->get();
                                ?>
                                @foreach ($addresses as $address)
                                    <div style="margin-top: 10px; border: 1px solid #FBD8B4; background-color: #FCF5EE; padding: 10px; display: flex;">
                                        <input type="radio" value="{{ $address->id }}" name="address" checked>
                                        <p style="margin-left: 10px;">{{ $address->name }}, {{ $address->landmark }}, {{ $address->address_lane_1 }}, {{ $address->area }}, {{ $address->town }}, {{ $address->state }} ({{ $address->mobile;}})({{ $address->email }})</p>
                                        <a href="{{ route('edit_address',['id' => $address->id])}}" style="color: blue;" class="edit-address">Edit</a>
                                    </div>
                                @endforeach
                            @else
                                <?php
                                $addresses = DB::table('addresses')->where('id', session('address_id'))->get();
                                ?>
                                @foreach ($addresses as $address)
                                    <div style="margin-top: 10px; border: 1px solid #FBD8B4; background-color: #FCF5EE; padding: 10px; display: flex;">
                                        <input type="radio" value="{{ $address->id }}" name="address" checked>
                                        <p style="margin-left: 10px;">{{ $address->name }}, {{ $address->landmark }}, {{ $address->address_lane_1 }}, {{ $address->area }}, {{ $address->town }}, {{ $address->state }} ({{ $address->mobile;}})({{ $address->email }})</p>
                                        <a href="{{ route('edit_address',['id' => $address->id])}}" style="color: blue;" class="edit-address">Edit</a>
                                    </div>
                                @endforeach
                            @endif
                            <br><a href="{{ route('add_new_address') }}">Add a new address</a>
                            <div style="background-color: #F0F2F2; padding: 10px;">
                                <button style="color: #0F1111; padding: 10px; border-radius: 10px; border-color: #ffd814; background-color: #ffd814;" class="use-address-btn">Use this address</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card mt-5 card-rounded">
                    <div class="card-body">
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="coupon_code">Do you have a coupon code?</label>
                                <div class="input-group">
                                    @csrf
                                    <input type="text" class="form-control" name="coupon_code" id="coupon_code" value="" placeholder="Enter coupon code">
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-outline-success" id="coupon-apply">Apply</button>
                                    </div>
                                </div>
                                <small class="text-danger error-message" style="display: none;"></small>
                            </div>
                            <div class="form-group col-md-6" >
                                <div class="input-group p-2">
                                    <!-- <p>Note :-</p> -->
                                    <div class="mt-0 pl-2">
                                        <small class="text-danger">When using the coupon, avoid refreshing the page. If you accidentally do, don't worry—In some cases the coupon details will still be valid when you proceed with your order.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <!-- Stripe Card Element -->
                <div class="container">
                    <div class="payment">
                        <div class="payment__title"> Payment Information </div>
                        <div class="card">
                            <div class="card-body">
                                <form id="payment-form">
                                    <div class="form-group">
                                        <label for="card-element">Credit Card Information</label>
                                        <div id="card-element" class="form-control" style=" padding:10px;">
                                            <!-- A Stripe Element will be inserted here. -->
                                        </div>
                                        <!-- Used to display form errors. -->
                                        <div id="card-errors" role="alert" class="text-danger mt-2"></div>
                                    </div>
                                    <button type="submit" id="placeorder" class="btn btn-primary btn-block">Place your Order
                                        <i class="icon icon-arrow-right-circle"></i>
                                    </button>
                                </form>
                                <a href="{{ route('usersproducts')}}" class="btn btn-secondary mt-3">Go Back to Shop</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {

            var discount = 0;
            $('#coupon-apply').on('click', function(){
                var couponCode = $('#coupon_code').val();
                var amountText = $('#show-total-amount').text();
                var amountMatch = amountText.match(/[\d,\.]+/);
                var amount = amountMatch ? parseFloat(amountMatch[0].replace(/,/g, '')) : 0; 
                // alert(amount);

                $.ajax({
                    type: 'POST',
                    url: '{{ route('apply_coupon') }}',
                    data: {
                        _token: '{{ csrf_token() }}',
                        amount: amount,
                        coupon_code: couponCode,
                    },
                    success: function(data) {
                        if (data.status === 'success') {
                            // alert(data.discount);
                            discount = data.discount;

                            $('#coupon_code').removeClass('border-danger');
                            $('#show-total-amount').text('Total: $' + data.discount);

                            $('.error-message').text("The coupon code has been successfully applied, and the discount amount is less than expected. You can verify this in the product details section above.").addClass('success').show();
                            $('#coupon-apply').prop('disabled', true);                            

                        } else {
                            $('.error-message').text(data.message).show();
                            $('#coupon_code').addClass('border-danger');
                        }
                    },
                    error: function(xhr, status, error) {

                        var errorMessage = xhr.responseJSON.message || 'An error occurred while applying the coupon.';
                        $('.error-message').text(errorMessage).show();
                        $('#coupon_code').addClass('border-danger');
                    }
                });
            });

            $('.change-link').on('click', function(event) {
                event.preventDefault();
                $(this).closest('.details__user').find('.shipping-form').toggle();
            });

            var totalAmount = 0;
            $('.details__item').each(function() {
                var quantity = $(this).attr('data-quantity');
                var price = parseFloat($(this).find('.item__price').text().replace('$ ', ''));
                totalAmount += price;
                $('#show-total-amount').text('Total:  $' + totalAmount);
            });

            $('.use-address-btn').on('click', function(event) {
                event.preventDefault();
                var selectedAddressId = $(this).closest('.shipping-form').find('input[name="address"]:checked').val();
                var selectedAddress = $(this).closest('.shipping-form').find('input[name="address"][value="' + selectedAddressId + '"]').closest('div').find('p').text();
                var addressParts = selectedAddress.split(',');
                var name = addressParts[0].trim();
                var address = addressParts.slice(1).join(', ').trim();
                $('.user__name').text(name);
                $('.user__address').text('Shipping Address: ' + address);
            });

            var stripe = Stripe('pk_test_51PKwaLL5Iy13zKBNa0KBP1AoyyRKgyHxlr4Tc8nhlvdy4SF9sziKIi5XXwkYvc1MIUhqhvc0mKKpNDI5MXEbaBna00UZoKEVvN');
            var elements = stripe.elements();
            var card = elements.create('card');
            card.mount('#card-element');

            card.addEventListener('change', function(event) {
                var displayError = document.getElementById('card-errors');
                if (event.error) {
                    displayError.textContent = event.error.message;
                } else {
                    displayError.textContent = '';
                }
            });

            $('#payment-form').on('submit', function(event) {
                event.preventDefault();

                var selectedAddressId = $('.shipping-form').find('input[name="address"]:checked').val();
                if (!selectedAddressId) {
                    alert("Please select an address to place the order");
                    return;
                }

                var totalAmount = 0;
                $('.details__item').each(function() {
                    var quantity = $(this).attr('data-quantity');
                    var price = parseFloat($(this).find('.item__price').text().replace('$ ', ''));
                    if(discount == "0"){
                        totalAmount += price;
                    }else{
                        totalAmount = discount;
                    }
                });

                console.log(totalAmount);

                stripe.createPaymentMethod({
                    type: 'card',
                    card: card
                }).then(function(result) {
                    if (result.error) {
                        $('#loader').hide(); // Hide the loader on error
                        var errorElement = document.getElementById('card-errors');
                        errorElement.textContent = result.error.message;
                    } else {
                        if (confirm('Kindly verify whether you wish to proceed with the order. If you confirm, the amount will be automatically debited from your account.') == true) {
                            $('#loader').show();
                            
                            var data = {
                                _token: "{{ csrf_token() }}",
                                selectedAddressId: selectedAddressId,
                                paymentMethodId: result.paymentMethod.id,
                                totalAmount: totalAmount,
                                product_ids: $('.details__item').map(function() {
                                    return $(this).attr("data-product-id");
                                }).get(),
                                product_quantities: $('.details__item').map(function() {
                                    return $(this).attr("data-quantity");
                                }).get()
                            };

                            var coupon = $('#coupon_code').val();
                            if (coupon !== '') {
                                data.couponCode = coupon;
                            }

                            $.ajax({
                                type: "POST",
                                url: "{{ route('place_order') }}",
                                data: data,
                                success: function(data) {
                                    $('#loader').hide(); // Hide the loader on success
                                    alert("Order placed successfully!");
                                    window.location.href = '{{ route('usersorders') }}';
                                },
                                error: function(xhr, status, error) {
                                    $('#loader').hide(); // Hide the loader on error
                                    alert("Error placing order: " + error);
                                }
                            });
                        } else {
                            $('#loader').hide(); // Hide the loader if confirmation is cancelled
                        }
                    }
                });
            });
        });
    </script>
    <style>
        .input-group-append .btn {
            padding: 0.375rem 0.75rem;
            border-radius: 5px !important;
            font-family: 'Times New Roman', Times, serif;
        }
        .border-danger {
            border: 1px solid red !important;
        }
        .success{
            color: green !important;
        }
        #coupon-apply:disabled {
            cursor: not-allowed;
            opacity: 0.6; /* Optional: to visually indicate that the button is disabled */
        }

    </style>  


</body>
</html>
