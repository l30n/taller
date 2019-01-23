<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetServicesRequest;
use App\Models\Car;
use App\Models\Service;

class ServicesController extends Controller
{
    //
    public function get(GetServicesRequest $request)
    {

        $car = Car::has('carServices')
            ->where('brand', '=', $request->get('brand'))
            ->where('start_year', '<=', $request->get('year'))
            ->where('end_year', '>=', $request->get('year'))
            ->first();

        if (!$car) {
            return [];
        }

        $services = Service::whereHas('carServices', function ($query) use ($car) {
            $query->where('car_id', '=', $car->id);
        })->with('items')->with('items.carServiceItems')->get();

        $services->map(function ($service) use ($car) {
            $service->items->transform(function ($item) use ($car, $service) {
                $carServiceItem = $item->carServiceItems->where('car_id', '=', $car->id)
                    ->where('service_id', '=', $service->id)
                    ->where('item_id', '=', $item->id)->first();

                $item = $item->toArray();

                $item['price'] = floatval($carServiceItem->price);
                $item['low'] = $carServiceItem->low;
                $item['mid'] = $carServiceItem->mid;
                $item['high'] = $carServiceItem->high;

                return $item;
            });
        });

        return $services;
    }
}
