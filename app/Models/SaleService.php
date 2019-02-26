<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SaleService extends Model
{
    //
    public $fillable = [
        'car_id', 'service_id', 'sale_id', 'item_id', 'year', 'price',
    ];

    public function service()
    {
        return $this->belongsTo('App\Models\Service');
    }
}
