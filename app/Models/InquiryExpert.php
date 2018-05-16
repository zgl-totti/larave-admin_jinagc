<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class InquiryExpert extends Model
{
    protected $table='inquiry_expert';

    const GENDER_MAN = 1;
    const GENDER_WOMAN =2;
    const GENDER_PRIVARY =3;

    public static function genderMap()
    {
        return [
            self::GENDER_MAN => '男',
            self::GENDER_WOMAN => '女',
            self::GENDER_PRIVARY => '保密'
        ];
    }

    public function departments()
    {
        return $this->hasOne(InquiryDepartments::class,'id','departments_id');
    }
}
