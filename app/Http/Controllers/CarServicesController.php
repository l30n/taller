<?php

namespace App\Http\Controllers;

use App\Http\Requests\SaveCarServiceRequest;
use App\Models\CarService;
use App\Models\CarServiceItem;
use Illuminate\Http\Request;

class CarServicesController extends Controller
{
    //
    public function index(Request $request)
    {
        return view('carservices.index');
    }

    public function create(Request $request)
    {
        return view('carservices.create');
    }

    public function edit($id)
    {
        $carService = CarService::find($id);

        $items = CarServiceItem::where([
            'car_id' => $carService->car_id,
            'service_id' => $carService->service_id,
        ])->with('item')->get();

        return view('carservices.edit', ['carService' => $carService, 'items' => $items]);
    }

    public function get(Request $request)
    {
        if ($request->has('all')) {
            return CarService::with('car')->with('service')->get();
        }

        return CarService::with('car')->with('service')->paginate(10);
    }

    public function save(SaveCarServiceRequest $request)
    {
        $car = $request->get('car');
        $service = $request->get('service');

        CarService::firstOrCreate([
            'car_id' => $car,
            'service_id' => $service,
            'comment' => '',
            'price' => 0,
            'low' => 0,
            'mid' => 0,
            'high' => 0,
        ]);

        $items = collect($request->get('items'));

        $ids = CarServiceItem::select('id')->where([
            'car_id' => $car,
            'service_id' => $service,
        ])->get();

        $ids->each(function ($id, $key) use ($items) {
            if (!$items->contains('id', $id->id)) {
                CarServiceItem::find($id->id)->delete();
            }
        });

        $items->each(function ($item, $key) use ($car, $service) {
            $item['car_id'] = $car;
            $item['service_id'] = $service;
            $item['item_id'] = $item['id'];
            $item['comment'] = '';

            if (isset($item['name'])) {
                unset($item['id']);
                unset($item['name']);
                unset($item['description']);
            }

            if (isset($item['item'])) {
                $item['item_id'] = $item['item']['id'];
                unset($item['item']);
            }

            if (isset($item['id'])) {
                CarServiceItem::find($item['id'])->update($item);
            } else {
                CarServiceItem::firstOrCreate($item);
            }
        });

        return response()->json([
            'success' => true,
        ]);
    }
}
