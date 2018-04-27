<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class AfterSales extends Model
{
    public function order()
    {
        return $this->hasOne(Order::class,'id','order_id');
    }

    public function goods()
    {
        return $this->hasOne(Goods::class,'id','goods_id');
    }

}
