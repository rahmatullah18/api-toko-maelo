<?php

namespace Database\Seeders;

use App\Models\Category;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table("products")->truncate();

        $faker = Faker::create("id_ID");
        $title = $faker->title;
        $categoryIds = Category::pluck("id")->all();
        for ($i = 0; $i <= 10; $i++) {
            DB::table("products")->insert([
                "title" => $title,
                "slug" => Str::slug($title),
                "category_id" => $faker->randomElement($categoryIds),
                "price" => $faker->randomNumber(2),
                "image" => $faker->image,
                "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
            ]);
        }
    }
}
