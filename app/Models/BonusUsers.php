<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class BonusUsers extends Model
{
    public function user()
    {
        return $this->hasOne(\App\User::class,'id','user_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class,'id','order_id');
    }

    public function bonus()
    {
        return $this->hasOne(Bonus::class,'id','bonus_id');
    }
}
