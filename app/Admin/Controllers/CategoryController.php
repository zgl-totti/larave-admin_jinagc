<?php

namespace App\Admin\Controllers;

use App\Models\Category;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Tree;

class CategoryController extends Controller
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

            $content->header('分类');
            $content->description('列表');

            $content->body($this->treeView()->render());

            //$content->body($this->grid());
        });
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        return Category::tree(function (Tree $tree) {

            $tree->disableSave();

            $tree->branch(function ($branch) {

                //$payload = "<i class='fa {$branch['icon']}'></i>&nbsp;<strong>{$branch['cate_name']}</strong>";

                $payload = "&nbsp;<strong>{$branch['cate_name']}</strong>";

                return $payload;
            });

            /*//修改查询
            $tree->query(function ($model) {
                return $model->where('status', 1);
            });*/

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

            $content->header('分类');
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

            $content->header('分类');
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
        return Admin::grid(Category::class, function (Grid $grid) {

            $grid->id('编号')->sortable();
            $grid->column('cate_name','名称')->display(function ($cate_name){
                return str_limit($cate_name,30);
            });
            $grid->parent_id('父类')->display(function ($parent_id){
                $info=Category::find($parent_id);
                return $info->cate_name ?? 'ROOT';
            });
            //$grid->column('path','分类路径');
            $grid->status('状态')->display(function ($status){
                return Category::statusMap()[$status] ?? '未知';
            });

            $grid->created_at('添加时间');
            //$grid->updated_at('修改时间');

            $grid->filter(function ($filter) {
                // 去掉默认的id过滤器
                $filter->disableIdFilter();
                // 在这里添加字段过滤器
                $filter->like('cate_name', '名称')->placeholder('请输入分类名称');
                $filter->equal('status', '状态')->radio(['' => '全部'] + Category::statusMap());
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
        return Admin::form(Category::class, function (Form $form) {

            $form->display('id', '编号');

            $id=request()->route()->parameters()['category'] ?? 0;

            if($id){
                $form->text('cate_name','名称')->rules('required|unique:category,cate_name,'.$id.',id');
            }else{
                $form->text('cate_name','名称')->rules('required|unique:category');
            }

            $form->select('parent_id','父类')->options(Category::selectOptions())->rules('required');

            if($id){
                $form->select('status','状态')->options(Category::statusMap());
            }

            $form->display('created_at', '创建时间');
            //$form->display('updated_at', 'Updated At');

            $form->saved(function (Form $form){
                $info=Category::find($form->model()->id);
                if($info->parent_id==0){
                    $path=$form->model()->id;
                }else{
                    $parent=Category::find($info->parent_id);
                    $path=$parent->cate_path.','.$form->model()->id;
                }
                $info->cate_path=$path;
                $info->save();
            });

        });
    }
}
