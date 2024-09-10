@extends('layouts.default')

@section('title', 'Orders || Users')

@section('content')
<!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-U+H8lR8kPy4W/h6BK3kfrbdVtj/8+tZxFmiCt7H1kPtzKxJOjIyQhCzI8k4x2j8jl4oY7r4g2MJlh7/TMIBXrQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{ asset('css/styles.css') }}" rel="stylesheet">
@if (session('status'))
    <div class="fade-out" style="position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); z-index: 1000; background-color: #34C759; padding: 15px; border-radius: 10px;">{{ session('status') }}
    </div>

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

@if(session('role') != 'guest')
    @if($orders->isEmpty())
        <div class="card rounded">
            <div class="card-body">
                <div class="alert alert-warning">You didn't place an order yet. Once you place an order, you will be able to view your product details.</div>
                <div class="mt-4">
                    <h5>How to Place an Order</h5>
                    <ol>
                        <li><strong>Go to the Product Page:</strong> Navigate to the product page where you can browse various products available for purchase.</li>
                        <li><strong>Select a Product:</strong> Choose the product you wish to buy by clicking on it.</li>
                        <li><strong>Add to Cart:</strong> On the product details page, click on the "Add to Cart" button to add the product to your shopping cart.</li>
                        <li><strong>Proceed to Buy Now:</strong> After adding the product to your cart, click on the "Buy Now" button located at the bottom right corner of the page. This will redirect you to the checkout page.</li>
                        <li><strong>Review Your Order:</strong> On the checkout page, you will see the details of the products in your cart.</li>
                        <li><strong>Add Shipping Address:</strong> Enter your shipping address. If you have not added an address previously, you will be prompted to do so. Ensure that your address is accurate and complete.</li>
                        <li><strong>Apply Coupon Code:</strong> If you have a coupon, enter the coupon code in the designated field and click "Apply". The discount will be applied to your order if the coupon code is valid.</li>
                        <li><strong>Enter Payment Details:</strong> Provide your credit card details for payment. Make sure all the information is correct.</li>
                        <li><strong>Place Your Order:</strong> After entering your payment details, click on the "Place Order" button.</li>
                        <li><strong>Confirm Order:</strong> A confirmation prompt will appear. Click "OK" to confirm that you want to place the order.</li>
                        <li><strong>Order Confirmation:</strong> After confirming, your order will be placed, and you will be redirected to the order page where you can view the details of your order.</li>
                    </ol>
                </div>
            </div>
        </div>


    @else
        @foreach ($orders as $order)
            <div class="card" style="border-radius: 10px; margin-bottom: 10px;">
                <?php
                $product = DB::table('products')->where('id', $order->product_id)->first();
                ?>
                <div class="card-header d-flex justify-content-between" style="border-radius: 10px;">
                    <div>
                        <strong>Ordered</strong><br>
                        <span>{{$order->created_at->format('M d, Y')}}</s>
                    </div>
                    <div>
                        <strong>Total</strong><br>
                        <span>${{ $product->price*$order->quantity }}</span>
                    </div>
                        <div class="dropdown">
                            <?php
                            $address = DB::table('addresses')->where('id', $order->address_id)->first();
                            ?>
                            <strong>Shipped to</strong><br>
                            <a href="#" id="show-address-{{ $order->id }}" data-toggle="modal" data-target="#address-modal-{{ $order->id }}">{{ $address->name }}<i class="fas fa-chevron-down"></i></a>
                            </div>
                        <!-- Bootstrap Model Start -->
                        <div class="modal fade" id="address-modal-{{ $order->id }}" tabindex="-1" role="dialog" aria-labelledby="address-modal-label-{{ $order->id }}" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="address-modal-label-{{ $order->id }}">Shipped to</h5>
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                        </button>
                                    </div>
                                    <div class="modal-body">
                                        <p><strong>Recipient:</strong>{{ $address->name }}</p>
                                        <p><strong>Address:</strong> {{ $address->address_lane_1 }}<br>
                                        {{$address->landmark}}<br>
                                        {{ $address->area }}<br>
                                        {{ $address->town }}, {{ $address->state }}, {{ $address->pincode }}<br>
                                        {{ $address->country }}</p>
                                        <p><strong>Contact:</strong> {{ $address->mobile }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!-- Bootstrap Model End -->
                    </span>
                    <div>
                        <strong>Order </strong>
                        <span>#1234567890</s>
                    </div>
                </div>
                <div class="card-body d-flex">
                    <img src="{{ asset('products_image/'.$product->image) }}" class="product-image mr-3" alt="Product Image">
                    <div>
                        <h5 class="card-title"><a href="{{ route('single_products',['id' => $product->id]) }}">{{$product->name}}</a></h5>
                        <p class="card-text">Order Date: {{$order->created_at->format('M d, Y') }}</p>
                        <p class="card-text">Total: ${{ $product->price }} X {{ $order->quantity }} = ${{ $product->price*$order->quantity }}</p>
                        @if($order->cancel == 0)
                            <a href="{{ route('order_details', ['id' => $order->id]) }}" class="btn btn-primary">View product or edit details</a>
                        @else
                            <a class="btn btn-danger">Order cancelled</a>
                        @endif
                    </div>
                </div>
            </div>
        @endforeach
    @endif
@else
    <div class="card" style="border-radius: 10px; margin-bottom: 10px; padding: 15px; text-align: center;">
        <h2 class="text-2xl font-semibold" style="font-size: 18px;"><b>I apologize, but as you placed your order in guest mode, you won't be able to view your order details directly on the website. However, the order details have been sent to your email address. Please check your email for further information.</b></h2>
    </div>
@endif
<script>
</script>
<style>
    .product-image {
    width: 240px;
    height: 148px;
    object-fit: cover;
    border-radius: 10px;
}
</style>
@endsection
