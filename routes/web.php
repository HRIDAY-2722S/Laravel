<?php

use App\Http\Controllers\QuotationController;
use App\Http\Controllers\GoogleAuthController;
use App\Http\Controllers\FacebookAuthController;
use App\Http\Controllers\LinkedinAuthController;
use App\Http\Middleware\Adminsessioncheck;
use App\Http\Middleware\Checklogin;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PdfController;
use App\Http\Middleware\Authenticateusersession;


Route::get('/logout', [LoginController::class, 'logout'])->name('logout');

Route::middleware(['middleware' => Checklogin::class])->group(function () {
    Route::get('/', [LoginController::class, 'login'])->name('login');
    Route::post('/', [LoginController::class, 'userlogin'])->name('userlogin');
    Route::get('/forgot-password', [LoginController::class, 'forgotpassword'])->name('forgotpassword');
    Route::post('/forgot-password', [LoginController::class, 'forgot_password'])->name('forgot_password');

    Route::post('/generate-otp', [LoginController::class, 'generate_otp'])->name('generate_otp');
    Route::get('/register', [LoginController::class, 'register'])->name('register');
    Route::post('/register', [LoginController::class, 'userregister'])->name('userregister');

    Route::get('/resetpassword/{token}', [LoginController::class, 'resetpassword'])->name('resetpassword');
    Route::post('/resetpassword/{token}', [LoginController::class, 'reset_password'])->name('reset_password');

    Route::get('/google', [GoogleAuthController::class, 'redirect'])->name('register_google');
    Route::get('/google/call-back', [GoogleAuthController::class, 'callbackGoogle']);


    Route::get('/facebook', [FacebookAuthController::class, 'redirect'])->name('register_facebook');
    Route::get('/facebook/call-back', [FacebookAuthController::class, 'callbackFacebook']);


    Route::get('/linkedin', [LinkedinAuthController::class, 'redirect'])->name('register_with_linkedin');
    Route::get('/linkedin/call-back', [LinkedinAuthController::class, 'callbackLinkedin']);

});

Route::group(['prefix' => 'users', 'middleware' => Authenticateusersession::class], function () {
    Route::get('/', [UsersController::class, 'dashboard'])->name('usersdashboard');
    Route::get('/profile', [UsersController::class, 'profile'])->name('userprofile');
    Route::post('/profile/update_user_profile', [UsersController::class, 'update_user_profile'])->name('update_user_profile');
    Route::post('/profile/update_user_password', [UsersController::class, 'update_user_password'])->name('update_user_password');
    Route::delete('/profile/delete_user_account', [UsersController::class, 'delete_user_account'])->name('delete_user_account');
    Route::get('/products', [UsersController::class, 'products'])->name('usersproducts');
    Route::get('/single_products/{id}', [UsersController::class, 'singleproducts'])->name('single_products');
    Route::get('/cart', [UsersController::class, 'cart'])->name('userscart');
    Route::post('/cart', [UsersController::class, 'updateCart'])->name('updatecart');
    Route::post('/cart/add-to-cart', [UsersController::class, 'addToCart'])->name('add_to_cart');
    Route::post('/cart/delete', [UsersController::class, 'deleteCartItem'])->name('deletecartitem');
    Route::get('/orders', [UsersController::class, 'orders'])->name('usersorders');

    Route::get('/search', [UsersController::class, 'search'])->name('usersearch');
    Route::get('/cart/autocomplete', [UsersController::class, 'autocomplete'])->name('autocomplete');
    Route::get('/checkout', [UsersController::class, 'checkout'])->name('checkout');

    Route::get('/get-address', [UsersController::class, 'get_address'])->name('get_address');
    Route::get('/edit-address/{id}', [UsersController::class, 'edit_address'])->name('edit_address');
    Route::post('/update-address/{id}', [UsersController::class, 'update_address'])->name('update_address');
    Route::get('/add-new-address', [UsersController::class, 'add_new_address'])->name('add_new_address');
    Route::post('/add-new-address', [UsersController::class, 'add_address'])->name('add_address');
    Route::post('/place-order', [UsersController::class, 'place_order'])->name('place_order');
    Route::get('/order-details/{id}', [UsersController::class, 'order_details'])->name('order_details');
    Route::get('/cancel-order/{id}', [UsersController::class, 'cancel_order'])->name('cancel_order');


    Route::get('/quotations/check', [QuotationController::class, 'checkExistingQuotation'])->name('check_existing_quotation');
    Route::post('/quotations/create', [QuotationController::class, 'createQuotation'])->name('create_quotation');
    Route::post('/quotations/add-to', [QuotationController::class, 'addToQuotation'])->name('add_to_quotation');

    Route::get('/quotations', [QuotationController::class, 'quotations'])->name('quotations');
    Route::post('quotations/{id}', [QuotationController::class, 'update_quotation_name'])->name('update_quotation_name');
    Route::delete('quotations/{id}', [QuotationController::class, 'delete_quotation'])->name('delete_quotation');
    Route::get('quotations-products/{id}', [QuotationController::class, 'show_quotation_products'])->name('show_quotation_products');
    Route::post('update-quotations-products-quantity', [QuotationController::class, 'update_quotations_products_quantity'])->name('update_quotations_products_quantity');
    Route::delete('delete-quotation-product/{id}', [QuotationController::class, 'delete_quotation_product'])->name('delete_quotation_product');
    Route::post('add-products-to-cart', [QuotationController::class, 'add_products_to_cart'])->name('add_products_to_cart');

    Route::post('apply-coupon', [UsersController::class, 'apply_coupon'])->name('apply_coupon');



});

Route::group(['prefix' => 'admin', 'middleware' => Adminsessioncheck::class], function () {

    Route::get('test', [AdminController::class, 'test'])->name('test');


    Route::get('/user-quotations', [QuotationController::class, 'user_quotations'])->name('user_quotations');
    Route::post('user-quotations', [QuotationController::class, 'updatequotationname'])->name('updatequotationname');
    Route::delete('user-quotations/{id}', [QuotationController::class, 'deletequotation'])->name('deletequotation');
    Route::get('quotations-products/{id}', [QuotationController::class, 'showquotationproducts'])->name('showquotationproducts');
    Route::post('update-quotations-products-quantity', [QuotationController::class, 'updatequotationsproductsquantity'])->name('updatequotationsproductsquantity');
    Route::delete('delete-quotation-product/{id}', [QuotationController::class, 'deletequotationproduct'])->name('deletequotationproduct');


    Route::get('coupons', [AdminController::class, 'coupons'])->name('coupons');
    Route::post('add-coupons', [AdminController::class, 'add_coupons'])->name('add_coupons');
    Route::delete('delete-coupon/{id}', [AdminController::class, 'delete_coupon'])->name('delete_coupon');


    Route::get('/', [AdminController::class, 'dashboard'])->name('admindashboard');
    Route::get('/products', [AdminController::class, 'products'])->name('products');
    Route::get('/add_products', [AdminController::class, 'add_products'])->name('add_products');
    Route::post('/add_products', [AdminController::class, 'add_products_details'])->name('add_products_details');
    Route::get('/singleproducts/{id}', [AdminController::class, 'singleproducts'])->name('singleproducts');
    Route::get('/edit_product/{id}', [AdminController::class, 'edit_product'])->name('edit_product');
    Route::post('/edit_product/{id}', [AdminController::class, 'update_product'])->name('update_product');
    Route::delete('/edit_product/{id}', [AdminController::class, 'delete_product'])->name('delete_product');
    Route::get('/users', [AdminController::class, 'users'])->name('users');
    Route::get('/getUsers', [AdminController::class, 'getUsers'])->name('getUsers');
    Route::post('/user/edit', [AdminController::class, 'update_user'])->name('updateuser');
    Route::post('/user/edit/email', [AdminController::class, 'update_user_email'])->name('updateuseremail');
    Route::post('/user/edit/status', [AdminController::class, 'update_user_status'])->name('update_user_status');
    Route::post('/user/delete', [AdminController::class, 'delete_user_fromtable'])->name('delete_user_fromtable');
    Route::get('/profile', [AdminController::class, 'profile'])->name('adminprofile');
    Route::get('/users/active/{id}/{page}', [AdminController::class, 'active'])->name('active');
    Route::get('/users/inactive/{id}/{page}', [AdminController::class, 'inactive'])->name('inactive');
    Route::post('/profile/update_profile', [AdminController::class, 'update_profile'])->name('update_profile');
    Route::post('/profile/update_password', [AdminController::class, 'update_password'])->name('update_password');
    Route::delete('/profile/delete_account', [AdminController::class, 'delete_account'])->name('delete_account');
    Route::delete('/users/{id}', [AdminController::class, 'delete_user'])->name('delete_user');
    Route::get('/orders', [AdminController::class, 'users_orders'])->name('users_orders');
    Route::get('/orders-details/{id}', [AdminController::class, 'single_order'])->name('single_order');
    Route::post('/orders-details/{id}', [AdminController::class, 'save_user'])->name('save_user');
    Route::get('/edit-user-address/{id}', [AdminController::class, 'edit_user_address'])->name('edit_user_address');
    Route::post('/edit-user-address/{id}', [AdminController::class, 'save_user_address'])->name('save_user_address');
    Route::post('/orders-details', [AdminController::class, 'update_order_status'])->name('update_order_status');
    Route::post('/cancel-order', [AdminController::class, 'cancel_user_order'])->name('cancel_user_order');
    // Route::post('/users_details/{id}', [AdminController::class, 'users_details'])->name('users_details');
    Route::get('/generate-user-pdf', [PdfController::class, 'generate_user_pdf'])->name('generate_user_pdf');
    Route::get('/generate-order-pdf', [PdfController::class, 'generate_order_pdf'])->name('generate_order_pdf');
    Route::get('/import-csv', [PdfController::class, 'import_csv'])->name('import_csv');
    Route::post('/import-csv', [PdfController::class, 'insert_user'])->name('insert_user');
    Route::get('/export-csv', [PdfController::class, 'export_csv'])->name('export_csv');
    Route::get('/export-excel', [PdfController::class, 'export_excel'])->name('export_excel');

    Route::get('/download/csv', function () {
        $file_path = public_path('downloads/user.csv');
        $file_name = 'users.csv';
    
        if (file_exists($file_path) && is_readable($file_path)) {
            return response()->download($file_path, $file_name);
        } else {
            abort(404, 'File not found');
        }
    })->name('download_sample_csv');
    
    Route::get('/download/excel', function () {
        $file_path = public_path('downloads/Spreadsheet.xlsx');
        $file_name = 'Spreadsheet.xlsx';
    
        if (file_exists($file_path) && is_readable($file_path)) {
            return response()->download($file_path, $file_name);
        } else {
            abort(404, 'File not found');
        }
    })->name('download_sample_excel');


    

    
});