<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveRoleRequest;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $permissions = Permission::get();

        return view('roles.index', [
            'permissions' => $permissions,
        ]);
    }

    //
    public function get(Request $request)
    {
        if ($request->has('all')) {
            return Role::with('permissions')->all();
        }

        return Role::with('permissions')->paginate(10);
    }

    public function save(SaveRoleRequest $request)
    {
        $name = $request['name'];

        if ($request->has('id')) {
            $role = Role::updateOrCreate($request->only('id'), $request->except(['id', 'permissions']));
        } else {
            $role = Role::firstOrCreate([
                'name' => $request->get('name'),
            ]);
        }

        foreach ($request['permissions'] as $permission) {
            $p[] = Permission::where('id', '=', $permission)->firstOrFail();
        }

        $role->syncPermissions($p);

        return $role;
    }

    public function delete($id)
    {
        $role = Role::findOrFail($id);

        $users = User::role($role)->get();

        if ($users->count() > 0) {
            return response()->json([
                'errors' => [
                    'used' => ['Rol esta asignado a ' . $users->count() . ' usuarios. Remover rol de usuarios asignados.'],
                ],
            ], 422);
        }

        return response()->json([
            'success' => $role->delete(),
        ]);
    }
}
