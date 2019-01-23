<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            ServicesTableSeeder::class,
            ItemsTableSeeder::class,
            ServiceItemsTableSeeder::class,
            CarsTableSeeder::class,
            CarServicesTableSeeder::class,
            CarServiceItemsTableSeeder::class,
            ClientsTableSeeder::class,
        ]);
    }
}
