<?php

use App\Http\Controllers\API\ApiController;
use App\Http\Controllers\API\AdminController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::group(['prefix' => 'v1/users'], function () {
    route::get('products', [ApiController::class, 'products']);
    route::get('single-products/{id}', [ApiController::class, 'single_products']);
    route::get('orders', [ApiController::class, 'orders']);
    route::get('orders-details/{id}', [ApiController::class, 'single_orders']);
    route::get('cancel-order/{id}', [ApiController::class, 'cancel_order']);
    route::get('profile', [ApiController::class, 'profile']);
    Route::post('profile/update-user-profile', [ApiController::class, 'update_user_profile']);
    Route::post('profile/update-user-password', [ApiController::class, 'update_user_password']);
    Route::post('add-to-cart', [ApiController::class, 'addtocart']);
    route::get('quotations/check', [ApiController::class, 'checkExistingQuotation']);
    route::post('quotations/create', [ApiController::class, 'createQuotation']);
    route::post('quotations/add-to', [ApiController::class, 'addToQuotation']);
    route::get('quotations', [ApiController::class, 'quotations']);
    route::post('quotations/{id}', [ApiController::class, 'update_quotation_name']);
    Route::delete('quotations/{id}', [ApiController::class, 'delete_quotation']);
    Route::get('quotations-products/{id}', [ApiController::class, 'show_quotation_products']);
    Route::delete('delete-quotation-product/{id}', [ApiController::class, 'delete_quotation_product']);
    Route::get('checkout', [ApiController::class, 'checkout']);
    Route::get('/edit-address/{id}', [ApiController::class, 'edit_address']);
    Route::post('/update-address/{id}', [ApiController::class, 'update_address']);
    Route::post('/add-new-address', [ApiController::class, 'add_address']);
});

Route::group(['prefix' => 'v1/admin'], function () {
    Route::get('/', [AdminController::class, 'dashboard']);
    Route::get('products', [AdminController::class, 'products']);
    Route::post('add_products', [AdminController::class, 'add_products_details']);
    Route::get('singleproducts/{id}', [AdminController::class, 'singleproducts']);
    Route::get('edit_product/{id}', [AdminController::class, 'edit_product']);
    Route::post('edit_product/{id}', [AdminController::class, 'update_product']);
    Route::delete('edit_product/{id}', [AdminController::class, 'delete_product']);
    Route::get('users', [AdminController::class, 'users']);
    Route::get('profile', [AdminController::class, 'profile']);
    Route::post('profile/update_profile', [AdminController::class, 'update_profile']);
    Route::post('profile/update_password', [AdminController::class, 'update_password']);
    Route::delete('users/{id}', [AdminController::class, 'delete_user']);
    Route::get('orders', [AdminController::class, 'users_orders']);
    Route::get('orders-details/{id}', [AdminController::class, 'single_order']);
    Route::post('orders-details/{id}', [AdminController::class, 'save_user']);
    Route::get('edit-user-address/{id}', [AdminController::class, 'edit_user_address']);
    Route::post('edit-user-address/{id}', [AdminController::class, 'save_user_address']);
    Route::post('orders-details/{id}', [AdminController::class, 'update_order_status']);
    Route::post('cancel-order/{id}', [AdminController::class, 'cancel_user_order']);
    Route::get('generate-user-pdf', [AdminController::class, 'generate_user_pdf']);
    Route::get('generate-order-pdf', [AdminController::class, 'generate_order_pdf']);
    Route::post('import-csv', [AdminController::class, 'insert_user']);
    Route::get('export-csv', [AdminController::class, 'export_csv']);
    Route::get('export-excel', [AdminController::class, 'export_excel']);
});

Route::group(['prefix' => 'v1'], function () {
    Route::post('register', [ApiController::class, 'register']);
    Route::post('/', [ApiController::class, 'login']);
    Route::post('/generate-otp', [ApiController::class, 'generate_otp']);
});

