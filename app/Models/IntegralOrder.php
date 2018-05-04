<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class IntegralOrder extends Model
{
    protected $table='integral_order';

    public static function statusMap()
    {
        return OrderStatus::whereNotIn('id',[1])->pluck('status_name','id')->toArray();
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

    public function orderGoods()
    {
        return $this->hasMany(IntegralOrderGoods::class,'order_id','id')
            ->join('integral_goods as ig','ig.id','integral_order_goods.goods_id')
            ->join('goods as g','g.id','ig.goods_id')
            ->select('integral_order_goods.*','ig.id','ig.goods_id','g.goods_name','g.goods_brief');
    }


    /**
     * 发货
     * @param int $id
     * @return bool
     * @author totti_zgl
     * @date 2018/4/17 13:27
     */
    public function shipments(int $id)
    {
        $info=IntegralOrder::find($id);
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
