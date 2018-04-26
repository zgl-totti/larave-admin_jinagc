<?php

namespace App\Admin\Controllers;

use App\Models\ArticleCates;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Tree;

class ArticleCateController extends Controller
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

            $content->header('文章');
            $content->description('分类');

            $content->body($this->treeView()->render());
            //$content->body($this->grid());
        });
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        return ArticleCates::tree(function (Tree $tree) {

            $tree->disableSave();

            $tree->branch(function ($branch) {

                //$payload = "<i class='fa {$branch['icon']}'></i>&nbsp;<strong>{$branch['cate_name']}</strong>";

                $payload = "&nbsp;<strong>{$branch['cate_name']}</strong>";

                return $payload;
            });
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

            $content->header('文章');
            $content->description('分类');

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

            $content->header('文章');
            $content->description('分类');

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
        return Admin::grid(ArticleCates::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

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
        return Admin::form(ArticleCates::class, function (Form $form) {

            //$form->display('id', '编号');

            $form->text('cate_name','名称')->rules('required');

            $form->select('parent_id','父类')->options(ArticleCates::selectOptions())->rules('required');


            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
