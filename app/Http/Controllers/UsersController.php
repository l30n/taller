<?php

namespace App\Http\Controllers;

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
}
