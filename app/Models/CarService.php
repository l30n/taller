<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarService extends Model
{
    //
    public $fillable = [
        'car_id',
        'service_id',
        'comment',
        'price',
        'low',
        'mid',
        'high',
    ];

    public function car()
    {
        return $this->belongsTo('App\Models\Car', 'car_id');
    }

    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'service_id');
    }
}
