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

    public function receipt($id = 0)
    {
        if (!empty($id)) {
            $sale = Sale::with('saleServices')->with('saleServices.item')->with('client')
                ->with(['car' => function ($query) {
                    $query->distinct('id');
                }])->with(['services' => function ($query) {
                $query->distinct('id');
            }])->find($id);

            return view('sales.receipt', [
                'sale' => $sale,
            ]);
        }

        return view('sales.receipt');
    }

    public function get()
    {
        return Sale::with('saleServices')->with('client')->with(['car' => function ($query) {
            $query->distinct('id');
        }])->with(['services' => function ($query) {
            $query->distinct('id');
        }])->paginate(10);
    }

    public function changeStatus(Request $request)
    {
        $sale = Sale::find($request->get('id'));

        $sale->status = $request->get('status');

        if ($sale->status == Sale::TERMINADO) {
            $sale->done_on = date('Y-m-d H:i:s');
            $sale->method = $request->get('method');
        }

        $sale->save();

        return response()->json([
            "success" => true,
        ]);
    }

    public function save(Request $request)
    {
        $year = $request->get('year');
        $price = $request->get('price');

        $car = Car::with(['carServices', 'carServices.service'])
            ->where('brand', '=', $request->get('brand'))
            ->where('start_year', '<=', $year)
            ->where('end_year', '>=', $year)
            ->first();

        $sale = new Sale();

        $sale->total = $request->get('total');
        $sale->client_id = 0;
        if ($request->get('client')) {
            $sale->client_id = $request->get('client');
        }

        $sale->save();

        foreach ($request->get('services') as $service) {
            foreach ($service['items'] as $item) {
                SaleService::create([
                    'sale_id' => $sale->id,
                    'car_id' => $car->id,
                    'service_id' => $service['id'],
                    'item_id' => $item['id'],
                    'year' => $year,
                    'price' => $item[$price . '_price'],
                ]);
            }
        }

        $sale->id;

        return $sale;
    }
}
