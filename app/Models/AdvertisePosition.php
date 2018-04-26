<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

use Encore\Admin\Traits\AdminBuilder;
use Encore\Admin\Traits\ModelTree;

class AdvertisePosition extends Model
{
    protected $table='advertise_position';

    use AdminBuilder, ModelTree {
        ModelTree::boot as treeBoot;
    }

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->setTitleColumn('position_name');
    }

    public function allNodes()
    {
        return static::get()->toArray();
    }



    public function advertise()
    {
        return $this->belongsTo(Advertise::class,'position_id');
    }
}
