<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table("categories")->truncate();

        $faker = Faker::create("id_ID");
        $title = $faker->sentence;
        for ($i = 0; $i <= 10; $i++) {
            DB::table("categories")->insert([
                "title" => $title,
                "slug" => Str::slug($title),
                "class" => "testing",
                "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
            ]);
        }
    }
}
