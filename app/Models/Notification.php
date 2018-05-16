<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $table='notification';

    public function type()
    {
        return $this->hasOne(NotificationType::class,'id','type_id');
    }
}
