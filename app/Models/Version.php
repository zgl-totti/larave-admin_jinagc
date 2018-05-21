<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class Version extends Model
{
    protected $table='version';

    const APP_ANDROID = 1;
    const APP_IOS = 2;
    const IS_FORCE = 1;
    const NOT_FORCE = 2;

    public static function appTypeMap()
    {
        return [
            self::APP_ANDROID => 'android',
            self::APP_IOS => 'ios'
        ];
    }

    public static function isForceMap()
    {
        return [
            self::IS_FORCE => '强制更新',
            self::NOT_FORCE => '不强制'
        ];
    }
}
