<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Service extends Model
{
    use SoftDeletes;

    public $fillable = ['name', 'description'];

    public function carServices()
    {
        return $this->hasMany('App\Models\CarService');
    }

    public function items()
    {
        return $this->hasManyThrough('App\Models\Item', 'App\Models\ServiceItem', 'service_id', 'id', 'id', 'item_id');
    }
}
