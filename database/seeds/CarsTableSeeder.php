<?php

use Illuminate\Database\Seeder;

class CarsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('cars')->insert([
            'brand' => 'Jetta',
            'start_year' => 1999,
            'end_year' => 2005,
        ]);
    }
}
