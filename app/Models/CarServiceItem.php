<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarServiceItem extends Model
{
    //

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
