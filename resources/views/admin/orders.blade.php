@extends('layouts.admindefault')

@section('title', 'Admin Dashboard')

@section('content')

<div class="page-breadcrumb">
    <div class="row">
        <div class="col-6">
            <h4 class="page-title">Dashboard</h4>
            <nav aria-label="breadcrumb">
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="/admin">Home</a></li>
                    <li class="breadcrumb-item" aria-current="page">Orders</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-md-flex align-items-center">
                        <div>
                            <h4 class="card-title">Orders</h4>
                            <!-- <p class="card-subtitle">Overview of Top Selling Items</p> -->
                        </div>
                        <div class="ml-auto">
                            <div class="dl">
                                <a class="btn btn-primary" target="__blank" href="{{ route('generate_order_pdf') }}">Generate pdf</a>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table v-middle">
                        <thead>
                            <tr class="bg-light">
                                <th class="border-top-0">Products</th>
                                <th class="border-top-0">User_Name</th>
                                <th class="border-top-0">Quantity</th>
                                <th class="border-top-0">Cancel_order</th>
                                <th class="border-top-0">Created_at</th>
                                <th class="border-top-0">Total</th>
                                <!-- <th class="border-top-0">Earnings</th> -->
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                            <tr>
                                <td>
                                    <?php
                                    $product = DB::table('products')->where('id', $order->product_id)->first();
                                    ?>
                                    <div class="d-flex align-items-center">
                                        <div class="m-r-10">
                                            <a href="{{ route('singleproducts',['id' => $order->product_id]) }}" class="text-white">
                                                <img src="{{ asset('products_image/'.$product->image) }}" class="rounded-circle" style="width: 40px; height: 40px; object-fit: cover;">
                                            </a>
                                        </div>&nbsp;&nbsp;&nbsp;
                                        <div class="">
                                            <a href="{{ route('single_order', ['id' => $order->id]) }}" class="m-b-0 font-16" style="color: blue;">{{ $product->name }}</a>
                                        </div>
                                    </div>
                                </td>
                                <?php
                                $user = DB::table('users')->where('id', $order->user_id)->first();
                                ?>
                                <td>{{ $user->name }}</td>
                                <td>{{ $order->quantity }}</</td>
                                <td>
                                    @if($order->cancel == 0)
                                    <label class="label label-danger btn btn-success">Ongoing</</label>
                                    @else
                                    <label class="label label-success btn btn-danger">Cancel</</label>
                                    @endif
                                </td>
                                <td>{{ $order->created_at->format('M d, Y') }}</</td>
                                <td>${{ $product->price*$order->quantity }}.00</td>
                                <!-- <td>
                                    <h5 class="m-b-0">$2850.06</h5>
                                </td> -->
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            <nav aria-label="Pagination">
                <ul class="pagination justify-content-center">
                    @if ($paginator->onFirstPage())
                        <li class="page-item disabled">
                            <span class="page-link">{{ __('Previous') }}</span>
                        </li>
                    @else
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->previousPageUrl() }}" rel="prev">{{ __('Previous') }}</a>
                        </li>
                    @endif

                    @for ($i = 1; $i <= $paginator->lastPage(); $i++)
                        @if ($i == $paginator->currentPage())
                            <li class="page-item active"><span class="page-link">{{ $i }}</span></li>
                        @else
                            <li class="page-item"><a class="page-link" href="{{ $paginator->url($i) }}">{{ $i }}</a></li>
                        @endif
                    @endfor

                    @if ($paginator->hasMorePages())
                        <li class="page-item">
                            <a class="page-link" href="{{ $paginator->nextPageUrl() }}" rel="next">{{ __('Next') }}</a>
                        </li>
                    @else
                        <li class="page-item disabled">
                            <span class="page-link">{{ __('Next') }}</span>
                        </li>
                    @endif
                </ul>
            </nav>
        </div>
    </div>
</div>

@endsection
