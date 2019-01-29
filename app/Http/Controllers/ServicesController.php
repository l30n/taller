<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetServicesRequest;
use App\Http\Requests\SaveServiceRequest;
use App\Models\Car;
use App\Models\CarServiceItem;
use App\Models\Service;

class ServicesController extends Controller
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
        return view('services.index');
    }

    public function get()
    {
        return Service::paginate(10);
    }

    public function save(SaveServiceRequest $request)
    {
        return Service::firstOrCreate($request->all());
    }

    public function getByCar(GetServicesRequest $request)
    {
        $car = Car::has('carServices')
            ->where('brand', '=', $request->get('brand'))
            ->where('start_year', '<=', $request->get('year'))
            ->where('end_year', '>=', $request->get('year'))
            ->first();

        if (!$car) {
            return [];
        }

        $carServiceItems = CarServiceItem::where('car_id', '=', $car->id)
            ->with('service')->with('item')->get();

        $services = collect();

        $carServiceItems->map(function ($carServiceItem) use ($services) {
            $service = collect($carServiceItem->service);
            if ($services->get($carServiceItem->service_id)) {
                $service = $services->get($carServiceItem->service_id);
            }

            $items = collect();
            if ($service->has('items')) {
                $items = $service->get('items');
            }
            $item = $carServiceItem->item->toArray();

            $item['price'] = floatval($carServiceItem->price);
            $item['low'] = $carServiceItem->low;
            $item['mid'] = $carServiceItem->mid;
            $item['high'] = $carServiceItem->high;

            $items->put($carServiceItem->item_id, $item);

            $service->put('items', $items);

            $services->put($carServiceItem->service_id, $service);
        });

        return $services->values()->toArray();

        $services->map(function ($service) use ($car) {
            $service->items->filter(function ($item, $key) use ($car, $service) {
                $carServiceItem = $item->carServiceItems->where('service_id', '=', $service->id)
                    ->where('item_id', '=', $item->id)->first();

                if (empty($carServiceItem)) {
                    return false;
                }

                $item = $item->toArray();

                $item['price'] = floatval($carServiceItem->price);
                $item['low'] = $carServiceItem->low;
                $item['mid'] = $carServiceItem->mid;
                $item['high'] = $carServiceItem->high;

                $service->items->put($key, $item);

                return true;
            });
        });

        return $services;
    }
}
