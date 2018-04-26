<?php

namespace App\Models;

//use Illuminate\Database\Eloquent\Model;

class ArticleContents extends Model
{
    public function cate()
    {
        return $this->hasOne(ArticleCates::class,'id','cate_id');
    }
}
