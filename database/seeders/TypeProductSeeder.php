<?php

namespace Database\Seeders;

use App\Models\Product;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class TypeProductSeeder extends Seeder
{
    public function run()
    {
        // DB::table("type_products")->truncate();

        $faker = Faker::create("id_ID");
        $productsIds = Product::pluck("id")->all();

        for ($i = 0; $i <= 10; $i++) {
            DB::table("type_products")->insert([
                "product_id" => $faker->randomElement($productsIds),
                "type_product_url" => $faker->url,
                "type_product_color" => $faker->colorName,
                "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
            ]);
        }
    }
}
