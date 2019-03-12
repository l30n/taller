<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveCarRequest;
use App\Models\Car;
use Illuminate\Http\Request;

class CarsController extends Controller
{
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
        return view('cars.index');
    }

    //
    public function get(Request $request)
    {
        if ($request->has('all')) {
            return Car::all();
        }

        if ($request->filled('search')) {
            return Car::where('brand', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('start_year', 'LIKE', '%' . $request->get('search') . '%')
                ->orWhere('end_year', 'LIKE', '%' . $request->get('search') . '%')
                ->paginate(10)
                ->setPath('')
                ->appends(array(
                    'search' => $request->get('search'),
                ));
        }

        return Car::paginate(10);
    }

    public function save(SaveCarRequest $request)
    {
        $car = Car::where('brand', '=', $request->get('brand'))
            ->where('start_year', '=', $request->get('year')[0])
            ->where('end_year', '=', $request->get('year')[1])
            ->first();

        if ($car) {
            return response()->json([
                'error' => 'El Carro ya existe',
            ]);
        }

        if ($request->has('id')) {
            return Car::updateOrCreate($request->only('id'), $request->except(['id', 'year']));
        }

        return Car::firstOrCreate([
            'brand' => $request->get('brand'),
            'start_year' => $request->get('year')[0],
            'end_year' => $request->get('year')[1],
        ]);
    }

    public function listBrands()
    {
        return Car::select('brand')->groupBy('brand')->orderBy('brand')->get();
    }

    public function delete($id)
    {
        return response()->json([
            'success' => Car::findOrFail($id)->delete(),
        ]);
    }
}
