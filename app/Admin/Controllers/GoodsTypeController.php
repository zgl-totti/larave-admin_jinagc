<?php

namespace App\Admin\Controllers;

use App\Models\GoodsType;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class GoodsTypeController extends Controller
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

            $content->header('商品类型');
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

            $content->header('商品');
            $content->description('类型');

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

            $content->header('商品');
            $content->description('类型');

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
        return Admin::grid(GoodsType::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->name('类型');

            $grid->tag('标签')->color();

            $grid->created_at('创建时间');
            //$grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(GoodsType::class, function (Form $form) {

            //$form->display('id', 'ID');

            $id=request()->route()->parameters()['goods_type'] ?? 0;
            if($id){
                $form->text('name','名称')->rules('required|unique:goods_type,name,'.$id.',id');
            }else{
                $form->text('name','名称')->rules('required|unique:goods_type');
            }

            $form->color('tag', '标签')->default('#ccc');

            $form->display('created_at', '创建时间');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
