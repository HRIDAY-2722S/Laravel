@extends('layouts.admindefault')

@section('title', 'Admin || Orderdetails')

@section('content')

  @if (session('success'))
    <div class="m-4 alert alert-success" style="padding: 15px;">
      {{ session('success') }}
    </div>
  @endif

  <div class="card m-4" style="width: 96%">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6 p-3 border border-secondary rounded">
          <h5 class="card-title">User Information</h5>
          @php
          $user = DB::table('users')->where('id', $order->user_id)->first();
          @endphp
          @if($order->user_id == "0")
            <div class="form-group row">
              <div class="col-md-8">
                <label for="name">Order made by the guest in guest mode.</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ $user->name }}" readonly>
                @error('name')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
            </div>
          @else
          <form method="post" action="{{ route('save_user', ['id' => $order->id]) }}">
            @csrf
            <div class="form-group row">
              <div class="col-md-6">
                <label for="name">Name:</label>
                <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" id="name" value="{{ $user->name }}" placeholder="Enter name">
                @error('name')
                  <span class="invalid-feedback">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-md-6">
                <label for="status">Status:</label>
                <select class="form-control @error('status') is-invalid @enderror" id="status" name="status">
                  <option value="">Select option</option>
                  <option value="1" {{ $user->status == '1' ? 'selected' : '' }}>Active</option>
                  <option value="0" {{ $user->status == '0' ? 'selected' : '' }}>Inactive</option>
                </select>
                @error('status')
                <span class="invalid-feedback" role="alert">{{ $message }}</span>
              @enderror
              </div>
            </div>
            <div class="form-group row">
              <div class="col-md-9">
                <label for="email">Email:</label>
                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ $user->email }}" placeholder="Enter email">
                @error('email')
                  <span class="invalid-feedback" role="alert">{{ $message }}</span>
                @enderror
              </div>
              <div class="col-md-3">
                <label for="submit"> &nbsp;</label>
                <button type="submit" class="btn btn-primary w-100">Update</button>
              </div>
            </div>
          </form>
          @endif
        </div>
        <div class="col-md-3 p-3 border border-secondary rounded">
          <h5 class="card-title">
            Shipping Address
            <a href="{{ route("edit_user_address", ['id' => $order->id]) }}"><i class="fas fa-pencil-alt float-right" style="cursor: pointer;"></i></a>
          </h5>
          @php
          $shipping = DB::table('addresses')->where('id', $order->address_id)->first();
          @endphp
          <p class="card-text">{{ $shipping->address_lane_1 }}, {{ $shipping->area }}, {{ $shipping->landmark }}</p>
          <p class="card-text">{{ $shipping->town }}, {{ $shipping->state }}, {{ $shipping->pincode }}</p>
          <p class="card-text">{{ $shipping->name }} ({{ $shipping->mobile }})</p>
          <p class="card-text">{{ $shipping->email }}</p>
        </div>
        <div class="col-md-3 p-3 border border-secondary rounded">
          <h5 class="card-title">
            Billing Address
            <a href="{{ route("edit_user_address", ['id' => $order->address_id]) }}"><i class="fas fa-pencil-alt float-right" style="cursor: pointer;"></i></a>
          </h5>
          <p class="card-text">{{ $shipping->address_lane_1 }}, {{ $shipping->area }}, {{ $shipping->landmark }}</p>
          <p class="card-text">{{ $shipping->town }}, {{ $shipping->state }}, {{ $shipping->pincode }}</p>
          <p class="card-text">{{ $shipping->name }} ({{ $shipping->mobile }})</p>
          <p class="card-text">{{ $shipping->email }}</p>
        </div>
      </div>
    </div>
  </div>

  <div class="card m-4" style="width: 96%">
    <div class="card-body">
      <h5 class="card-title">Product Details</h5>
      <table class="table table-striped">
        <thead>
          <tr>
            <th>Product Image</th>
            <th>Product Name</th>
            <th>Quantity</th>
            <th>Price</th>
            <th>Total</th>
          </tr>
        </thead>
        <tbody>
          @php
          $product = DB::table('products')->where('id', $order->product_id)->first();
          @endphp
          <tr>
            <td><img src="{{ asset('products_image/' . $product->image) }}" alt="Product Image" height="50" width="50"></td>
            <td>{{ $product->name }}</td>
            <td>{{ $order->quantity }}</td>
            <td>${{ $product->price }}</td>
            <td>${{ $product->price*$order->quantity }}.00</td>
          </tr>
          <tr>
            <th colspan="4">Subtotal</th>
            <td>${{ $product->price*$order->quantity }}.00</td>
          </tr>
          <tr>
            <th colspan="4">Tax (0%)</th>
            <td>$0.00</td>
          </tr>
          <tr>
            <th colspan="4">Total</th>
            <td>${{ $product->price*$order->quantity }}.00</td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>
  <div class="card m-4" style="width: 96%">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <h5 class="card-title">Payment Details</h5>
          <div class="row">
            <div class="col-md-6">
              <p><strong>Pay By:</strong> Credit Card</p>
            </div>
            <div class="col-md-6">
              <p><strong>Transaction ID:</strong> {{ $order->stripe_transaction_id }}</p>
            </div>
          </div>
        </div>
        <div class="col-md-6">
          
        </div>
      </div>
    </div>
  </div>
  <div class="card m-4" style="width: 96%">
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <h5 class="card-title">Actions</h5>
          <div class="row">
            <div class="col-md-6">
              <label for="update_status">Update Order Status:</label>
              <select class="form-control" id="update_status" @if($order->cancel == 1) disabled style="cursor: not-allowed;" @endif>
                  <option value="Order" {{ $order->status == 'Order' ? 'selected' : '' }}>Order</option>
                  <option value="Shipped" {{ $order->status == 'Shipped' ? 'selected' : '' }}>Shipped</option>
                  <option value="Out of delivery" {{ $order->status == 'Out of delivery' ? 'selected' : '' }}>Out of delivery</option>
                  <option value="Delivered" {{ $order->status == 'Delivered' ? 'selected' : '' }}>Delivered</option>
                  <option value="cancelled" {{ $order->status == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
              </select>
            </div>
            <div class="col-md-6">
              <label for="update_status">&nbsp;</label>
              <button class="btn btn-danger w-100 mb-3 cancel-order" @if($order->cancel == 1) disabled style="cursor: not-allowed;" @endif>@if($order->cancel == 1) Cancelled @else Cancel Order @endif</button>
            </div>
          </div>
        </div>
        <div class="col-md-6">

        </div>
      </div>
    </div>
  </div>
  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  <script>
    $(document).ready(function() {
      previousValue = $('#update_status').val();
      $('#update_status').change(function() {
        
        var status = $(this).val();
        if(status == "cancelled"){
          if(confirm("Please confirm if you would like to proceed with canceling this order. Once the order is canceled, it cannot be retrieved.")){
        
            $.ajax({
              url: "{{ route('update_order_status') }}",
              method: "POST",
              data: {
                _token: "{{ csrf_token() }}",
                status: status,
                order_id: "{{ $order->id }}"
              },
              success: function(data) {
                // console.log(data);
                location.reload();
              }
            });
          }else{
            $(this).val(previousValue);
          }
        }else{
          $.ajax({
            url: "{{ route('update_order_status') }}",
            method: "POST",
            data: {
              _token: "{{ csrf_token() }}",
              status: status,
              order_id: "{{ $order->id }}"
            },
            success: function(data) {
              // console.log(data);
              location.reload();
            }
          });
        }
      });

      $('.cancel-order').on('click', function(){
        if(confirm('Please confirm if you would like to proceed with canceling this order. Once the order is canceled, it cannot be retrieved.')){
          $.ajax({
            url: "{{ route('cancel_user_order') }}",
            method: "POST",
            data: {
              _token: "{{ csrf_token() }}",
              order_id: "{{ $order->id }}"
            },
            success: function(data) {
              // console.log(data);
              location.reload();
            }
          });
        }
      });
    });    
  </script>
@endsection
