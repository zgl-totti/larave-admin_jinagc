<?php

namespace App;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    const STATUS_ALLOT = 1;
    const STATUS_UNALLOT = 2;

    public static function statusMap()
    {
        return [
            self::STATUS_ALLOT=>'展示',
            self::STATUS_UNALLOT=>'禁用'
        ];
    }
}
