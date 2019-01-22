<?php

use Illuminate\Database\Seeder;

class ItemsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('items')->insert([
            'name' => 'Mano de Obra',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Aceite',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Balero Compresor',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Banda',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Base Compresor',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Compresor',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Condensador',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Consola',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Electrico',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Empaques',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Evaporador',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Filtro Acumulador',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Filtro de Cabina',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Gas',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Limpiador',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Manguera A/B',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Valvula de Expansion',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Valvula Expansion Trasera',
            'description' => '',
        ]);

        DB::table('items')->insert([
            'name' => 'Valvula de Servicio',
            'description' => '',
        ]);
    }
}
