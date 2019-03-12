<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveItemRequest;
use App\Models\Item;
use Illuminate\Http\Request;

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

    public function get(Request $request)
    {
        if ($request->has('all')) {
            return Item::all();
        }
        if ($request->filled('search')) {
            return Item::where('name', 'LIKE', '%' . $request->get('search') . '%')
                ->paginate(10)
                ->setPath('')
                ->appends(array(
                    'search' => $request->get('search'),
                ));
        }

        return Item::paginate(10);
    }

    public function save(SaveItemRequest $request)
    {
        if ($request->has('id')) {
            return Item::updateOrCreate($request->only('id'), $request->except('id'));
        }

        return Item::firstOrCreate($request->all());
    }

    public function delete($id)
    {
        return response()->json([
            'success' => Item::findOrFail($id)->delete(),
        ]);
    }
}
