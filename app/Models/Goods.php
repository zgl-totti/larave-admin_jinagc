<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class Goods extends Model
{

    public function getImagesAttribute($pictures)
    {
        return json_decode($pictures, true);
    }

    public function setImagesAttribute($pictures)
    {
        if (is_array($pictures)) {
            $this->attributes['images'] = json_encode($pictures);
        }
    }

    public function getTypeAttribute($type)
    {
        return json_decode($type, true);
    }

    public function setTypeAttribute($type)
    {
        if (is_array($type)) {
            $this->attributes['type'] = json_encode($type);
        }
    }

}
