<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //

    public function client()
    {
        return $this->belongsTo('App\Models\Client');
    }

    public function car()
    {
        return $this->hasManyThrough('App\Models\Car', 'App\Models\SaleService', 'sale_id', 'id', 'id', 'car_id');
    }

    public function services()
    {
        return $this->hasManyThrough('App\Models\Service', 'App\Models\SaleService', 'sale_id', 'id', 'id', 'service_id');
    }
}
