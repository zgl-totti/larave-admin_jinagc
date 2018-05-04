<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class SeckillOrder extends Model
{
    protected $table='seckill_order';

    public static function statusMap()
    {
        return OrderStatus::pluck('status_name','id')->toArray();
    }


    public function user()
    {
        return $this->hasOne(\App\User::class,'id','user_id');
    }

    public function seckill()
    {
        return $this->hasOne(Seckill::class,'id','seckill_id')
            ->join('goods','goods.id','seckill.goods_id')
            ->select('seckill.*','goods.goods_name','goods.id');
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
     * @date 2018/4/24 17:03
     */
    public function shipments(int $id)
    {
        $info=self::find($id);
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
