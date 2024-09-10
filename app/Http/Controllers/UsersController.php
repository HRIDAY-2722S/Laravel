<?php

namespace App\Http\Controllers;
use App\Models\Cart;
use App\Models\Coupon;
use App\Models\QuotationProduct;
use App\Models\User;
use App\Models\Order;
use App\Models\Quotation;
use App\Models\Address;
use App\Models\Product;
use App\Mail\Orders;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{
    public function dashboard(){
        return view('users/dashboard');
    }

    public function products(){
        $products = Product::paginate(20);
        return view('users/products', ['products' => $products])->with('paginator', $products);
    }

    public function singleproducts($id){
        $products = Product::where('id', $id)->get();
        return view('users/single_products', ['products' => $products]);
    }

    public function cart(){
        $cart = Cart::where('user_id', session('id'))->get();
        return view('users/cart', ['carts' => $cart]);
    }

    public function updateCart(Request $request)
    {
        if(session('role') == "guest"){
            $cartItems = $request->input('cart_items');

            $cartData = session('cart_data');
        
            foreach ($cartItems as $cartItem) {
                foreach ($cartData as &$product) {
                    if ($product['product_id'] == $cartItem['cart_id']) {
                        $product['quantity'] = $cartItem['quantity'];
                    }
                }
            }
        
            session(['cart_data' => $cartData]);
        
            return response()->json(['message' => 'Cart updated successfully']);
        }else{
            $cartItems = $request->input('cart_items');

            foreach ($cartItems as $cartItem) {
                // Update the cart item quantity
                $cart = Cart::where('id', $cartItem['cart_id'])->first();
                $cart->quantity = $cartItem['quantity'];
                $cart->total = $cart->price * $cartItem['quantity'];
                $cart->save();
            }

            $updatedCartItems = Cart::all();

            return response()->json(['cart_items' => $updatedCartItems]);
        }
    }

    public function deleteCartItem(Request $request)
    {
        $cartId = $request->input('cart_id');

        if(session('role') == "guest"){
            $cartData = session('cart_data', []);

            foreach ($cartData as $key => $item) {
                if ($item['product_id'] == $cartId) {
                    unset($cartData[$key]);
                    break;
                }
            }

            session(['cart_data' => array_values($cartData)]);

            return response()->json(['success' => true]);

        }else{
            $cart = Cart::where('id', $cartId)->first();
            $cart->delete();
            return response()->json(['success' => true]);
        }
    }

    public function profile(){
        $user = User::where('id', session('id'))->get();
        return view('users/profile',['users' => $user]);
    }

    public function update_user_profile(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|regex:/^[0-9]+$/|digits:10',
        ], [
            'name.required' => 'Please confirm that the name field is not left blank.',
            'email.required' => 'Please confirm that the email field is not left blank.',
            'email.email' => 'Could you please provide the correct email address?',
            'phone.digits' => 'Kindly input a 10 digit phone number.',
            'phone.regex' => 'A phone number should only consist of numbers.'
        ]);        

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $user = User::where('id', session('id'))->first();

            $user->name = $request->name;
            $user->email = $request->email;
            $user->phone = $request->phone;

            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time() . '-' . $image->getClientOriginalName();
                $image->move(public_path('profile_picture'), $imageName);
                $user->profile_picture = $imageName;
                $user->save();
            }

            $user->save();

            return redirect()->back()->with('status', 'Profile updated successfully.');
        }
    }

    public function update_user_password(Request $request){
        $validator = Validator::make($request->all(), [ 
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ], [
            'old_password.required' => 'Please confirm that the old password field is not left blank.',
            'new_password.required' => 'Please confirm that the new password field is not left blank.',
            'confirm_password.required' => 'Please confirm that the confirm password field is not left blank.',
            'confirm_password.same' => 'Please confirm that the new password and confirm password are same',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $user = User::where('id', session('id'))->first();
            if (Hash::check($request->old_password, $user->password)) {
                $user->password = Hash::make($request->new_password);
                $user->save();
                return redirect()->back()->with('status', 'Password updated successfully.');
            }else{
                return redirect()->back()->withErrors(['old_password' => 'Your old password does not match.'])->withInput();
            }   
        }
    }

    public function delete_user_account(){
        $user = User::where('id', session('id'))->first();
        $user->delete();
        session()->flush();
        return redirect()->route('login')->with('success', 'Your account has been deleted successfully.');
    }

    public function addToCart(Request $request)
    {
        $productId = $request->input('product_id');
        $price = $request->input('price');
        $quantity = 1;

        if (session('role') == 'guest') {

            $cartData = session('cart_data', []);

            $index = array_search($productId, array_column($cartData, 'product_id'));

            if ($index !== false) {
                $newQuantity = $cartData[$index]['quantity'] + $quantity;

                if ($newQuantity > 5) {

                    $cartData[$index]['quantity'] = 5;
                } else {

                    $cartData[$index]['quantity'] = $newQuantity;
                    $cartData[$index]['price'] = $price * $cartData[$index]['quantity'];
                }
            } else {

                $cartData[] = [
                    'product_id' => $productId,
                    'price' => $price,
                    'quantity' => min($quantity, 5),
                ];
            }

            session(['cart_data' => $cartData]);

        }else{

            // $cartData = session('cart_data', []);

            // $index = array_search($productId, array_column($cartData, 'product_id'));

            // if ($index !== false) {
            //     $newQuantity = $cartData[$index]['quantity'] + $quantity;

            //     if ($newQuantity > 30) {

            //         $cartData[$index]['quantity'] = 30;
            //     } else {

            //         $cartData[$index]['quantity'] = $newQuantity;
            //         $cartData[$index]['price'] = $price * $cartData[$index]['quantity'];
            //     }
            // } else {

            //     $cartData[] = [
            //         'product_id' => $productId,
            //         'price' => $price,
            //         'quantity' => min($quantity, 30),
            //     ];
            // }

            // session(['cart_data' => $cartData]);

            $userId = session('id');

            $cartItem = Cart::where('user_id', $userId)->where('product_id', $productId)->first();

            if ($cartItem) {
                if ($cartItem->quantity <= 30) {
                    $cartItem->quantity += 1;
                    $cartItem->price = $price * $cartItem->quantity;
                    $cartItem->subtotal = $price * $cartItem->quantity;
                    $cartItem->total = $price * $cartItem->quantity;
                    $cartItem->save();
                } else {
                    return response()->json(['message' => 'Product quantity cannot exceed 5'], 422);
                }
            } else {
                if ($quantity <= 30) {
                    $cartItem = new Cart();
                    $cartItem->user_id = $userId;
                    $cartItem->product_id = $productId;
                    $cartItem->quantity = $quantity;
                    $cartItem->price = $price * $quantity;
                    $cartItem->subtotal = $price * $quantity;
                    $cartItem->tax = 0.00;
                    $cartItem->total = $price * $quantity;
                    $cartItem->save();
                } else {
                    return response()->json(['message' => 'Product quantity cannot exceed 30'], 422);
                }
            }
            return response()->json(['message' => 'Product added to cart successfully']);
        }
    }

    public function autocomplete(Request $request)
    {
        $query = $request->get('query');
        $results = DB::table('products')->where('name', 'LIKE', "%{$query}%")->where('description', 'LIKE', "%{$query}%")->take(10)->get();
        return response()->json($results);
    }

    public function search(Request $request)
    {
        $query = $request->input('query');
    
        $users = Product::where('name', 'LIKE', "%{$query}%")->orWhere('description', 'LIKE', "%{$query}%")->get();
    
        return view('users.search', ['searchResults' => $users]);
    }

    public function checkout(Request $request) {
        $user_id = session('id');
        $products = Cart::where('user_id', $user_id)->get();

        return view('users.checkout', compact('products'));
    }

    public function edit_address($id){

        $address = Address::where('id', $id)->get();
        return view('users/edit_address', ['addresses' => $address]);
    }

    public function update_address(Request $request, $id){

        $validator = Validator::make($request->all(), [ 
            'country' => 'required',
            'name' => 'required',
            'mobile' => 'required',
            'email' => 'required|email',
            'pincode' => 'required',
            'address_lane_1' => 'required',
            'area' => 'required',
            'landmark' => 'required',
            'town' => 'required',
            'state' => 'required',
        ],[
            'country.required' => 'Country is required',
            'name.required' => 'Name is required',
            'mobile.required' => 'Mobile is required',
            'email.required' => 'Email is required',
            'email.email' => 'Email is invalid',
            'pincode.required' => 'Pincode is required',
            'address_lane_1.required' => 'Flat, House no., Building, Company, Apartment is required',
            'area.required' => 'Area, Street, Sector, Village is required',
            'landmark.required' => 'Landmark is required',
            'town.required' => 'Town is required',
            'state.required' => 'State is required',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $address = Address::find($id);
        $address->user_id = session('id');
        $address->country = request('country');
        $address->name = request('name');
        $address->mobile = request('mobile');
        $address->email = request('email');
        $address->pincode = request('pincode');
        $address->address_lane_1 = request('address_lane_1');
        $address->area = request('area');
        $address->landmark = request('landmark');
        $address->town = request('town');
        $address->state = request('state');
        $address->save();
        if(session('role') == "guest"){
            $request->session()->put('email', $request->email);
        }
        return redirect()->back()->with('status', 'Your address has been successfully updated. You can now proceed to the checkout page and select your address.');
    }

    public function get_address(Request $request){
        $addressId = $request->input('id');
        $address = Address::find($addressId);

        if (!$address) {
            return response()->json(['error' => 'Address not found'], 404);
        }

        $addressData = [
            'country' => $address->country,
            'name' => $address->name,
            'mobile' => $address->mobile,
            'pincode' => $address->pincode,
            'address_lane_1' => $address->address_lane_1,
            'area' => $address->area,
            'landmark' => $address->landmark,
            'town' => $address->town,
            'state' => $address->state,
        ];

        return response()->json($addressData);
    }

    public function add_new_address(){
        $address = Address::where('user_id', session('id'))->get();
        if(session('role') != 'guest'){
            if($address->count() == 5){
                return redirect()->route('checkout')->with('status', 'You are allowed to add a maximum of five addresses to your profile. If you wish to add another address, please update an existing one.');
            }
            else{
                return view('users/add_new_address');
            }
        }else{
            return view('users/add_new_address');
        }
    }

    public function add_address(Request $request){
        // try {
            $validator = Validator::make($request->all(), [ 
                'country' => 'required',
                'name' => 'required',
                'mobile' => 'required',
                'email' => 'required|email',
                'pincode' => 'required',
                'address_lane_1' => 'required',
                'area' => 'required',
                'landmark' => 'required',
                'town' => 'required',
                'state' => 'required',
            ],[
                'country.required' => 'Country is required',
                'name.required' => 'Name is required',
                'mobile.required' => 'Mobile is required',
                'email.required' => 'Email is required',
                'email.email' => 'Email is invalid',
                'pincode.required' => 'Pincode is required',
                'address_lane_1.required' => 'Flat, House no., Building, Company, Apartment is required',
                'area.required' => 'Area, Street, Sector, Village is required',
                'landmark.required' => 'Landmark is required',
                'town.required' => 'Town is required',
                'state.required' => 'State is required',
            ]);
    
            if ($validator->fails()) {
                return redirect()->back()->withErrors($validator)->withInput();
            } else {
                $address = new Address();
                $address->user_id = session('id');
                $address->country = request('country');
                $address->name = request('name');
                $address->mobile = request('mobile');
                $address->email = request('email');
                $address->pincode = request('pincode');
                $address->address_lane_1 = request('address_lane_1');
                $address->area = request('area');
                $address->landmark = request('landmark');
                $address->town = request('town');
                $address->state = request('state');
                $address->save();
    
                if(session('role') == 'guest'){
                    $request->session()->put('address_id', $address->id);
                    $request->session()->put('email', $request->email);
                }
    
                return redirect()->route('checkout')->with('status', 'Your address has been successfully added. You can proceed to the checkout page to complete the next steps.');
            }
        // } catch (\Exception $e) {
        //     return redirect()->back()->with('error', 'Failed to add address: '. $e->getMessage());
        // }
    }

    public function place_order(Request $request){
        $productIds = $request->input('product_ids');
        $productQuantities = $request->input('product_quantities');
        $userId = session('id');
        $addressId = $request->input('selectedAddressId');
        $paymentMethodId = $request->input('paymentMethodId');
        $totalAmount = $request->input('totalAmount');
        $couponCode = $request->input('couponCode');
    
        try {

            if ($couponCode) {
                $coupon = Coupon::where('code', $couponCode)->first();
                if ($coupon) {
                    $coupon->user_id = session('id');
                    $coupon->used = 1;
                    $coupon->save();
                }
            }

            $stripe = new \Stripe\StripeClient(env('STRIPE_SECRET'));
    

            $paymentIntent = $stripe->paymentIntents->create([
                'amount' => $totalAmount * 100,
                'currency' => 'usd',
                'payment_method' => $paymentMethodId,
                'confirmation_method' => 'manual',
                'confirm' => true,
                'return_url' => url('http://127.0.0.1:8000/users/orders'),
            ]);
    

            if ($paymentIntent->status === 'requires_action' || $paymentIntent->status === 'requires_source_action') {
                return response()->json([
                    'requires_action' => true,
                    'payment_intent_client_secret' => $paymentIntent->client_secret,
                ]);
            } else if ($paymentIntent->status === 'succeeded') {
                $orders = [];
    
                foreach ($productIds as $key => $productId) {
                    $order = new Order();
                    $order->user_id = $userId;
                    $order->address_id = $addressId;
                    $order->product_id = $productId;
                    $order->quantity = $productQuantities[$key];
                    $order->stripe_transaction_id = $paymentIntent->id;
                    $order->save();
                    $orders[] = $order;
                }
    
                Mail::to(session('email'))->send(new Orders($orders));
    
                return response()->json([
                    'message' => 'Order placed successfully!',
                    'order_ids' => Order::where('user_id', $userId)->latest()->pluck('id'),
                ], 201);
            } else {
                return response()->json(['error' => 'Payment failed.'], 500);
            }
        } catch (\Exception $e) {
            // Handle errors
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function orders(){
        $orders = Order::where('user_id', session('id'))->orderBy('id', 'DESC')->get();
        return view('users/orders', ['orders' => $orders]);
    }

    public function order_details($id){
        $order = Order::where('id', $id)->first();
        return view('users/view_order', ['order' => $order]);
    }

    public function cancel_order($id){
        $order = Order::where('id', $id)->first();
        $order->cancel = '1';
        $order->save();
        return redirect()->route('usersorders')->with('status', 'Your order has been cancelled.');
    }

    public function apply_coupon(Request $request) {

        $request->validate([
            'coupon_code' => 'required',
        ],[
            'coupon_code.required' => 'Please input a valid coupon code if you have one.',
        ]);
    
        $coupon = Coupon::where('code', $request->coupon_code)->first();
    
        if (!$coupon) {
            return response()->json(['status' => 'error','message' => 'Coupon code is invalid',], 400);
        }
    
        if ($coupon->used == "1") {
            return response()->json(['status' => 'error','message' => 'Coupon code is expired or already used',], 400);
        }

        $discountPercentage = $coupon->discount / 100;
        $discountAmount = $request->amount * $discountPercentage;

        if ($discountAmount <= $coupon->upto) {
            $total = $request->amount - $discountAmount;
        } else {
            $total = $request->amount - $coupon->upto;
        }
    
        return response()->json(['status' => 'success','message' => 'Coupon applied successfully','discount' => $total], 200);
    }
    
}
