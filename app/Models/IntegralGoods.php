<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class IntegralGoods extends Model
{
    public function goods(){
        return $this->hasOne(Goods::class,'id','goods_id');
    }
}
