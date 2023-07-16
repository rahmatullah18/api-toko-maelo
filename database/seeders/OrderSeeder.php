<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;
use Illuminate\Support\Facades\DB;

class OrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // DB::table("orders")->truncate();

        $faker = Faker::create("id_ID");
        $productsIds = Product::pluck("id")->all();
        $usersIds = User::pluck("id")->all();
        $statusList = ["pending", "In Progress", "Completed", "Cancelled"];
        for ($i = 0; $i <= 10; $i++) {
            DB::table("orders")->insert([
                "quantity" => 10,
                "product_id" => $faker->randomElement($productsIds),
                "user_id" => $faker->randomElement($usersIds),
                "total_price" => $faker->randomNumber(2),
                "status" => $faker->randomElement($statusList),
                "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
            ]);
        }
    }
}
