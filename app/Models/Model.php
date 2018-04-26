<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model as BaseModel;

class Model extends BaseModel
{
    protected $guarded=[];

    const STATUS_ALLOT = 1;
    const STATUS_UNALLOT = 2;

    public static function statusMap()
    {
        return [
            self::STATUS_ALLOT=>'展示',
            self::STATUS_UNALLOT=>'下架'
        ];
    }
}
