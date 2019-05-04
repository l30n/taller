<?php

namespace App\Http\Controllers;

use App\Models\Car;
use App\Models\CarServiceItem;
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
    { }

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
            $sale = Sale::with('saleServices')->with('saleServices.item')->with('client')->with('user')
                ->with(['car' => function ($query) {
                    $query->distinct('id');
                }])->with(['services' => function ($query) {
                    $query->distinct('id');
                }])->where('create_at', '>=', date('Y-m-d 00:00:00', strtotime('-7 days')))->find($id);

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
            if ($request->has('concept')) {
                $sale->concept = $request->get('concept');
            }
            if ($request->has('total')) {
                $sale->total = $request->get('total');
            }
            if ($request->has('user')) {
                $sale->user_id = $request->get('user');
            }
            if ($request->has('details')) {
                $sale->details = $request->get('details');
            }
            if ($request->has('guaranty')) {
                $sale->guaranty = $request->get('guaranty');
            }
            if ($request->has('tax')) {
                $sale->tax = $request->get('tax');
            }
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

        $car = Car::where('brand', '=', $request->get('brand'))
            ->where('start_year', '<=', $year)
            ->where('end_year', '>=', $year)
            ->first();

        if (!$car) {
            $car = Car::where('brand', '=', $request->get('brand'))
                ->where('start_year', '<=', $year + 1)
                ->where('end_year', '>=', $year + 1)
                ->first();
            if (!$car) {
                $car = Car::where('brand', '=', $request->get('brand'))
                    ->where('start_year', '<=', $year - 1)
                    ->where('end_year', '>=', $year - 1)
                    ->first();
                if (!$car) {
                    $car = Car::create([
                        'brand' => $request->get('brand'),
                        'start_year' => $year,
                        'end_year' => $year
                    ]);
                } else {
                    $car->end_year++;
                }
            } else {
                $car->start_year--;
            }
            $car->save();
        }

        $sale = new Sale();

        $sale->total = $request->get('total');
        if ($request->has('service') && $sale->total == 0) {
            $sale->total = CarServiceItem::where('car_id', $car->id)->where('service_id', $request->get('service'))->sum('price');
        }
        $sale->user_id = $request->get('user');
        $sale->client_id = 0;
        if ($request->get('client')) {
            $sale->client_id = $request->get('client');
        }

        $sale->save();

        if ($request->has('service')) {
            $items = CarServiceItem::where('car_id', $car->id)->where('service_id', $request->get('service'))->get();

            if ($items->isNotEmpty()) {
                $items->each(function ($item, $key) use ($sale, $year) {
                    SaleService::create([
                        'sale_id' => $sale->id,
                        'car_id' => $item->car_id,
                        'service_id' => $item->service_id,
                        'item_id' => $item->item_id,
                        'year' => $year,
                        'price' => $item->price,
                    ]);
                });
            } else {
                SaleService::create([
                    'sale_id' => $sale->id,
                    'car_id' => $car->id,
                    'service_id' => $request->get('service'),
                    'item_id' => 1,
                    'year' => $year,
                    'price' => 0,
                ]);
            }
        } else if ($request->has('services')) {
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
        }

        return $sale;
    }
}
