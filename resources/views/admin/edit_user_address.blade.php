@extends('layouts.admindefault')

@section('title', 'Admin || User Address')

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 mb-5 mt-5 p-5 border rounded" style="border-color: #dbe2ea;">
                <h2 class="text-center">Edit User's Address</h2>
                @if($addresses->isEmpty())
                    <p>No addresses found for this user.</p>
                @else
                    @foreach($addresses as $address)
                        <form method="post" action="{{ route('save_user_address', ['id' => $order->id]) }}">
                            @csrf
                            <div class="form-group">
                                <label for="country">Country/Region</label>
                                <select id="country" class="form-control @error('country') is-invalid @enderror" name="country">
                                    <option value="">Select Country</option>
                                    <?php
                                    $countries = DB::table('countries')->get();
                                    ?>
                                    @foreach($countries as $country)
                                        <option value="{{ $country->name }}" {{ $country->name == $address->country ? 'selected="selected"' : '' }}>{{ $country->name }}</option>
                                    @endforeach
                                </select>
                                @error('country')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="full_name">Full Name (First and Last Name)</label>
                                <input type="text" id="full_name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $address->name }}">
                                @error('name')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="mobile_number">Mobile Number</label>
                                <input type="tel" id="mobile_number" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ $address->mobile }}">
                                <small class="form-text text-muted">May be used to assist delivery.</small>
                                @error('mobile')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="mobile_number">Email</label>
                                <input type="text" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ $address->email }}">
                                <small class="form-text text-muted">May be used to assist delivery.</small>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="pin_code">Pin Code</label>
                                <input type="number" id="pin_code" name="pincode" class="form-control @error('pincode') is-invalid @enderror" value="{{ $address->pincode }}">
                                @error('pincode')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="house_no">Flat, House No., Building, Company, Apartment</label>
                                <input type="text" id="house_no" name="address_lane_1" class="form-control @error('address_lane_1') is-invalid @enderror" value="{{ $address->address_lane_1 }}">
                                @error('address_lane_1')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="area">Area, Street, Sector, Village</label>
                                <input type="text" id="area" name="area" class="form-control @error('area') is-invalid @enderror" value="{{ $address->area }}">
                                @error('area')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="landmark">Landmark</label>
                                <input type="text" id="landmark" name="landmark" class="form-control @error('landmark') is-invalid @enderror" value="{{ $address->landmark }}">
                                @error('landmark')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="town_city">Town/City</label>
                                    <input type="text" name="town" id="town_city" class="form-control @error('town') is-invalid @enderror" value="{{ $address->town }}">
                                    @error('town')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <div class="form-group col-md-6">
                                    <label for="state">State</label>
                                    <select id="state" class="form-control @error('state') is-invalid @enderror" name="state">
                                        <option value="">Select State</option>
                                        <?php
                                        $states = DB::table('states')->get();
                                        ?>
                                        @foreach($states as $state)
                                            <option value="{{ $state->name }}" {{ $state->name == $address->state ? 'selected="selected"' : '' }}>{{ $state->name }}</option>
                                        @endforeach
                                    </select>
                                    @error('state')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                        </form>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
@endsection
