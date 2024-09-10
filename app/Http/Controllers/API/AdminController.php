<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Address;
use App\Models\Order;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Quotation;
use App\Models\User;
use Dompdf\Dompdf;
use Dompdf\Options;
use Hash;
use Illuminate\Http\Request;
use League\Csv\Writer;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Str;
use Validator;

class AdminController extends Controller
{

    public function dashboard(Request $request){
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
        $totalUsers = User::where('role', 'user')->count();
        $activeUsers = User::where('status', '1')->where('role', 'user')->count();
        $inactiveUsers = User::where('status', '0')->where('role', 'user')->count();
        $totalproducts = Product::all()->count();
        $totalorder = Order::all()->count();
        $totalquotations = Quotation::all()->count();

        return response()->json([
            'success' => true,
            'message' => 'Data fetch successfull',
            'totalUsers' => $totalUsers,
            'activeUsers' => $activeUsers,
            'inactiveUsers' => $inactiveUsers,
            'totalproducts' => $totalproducts,
            'totalorder' => $totalorder,
            'totalquotations' => $totalquotations,
        ]);
    }

    public function products(Request $request){
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
        $products = Product::paginate(20);
        return response()->json([
            'success' => true,
            'message' => 'Data fetch successfull',
            'data' => $products
        ]);
    }

    public function add_products_details(Request $request){
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
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'images' => 'required',
        ], [
            'name.required' => 'Please provide the name of the product.',
            'description.required' => 'Please provide a description for the product.',
            'price.required' => 'Please provide the price of the product.',
            'images.required' => 'Please provide at least one product image.'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $product = new Product();
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
        $product->save();
    
        $imagePaths = [];
        $images = $request->file('images');
    
        foreach ($images as $image) {
            $imageName = time() . '-' . $image->getClientOriginalName();
            $image->move(public_path('products_image'), $imageName);
            $imagePaths[] = $imageName;
        }
    
        $product->image = $imagePaths[0];
        $product->save();
    
        $productImage = new ProductImage();
        $productImage->product_id = $product->id;
        $productImage->images = implode(',', $imagePaths);
        $productImage->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Product created successfully.',
            'data' => $product
        ], 200);
    }

    public function singleproducts(Request $request, $id){
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
        $products = Product::where('id', $id)->get();
        if($products->count() > 0){
            return response()->json([
                'success' => true,
                'message' => 'Product found',
                'data' => $products
            ], 200);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
    }

    public function edit_product(Request $request, $id){
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

        $products = Product::where('id', $id)->get();
        if($products->count() > 0){
            return response()->json([
                'success' => true,
                'message' => 'Product found',
                'data' => $products
            ]);
        }else{
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
    }

    public function update_product(Request $request, $id){
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
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'images.*' => 'image|mimes:jpeg,png,gif|max:2048'
        ], [
            'name.required' => 'Product Name is required',
            'description.required' => 'Product Description is required',
            'price.required' => 'Product Price is required',
            'price.numeric' => 'Product Price must be a number',
            'images.*.image' => 'Each file must be an image',
            'images.*.mimes' => 'Only JPEG, PNG, and GIF images are allowed',
            'images.*.max' => 'Image size should not exceed 2MB'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }
    
        $product->name = $request->name;
        $product->description = $request->description;
        $product->price = $request->price;
    
        if ($request->hasFile('images')) {
            $images = $request->file('images');
            $imageNames = [];
    
            foreach ($images as $image) {
                $imageName = time() . '_' . $image->getClientOriginalName();
                $image->move(public_path('products_image'), $imageName);
                $imageNames[] = $imageName;
            }
    
            $product->image = $imageNames[0];
    
            $productImage = ProductImage::updateOrCreate(
                ['product_id' => $id],
                ['images' => implode(',', $imageNames)]
            );
        }
    
        $product->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Product updated successfully.',
            'data' => $product
        ], 200);
    }

    public function delete_product(Request $request, $id){
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

        $product = Product::find($id);
        if (!$product) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found'
            ], 404);
        }else{
            $product->delete();
            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.'
            ], 200);
        }
    }

    public function users(Request $request){
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

        $users = User::select('id', 'profile_picture', 'name', 'email', 'status')->where('role', 'user')->get();

        return response()->json([
            'success' => true,
            'message' => 'Users details fetch successfull',
            'users' => $users
        ], 200);
    }

    public function profile(Request $request){
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
        $user = User::where('id', $checktoken->id)->get();
        return response()->json([
            'success' => true,
            'message' => 'Profile details fetch successfull',
            'user' => $user
        ], 200);
    }

    public function update_profile(Request $request){
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
            'name' => 'required',
            'email' => 'required|email',
        ], [
            'name.required' => 'Please confirm that the name field is not left blank.',
            'email.required' => 'Please confirm that the email field is not left blank.',
            'email.email' => 'Could you please provide the correct email address?'
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }
    
        $user = User::find($checktoken->id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }
    
        $user->name = $request->name;
        $user->email = $request->email;
    
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imageName = time() . '-' . $image->getClientOriginalName();
            $image->move(public_path('profile_picture'), $imageName);
            $user->profile_picture = $imageName;
        }
    
        $user->save();
    
        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully.',
            'data' => $user
        ], 200);
    }

    public function update_password(Request $request){
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
            'old_password' => 'required',
            'new_password' => 'required',
            'confirm_password' => 'required|same:new_password',
        ], [
            'old_password.required' => 'Please confirm that the old password field is not left blank.',
            'new_password.required' => 'Please confirm that the new password field is not left blank.',
            'new_password.min' => 'The new password must be at least 8 characters long.',
            'confirm_password.required' => 'Please confirm that the confirm password field is not left blank.',
            'confirm_password.same' => 'Please confirm that the new password and confirm password are the same.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        }

        $user = User::find($checktoken->id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }

        if (!Hash::check($request->old_password, $user->password)) {
            return response()->json([
                'success' => false,
                'message' => 'Your old password does not match.'
            ], 422);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'Password updated successfully.'
        ], 200);
    }

    public function delete_user(Request $request, $id){
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
        $user = User::find($id);
        $user->delete();
        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'User not found'
            ], 404);
        }else{
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.'
            ], 200);
        }
    }

    public function users_orders(Request $request){
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
        $orders = Order::orderBy('id', 'DESC')->paginate(10);

        return response()->json([
            'success' => true,
            'message' => 'Orders details fetch successfull',
            'data' => $orders
        ], 200);
    }

    public function single_order(Request $request, $id){
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
    
        $order = Order::findOrFail($id);
        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Order not found'
            ], 404);
        }else{
            return response()->json([
                'success' => true,
                'message' => 'Order details fetch successfull',
                'data' => $order
            ], 200);
        }
    }

    public function save_user(Request $request, $id){
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

        $order = Order::findOrFail($id);
        $user = User::findOrFail($order->user_id);

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'status' => 'required',
        ],[
            'name.required' => 'Please confirm that the name field is not left blank.',
            'email.required' => 'Please confirm that the email field is not left blank.',
            'email.email' => 'Please confirm that you entered a valid email.',
            'status.required' => 'Please confirm that the status is required for the user.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors' => $validator->errors()
            ], 422);
        };

        $user->name = $request->name;
        $user->email = $request->email;
        $user->status = $request->status;
        $user->save();

        return response()->json([
            'success' => true,
            'message' => 'User details updated successfully.'
        ]);
    }

    public function edit_user_address(Request $request, $id){
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
        $order = Order::findOrFail($id);
        $address = Address::where('id', $order->address_id)->get();
        return response()->json([
            'success' => true,
            'message' => 'address details fetch successfull',
            'address' => $address
        ]);
    }

    public function save_user_address(Request $request, $id){
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
        $order = Order::findOrFail($id);
        $address = Address::findOrFail($order->address_id);
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
            'email.email' => 'Please confirm that the you entered a valid email.',
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
        }else{
            $address->user_id = $order->user_id;
            $address->country = request('country');
            $address->name = request('name');
            $address->mobile = request('mobile');
            $address->pincode = request('pincode');
            $address->address_lane_1 = request('address_lane_1');
            $address->area = request('area');
            $address->landmark = request('landmark');
            $address->town = request('town');
            $address->state = request('state');
            $address->save();
            return response()->json([
                'success' => true,
                'message' => 'Address updated successfully',
                'data' => $address
            ], 200);
        }        
    }

    public function update_order_status(Request $request, $id){
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
        $order = Order::findOrFail($id);
        $order->status = $request->status;
        if($request->status == "cancelled"){
            $order->cancel = '1';
        }else{
            $order->cancel = '0';
        }
        $order->save();
        return response()->json([
            'success' => true,
            'message' => 'Order status updated successfully',
            'data' => $order
        ], 200);
    }

    public function cancel_user_order(Request $request, $id){
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
        $order = Order::findOrFail($id);
        $order->cancel = '1';
        $order->status = 'cancelled';
        $order->save();
        return response()->json([
            'success' => true,
            'message' => 'Order cancelled successfully',
            'data' => $order
        ]);
    }

    public function generate_user_pdf(Request $request){
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

        $user = User::where('role', 'user')->orderBy('id', 'ASC')->get();
        $data = [
            'users' => $user
        ];
    
        $pdfOptions = new Options();
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($pdfOptions);

        $html = view('pdf.sample', $data)->render();

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'landscape');

        $dompdf->render();

        return $dompdf->stream('sample.pdf', [
            'Attachment' => false 
        ]);
    }

    public function generate_order_pdf(Request $request){
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
        
        $order = Order::orderBy('id', 'DESC')->get();
        $data = [
            'orders' => $order
        ];
        $pdfOptions = new Options();
        $pdfOptions->set('isHtml5ParserEnabled', true);
        $pdfOptions->set('isRemoteEnabled', true);

        $dompdf = new Dompdf($pdfOptions);

        $html = view('pdf.order', $data)->render();

        $dompdf->loadHtml($html);

        $dompdf->setPaper('A4', 'portraint');

        $dompdf->render();

        return $dompdf->stream('sample.pdf', [
            'Attachment' => false 
        ]);
    }

    public function insert_user(Request $request) {
        try {
            $request->validate([
                'csvFile' => 'required',
            ],[
                'csvFile.required' => 'Kindly choose a CSV or an Excel file to import user data.',
            ]);
    
            if ($request->hasFile('csvFile')) {
                $file = $request->file('csvFile');
                $filePath = $file->getRealPath();
                $fileExtension = $file->getClientOriginalExtension();
    
                if ($fileExtension == 'csv') {
    
                    $csvData = array_map('str_getcsv', file($filePath));
                    $headers = array_shift($csvData);
    
                    $existingUsers = [];
                    $newUsers = [];
    
                    foreach ($csvData as $row) {
                        if (count($row) < 3) {
                            return response()->json(['success' => false, 'message' => 'Invalid CSV file format. Each row should have at least 3 columns.'], 422);
                        }
    
                        $email = $row[1];
                        if (User::where('email', $email)->exists()) {
                            $existingUsers[] = $email;
                        } else {
                            $user = new User();
                            $user->name = $row[0];
                            $user->email = $email;
                            $user->password = Hash::make($row[2]);
                            $user->remember_token = Str::random(40);
                            $newUsers[] = $user;
                        }
                    }
    
                    $existingCount = count($existingUsers);
                    $newCount = count($newUsers);
    
                    foreach ($newUsers as $user) {
                        if (!$user->save()) {
                            return response()->json(['success' => false, 'message' => 'Failed to save user data to the database.'], 500);
                        }
                    }
    
                    if ($existingCount > 0) {
                        $errorMessage = "The following $existingCount emails already exist: ". implode('|| ', $existingUsers);
                        return response()->json(['success' => false, 'message' => $errorMessage], 422);
                    }
    
                    $successMessage = "Successfully imported $newCount new users.";
                    return response()->json(['success' => true, 'message' => $successMessage], 200);
    
                } elseif ($fileExtension == 'xls' || $fileExtension == 'xlsx') {
                    try {
                        $reader = new \PhpOffice\PhpSpreadsheet\Reader\Xlsx();
                        $spreadsheet = $reader->load($filePath);
                        $sheet = $spreadsheet->getActiveSheet();
                    
                        $existingUsers = [];
                        $newUsers = [];
                    
                        $rowIterator = $sheet->getRowIterator();
                        $rowNum = 1;
                        foreach ($rowIterator as $row) {
                            if ($rowNum > 1) {
                                $cellIterator = $row->getCellIterator();
                                $cells = [];
                                foreach ($cellIterator as $cell) {
                                    $cells[] = $cell->getValue();
                                }
                    
                                if (count($cells) < 3) {
                                    return response()->json(['success' => false, 'message' => 'Invalid Excel file format. Each row should have at least 3 columns.'], 422);
                                }
                    
                                $email = $cells[1];
                                if (User::where('email', $email)->exists()) {
                                    $existingUsers[] = $email;
                                } else {
                                    $user = new User();
                                    $user->name = $cells[0];
                                    $user->email = $email;
                                    $user->password = Hash::make($cells[2]);
                                    $user->remember_token = Str::random(40);
                                    $newUsers[] = $user;
                                }
                            }
                            $rowNum++;
                        }
                    
                        $existingCount = count($existingUsers);
                        $newCount = count($newUsers);
                    
                        foreach ($newUsers as $user) {
                            if (!$user->save()) {
                                return response()->json(['success' => false, 'message' => 'Failed to save user data to the database.'], 500);
                            }
                        }
                    
                        if ($existingCount > 0) {
                            $errorMessage = "The following $existingCount emails already exist: ". implode('|| ', $existingUsers);
                            return response()->json(['success' => false, 'message' => $errorMessage], 422);
                        }
                    
                        $successMessage = "Successfully imported $newCount new users from Excel file.";
                        return response()->json(['success' => true, 'message' => $successMessage], 200);
                    
                    } catch (\PhpOffice\PhpSpreadsheet\Reader\Exception $e) {
                        return response()->json(['success' => false, 'message' => 'The Excel file appears to be corrupted and cannot be read.'], 422);
                    }
    
                } else {
                    return response()->json(['success' => false, 'message' => 'Invalid file type. Only CSV, XLS, and XLSX files are allowed.'], 422);
                }
    
            } else {
                return response()->json(['success' => false, 'message' => 'No file selected!'], 422);
            }
    
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => $e->getMessage()], 500);
        }
    }

    public function export_csv(Request $request){
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
        $users = User::where('role', 'user')->get();

        $csv = Writer::createFromFileObject(new \SplTempFileObject());
    
        $csv->insertOne(array('ID', 'Name', 'Email', 'Status', 'Email_verification', 'Role', 'Created_at', 'Updated_at'));
    
        foreach ($users as $user) {
            $status = $user->status == 0 ? 'Inactive' : 'Active';
            $email_verification = $user->email_verify == 1 ? 'Verified' : 'Not-Verified';
            $created_at = $user->created_at->format('d M, Y H:i:s A');
            $updated_at = $user->updated_at->format('d M, Y H:i:s');

            $csv->insertOne(array($user->name, $user->email, $status, $email_verification, $user->role, $created_at, $updated_at));
        }
    
        $headers = array(
            "Content-Type" => "text/csv",
            "Content-Disposition" => "attachment; filename=usersdata.csv",
        );
    
        return response()->make($csv->getContent(), 200, $headers);
        
    }

    public function export_excel(Request $request){
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
    
        $users = User::where('role', 'user')->get();
    
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
    
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Status');
        $sheet->setCellValue('E1', 'Email Verification');
        $sheet->setCellValue('F1', 'Role');
        $sheet->setCellValue('G1', 'Created At');
        $sheet->setCellValue('H1', 'Updated At');
    
        $row = 2;
        foreach ($users as $user) {
            $status = $user->status == 0 ? 'Inactive' : 'Active';
            $emailVerification = $user->email_verify == 1 ? 'Verified' : 'Not Verified';
            
            $sheet->setCellValue('A' . $row, $user->id);
            $sheet->setCellValue('B' . $row, $user->name);
            $sheet->setCellValue('C' . $row, $user->email);
            $sheet->setCellValue('D' . $row, $status);
            $sheet->setCellValue('E' . $row, $emailVerification);
            $sheet->setCellValue('F' . $row, $user->role);
    
            $sheet->setCellValue('G' . $row, $user->created_at ? $user->created_at->format('d M, Y H:i:s A') : '');
            $sheet->setCellValue('H' . $row, $user->updated_at ? $user->updated_at->format('d M, Y H:i:s A') : '');
    
            $row++;
        }
    
        $writer = new Xlsx($spreadsheet);
        $fileName = 'usersdatain.xlsx';
    
        $tempFilePath = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($tempFilePath);
    
        return response()->download($tempFilePath, $fileName)->deleteFileAfterSend(true);
    }
    
}
