<?php

use Illuminate\Database\Seeder;

class ServiceItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('service_items')->insert([
            'service_id' => 10,
            'item_id' => 1,
        ]);

        DB::table('service_items')->insert([
            'service_id' => 10,
            'item_id' => 6,
        ]);

        DB::table('service_items')->insert([
            'service_id' => 10,
            'item_id' => 7,
        ]);

        DB::table('service_items')->insert([
            'service_id' => 10,
            'item_id' => 12,
        ]);

        DB::table('service_items')->insert([
            'service_id' => 10,
            'item_id' => 2,
        ]);

        DB::table('service_items')->insert([
            'service_id' => 10,
            'item_id' => 10,
        ]);

        DB::table('service_items')->insert([
            'service_id' => 10,
            'item_id' => 14,
        ]);

        DB::table('service_items')->insert([
            'service_id' => 10,
            'item_id' => 15,
        ]);

        DB::table('service_items')->insert([
            'service_id' => 10,
            'item_id' => 19,
        ]);

        DB::table('service_items')->insert([
            'service_id' => 10,
            'item_id' => 13,
        ]);
    }
}
