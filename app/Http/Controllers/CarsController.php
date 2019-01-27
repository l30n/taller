<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveCarRequest;
use App\Models\Car;

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
    public function get()   
    {
        return Car::paginate(10);
    }

    public function save(SaveCarRequest $request)
    {
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
}
