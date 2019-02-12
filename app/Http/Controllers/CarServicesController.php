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

    public function get()
    {
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

        $items = $request->get('items');

        foreach ($items as $item) {
            $item['car_id'] = $car;
            $item['service_id'] = $service;
            $item['item_id'] = $item['id'];
            $item['comment'] = '';

            unset($item['id']);
            unset($item['name']);
            unset($item['description']);

            CarServiceItem::firstOrCreate($item);
        }

        return response()->json([
            'success' => true,
        ]);
    }
}
