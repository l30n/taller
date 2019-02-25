<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $role = new Role();
        $role->name = 'Administrador';
        $role->save();

        $permissions = [
            'ordenes' => ['listar', 'agregar', 'editar', 'borrar'],
            'carros_servicios' => ['listar', 'agregar', 'editar', 'borrar'],
            'servicios' => ['listar', 'agregar', 'editar', 'borrar'],
            'carros' => ['listar', 'agregar', 'editar', 'borrar'],
            'articulos' => ['listar', 'agregar', 'editar', 'borrar'],
            'clientes' => ['listar', 'agregar', 'editar', 'borrar'],
            'usuarios' => ['listar', 'agregar', 'editar', 'borrar'],
            'roles' => ['listar', 'agregar', 'editar', 'borrar'],
        ];

        foreach ($permissions as $key => $ps) {
            foreach ($ps as $p) {
                $permission = new Permission();
                $permission->name = $p . ' ' . $key;
                $permission->save();

                $role->givePermissionTo($permission);
            }
        }
    }
}
