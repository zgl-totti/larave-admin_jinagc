<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class InquiryAsk extends Model
{
    protected $table='inquiry_ask';

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
