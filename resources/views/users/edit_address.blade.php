<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Address Form</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css">
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-8 offset-md-2 mb-5 mt-5 p-5" style="border: 2px solid #dbe2ea; border-radius: 15px;">
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
                <h2 class="text-center">Address Form</h2>
                @if($addresses->isEmpty())
                    <p>No addresses found for this user.</p>
                @else
                    @foreach($addresses as $address)
                        <form method="post" action="{{ route('update_address', ['id' => $address->id]) }}">
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
                                    <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="full_name">Full name (First and Last name)</label>
                                <input type="text" id="full_name" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ $address->name }}">
                                @error('name')
                                <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="mobile_number">Mobile Number</label>
                                <input type="tel" id="mobile_number" name="mobile" class="form-control @error('mobile') is-invalid @enderror" value="{{ $address->mobile }}">
                                <small class="form-text text-muted">May be used to assist delivery.</small>
                                @error('mobile')
                                <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="mobile_number">Email</label>
                                <input type="text" id="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ $address->email }}">
                                @error('email')
                                <p class="invalid-feedback">{{ $message }}</p>
                            @enderror
                            </div>
                            <div class="form-group">
                                <label for="pin_code">Pin Code</label>
                                <input type="number" id="pin_code" name="pincode" class="form-control @error('pincode') is-invalid @enderror" value="{{ $address->pincode }}">
                                @error('pincode')
                                <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="house_no">Flat, House no., Building, Company, Apartment</label>
                                <input type="text" id="house_no" name="address_lane_1" class="form-control @error('address_lane_1') is-invalid @enderror" value="{{ $address->address_lane_1 }}">
                                @error('address_lane_1')
                                <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="area">Area, Street, Sector, Village</label>
                                <input type="text" id="area" name="area" class="form-control @error('area') is-invalid @enderror" value="{{ $address->area }}">
                                @error('area')
                                <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-group">
                                <label for="landmark">Landmark</label>
                                <input type="text" id="landmark" name="landmark" class="form-control @error('landmark') is-invalid @enderror" value="{{ $address->landmark }}">
                                @error('landmark')
                                <p class="invalid-feedback">{{ $message }}</p>
                                @enderror
                            </div>
                            <div class="form-row">
                                <div class="form-group col-md-6">
                                    <label for="town_city">Town/City</label>
                                    <input type="text" name="town" id="town_city" class="form-control @error('town') is-invalid @enderror" value="{{ $address->town }}">
                                    @error('town')
                                    <p class="invalid-feedback">{{ $message }}</p>
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
                                    <p class="invalid-feedback">{{ $message }}</p>
                                    @enderror
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary">Submit</button>
                            <a href="{{ route('checkout') }}" class="btn btn-secondary" style="color: white;">Go Back</a>
                        </form>
                    @endforeach
                @endif
            </div>
        </div>
    </div>
</body>
</html>