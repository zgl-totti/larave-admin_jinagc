<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class UsersIntegral extends Model
{
    protected $table='users_integral';

    /**
     * 积分来源于注册
     */
    const SOURCE_REGISTER=1;

    /**
     * 积分来源于订单
     */
    const SOURCE_ORDER=2;

    /**
     * 积分来源于评论
     */
    const SOURCE_COMMENT=3;

    public static function sourceMap()
    {
        return [
            self::SOURCE_REGISTER=>'注册',
            self::SOURCE_ORDER=>'订单',
            self::SOURCE_COMMENT=>'评论'
        ];
    }


    public function user()
    {
        return $this->hasOne(\App\User::class,'id','user_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class,'id','order_id');
    }
}
