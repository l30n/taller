<?php

namespace App\Http\Controllers;

use App\Models\Car;

class CarsController extends Controller
{
    //
    public function get()
    {
        return Car::all();
    }

    public function listBrands()
    {
        return Car::select('brand')->groupBy('brand')->orderBy('brand')->get();
    }
}
