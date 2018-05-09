<?php

namespace App\Admin\Controllers;

use App\Models\Navigation;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class NavigationController extends Controller
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

            $content->header('导航');
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

            $content->header('导航');
            $content->description('');

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

            $content->header('导航');
            $content->description('新建');

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
        return Admin::grid(Navigation::class, function (Grid $grid) {

            $grid->id('编号')->sortable();
            $grid->nav_name('导航名称');
            $grid->nav_url('导航链接');
            $grid->priority('优先度')->editable();
            $grid->status('状态')->switch([
                'on'=>['text'=>'展示'],
                'off'=>['text'=>'下架']
            ]);

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->disableExport();

            $grid->filter(function ($filter){
                $filter->disableIdFilter();
                $filter->like('nav_name','商品名称')->placeholder('请输入商品名称');
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
        return Admin::form(Navigation::class, function (Form $form) {

            //$form->display('id', 'ID');

            $id=request()->route()->parameters()['navigation'] ?? 0;
            if($id){
                $form->text('nav_name','导航名称')->rules('required|unique:navigation,nav_name,'.$id.',id');
            }else{
                $form->text('nav_name','导航名称')->rules('required|unique:navigation');
            }

            $form->text('nav_url','导航链接')->rules('required');

            $form->number('priority','优先度');

            if($id){
                $form->switch('status','状态');
            }

            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
