<?php

namespace App\Admin\Controllers;

use App\Models\Source;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class SourceController extends Controller
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

            $content->header('订单来源');
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

            $content->header('订单');
            $content->description('来源');

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

            $content->header('订单');
            $content->description('来源');

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
        return Admin::grid(Source::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->source_name('订单来源')->label();

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->filter(function ($filter){
                $filter->disableIdFilter();
                $filter->like('source_name','来源名称')->placeholder('请输入来源名称');
            });
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(Source::class, function (Form $form) {

            //$form->display('id', 'ID');

            $id=request()->route()->parameters()['source'] ?? 0;
            if($id){
                $form->text('source_name','订单来源')->rules('required|unique:source,source_name,'.$id.',id');
            }else{
                $form->text('source_name','订单来源')->rules('required|unique:source');
            }

            $form->display('created_at', '创建时间');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
