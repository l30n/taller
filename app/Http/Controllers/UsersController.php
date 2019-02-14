<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveUserRequest;
use App\User;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    //
    public function index()
    {
        return view('users.index');
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

        return User::firstOrCreate($user);
    }
}
