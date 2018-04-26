<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class OrderGoods extends Model
{
    protected $table='order_goods';

    public function order()
    {
        return $this->belongsTo(Order::class,'order_id');
    }
}
