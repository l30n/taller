<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Item extends Model
{
    use SoftDeletes;

    public $fillable = ['name', 'description'];

    public function carServiceItems()
    {
        return $this->hasMany('App\Models\CarServiceItem');
    }
}
