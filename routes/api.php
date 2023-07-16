<?php

use App\Http\Controllers\ProductController;
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
});
