<?php

use Illuminate\Database\Seeder;

class ServicesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('services')->insert([
            'name' => 'Cambio de Aceite',
            'description' => '',
        ]);

        DB::table('services')->insert([
            'name' => 'Cambio de Compresor',
            'description' => '',
        ]);

        DB::table('services')->insert([
            'name' => 'Cambio de Condensador',
            'description' => '',
        ]);

        DB::table('services')->insert([
            'name' => 'Cambio de Evaporador',
            'description' => '',
        ]);

        DB::table('services')->insert([
            'name' => 'Cambio de Manguera',
            'description' => '',
        ]);

        DB::table('services')->insert([
            'name' => 'Cambio Filtro Aculador',
            'description' => '',
        ]);

        DB::table('services')->insert([
            'name' => 'Cambio Valvula Expansion',
            'description' => '',
        ]);

        DB::table('services')->insert([
            'name' => 'Cambio Valvula A/B',
            'description' => '',
        ]);

        DB::table('services')->insert([
            'name' => 'Equipo Completo',
            'description' => '',
        ]);

        DB::table('services')->insert([
            'name' => 'Lavado de Sistema',
            'description' => '',
        ]);

        DB::table('services')->insert([
            'name' => 'Relay Independiente',
            'description' => '',
        ]);

        DB::table('services')->insert([
            'name' => 'Valvula Electronica',
            'description' => '',
        ]);
    }
}
