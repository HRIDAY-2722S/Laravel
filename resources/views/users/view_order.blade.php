@extends('layouts.default')

@section('title', 'Orders || Users')

@section('content')
<!-- <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet"> -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" integrity="sha512-U+H8lR8kPy4W/h6BK3kfrbdVtj/8+tZxFmiCt7H1kPtzKxJOjIyQhCzI8k4x2j8jl4oY7r4g2MJlh7/TMIBXrQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
<link href="{{ asset('css/styles.css') }}" rel="stylesheet">
<?php
// print_r($order->address_id);
?>
<div class="card" style="border-radius: 10px; margin-bottom: 10px; border: 2px solid #dbe2ea; padding: 10px;">
    <div class="card-header d-flex justify-content-between" style="border-radius: 10px;">
        <div>
            <?php
            $address = DB::table('addresses')->where('id', $order->address_id)->first();
            ?>
            <strong>Shipping Address</strong><br>
            <ul class="list-unstyled" style="color: #000000;">
                <li>{{ $address->name }}</li>
                <li>{{ $address->address_lane_1 }}</li>
                <li>{{ $address->landmark }}</li>
                <li>{{ $address->town }}, {{ $address->state }} {{ $address->pincode }}</li>
                <li>{{ $address->country }}</li>
            </ul>
        </div>
        <div>
            <strong>Payment Methods</strong><br>
            <span>Credit Card</span>
        </div>
        <div style="width: 30%;">
            <strong>Order Summary</strong><br>
            <?php
            $product = DB::table('products')->where('id', $order->product_id)->first();
            ?>
            <ul class="list-unstyled" style="color: #000000;">
                <li>Item(s) subtotal: <span style="float: right;">${{ $product->price*$order->quantity }}.00</span></li>
                <li>Shipping: <span style="float: right;">$0.00</span></li>
                <li>Fee: <span style="float: right;">$0.00</span></li>
                <li>Total: <span style="float: right;">${{ $product->price*$order->quantity }}.00</span></li>
                <li>Promotion Applied: <span style="float: right;"></span></li>
                <li>Grand total: <span style="float: right;">${{ $product->price*$order->quantity }}.00</span></li>
            </ul>
        </div>
    </div>
</div>
<div class="card">
    <div class="progress-container" style="">
        <div class="progress-bar" style="width: 
            @if($order->status == 'Order') 1%
            @elseif($order->status == 'Shipped') 32%
            @elseif($order->status == 'Out of delivery') 63%
            @elseif($order->status == 'Delivered') 100%
            @endif;">
        </div>
    </div>
    <div class="progress-text-container">
        <div class="progress-text">Order</div>
        <div class="progress-text">Shipped</div>
        <div class="progress-text">Out of delivery</div>
        <div class="progress-text">Delivered</div>
    </div> 
</div>


<div class="card" style="border-radius: 10px; margin-bottom: 10px; border: 2px solid #dbe2ea; padding: 10p;">
    <div class="container">
        <div class="row">
            <div class="col-md-4">
                <img src="{{ asset('products_image/'.$product->image) }}" alt="Fire-Boltt Phoenix Pro" class="img-fluid">
            </div>
            <div class="col-md-8 mt-5">
                <a href="{{ route('single_products', ['id' => $product->id]) }}" class="mb-3">{{ $product->name }}<br>{{ $product->description }}</a>
                <div class="text-center mt-4">
                    <a href="{{ route('cancel_order', ['id' => $order->id]) }}" class="btn btn-primary cancel-order-link" style="color: white;">Cancel Order</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
$(document).ready(function() {
    $('.cancel-order-link').click(function(event) {
        event.preventDefault();

        var confirmation = confirm("Please confirm if you would like to proceed with canceling this order. Once the order is canceled, it cannot be retrieved.");

        if (confirmation) {
            window.location.href = $(this).attr('href'); // Proceed with the navigation
        }
    });
});
</script>

<style>
.card {
    border-radius: 10px;
    margin-bottom: 10px;
    border: 2px solid #dbe2ea;
    padding: 20px;
}

.progress-container {
    margin-top: 10px;
    background-color: #e0e0e0;
    border-radius: 10px;
    overflow: hidden;
    height: 10px;
    margin-bottom: 10px;
    position: relative;
}

.progress-bar {
    background-color: #34C759;
    height: 100%;
    transition: width 0.3s ease;
    border-radius: 10px 0 0 10px;
}

.progress-text-container {
    display: flex;
    justify-content: space-between;
    position: relative;
    top: -10px;
}

.progress-text {
    font-size: 14px;
    color: #333;
    position: relative;
}

.progress-text:nth-child(2) {
    left: 0%;
}

.progress-text:nth-child(3) {
    left: 0%;
}
</style>


@endsection
