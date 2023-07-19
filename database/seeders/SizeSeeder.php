<?php

namespace Database\Seeders;

use App\Models\TypeProduct;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class SizeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table("sizes")->truncate();

        $faker = Faker::create("id_ID");
        $typeProductsIds = TypeProduct::pluck("id")->all();

        for ($i = 0; $i <= 10; $i++) {
            DB::table("sizes")->insert([
                "type_product_id" => $faker->randomElement($typeProductsIds),
                "size_size" => $faker->randomNumber(2),
                "size_stock" => $faker->randomNumber(1),
                "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
            ]);
        }
    }
}
