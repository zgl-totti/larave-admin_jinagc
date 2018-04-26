<?php

namespace App\Admin\Controllers;

use App\Models\Express;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class ExpressController extends Controller
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

            $content->header('快递');
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

            $content->header('快递');
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

            $content->header('快递');
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
        return Admin::grid(Express::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->express_name('快递公司')->label();
            //$grid->express_price('快递费');

            $grid->status('状态')->switch([
                'on'=>['text'=>'展示'],
                'off'=>['text'=>'下架']
            ]);

            /*$grid->status('状态')->display(function ($status){
               return Express::statusMap()[$status] ?? '未知';
            })->label();*/

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
        return Admin::form(Express::class, function (Form $form) {

            //$form->display('id', 'ID');

            $id=request()->route()->parameters()['express'];
            if (empty($id)){
                $form->text('express_name','快递公司')->rules('required|unique:express');
            }else{
                $form->text('express_name','快递公司')->rules('required|unique:express,express_name,'.$id.',id');
            }

            //$form->currency('express_price','快递费')->rules('required|numeric');

            if($id){
                $form->switch('status','状态');
                //$form->select('status','状态')->options(Express::statusMap());
            }

            $form->display('created_at', '创建时间');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
