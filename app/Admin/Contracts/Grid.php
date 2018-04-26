<?php
namespace App\Admin\Contracts;

use Encore\Admin\Grid as EncoreGrid;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Collection;
use Closure;

class Grid extends EncoreGrid
{
    protected $queryModel;

    public function __construct(Eloquent $model, Closure $builder)
    {
        $this->keyName = $model->getKeyName();
        $this->model = new Model($model);
        $this->columns = new Collection();
        $this->rows = new Collection();
        $this->builder = $builder;

        $this->setupTools();
        $this->setupFilter();
        $this->setupExporter();
    }

    /**
     * 获取包含查询条件的model
     * @return Eloquent
     */
    public function getQuerylModel()
    {
        $queries = $this->model()
            ->addConditions($this->getFilter()->conditions())
            ->getQueries();
        $this->queryModel=  $this->model()->eloquent();
        $queries->unique()->reject(function ($query) {
            return $query['method'] == 'paginate';
        })->each(function ($query)  {
            $this->queryModel = $this->queryModel->{$query['method']}(...$query['arguments']);
        });

        return $this->queryModel;
    }


}