<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class Promotions extends Model
{
    protected $table='promotions';

    public function getTypeAttribute($type)
    {
        return json_decode($type, true);
    }

    public function setTypeAttribute($type)
    {
        if (is_array($type)) {
            $this->attributes['type'] = json_encode($type);
        }
    }

    public function goods()
    {
        return $this->hasOne(Goods::class,'id','goods_id');
    }
}
