<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveClientRequest;
use App\Models\Client;
use Illuminate\Http\Request;

class ClientsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     *
     */
    public function get(Request $request)
    {
        if ($request->has('all')) {
            return Client::all();
        }

        if ($request->filled('search')) {
            return Client::where('name', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('phonenumber', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('address', 'LIKE', '%' . $request->get('search') . '%')
                ->paginate(10)
                ->setPath('')
                ->appends(array(
                    'search' => $request->get('search'),
                ));
        }

        return Client::paginate(10);
    }

    public function index()
    {
        return view('clients.index');
    }

    public function save(SaveClientRequest $request)
    {
        if ($request->has('id')) {
            return Client::updateOrCreate($request->only('id'), $request->except('id'));
        }

        return Client::firstOrCreate($request->all());
    }
}
