<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function productsFilterByCategory()
    {
        $categories = DB::table('categories')
            ->join('products', 'categories.id', '=', 'category_id')
            ->select("categories.category_title", "categories.category_slug", "categories.category_class", 'products.product_title', 'products.product_slug', 'products.product_price', 'products.prduct_image', 'products.id')
            ->get();

        $groupCategory = $categories->groupBy('category_slug')->values();
        return response()->json($groupCategory);
    }

    public function getAllProductByCategory($id)
    {
        $products = Category::where('category_slug', '=', "$id")
            ->join('products', 'categories.id', '=', 'category_id')
            ->get();
        return response()->json($products);
    }

    public function getOneProductBySlug($slug)
    {
        // product
        $getProduct = DB::table('products')->where('product_slug', '=', "$slug")
            ->first();
        $productCollect = collect($getProduct);
        // type_product
        $getTypeProducts = DB::table('type_products')->where('product_id', '=', $productCollect['id'])
            ->get(['id', 'type_product_url', 'type_product_color', 'type_product_stock', 'type_product_size']);
        $typeProductsCollect = collect($getTypeProducts);

        return response()->json([
            "id" => $productCollect['id'],
            "product_name" => $productCollect["product_title"],
            "product_slug" => $productCollect["product_slug"],
            "product_price" => $productCollect["product_price"],
            "prduct_image" => $productCollect["prduct_image"],
            "type_products" => $typeProductsCollect,
        ]);
    }
}
