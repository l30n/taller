<?php

namespace App\Http\Controllers;

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

    public function listBrands()
    {
        return Car::select('brand')->groupBy('brand')->orderBy('brand')->get();
    }
}
