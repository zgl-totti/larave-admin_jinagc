<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $table='order';

    public static function statusMap()
    {
        return OrderStatus::pluck('status_name','id')->toArray();
    }

    public function orderGoods()
    {
        return $this->hasMany(OrderGoods::class,'order_id','id')
            ->join('goods','goods.id','order_goods.goods_id')
            ->join('goods_type as t','t.id','order_goods.type_id')
            ->select('order_goods.*','goods.id','goods.goods_name','goods.goods_brief','t.id as type_id','t.name as type_name');
    }

    public function user()
    {
        return $this->hasOne(\App\User::class,'id','user_id');
    }

    public function status()
    {
        return $this->hasOne(OrderStatus::class,'id','order_status');
    }

    public function source()
    {
        return $this->hasOne(Source::class,'id','order_source');
    }

    public function express()
    {
        return $this->hasOne(Express::class,'id','express_id');
    }

    public function pay()
    {
        return $this->hasOne(Pay::class,'id','pay_type');
    }


    /**
     * 发货
     * @param int $id
     * @return bool
     * @author totti_zgl
     * @date 2018/4/12 11:10
     */
    public function shipments(int $id) : bool
    {
        $info=Order::find($id);
        if($info->order_status!=2){
            return false;
        }
        $info->order_status=3;
        if($info->save()){
            return true;
        }
        return false;
    }
}
