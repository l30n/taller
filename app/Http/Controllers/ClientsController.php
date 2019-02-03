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

        return Client::paginate(10);
    }

    public function index()
    {
        return view('clients.index');
    }

    public function save(SaveClientRequest $request)
    {
        $client = $request->all();

        return Client::firstOrCreate($client);
    }
}
