<?php

namespace App\Http\Controllers;

use App\Models\cart;
use App\Models\Product;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use App\Models\Quotation;
use App\Models\QuotationProduct;

class QuotationController extends Controller
{

    public function checkExistingQuotation(Request $request)
    {
        $userId = session('id');
        $quotations = Quotation::where('user_id', $userId)->get();
        return response()->json(['quotations' => $quotations]);
    }

    public function createQuotation(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
        ]);

        $exsists = Quotation::where('name', $request->name)->where('user_id', session('id'))->exists();        
        if ($exsists) {
            return response()->json(['message' => 'Quotation already exists'], 400);
        }else{
            $userId = session('id');
            $quotation = Quotation::create([
                'user_id' => $userId,
                'name' => $request->name
            ]);
            if($quotation){
                $quotations_product =  new QuotationProduct;
                $quotations_product->quotation_id = $quotation->id;
                $quotations_product->user_id = session('id');
                $quotations_product->product_id = $request->product_id;
                $quotations_product->price = $request->price;
                $quotations_product->quantity = 1;
                $quotations_product->subtotal = $request->price;
                $quotations_product->total = $request->price * 1;
                $quotations_product->save();
            }


            return response()->json($quotation);
        }
    }

    public function addToQuotation(Request $request)
    {
        $request->validate([
            'quotation_id' => 'required|integer',
            'product_id' => 'required|integer',
            'price' => 'required|numeric',
        ]);

        $quotationId = $request->quotation_id;
        $productId = $request->product_id;
        $price = $request->price;

        $quotationProduct = QuotationProduct::where('quotation_id', $quotationId)
        ->where('product_id', $productId)
        ->first();

        if ($quotationProduct) {
            // Product already exists within the same quotation, increase the quantity
            $quotationProduct->quantity += 1;
            $quotationProduct->subtotal = $quotationProduct->price * $quotationProduct->quantity;
            $quotationProduct->total = $quotationProduct->subtotal;
            $quotationProduct->save();
        } else {

            $quantity = 1; 
            $subtotal = $price * $quantity;
            $total = $subtotal; 

            $quotationProduct = QuotationProduct::create([
                'user_id' => session('id'),
                'quotation_id' => $quotationId,
                'product_id' => $productId,
                'price' => $price,
                'quantity' => $quantity,
                'subtotal' => $subtotal,
                'total' => $total,
            ]);
        }

        // $quotation = Quotation::find($quotationId);
        // if ($quotation) {
        //     $quotation->total += $total;
        //     $quotation->save();
        // }

        return response()->json(['success' => true]);
    }
    
    public function quotations(){
        $quotations = Quotation::where('user_id', session('id'))->orderBy('id', 'DESC')->get();

        return view('users/quotations', ['quotations' => $quotations]);
    }

    public function update_quotation_name(Request $request, $id){

        $quotation = Quotation::find($id);

        $existingQuotation = Quotation::where('user_id', session('id'))->where('name', $request->name)->where('id', '!=', $id)->first();

        if ($existingQuotation) {
            return response()->json(['message' => 'Quotation with this name already exists.'], 400);
        }
        
        $quotation->name = $request->name;
        $quotation->save();

        return redirect()->back()->with('success' , 'Name updated successfull');
        
    }

    public function delete_quotation($id){
        
        $quotation = Quotation::find($id);
        
        if ($quotation) {

            QuotationProduct::where('quotation_id', $id)->delete();
            
            $quotation->delete();
    
            return redirect()->back()->with('success', 'Quotation deleted successfully.');
        }
    
        return redirect()->back()->with('error', 'Quotation not found.');
    }

    public function show_quotation_products($id){
        $quotation = Quotation::find($id);

        if($quotation){
            $quotationproducts = QuotationProduct::where('quotation_id', $id)->get();

            return view('users/showquotations', ['quotationproducts' => $quotationproducts]);
        }
    }
    
    public function update_quotations_products_quantity(Request $request)
    {

        $quantities = $request->input('quantities');    

        try {
            foreach ($request->quantities as $item) {
                $quotationProduct = QuotationProduct::find($item['id']);
                if ($quotationProduct) {
                    $quotationProduct->quantity = $item['quantity'];
                    
                    $quotationProduct->subtotal = $quotationProduct->price * $item['quantity'];
                    $subtotal = $quotationProduct->price * $item['quantity'];

                    $discount = 0;

                    if ($item['quantity'] >= 9 && $item['quantity'] <= 14) {
                        $discount = 0.025 * $subtotal;
                    } else if ($item['quantity'] >= 15 && $item['quantity'] <= 20) {
                        $discount = 0.035 * $subtotal;
                    } else if ($item['quantity'] > 20) {
                        $discount = 0.05 * $subtotal;
                    }
                    $quotationProduct->total = $subtotal - $discount;
                    
                    $quotationProduct->save();
                }
            }
    
            return response()->json(['success' => $subtotal - $discount]);

        } catch (\Exception $e) {

            return response()->json(['message' => 'An error occurred while updating quantities.', 'error' => $e->getMessage()], 500);

        }
    }

    public function delete_quotation_product($id)
    {
        $quotationProduct = QuotationProduct::find($id);
    
        if ($quotationProduct) {
            $quotationId = $quotationProduct->quotation_id;
            $remainingProductsCount = QuotationProduct::where('quotation_id', $quotationId)->count();
    
            $quotationProduct->delete();
    
            if ($remainingProductsCount == 1) {
                $name = Quotation::find($quotationId);
                return redirect()->route('quotations')->with('success', 'All items have been removed from quotation '.$name->name);
            }
    
            return redirect()->back()->with('success', 'Product deleted successfully.');
        }
    
        return redirect()->back()->with('error', 'Product not found.');
    }

    public function add_products_to_cart(Request $request)
    {
        $request->validate([
            'quotation_id' => 'required|integer|exists:quotations,id',
        ]);
    
        $quotationId = $request->input('quotation_id');
    
        DB::beginTransaction();
    
        try {
            Cart::where('user_id', session('id'))->delete();
    
            $quotationProducts = DB::table('quotation_products')
                ->where('quotation_id', $quotationId)
                ->get();
    
            foreach ($quotationProducts as $quotationProduct) {

                $product = DB::table('products')->where('id', $quotationProduct->product_id)->first();
    

                $price = $product->price;
                $quantity = $quotationProduct->quantity;
                $subtotal = $price * $quantity;
    
                $discount = 0;
                if ($quantity >= 9 && $quantity <= 14) {
                    $discount = 0.025;
                } elseif ($quantity >= 15 && $quantity <= 20) {
                    $discount = 0.035;
                } elseif ($quantity > 20) {
                    $discount = 0.05;
                }
                $total = $subtotal - ($subtotal * $discount);
    

                Cart::create([
                    'user_id' => session('id'),
                    'product_id' => $product->id,
                    'quantity' => $quantity,
                    'price' => $price,
                    'subtotal' => $subtotal,
                    'tax' => '0.00',
                    'total' => $total,
                ]);
            }
    

            DB::commit();
    
            return response()->json(['success' => 'Products added to cart successfully.']);
        } catch (\Exception $e) {

            DB::rollback();
    
            return response()->json(['message' => 'An error occurred while updating quantities.', 'error' => $e->getMessage()], 500);
        }
    }
    
    public function user_quotations(){

        $quotations = Quotation::orderBy('id', 'DESC')->get();

        return view('admin/quotations', ['quotations' => $quotations]);
    }

    public function updatequotationname(Request $request){
        $id = $request->id;
        $name = $request->name;
        $quotation = Quotation::find($id);

        $existingQuotation = Quotation::where('user_id', $request->userid)->where('name', $request->name)->where('id', '!=', $id)->first();

        if ($existingQuotation) {
            return response()->json(['message' => 'Quotation with this name already exists.'], 400);
        }
        
        $quotation->name = $request->name;
        $quotation->save();

        return redirect()->back()->with('success' , 'Name updated successfull');
        
    }

    public function deletequotation($id){

        $quotation = Quotation::find($id);
        $quotation->delete();
        return redirect()->back()->with('success' , 'Quotation deleted successfull');
    
    }

    public function showquotationproducts($id){
        $quotationproducts = QuotationProduct::where('quotation_id', $id)->get();
        
        return view('admin/quotations-produt', ['quotationproducts' => $quotationproducts]);
    }
    
    public function updatequotationsproductsquantity(Request $request){

        $quantities = $request->input('quantities');    

        try {
            foreach ($request->quantities as $item) {
                $quotationProduct = QuotationProduct::find($item['id']);
                if ($quotationProduct) {
                    $quotationProduct->quantity = $item['quantity'];
                    
                    $quotationProduct->subtotal = $quotationProduct->price * $item['quantity'];
                    $subtotal = $quotationProduct->price * $item['quantity'];

                    $discount = 0;

                    if ($item['quantity'] >= 9 && $item['quantity'] <= 14) {
                        $discount = 0.025 * $subtotal;
                    } else if ($item['quantity'] >= 15 && $item['quantity'] <= 20) {
                        $discount = 0.035 * $subtotal;
                    } else if ($item['quantity'] > 20) {
                        $discount = 0.05 * $subtotal;
                    }
                    $quotationProduct->total = $subtotal - $discount;
                    
                    $quotationProduct->save();
                }
            }
    
            return response()->json(['success' => $subtotal - $discount]);

        } catch (\Exception $e) {

            return response()->json(['message' => 'An error occurred while updating quantities.', 'error' => $e->getMessage()], 500);

        }

    }

    public function deletequotationproduct($id){
        
        $quotationProduct = QuotationProduct::find($id);
    
        if ($quotationProduct) {
            $quotationId = $quotationProduct->quotation_id;
            $remainingProductsCount = QuotationProduct::where('quotation_id', $quotationId)->count();
    
            $quotationProduct->delete();
    
            if ($remainingProductsCount == 1) {
                $name = Quotation::find($quotationId);
                return redirect()->route('user_quotations')->with('success', 'All items have been removed from quotation '.$name->name);
            }
    
            return redirect()->back()->with('success', 'Product deleted successfully.');
        }
    
        return redirect()->back()->with('error', 'Product not found.');

    }
}
