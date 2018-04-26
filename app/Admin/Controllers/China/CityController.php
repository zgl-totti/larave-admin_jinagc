<?php

namespace App\Admin\Controllers\China;

use App\Models\ChinaArea;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class CityController extends Controller
{
    use ModelForm;

    /**
     * Index interface.
     *
     * @return Content
     */
    public function index()
    {
        return Admin::content(function (Content $content) {
            $content->header('城市');
            $content->description('列表');
            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {
            $content->header('City');
            $content->description('description');
            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    public function create()
    {
        return Admin::content(function (Content $content) {
            $content->header('Country');
            $content->description('description');
            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(ChinaArea::class, function (Grid $grid) {

            $grid->model()->city();

            $grid->name('城市')->editable();

            $grid->children('县/区')->pluck('name')->label();

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();
                $filter->like('name','城市');
                $filter->equal('parent_id', '省')->select(ChinaArea::province()->pluck('name', 'id'));
            });

            $grid->disableActions();
            $grid->disableCreation();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(ChinaArea::class, function (Form $form) {

            $form->display('id');
            $form->text('name');
        });
    }
}
