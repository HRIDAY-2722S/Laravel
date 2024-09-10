<!-- order-pdf.blade.php -->

<!DOCTYPE html>
<html>
<head>
    <title>Order || PDF</title>
</head>
<body><?php $sr = 0; ?>
    @foreach ($orders as $order)
        <?php $sr++; ?>
        <h2 @if($order->cancel == 1) style="color: red;" @endif>Order Items({{ $sr }})</h2>
        <table>
            <thead>
                <tr>
                    <th>Product</th>
                    <th>User Name</th>
                    <th>Quantity</th>
                    <th>Price</th>
                    <th>Subtotal</th>
                    <th>Status</th>
                    <th>Created_at</th>
                </tr>
            </thead>
            <tbody>
                    <tr style="@if($order->cancel ==1 ) color: red; @endif">
                        <?php
                        $product = DB::table('products')->where('id', $order->product_id)->first();
                        ?>
                        <td>{{ $product->name }}</td>
                        <?php
                        $user = DB::table('users')->where('id', $order->user_id)->first();
                        ?>
                        <td>{{ $user->name }}</td>
                        <td>{{ $order->quantity }}</td>
                        <td>${{ $product->price }}</td>
                        <td>${{ $product->price*$order->quantity }}.00</td>
                        <td>
                            @if ($order->cancel == 0)
                            <span class="badge badge-success">Active</span>
                            @else
                            <span class="badge badge-danger">Cancelled</span>
                            @endif
                        </td>
                        <td>{{ $order->created_at->format('d M, Y') }}</td>
                    </tr>
            </tbody>
            <tfoot>
                <tr>
                    <th colspan="6">Total</th>
                    <th>${{ $product->price*$order->quantity }}.00</th>
                </tr>
            </tfoot>
        </table>

        <h2>Payment Information</h2>
        <p>Payment Method: Credit Card</p>
        <p>Payment Date: {{ $order->created_at->format('d M, Y h:i:s A') }}</p>

        <h2>Shipping Information</h2>
        <?php
        $address = DB::table('addresses')->where('id', $order->address_id)->first();
        ?>
        <!-- <p>Shipping Method: {{ $order->shipping_method }}</p> -->
        <p>Shipping Address: {{ $address->name }}, {{ $address->address_lane_1 }}, {{ $address->area }},{{ $address->landmark }}, {{ $address->town }}, {{ $address->state }}, {{ $address->country }}, {{ $address->pincode }}, ({{ $address->mobile }})</p>
        
    @endforeach
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</body>
</html>