<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarServiceItem extends Model
{
    //
    protected $softDelete = false;

    public $fillable = [
        'item_id',
        'car_id',
        'service_id',
        'comment',
        'price',
        'low',
        'low_price',
        'mid',
        'mid_price',
        'high',
        'high_price',
    ];

    public function car()
    {
        return $this->belongsTo('App\Models\Car');
    }

    public function service()
    {
        return $this->belongsTo('App\Models\Service');
    }

    public function item()
    {
        return $this->belongsTo('App\Models\Item');
    }
}
