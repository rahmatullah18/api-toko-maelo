<?php

use App\Http\Controllers\CategoryController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\UserController;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware("auth:sanctum")->get("/user", function (Request $request) {
    return $request->user();
});


Route::get("/testing-api", function () {
    return response()->json([
        "message" => "Hello World",
    ]);
});

// Product
Route::prefix("product")->group(function () {
    // get product filter by category
    Route::get("/products-filter-by-category", [
        ProductController::class,
        "productsFilterByCategory",
    ]);

    Route::get("/get-products-by-category/{id}", [
        ProductController::class,
        "getAllProductByCategory",
    ]);

    Route::get("/get-product-by-slug/{slug}", [
        ProductController::class,
        "getOneProductBySlug"
    ]);


    // admin
    // product
    Route::get("/get-all-product", [ProductController::class, 'allProducts']);
    Route::get("/get-product-with-type/{slug}", [ProductController::class, 'getProductWithType']);
    Route::post('/create-data-product',  [ProductController::class, 'createDataProduct']);

    Route::post('/update-data-product/{slug}',  [ProductController::class, 'updateDataProduct']);
    Route::delete('/delete-data-product/{slug}',  [ProductController::class, 'deleteDataProduct']);
    Route::get('/search-product/{query}',  [ProductController::class, 'searchProduct']);
});

// category
Route::prefix("category")->group(function () {
    Route::get("/get-all-category", [
        CategoryController::class,
        "getAllCategory",
    ]);
});

// orders
Route::prefix("order")->group(function () {
    Route::get('/get-all-orders', [OrderController::class, 'getAllOrder']);
    Route::post('/create-order', [OrderController::class, 'createOrder']);
    Route::post('/send-message', [OrderController::class, 'sendMessage']);
    Route::post('/update-status-order', [OrderController::class, 'updateStatusOrder']);
    Route::delete('/delete-order/{id}', [OrderController::class, 'deleteOrder']);
});

// report
Route::post('/report', [ReportController::class, 'index']);
// login
Route::post("/login", [LoginController::class, 'userLogin']);

// login register user
Route::post('/register-user', [UserController::class, 'registerUser']);
Route::post('/login-user', [UserController::class, 'loginUser']);
Route::post('/pesanan-diterima', [OrderController::class, 'pesananDiterima']);
