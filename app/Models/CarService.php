<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CarService extends Model
{
    //
    public function service()
    {
        return $this->belongsTo('App\Models\Service', 'service_id');
    }
}
