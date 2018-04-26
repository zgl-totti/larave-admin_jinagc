<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class Seckill extends Model
{
    protected $table='seckill';

    public function goods()
    {
        return $this->hasOne(Goods::class,'id','goods_id');
    }
}
