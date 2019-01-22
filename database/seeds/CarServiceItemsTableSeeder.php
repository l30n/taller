<?php

use Illuminate\Database\Seeder;

class CarServiceItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('car_service_items')->insert([
            'car_id' => 1,
            'service_id' => 10,
            'item_id' => 1,
            'comment' => '',
            'price' => 3000,
            'low' => 0,
            'mid' => 0,
            'high' => 0,
        ]);

        DB::table('car_service_items')->insert([
            'car_id' => 1,
            'service_id' => 10,
            'item_id' => 6,
            'comment' => '',
            'price' => 1456,
            'low' => 20,
            'mid' => 25,
            'high' => 30,
        ]);

        DB::table('car_service_items')->insert([
            'car_id' => 1,
            'service_id' => 10,
            'item_id' => 7,
            'comment' => '',
            'price' => 1456,
            'low' => 20,
            'mid' => 25,
            'high' => 30,
        ]);

        DB::table('car_service_items')->insert([
            'car_id' => 1,
            'service_id' => 10,
            'item_id' => 12,
            'comment' => '',
            'price' =>  1456,
            'low' => 20,
            'mid' => 25,
            'high' => 30,
        ]);

        DB::table('car_service_items')->insert([
            'car_id' => 1,
            'service_id' => 10,
            'item_id' => 2,
            'comment' => '',
            'price' =>  1456,
            'low' => 20,
            'mid' => 25,
            'high' => 30,
        ]);

        DB::table('car_service_items')->insert([
            'car_id' => 1,
            'service_id' => 10,
            'item_id' => 10,
            'comment' => '',
            'price' =>  1456,
            'low' => 20,
            'mid' => 25,
            'high' => 30,
        ]);

        DB::table('car_service_items')->insert([
            'car_id' => 1,
            'service_id' => 10,
            'item_id' => 14,
            'comment' => '',
            'price' =>  1456,
            'low' => 20,
            'mid' => 25,
            'high' => 30,
        ]);

        DB::table('car_service_items')->insert([
            'car_id' => 1,
            'service_id' => 10,
            'item_id' => 15,
            'comment' => '',
            'price' =>  1456,
            'low' => 20,
            'mid' => 25,
            'high' => 30,
        ]);

        DB::table('car_service_items')->insert([
            'car_id' => 1,
            'service_id' => 10,
            'item_id' => 19,
            'comment' => '',
            'price' =>  1456,
            'low' => 20,
            'mid' => 25,
            'high' => 30,
        ]);

        DB::table('car_service_items')->insert([
            'car_id' => 1,
            'service_id' => 10,
            'item_id' => 13,
            'comment' => '',
            'price' =>  1456,
            'low' => 20,
            'mid' => 25,
            'high' => 30,
        ]);
    }
}
