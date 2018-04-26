<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class Bonus extends Model
{
    protected $table='bonus';

    public function getSourceAttribute($source)
    {
        return json_decode($source, true);
    }

    public function setSourceAttribute($source)
    {
        if (is_array($source)) {
            $this->attributes['source'] = json_encode($source);
        }
    }

}
