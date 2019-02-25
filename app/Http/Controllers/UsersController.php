<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUserRequest;
use App\User;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;

class UsersController extends Controller
{
    //
    public function index()
    {
        $roles = Role::get();

        return view('users.index', [
            'roles' => $roles,
        ]);
    }

    //
    public function get(Request $request)
    {
        if ($request->has('all')) {
            return User::all();
        }

        return User::paginate(10);
    }

    public function save(SaveUserRequest $request)
    {
        $user = $request->all();

        $user['password'] = bcrypt($user['password']);

        $role = Role::find($user['role']);

        unset($user['role']);

        $user = User::firstOrCreate($user);

        $user->syncRoles($role);

        return $user;
    }
}
