<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Confirmation</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="email-container">
        <header class="email-header">
            <img src="logo-placeholder.png" alt="Company Logo" class="logo">
        </header>
        <main class="email-body">
            <h1>Order Confirmation</h1>
            <p>Thank you for your order! Your order has been received and is now being processed. Your order details are shown below for your reference:</p>
            <section class="order-summary">
                <h2>Order Summary</h2>
                <table>
                    <thead>
                        <tr>
                            <th>Item</th>
                            <th>Quantity</th>
                            <th>Price</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $total = 0;
                        @endphp
                        @foreach ($orders as $order)
                            @php
                                $product = DB::table('products')->where('id', $order->product_id)->first();
                                $subtotal = $order->quantity * $product->price;
                                $total += $subtotal;
                            @endphp
                            <tr>
                                <td><img src="{{ asset('products_image/'.$product->image) }}" width="50" height="50"></td>
                                <td>{{ $product->name }}</td>
                                <td>{{ $order->quantity }}</td>
                                <td>${{ $subtotal }}.00</td>
                            </tr>
                        @endforeach
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="3" style="text-align: right;">Total</th>
                            <th>${{ $total }}.00</th>
                        </tr>
                    </tfoot>
                </table>
            </section>
            <section class="customer-info">
                <h2>Customer Information</h2>
                <table>
                    <tr>
                        <th>Shipping Address</th>
                        <th>Billing Address</th>
                    </tr>
                    <tr>
                        @php
                            $address = DB::table('addresses')->where('id', $order->address_id)->first();
                        @endphp
                        <td>
                            {{ $address->name }}<br>
                            {{ $address->landmark }}, {{ $address->address_lane_1 }}<br>
                            {{ $address->area }},  {{ $address->town }}, ({{ $address->pincode }})<br>
                            {{ $address->town }}, {{ $address->state }}, {{ $address->country }}<br>
                            {{ $address->mobile }}
                        </td>
                        <td>
                            {{ $address->name }}<br>
                            {{ $address->landmark }}, {{ $address->address_lane_1 }}<br>
                            {{ $address->area }},  {{ $address->town }}, ({{ $address->pincode }})<br>
                            {{ $address->town }}, {{ $address->state }}, {{ $address->country }}<br>
                            {{ $address->mobile }}
                        </td>
                    </tr>
                    <tr>
                        <th>Shipping Method</th>
                        <th>Payment Method</th>
                    </tr>
                    <tr>
                        <td>Free Shipping</td>
                        <td>Credit Card</td>
                    </tr>
                </table>
            </section>
        </main>
        <footer class="email-footer">
            <p>© 2024 Polaroid Originals. All rights reserved.</p>
        </footer>
    </div>
    <style>
        table {
            font-family: arial, sans-serif;
            border-collapse: collapse;
            width: 100%;
        }

        td, th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        tr:nth-child(even) {
            background-color: #dddddd;
        }

        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .email-container {
            max-width: 600px;
            margin: 20px auto;
            background-color: #ffffff;
            border: 1px solid #dddddd;
            padding: 20px;
        }

        .email-header {
            background-color: #345C72;
            padding: 20px;
            text-align: center;
        }

        .logo {
            max-width: 150px;
        }

        .email-body {
            padding: 20px;
        }

        .email-body h1 {
            color: #345C72;
        }

        .order-summary, .customer-info {
            margin-top: 20px;
        }

        .order-summary table {
            width: 100%;
            border-collapse: collapse;
        }

        .order-summary th, .order-summary td {
            border: 1px solid #dddddd;
            padding: 8px;
            text-align: left;
        }

        .email-footer {
            background-color: #345C72;
            color: #ffffff;
            text-align: center;
            padding: 10px;
            margin-top: 20px;
        }
    </style>
</body>
</html>
