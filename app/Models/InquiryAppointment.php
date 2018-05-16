<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class InquiryAppointment extends Model
{
    protected $table='inquiry_appointment';

    const TYPE_PHONE=1;
    const TYPE_ONLINE=2;

    public static function typeMap()
    {
        return [
            self::TYPE_PHONE=>'电话预约',
            self::TYPE_ONLINE=>'线下预约'
        ];
    }


    public function departments()
    {
        return $this->hasOne(InquiryDepartments::class,'id','departments_id');
    }

    public function expert()
    {
        return $this->hasOne(InquiryExpert::class,'id','expert_id');
    }

    public function user()
    {
        return $this->hasOne(\App\User::class,'id','user_id');
    }

    public function source()
    {
        return $this->hasOne(Source::class,'id','source_id');
    }
}
