<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\Sale;
use App\Models\SaleService;
use Illuminate\Http\Request;

class SalesController extends Controller
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
        return view('sales.list');
    }

    public function create()
    {
        return view('sales.create');
    }

    public function receipt()
    {
        return view('sales.receipt');
    }

    public function get()
    {
        return Sale::with('client')->with('car')->with('services')->paginate(10);
    }

    public function save(Request $request)
    {
        $car = Car::with(['carServices', 'carServices.service'])
            ->where('brand', '=', $request->get('brand'))
            ->where('start_year', '<=', $request->get('year'))
            ->where('end_year', '>=', $request->get('year'))
            ->first();

        $sale = new Sale();

        $sale->total = $request->get('total');
        $sale->client_id = 0;
        if ($request->get('client')) {
            $sale->client_id = $request->get('client');
        }

        $sale->save();

        foreach ($request->get('services') as $service) {
            SaleService::create([
                'sale_id' => $sale->id,
                'car_id' => $car->id,
                'service_id' => $service['id'],
            ]);
        }

        $sale->id;

        return $sale;
    }
}
