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
                    <li class="breadcrumb-item" aria-current="page">Dashboard</li>
                </ol>
            </nav>
        </div>
    </div>
</div>

<div class="container-fluid">

    <div class="row">
        <div class="col-sm-12 col-lg-4">
            <div class="card card-hover">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="mr-4">
                            <small>Total Users</small>
                            <h4 class="mb-0">{{ $totalUsers }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- column -->
        <div class="col-sm-12 col-lg-4">
            <div class="card card-hover bg-red">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="mr-4">
                            <small>Inactive Users</small>
                            <h4 class="mb-0">{{ $inactiveUsers }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- column -->
        <div class="col-sm-12 col-lg-4">
            <div class="card card-hover bg-green">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="mr-4">
                            <small>Active Users</small>
                            <h4 class="mb-0">{{ $activeUsers }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-12 col-lg-4">
            <div class="card card-hover bg-dark">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="mr-4">
                            <small>Total Products</small>
                            <h4 class="mb-0">{{ $totalproducts }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4">
            <div class="card card-hover bg-light">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="mr-4">
                            <small>Total Orders</small>
                            <h4 class="mb-0">{{ $totalorder }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-12 col-lg-4">
            <div class="card card-hover bg-green">
                <div class="card-body">
                    <div class="d-flex">
                        <div class="mr-4">
                            @php
                            $totalquotation = DB::table('quotations')->count();
                            @endphp
                            <small>Total Quotations</small>
                            <h4 class="mb-0">{{ $totalquotation }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- <a href="{{ route('test') }}">Test</a> -->
    <!-- <div class="row">
        <div class="col-sm-12">
            <div class="card">
                <div class="card-body">
                    <div class="d-md-flex align-items-center">
                        <div>
                            <h4 class="card-title">Top Selling Products</h4>
                            <p class="card-subtitle">Overview of Top Selling Items</p>
                        </div>
                        <div class="ml-auto">
                            <div class="dl">
                                <select class="custom-select">
                                    <option value="0" selected="">Monthly</option>
                                    <option value="1">Daily</option>
                                    <option value="2">Weekly</option>
                                    <option value="3">Yearly</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="table-responsive">
                    <table class="table v-middle">
                        <thead>
                            <tr class="bg-light">
                                <th class="border-top-0">Products</th>
                                <th class="border-top-0">License</th>
                                <th class="border-top-0">Support Agent</th>
                                <th class="border-top-0">Technology</th>
                                <th class="border-top-0">Tickets</th>
                                <th class="border-top-0">Sales</th>
                                <th class="border-top-0">Earnings</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="m-r-10"><a class="btn btn-circle btn-info text-white">EA</a></div>
                                        <div class="">
                                            <h4 class="m-b-0 font-16">Elite Admin</h4>
                                        </div>
                                    </div>
                                </td>
                                <td>Single Use</td>
                                <td>John Doe</td>
                                <td>
                                    <label class="label label-danger">Angular</label>
                                </td>
                                <td>46</td>
                                <td>356</td>
                                <td>
                                    <h5 class="m-b-0">$2850.06</h5>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div> -->
</div>

@endsection
