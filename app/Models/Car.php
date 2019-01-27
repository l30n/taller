<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Car extends Model
{
    //
    public $fillable = ['brand', 'start_year', 'end_year'];

    public function carServices()
    {
        return $this->hasMany('App\Models\CarService');
    }

    public function services()
    {
        return $this->hasManyThrough('App\Models\Service', 'App\Models\CarService', 'car_id', 'service_id', 'service_id');
    }
}
