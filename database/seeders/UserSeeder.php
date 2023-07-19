<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

use Faker\Factory as Faker;

class UserSeeder extends Seeder
{
    public function run()
    {
        // DB::table("users")->truncate();
        $faker = Faker::create("id_ID");
        // $phoneNumber = $faker->unique()->numerify("08##########");
        for ($i = 0; $i <= 10; $i++) {
            DB::table("users")->insert([
                "user_name" => $faker->name,
                "user_no_wa" => $faker->unique()->numerify("08##########"),
                "user_address" => $faker->address,
                "created_at" => Carbon::now()->format("Y-m-d H:i:s"),
                "updated_at" => Carbon::now()->format("Y-m-d H:i:s"),
            ]);
        }
    }
}
