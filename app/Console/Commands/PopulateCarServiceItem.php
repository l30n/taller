<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Car;
use App\Models\Service;
use App\Models\Item;
use App\Models\CarService;
use App\Models\CarServiceItem;

class PopulateCarServiceItem extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'populate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //
        $content = file_get_contents(storage_path() . './inserts.txt');
        $inserts = explode(';', $content);
        foreach ($inserts as $key => $insert) {
            preg_match("/(?<=\()(.*?)(?=\))/", $insert, $insert);
            if (isset($insert[0])) {
                $data = explode(',', $insert[0]);
                $car = Car::where('brand', $data[0])->where('start_year', $data[1])->where('end_year', $data[2])->first();
                var_dump($key);
                if (!$car) {
                    var_dump("Car");
                    var_dump($data);
                    break;
                }
                $service = Service::where('name', $data[3])->first();
                if (!$service) {
                    var_dump("Service");
                    var_dump($data);
                    break;
                }
                $item = Item::where('name', $data[4])->first();
                if (!$item) {
                    var_dump("Item");
                    var_dump($data);
                    break;
                }
                /*$carService = CarService::where('car_id', $car->id)
                    ->where('service_id', $service->id)
                    ->first();
                if (!$carService) {
                    var_dump("carService");
                    var_dump($data);
                    CarService::create([
                        'car_id' => $car->id,
                        'service_id' => $service->id
                    ]);
                }*/
                $carServiceItem = CarServiceItem::where('car_id', $car->id)
                    ->where('service_id', $service->id)
                    ->where('item_id', $item->id)
                    ->first();
                if (!$carServiceItem) {
                    var_dump("carServiceItem");
                    var_dump($data);
                    $data[6] = !empty($data[6]) ? $data[6] : 0;
                    $data[8] = !empty($data[8]) ? $data[8] : 0;
                    $data[10] = !empty($data[10]) ? $data[10] : 0;
                    $carServiceItem = CarServiceItem::firstOrCreate([
                        'car_id' => $car->id,
                        'service_id' => $service->id,
                        'item_id' => $item->id,
                        'price' => floatval($data[5]),
                        'low' => floatval($data[6]) * 100,
                        'low_price' => floatval($data[7]),
                        'mid' => floatval($data[8]) * 100,
                        'mid_price' => floatval($data[9]),
                        'high' => floatval($data[10]) * 100,
                        'high_price' => floatval($data[11])
                    ]);
                    var_dump($carServiceItem->toArray());
                }
            }
        }
    }
}
