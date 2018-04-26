<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;

class Comment extends Model
{
    use AdminBuilder, ModelTree {
        ModelTree::boot as treeBoot;
    }

    protected $table='comment';

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTitleColumn('content');
    }

    public function allNodes()
    {
        return static::get()->toArray();
    }



    public function user()
    {
        return $this->hasOne(\App\User::class,'id','user_id');
    }

    public function order()
    {
        return $this->hasOne(Order::class,'id','order_id');
    }

    public function integralOrder()
    {
        return $this->hasOne(IntegralOrder::class,'id','order_id');
    }

    public function goods()
    {
        return $this->hasOne(Goods::class,'id','goods_id');
    }

    public function commentStatus()
    {
        return $this->hasOne(CommentStatus::class,'id','comment_status');
    }

    public function reply()
    {
        return $this->hasOne(CommentReply::class,'comment_id','id');
    }
}
