<?php

namespace App\Admin\Contracts ;

use Encore\Admin\Admin as EncoreAdmin ;
use Closure;
use Encore\Admin\Grid;

class Admin extends EncoreAdmin
{
    public function grid($model, Closure $callable)
    {
        return new Grid($this->getModel($model), $callable);
    }
}