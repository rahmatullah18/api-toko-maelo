<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function productsFilterByCategory()
    {
        $products = DB::table('products')
            ->join('categories', 'category_id', '=', 'categories.id')
            ->select("categories.category_title", "categories.category_slug", 'products.product_title', 'products.product_slug', 'products.product_price')
            ->get();

        // group byCateogry
        $categories = collect($products)
            ->groupBy('category_title');

        return response()->json([
            'data' => $categories
        ]);
    }
}
