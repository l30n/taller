<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Sale extends Model
{
    //
    const COTIZACION = 0;
    const PROCESO = 1;
    const TERMINADO = 2;
    const CANCELADO = 3;

    public function saleServices()
    {
        return $this->hasMany('App\Models\SaleService');
    }

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
