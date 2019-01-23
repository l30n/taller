<?php

use Illuminate\Database\Seeder;

class CarServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('car_services')->insert([
            'car_id' => 1,
            'service_id' => 10,
            'comment' => '',
            'price' => 1000,
            'low' => 20,
            'mid' => 25,
            'high' => 30,
        ]);
    }
}
