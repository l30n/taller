<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveRoleRequest;
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
        return view('roles.index');
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

        $role = new Role();
        $role->name = $name;
        $role->save();

        foreach ($request['permissions'] as $permission) {
            $p = Permission::where('id', '=', $permission)->firstOrFail();
            $role->givePermissionTo($p);
        }

        return $role;
    }
}
