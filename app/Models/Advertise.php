<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class Advertise extends Model
{
    protected $table='advertise';

    public function position()
    {
        return $this->hasOne(AdvertisePosition::class,'id','position_id');
    }
}
