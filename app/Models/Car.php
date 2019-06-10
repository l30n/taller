<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Car extends Model
{
    use SoftDeletes;

    public $fillable = ['maker', 'brand', 'start_year', 'end_year'];

    public function carServices()
    {
        return $this->hasMany('App\Models\CarService');
    }

    public function carServiceItems()
    {
        return $this->hasMany('App\Models\CarServiceItem');
    }

    public function services()
    {
        return $this->hasManyThrough('App\Models\Service', 'App\Models\CarService', 'car_id', 'service_id', 'service_id');
    }
}
