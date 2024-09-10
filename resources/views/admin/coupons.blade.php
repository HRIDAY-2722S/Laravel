@extends('layouts.admindefault')

@section('title', 'Coupons || Admin')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-12 mt-4">
            <div class="card">
                <div class="card-header">
                    <h4>Coupons</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    <form action="{{ route('add_coupons') }}" method="POST">
                        @csrf
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="name">Coupon Name</label>
                                <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}">
                                @error('name')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="code">Coupon Code</label>
                                <div class="input-group">
                                    <input type="text" class="form-control @error('code') is-invalid @enderror" id="code" name="code" placeholder="Click on generate to generate random coupon code" value="{{ old('code') }}" readonly>
                                    <div class="input-group-append">
                                        <button type="button" class="btn btn-secondary" id="generate-code-btn">Generate</button>
                                    </div>
                                    @error('code')
                                    <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                    @enderror
                                </div>
                            </div>
                        </div>
                        <div class="form-row">
                            <div class="form-group col-md-6">
                                <label for="discount">Discount (%)</label>
                                <input type="text" class="form-control @error('discount') is-invalid @enderror" id="discount" name="discount" value="{{ old('discount') }}">
                                @error('discount')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                            <div class="form-group col-md-6">
                                <label for="upto">Upto (Amount)</label>
                                <input type="text" class="form-control @error('upto') is-invalid @enderror" id="upto" name="upto" value="{{ old('upto') }}">
                                @error('upto')
                                <span class="invalid-feedback" role="alert">{{ $message }}</span>
                                @enderror
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Add Coupon</button>
                    </form>
                    <br>
                </div>
            </div>
            <div class="card">
                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Id</th>
                            <th>User Name</th>
                            <th>Coupon Name</th>
                            <th>Coupon Code</th>
                            <th>Discount</th>
                            <th>Upto</th>
                            <th>Created At</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>@php $sr = 0; @endphp
                        @foreach ($coupons as $coupon)
                        @php $sr++; @endphp
                        <tr>
                            <td>{{ $sr }}</td>
                            <?php
                            if($coupon->user_id){
                                $user = DB::table('users')->where('id', $coupon->user_id)->first();
                            ?>
                                <td style="color: red;">{{ $user->name }}</td>
                            <?php
                            }else{ ?>
                            <td></td>
                            <?php }
                            ?>
                            <td>{{ $coupon->name }}</td>
                            <td>{{ $coupon->code }}</td>
                            <td>{{ $coupon->discount }}%</td>
                            <td>${{ $coupon->upto }}</td>
                            <td>{{ $coupon->created_at->format('d M y') }}</td>
                            <td>
                                <form action="{{ route('delete_coupon', ['id' => $coupon->id]) }}" method="POST" class="delete-form">
                                    @csrf
                                    @method('DELETE')
                                    <input type="hidden" id="coupon_id" value="{{ $coupon->id }}">
                                    <button type="button" class="btn btn-outline-danger delete">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function() {
        $('#generate-code-btn').on('click', function() {
            var code = generateCouponCode();
            $('#code').val(code);
        });

        function generateCouponCode() {
            var characters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789abcdefghijklmnopqrstuvwxyz';
            var code = '';
            for (var i = 0; i < 10; i++) {
                var randomIndex = Math.floor(Math.random() * characters.length);
                code += characters.charAt(randomIndex);
            }
            return code;
        }

        $('.delete').click(function(e) {
            e.preventDefault();
            var form = $(this).closest('form');
            var id = $(this).closest('tr').find('#coupon_id').val();
            if(confirm('Are you sure that you wish to delete this coupon?')) {
                form.submit();
            }
        });
    });
</script>
@endsection
