<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'name' => 'Sergio Leon',
            'email' => 'sergiom.leon@gmail.com',
            'password' => bcrypt('PWD4sl!'),
        ]);

        DB::table('users')->insert([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('PWD4ad!'),
        ]);
    }
}
