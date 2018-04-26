<?php
namespace App\Admin\Contracts ;

use Encore\Admin\Grid\Model as EncoreGridModel;

class Model extends EncoreGridModel
{
    public function getQueries()
    {
        return $this->queries;
    }
}