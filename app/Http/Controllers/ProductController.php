<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

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

    public function allProducts()
    {
        $products = DB::table('products')
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($products);
    }

    public function createDataProduct(Request $request)
    {
        try {
            $typeProducts = $request->input("type_products");
            $imageName = $request->file('prduct_image')->getClientOriginalName();
            $request->file('prduct_image')->storeAs('images', $imageName, 'public');
            $product = Product::create([
                'category_id' => (int)$request->category_id,
                'product_title' => $request->product_title,
                'product_slug' => $request->product_slug,
                'product_price' => (int)$request->product_price,
                'prduct_image' => $imageName
            ]);
            foreach ($typeProducts as $type) {
                $product->typeProducts()->create([
                    'type_product_color' => $type['type_product_color'],
                    'type_product_size' => $type['type_product_size'],
                    'type_product_stock' => (int) $type['type_product_stock'],
                    'type_product_url' => '-',
                ]);
            }


            return response()->json([
                'message' => 'Data berhasil di input',
                'tes' => is_array($typeProducts)
            ], 201);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 400);
        }
    }

    public function getProductWithType($slug)
    {
        $product = Product::with('typeProducts')->where('product_slug', '=', "$slug")->first();
        return response()->json([
            'category_id' => $product->category_id,
            'product_title' => $product->product_title,
            'product_slug' => $product->product_slug,
            'product_price' => $product->product_price,
            'prduct_image' => $product->prduct_image,
            'type_products' => $product->typeProducts,
        ]);
    }

    public function updateDataProduct(Request $request, $slug)
    {
        try {
            $product = Product::where("product_slug", "=", "$slug")->first();

            $typeProducts = $request->input("type_products");

            if ($request->hasFile('prduct_image')) {
                $imageName = $request->file('prduct_image')->getClientOriginalName();
                $request->file('prduct_image')->storeAs('images', $imageName, 'public');
                $product->prduct_image = $imageName;
            }

            $product->category_id = (int) $request->category_id;
            $product->product_title = $request->product_title;
            $product->product_slug = $request->product_slug;
            $product->product_price = (int) $request->product_price;
            $product->save();

            // Delete existing type products for the product
            $product->typeProducts()->delete();

            foreach ($typeProducts as $type) {
                $product->typeProducts()->create([
                    'type_product_color' => $type['type_product_color'],
                    'type_product_size' => $type['type_product_size'],
                    'type_product_stock' => (int) $type['type_product_stock'],
                    'type_product_url' => '-',
                ]);
            }

            return response()->json([
                'message' => 'Data berhasil diupdate',
                // 'tes' => $request->all()
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 400);
        }
    }


    public function deleteDataProduct($slug)
    {
        try {
            // Ambil data produk berdasarkan slug
            $product = Product::where("product_slug", "=", $slug)->first();

            if (!$product) {
                return response()->json([
                    'message' => 'Produk tidak ditemukan'
                ], 404);
            }

            // Hapus tipe-tipe produk terlebih dahulu
            $product->typeProducts()->delete();

            // Hapus gambar dari direktori public/images jika ada
            if ($product->prduct_image) {
                Storage::delete('public/images/' . $product->prduct_image);
            }

            // Setelah tipe-tipe produk dan gambar dihapus, hapus produk itu sendiri
            $product->delete();

            return response()->json([
                'message' => 'Produk beserta tipe-tipe produknya dan gambar berhasil dihapus'
            ], 200);
        } catch (\Throwable $th) {
            return response()->json([
                'message' => $th->getMessage()
            ], 400);
        }
    }

    public function searchProduct($query)
    {
        // $query = $request->get('search');

        $products = Product::where('product_title', 'like', "%{$query}%")
            ->get(['id', 'product_slug', 'product_title']);

        return response()->json($products);
    }
}
