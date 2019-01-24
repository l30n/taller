<?php

namespace App\Http\Controllers;

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
}
