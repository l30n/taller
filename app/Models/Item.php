<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    //
    public function carServiceItems()
    {
        return $this->hasMany('App\Models\CarServiceItem');
    }
}
