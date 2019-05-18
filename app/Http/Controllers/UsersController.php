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
            if ($request->has('role')) {
                return User::role($request->get('role'))->get();
            }

            return User::with('roles')->all();
        }

        if ($request->filled('search')) {
            return User::where('name', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('email', 'LIKE', '%' . $request->get('search') . '%')
                ->paginate(10)
                ->setPath('')
                ->appends(array(
                    'search' => $request->get('search'),
                ));
        }

        return User::with('roles')->paginate(10);
    }

    public function save(SaveUserRequest $request)
    {
        $user = $request->all();

        unset($user['id']);
        if ($request->filled('password')) {
            $user['password'] = bcrypt($user['password']);
        } else {
            unset($user['password']);
        }

        $role = Role::find($user['role']);

        unset($user['role']);

        $id = 0;
        $userDB = User::where('email', $request->get('email'))->withTrashed()->first();
        if ($userDB) {
            $id = ['id' => $userDB->id];
            $userDB->restore();
        }

        if ($request->has('id')) {
            $id = $request->only('id');
        }

        if ($id) {
            $user = User::updateOrCreate($id, $user);
        } else {
            $user = User::firstOrCreate($user);
        }

        $user->syncRoles($role);

        return $user;
    }

    public function delete($id)
    {
        return response()->json([
            'success' => User::findOrFail($id)->delete(),
        ]);
    }
}
