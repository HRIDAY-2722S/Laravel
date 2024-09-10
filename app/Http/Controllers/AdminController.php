<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use App\Models\Order;
use App\Models\User;
use App\Models\Address;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    public function dashboard(){
        $totalUsers = User::where('role', 'user')->count();
        $activeUsers = User::where('status', '1')->where('role', 'user')->count();
        $inactiveUsers = User::where('status', '0')->where('role', 'user')->count();
        $totalproducts = Product::all()->count();
        $totalorder = Order::all()->count();

        return view('admin/dashboard',['totalUsers' => $totalUsers, 'activeUsers' => $activeUsers, 'inactiveUsers' => $inactiveUsers, 'totalproducts' => $totalproducts, 'totalorder' => $totalorder]);
    }

    public function products(){
        $products = Product::paginate(20);
        return view('admin/products', ['products' => $products])->with('paginator', $products);
    }

    public function singleproducts($id){
        $products = Product::where('id', $id)->get();
        return view('admin/single_products', ['products' => $products]);
    }

    public function edit_product($id){
        $products = Product::where('id', $id)->get();
        return view('admin/edit_product', ['products' => $products]);
    }

    public function update_product(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required',
        ], [
            'name.required' => 'Product Name is required',
        ]);        

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        } else {
            $product = Product::find($id);
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;

            if ($request->hasFile('images')) {
                $images = $request->file('images');
                $imageNames = [];

                foreach ($images as $image) {
                    
                    $mimeType = $image->getClientMimeType();

                    if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif'])) {
                        return redirect()->back()->withErrors(['images' => 'Only JPEG, PNG, and GIF images are allowed.'])->withInput();
                    }
                    $imageName = time() . '_' . $image->getClientOriginalName();
                    $image->move(public_path('products_image'), $imageName);
                    $imageNames[] = $imageName;
                }

                $productImage = ProductImage::firstOrNew(['product_id' => $id]);

                $productImage->product_id = $id;
                $productImage->images = implode(',', $imageNames);
                $productImage->save();

                if (isset($images[0])) {
                    $imageName = time() . '_' . $images[0]->getClientOriginalName();
                    $product->image = $imageName;
                }
            }
            $product->save();
        }
        return redirect()->back()->with('status', 'Product Update successful');
    }

    public function delete_product($id){
        $product = Product::find($id);
        $product->delete();
        return redirect()->route('products')->with('status', 'Product Delete successful');
    }

    public function add_products(){
        return view('admin/add_products');
    }

    public function add_products_details(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'description' => 'required',
            'price' => 'required|numeric',
            'images' => 'required',
        ], [
            'name.required' => 'Please provide the name of the product.',
            'description.required' => 'Please provide a description for the product.',
            'price.required' => 'Please provide the price of the product.',
            'images.required' => 'Please ensure that you provide at least one product image, and you can choose multiple images for a single product.',
        ]);        

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $product = new Product();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            

            $images = $request->file('images');

            $imagePaths = [];

            foreach ($images as $image) {
                $mimeType = $image->getMimeType();
                if (!in_array($mimeType, ['image/jpeg', 'image/png', 'image/gif'])) {
                    return redirect()->back()->withErrors(['images' => 'Invalid image type. Only JPEG, PNG and gif are allowed.'])->withInput();
                }

                $imageName = time() . '-' . $image->getClientOriginalName();

                $imagePath = $image->move(public_path('products_image'), $imageName);

                $imagePaths[] = $imageName;
            }

            $product->image = $imagePaths[0];
            $product->save();
            $productId = $product->id;

            $productImage = new ProductImage();

            $productImage->product_id = $productId;
            $productImage->images = implode(',', $imagePaths);
            $productImage->save();
        }
        return redirect()->back()->with('success', 'Product created successfully.');
    }

    public function getUsers(Request $request)
    {
        $search = $request->search['value'];
        $orderbycolumn = ['id', '', 'name', 'email', '', ''];
        $order = $request->order[0]['column'];
        $orderdirection = $request->order[0]['dir'];

        $users = User::where('role', 'user')
        ->when($search, function($query) use ($search){
            $query->where(function($where) use ($search) {
                $where->where('name','like',"%$search%")->orWhere('email','like',"%$search%");
            })->where('role', 'user');
        })
        ->orderBy($orderbycolumn[$order], $orderdirection);

        $total = $users->count();
        $items = $users->skip($request->start)->take($request->length)->get();

        return response()->json([
            'draw' => $request->draw,
            'recordsTotal' => $total,
            'recordsFiltered' => $total,
            'data' => $items,
        ]);
    }

    public function update_user(Request $request)
    {
        $user = User::findOrFail($request->id);
        $user->name = $request->name;
    
        $user->save();
    
        return response()->json(["message" => "Updated successfully"]);
    }

    public function update_user_email(Request $request){

        $user = User::findOrFail($request->id);
        $user->email = $request->email;
    
        $user->save();
    
        return response()->json(["message" => "Updated successfully"]);
    }

    public function update_user_status(Request $request){
        $user = User::findOrFail($request->id);
        $user->status = $request->status;
        $user->save();

        return response()->json(["message" => "Update successfull"]);
    }
    

    public function delete_user_fromtable(Request $request){
        $user = User::findOrFail($request->id);
        $user->delete();
        return response()->json(["message" => "Deleted successfully"]);
    }

    public function users(){
        // $user = User::where('role', 'user')->orderBy('id', 'DESC')->paginate(10);
        // return view('admin.users', ['users' => $user])->with('paginator', $user);

        // if (request()->ajax()) {
        //     $users = User::select('id', 'profile_picture', 'name', 'email', 'status');
        //     return DataTables::of($users)->make(true);
        // }

        // return view('admin.users');

        $users = User::select('id', 'profile_picture', 'name', 'email', 'status')->get();

        return view('admin/users', ['data' => $users]);
    }

    public function active($id, $page){
        $user = User::find($id);
        $user->status = 1;
        $user->save();
        return redirect()->route('users', ['page' => $page])->with('success', 'User activated successfully.');
    }

    public function inactive($id, $page){
        $user = User::find($id);
        $user->status = 0;
        $user->save();
        return redirect()->route('users', ['page' => $page])->with('success', 'User activated successfully.');
    }

    public function delete_user($id){
        $user = User::find($id);
        $user->delete();
        return redirect()->back()->with('success', 'User deleted successfully.');
    }

    public function profile(){
        $user = User::where('id', session('id'))->get();
        return view('admin/profile', ['users' => $user]);
    }

    public function update_profile(Request $request){
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
        ], [
            'name.required' => 'Please confirm that the name field is not left blank.',
            'email.required' => 'Please confirm that the email field is not left blank.',
            'email.email' => 'Could you please provide the correct email address?',
        ]);        

        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $user = User::where('id', session('id'))->first();

            $user->name = $request->name;
            $user->email = $request->email;

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

    public function update_password(Request $request){
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

    public function delete_account(){
        $user = User::where('id', session('id'))->first();
        $user->delete();
        session()->flush();
        return redirect()->route('login')->with('success', 'Your account has been deleted successfully.');
    }

    public function users_orders(){
        $order = Order::orderBy('id', 'DESC')->paginate(10);
        return view('admin/orders',['orders' => $order])->with('paginator', $order);
    }

    public function single_order($id){
        $order = Order::findOrFail($id);
        return view('admin/single_order',['order' => $order]);
    }

    public function save_user(Request $request, $id){
        $order = Order::findOrFail($id);
        $user = User::findOrFail($order->user_id);
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'email' => 'required|email',
            'status' => 'required',
        ],[
            'name.required' => 'Please confirm that the name field is not left blank.',
            'email.required' => 'Please confirm that the email field is not left blank.',
            'email.email' => 'Please confirm that the you entered a valid email.',
            'status.required' => 'Please confirm that the status is required for the user.',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }else{
            $user->name = $request->name;
            $user->email = $request->email;
            $user->status = $request->status;
            $user->save();
            return redirect()->back()->with('success', 'User details have been successfully updated.');
        }
                        
    }

    public function edit_user_address($id){
        $order = Order::findOrFail($id);
        $address = Address::where('id', $order->address_id)->get();
        return view('admin/edit_user_address',['addresses' => $address, 'order' => $order]);
    }

    public function save_user_address(Request $request, $id){
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
            return redirect()->back()->withErrors($validator)->withInput();
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
            return redirect()->route('single_order', ['id' => $order->id])->with('success', 'The address for the user has been updated successfully.');
        }        
    }

    public function update_order_status(Request $request){
        $order = Order::findOrFail($request->order_id);
        $order->status = $request->status;
        if($request->status == "cancelled"){
            $order->cancel = '1';
        }else{
            $order->cancel = '0';
        }
        $order->save();
        return redirect()->back()->with('success', 'The order status has been updated successfully.');
    }

    public function cancel_user_order(Request $request){
        $order = Order::findOrFail($request->order_id);
        $order->cancel = '1';
        $order->status = 'cancelled';
        $order->save();
        return redirect()->back()->with('success', 'The order has been cancelled successfully.');
    }

    public function coupons(){

        $coupons = Coupon::orderBy('id', 'DESC')->get();
        return view('admin/coupons', ['coupons' => $coupons]);
    }

    public function add_coupons(Request $request){

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'code' => 'required',
            'discount' => 'required|numeric',
            'upto' => 'required|numeric',
        ]);
        if ($validator->fails()) {
            return redirect()->back()->withErrors($validator)->withInput();
        }

        $existingCoupon = Coupon::where('code', $request->code)->first();

        if ($existingCoupon) {
            return redirect()->back()->withErrors(['code' => 'The coupon code already exists.'])->withInput();
        }

        $coupon = new Coupon();
        $coupon->name = $request->name;
        $coupon->code = $request->code;
        $coupon->discount = $request->discount;
        $coupon->upto = $request->upto;
        $coupon->save();
        return redirect()->back()->with('success', 'The coupon has been added successfully.');
    }

    public function delete_coupon($id){
        $coupon = Coupon::find($id);
        $coupon->delete();
        return redirect()->back()->with('success', 'The coupon has been deleted successfully.');
    }

    public function test(){
        return view('admin/test');
    }
    
    
}
