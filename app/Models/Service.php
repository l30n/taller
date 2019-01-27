<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    public $fillable = ['name', 'description'];

    //
    public function carServices()
    {
        return $this->hasMany('App\Models\CarService');
    }

    public function items()
    {
        return $this->hasManyThrough('App\Models\Item', 'App\Models\ServiceItem', 'service_id', 'id', 'id', 'item_id');
    }
}
