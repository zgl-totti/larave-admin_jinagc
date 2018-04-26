<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class Promotions extends Model
{
    protected $table='promotions';

    public function goods()
    {
        return $this->hasOne(Goods::class,'id','goods_id');
    }
}
