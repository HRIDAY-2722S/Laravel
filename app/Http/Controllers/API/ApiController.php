<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\cart;
use App\Models\Order;
use App\Models\Product;
use App\Models\Quotation;
use App\Models\QuotationProduct;
use App\Models\User;
use Cache;
use Hash;
use Auth;
use Illuminate\Http\Request;
use Str;
use Validator;

class ApiController extends Controller
{

    public function generate_otp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'phone' => 'required|regex:/^[0-9]+$/|digits:10'
        ], [
            'phone.digits' => 'Kindly input a 10 digit phone number.'
        ]);
        if($validator ->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $otp = rand(100000, 999999);

        Cache::put('otp_' . $request->mobile, $otp, 300);

        $brandName = 'YourBrandName';

        $basic  = new \Vonage\Client\Credentials\Basic("b50d35fb", "b3SvHLcU3ylqzSuz");
        $client = new \Vonage\Client($basic);

        $messageText = "Your mobile verification OTP is $otp. This OTP is valid for the next 5 minutes.";

        try {
            $response = $client->sms()->send(
                new \Vonage\SMS\Message\SMS("917717432780", $brandName, $messageText)
            );

            $message = $response->current();

            if ($message->getStatus() == 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'The message was sent successfully',
                ], 200);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'The message failed with status: ' . $message->getStatus(),
                ], 500);
            }
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'An error occurred: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function index(Request $request)
    {
        $token = $request->bearerToken();

        if (!$token) {
            return response()->json(['success' => false, 'message' => 'Could you kindly share the token received during the login process?'], 401);
        }

        $user = User::where('token', $token)->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => "The token you've entered is invalid. Please provide a valid token."], 401);
        }

        $users = User::where('role', 'user')->get();

        return response()->json([
            'success' => true,
            'message' => 'User data fetch successfull',
            'Users' => $users,
        ]);
    }

    public function register(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required',
                'email' => 'required|email|unique:users',
                'phone' => 'required|regex:/^[0-9]+$/|digits:10',
                // 'passwords' => 'required|min:8',
                'password' => 'required|string|min:8|regex:/[a-z]/|regex:/[A-Z]/|regex:/[0-9]/|regex:/[@$!%*?&]/',
                'password_confirmation' => 'required|same:password',
                'profile_image' => 'required|image|mimes:jpeg,png,jpg,gif',
                // 'otp' => 'required',
            ], [
                'email.email' => 'Kindly input a correct email address.',
                'email.unique' => 'This email address is already registered.',
                'phone.digits' => 'Kindly input a 10 digit phone number.',
                'phone.regex' => 'A phone number should only consist of numbers.',
                'otp.required' => 'One Time Password(OTP) is required.',
                'password.required' => 'A password is required.',
                'password.min' => 'Password must be at least 8 characters long.',
                'password.regex' => 'Password must include at least one lowercase letter, one uppercase letter, one digit, and one special character.',
                'password_confirmation.required' => 'Please confirm your password.',
                'password_confirmation.same' => 'Password and confirm password does not match.',
                'profile_image.required' => 'Kindly select a valid image file.',
            ]);
            if($validator ->fails()){
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $validator->errors(),
                ], 422);
            }

            $input = $request->all();
            $input['password'] = bcrypt($input['password']);
            $input['remember_token'] = Str::random(40);
        
            if ($request->hasFile('profile_image')) {
                $profileImage = $request->file('profile_image');
                $imageName = time().'.'.$profileImage->getClientOriginalExtension();
        
                if (in_array($profileImage->getClientMimeType(), ['image/jpeg', 'image/png', 'image/gif'])) {
                    $profileImage->move(public_path('profile_picture'), $imageName);
        
                    $input['profile_picture'] = $imageName;
                } else {
                    return response()->json(['success' => false, 'message' => 'Invalid image type'], 400);
                }
            }

            $user = User::create($input);

            $success['token'] = $user->createToken('MyApp')->plainTextToken;
            $success['name'] = $user->name;
            $response = [
                'success' => true,
                'data' => $success,
                'message' => 'User register successfully'
            ];
            return response()->json($response, 200);
        } catch (\Exception $e) {
            return response()->json(['message' => 'An error occurred: ' . $e->getMessage()], 500);
        }

    }

    public function login(Request $request){

        $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ], [
            'email.required' => 'Email is required',
            'password.required' => 'Password is required',
        ]);
        if($validator->fails()){
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Email not found'
            ], 404);
        }

        if (!Hash::check($request->password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Incorrect password'
            ], 401);
        }

        $success['token'] = $user->createToken('MyApp')->plainTextToken;
        $success['name'] = $user->name;
        $token = explode('|', $success['token'])[1];

        // Save token to database
        $user->token = $token;
        $user->save();

        $response = [
            'success' => true,
            'data' => $success,
            'message' => 'User login successfully'
        ];

        return response()->json($response, 200);
    }

    public function products(Request $request){

        $token = $request->bearerToken();

        if(!$token){
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 401);
        }

        $checktoken = User::where('token', $token)->first();
        if(!$checktoken){
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        $products = Product::paginate(20);
        return response()->json([
            'success' => true,
            'data' => $products,
            'message' => 'Products retrieved successfully'
        ], 200);
    }

    public function single_products(Request $request, $id){

        if (!$id) {
            return response()->json([
                'success' => false,
                'message' => 'Please provide an ID to get the product details'
            ], 400);
        }

        $token = $request->bearerToken();
        if(!$token){
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 401);
        }
        $checktoken = User::where('token', $token)->first();
        if(!$checktoken){
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        $product = Product::find($id);

        if($product){
            return response()->json([
                'success' => true,
                'data' => $product,
                'message' => 'Product details fetch successfull'
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
    }

    public function orders(Request $request){
        $token = $request->bearerToken();
        if(!$token){
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 401);
        }
        $checktoken = User::where('token', $token)->first();
        if(!$checktoken){
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        $orders = Order::where('user_id', $checktoken->id)->get();
        if($orders->count() > 0){
            return response()->json([
                'success' => true,
                'data' => $orders,
                'message' => 'Orders fetch successfull'
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'User not place a single order yet'
            ], 404);
        }
    }

    public function single_orders(Request $request, $id)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 401);
        }

        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        $order = Order::find($id);
        if ($order) {
            return response()->json([
                'success' => true,
                'message' => 'Order fetch successful',
                'data' => $order,
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }
    }

    public function cancel_order(Request $request, $id){
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 401);
        }
        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }
        $order = Order::find($id);
        if ($order) {
            $order->status = 'Cancelled';
            $order->cancel = "1";
            $order->save();
            return response()->json([
                'success' => true,
                'message' => 'Order cancelled successfully',
                'data' => $order
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }
                        
    }

    public function profile(Request $request){
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 401);
        }
        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }
        $user = User::where('token', $token)->first();
        if ($user) {
            return response()->json([
                'success' => true,
                'message' => 'User profile',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }

    public function update_user_profile(Request $request){

        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 401);
        }
        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false, 
                'message' => 'Invalid token'
            ], 401);
        }

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$checktoken->id,
            'phone' => 'required|regex:/^[0-9]+$/|digits:10',
        ], [
            'email.email' => 'Kindly input a correct email address.',
            'email.unique' => 'This email address is already registered.',
            'phone.digits' => 'Kindly input a 10 digit phone number.',
            'phone.regex' => 'A phone number should only consist of numbers.',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validation->errors()
            ], 422);
        }

        $user = User::where('token', $token)->first();
        if ($user) {
            $user->name = $request->input('name');
            $user->email = $request->input('email');
            $user->phone = $request->input('phone');
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imageName = time().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('profile_picture'), $imageName);
                $user->profile_picture = $imageName;
                $user->save();
            }
            $user->save();
            return response()->json([
                'success' => true,
                'message' => 'User profile updated',
                'data' => $user
            ], 200);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    }

    public function update_user_password(Request $request){
        $token = $request->bearerToken();

        if(!$token){
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 422);
        }
        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }
        $validation = Validator::make($request->all(), [
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ], [
            'old_password.required' => 'Please confirm that the old password field is not left blank.',
            'new_password.required' => 'Please confirm that the new password field is not left blank.',
            'confirm_password.required' => 'Please confirm that the confirm password field is not left blank.',
            'confirm_password.same' => 'Please confirm that the new password and confirm password are same',
        ]);

        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validation->errors()
            ], 422);
        }
        $user = User::where('token', $token)->first();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }else{
            if(Hash::check($request->old_password, $user->password)){
                $user->password = bcrypt($request->new_password);
                $user->save();
                return response()->json([
                    'success' => true,
                    'message' => 'Password updated successfully',
                    'data' => $user
                ], 200);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'Old password is incorrect',
                ], 422);
            }
        }
    }

    public function addtocart(Request $request)
    {
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 422);
        }
    
        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        $validation = Validator::make($request->all(), [
            'product_id' => 'required|integer',
        ], [
            'product_id.required' => 'Product id is required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validation->errors()
            ], 422);
        }
    
        $product_id = $request->product_id;
        $quantity = 1;
        $user_id = $checktoken->id;
    
        $product = Product::find($product_id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
    
        $cart = Cart::where('user_id', $user_id)->where('product_id', $product_id)->first();
        if ($cart) {
            if ($cart->quantity < 30) {
                $cart->quantity += $quantity;
                if ($cart->quantity > 30) {
                    return response()->json(['message' => 'Product quantity cannot exceed 30'], 422);
                }
                $cart->price = $product->price;
                $cart->subtotal = $product->price * $cart->quantity;
                $cart->total = $product->price * $cart->quantity;
                $cart->save();
            } else {
                return response()->json(['message' => 'Product quantity cannot exceed 30'], 422);
            }
        } else {
            if ($quantity <= 30) {
                $cart = new Cart();
                $cart->user_id = $user_id;
                $cart->product_id = $product_id;
                $cart->quantity = $quantity;
                $cart->price = $product->price;
                $cart->subtotal = $product->price * $quantity;
                $cart->tax = 0.00;
                $cart->total = $product->price * $quantity;
                $cart->save();
    
                return response()->json([
                    'success' => true,
                    'message' => 'Product added to cart successfully',
                    'data' => $cart
                ], 200);
            } else {
                return response()->json(['message' => 'Product quantity cannot exceed 30'], 422);
            }
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Cart updated successfully',
            'data' => $cart
        ], 200);
    }

    public function checkExistingQuotation(Request $request){

        $token = $request->bearerToken();

        if(!$token){
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 422);
        }
        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }
        $userId = $checktoken->id;
        $quotations = Quotation::where('user_id', $userId)->get();
        if($quotations->count() > 0){
            return response()->json([
                'success' => true,
                'message' => 'Quotations found',
                'data' => $quotations
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'No quotations found'
            ]);
        }
    }

    public function createQuotation(Request $request){

        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 422);
        }

        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        $validation = Validator::make($request->all(), [
            'name' => 'required',
            'product_id' => 'required|integer',
        ], [
            'name.required' => 'Name is required',
            'product_id.required' => 'Product id is required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validation->errors()
            ], 422);
        }

        $userId = $checktoken->id;
        $product_id = $request->product_id;

        $exists = Quotation::where('name', $request->name)->where('user_id', $userId)->exists();
        if ($exists) {
            return response()->json([
                'success' => false,
                'message' => 'Quotation already exists',
                'data' => $exists
            ], 400);
        } else {
            $quotation = Quotation::create([
                'user_id' => $userId,
                'name' => $request->name
            ]);

            if ($quotation) {
                $product = Product::find($product_id);
                if (!$product) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Product not found'
                    ], 404);
                }

                $quotationProduct = new QuotationProduct;
                $quotationProduct->quotation_id = $quotation->id;
                $quotationProduct->user_id = $userId;
                $quotationProduct->product_id = $product_id;
                $quotationProduct->price = $product->price;
                $quotationProduct->quantity = 1;
                $quotationProduct->subtotal = $product->price;
                $quotationProduct->total = $product->price * 1;
                $quotationProduct->save();
            }

            return response()->json([
                'success' => true,
                'message' => 'Quotation created successfully',
                'data' => $quotation,
                'product' => $product
            ], 200);
        }
    }

    public function addToQuotation(Request $request){
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 422);
        }
    
        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }
    
        $validation = Validator::make($request->all(), [
            'quotation_id' => 'required|integer',
            'product_id' => 'required|integer',
        ], [
            'quotation_id.required' => 'Quotation id is required',
            'product_id.required' => 'Product id is required',
        ]);
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validation->errors()
            ], 422);
        }
    
        $quotationId = $request->quotation_id;
        $productId = $request->product_id;
    
        $product = Product::find($productId);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
    
        $userId = $checktoken->id;
        $price = $product->price;
    
        $quotationProduct = QuotationProduct::where('quotation_id', $quotationId)
            ->where('product_id', $productId)
            ->first();
    
        if ($quotationProduct) {
            $quotationProduct->quantity += 1;
            $quotationProduct->subtotal = $quotationProduct->price * $quotationProduct->quantity;
            $quotationProduct->total = $quotationProduct->subtotal;
            $quotationProduct->save();
        } else {
            $quantity = 1;
            $subtotal = $price * $quantity;
            $total = $subtotal;
    
            $quotationProduct = QuotationProduct::create([
                'user_id' => $userId,
                'quotation_id' => $quotationId,
                'product_id' => $productId,
                'price' => $price,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'total' => $total,
            ]);
        }
    
        return response()->json([
            'success' => true,
            'message' => 'Product added to quotation successfully',
            'data' => $quotationProduct
        ], 200);
    }

    public function quotations(Request $request){
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 422);
        }
    
        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        $quotations = Quotation::where('user_id', $checktoken->id)->orderBy('id', 'DESC')->get();   
        if($quotations->count() > 0){
            return response()->json([
                'success' => true,
                'message' => 'Quotations retrieved successfully',
                'data' => $quotations
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'No Quotations found',
            ], 200);
        }
    }

    public function update_quotation_name(Request $request, $id){
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 422);
        }
    
        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        $validation = Validator::make($request->all(), [
            'name' => 'required',
        ], [
            'name.required' => 'Name is required',
        ]);    
        if ($validation->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validation->errors()
            ], 422);
        }
        $quotation = Quotation::find($id);

        $existingQuotation = Quotation::where('user_id', $checktoken->id)->where('name', $request->name)->where('id', '!=', $id)->first();

        if ($existingQuotation) {
            return response()->json([
                'success' => false,
                'message' => 'Quotation with this name already exists.'
            ], 400);
        }
        
        $quotation->name = $request->name;
        $quotation->save();

        return response()->json([
            'success' => true,
            'message' => 'Name updated successfull',
            'data' => $quotation
        ]);

    }

    public function delete_quotation(Request $request, $id){
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 422);
        }
    
        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        $quotation = Quotation::find($id);
        
        if ($quotation) {

            QuotationProduct::where('quotation_id', $id)->delete();
            
            $quotation->delete();
    
            return response()->json([
                'success' => true,
                'message' => 'Quotation deleted successfully'
            ]);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'Quotation not found'
        ]);
    }

    public function show_quotation_products(Request $request, $id){
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 422);
        }
    
        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }
        $quotation = Quotation::find($id);

        if($quotation){
            $quotationproducts = QuotationProduct::where('quotation_id', $id)->get();

            if($quotationproducts->count() > 0){
                return response()->json([
                    'success' => true,
                    'message' => 'Quotation product fetch successfull',
                    'data' => $quotationproducts
                ]);
            }else{
                return response()->json([
                    'success' => false,
                    'message' => 'No quotation products found'
                ]);
            }
        }else{
            return response()->json([
                'success' => false,
                'message' => 'No Quotations found',
            ], 200);
        }
    }

    public function delete_quotation_product(Request $request, $id){
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 422);
        }
    
        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }
        $quotationProduct = QuotationProduct::find($id);
    
        if ($quotationProduct) {
            $quotationId = $quotationProduct->quotation_id;
            $remainingProductsCount = QuotationProduct::where('quotation_id', $quotationId)->count();
    
            $quotationProduct->delete();
    
            if ($remainingProductsCount == 1) {
                $name = Quotation::find($quotationId);
                return response()->json([
                    'success' => true,
                    'message' => 'All products have been deleted from the quotation',
                ]);
            }
    
            return response()->json([
                'success' => true,
                'message' => 'Quotation product deleted successfully',
            ]);
        }
    
        return response()->json([
            'success' => false,
            'message' => 'Quotation product not found',
        ]);

    }

    public function checkout(Request $request){
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 422);
        }
    
        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        $user_id = $checktoken->id;
        $products = Cart::where('user_id', $user_id)->get();
        if($products->count() > 0){
            return response()->json([
                'success' => true,
                'message' => 'Checkout successful',
                'products' => $products,
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'No products in cart',
            ]);
        }
    }

    public function edit_address(Request $request, $id){
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 422);
        }
    
        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        $address = Address::where('id', $id)->get();
        if($address->count() > 0){
            return response()->json([
                'success' => true,
                'message' => 'Address found',
                'address' => $address,
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Address not found',
            ]);
        }

    }

    public function update_address(Request $request, $id){
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 422);
        }
    
        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }

        $address = Address::find($id);
        if (!$address) {
            return response()->json([
                'success' => false,
                'message' => 'Address not found'
            ], 404);
        }
    
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
        ], [
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
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $address->user_id = $checktoken->id;
        $address->country = $request->country;
        $address->name = $request->name;
        $address->mobile = $request->mobile;
        $address->email = $request->email;
        $address->pincode = $request->pincode;
        $address->address_lane_1 = $request->address_lane_1;
        $address->area = $request->area;
        $address->landmark = $request->landmark;
        $address->town = $request->town;
        $address->state = $request->state;
        $address->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Your address has been successfully updated. You can now proceed to the checkout page and select your address.'
        ], 200);
    }

    public function add_address(Request $request){
        $token = $request->bearerToken();
        if (!$token) {
            return response()->json([
                'success' => false,
                'message' => 'Token not found'
            ], 422);
        }
    
        $checktoken = User::where('token', $token)->first();
        if (!$checktoken) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid token'
            ], 401);
        }
    
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
        ], [
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
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        } else {
            $userId = $checktoken->id;
    
            $address = new Address();
            $address->user_id = $userId;
            $address->country = $request->country;
            $address->name = $request->name;
            $address->mobile = $request->mobile;
            $address->email = $request->email;
            $address->pincode = $request->pincode;
            $address->address_lane_1 = $request->address_lane_1;
            $address->area = $request->area;
            $address->landmark = $request->landmark;
            $address->town = $request->town;
            $address->state = $request->state;
            $address->save();
    
            return response()->json([
                'success' => true,
                'message' => 'Your address has been successfully added. You can proceed to the checkout page to complete the next steps.',
                'data' => $address
            ], 200);
        }
    }
}


