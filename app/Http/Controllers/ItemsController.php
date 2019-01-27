<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveItemRequest;
use App\Models\Item;

class ItemsController extends Controller
{
    //
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    public function index()
    {
        return view('items.index');
    }

    public function get()
    {
        return Item::paginate(10);
    }

    public function save(SaveItemRequest $request)
    {
        $item = $request->all();

        return Item::firstOrCreate($item);
    }
}
