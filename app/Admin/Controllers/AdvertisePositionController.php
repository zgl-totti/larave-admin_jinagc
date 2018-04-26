<?php

namespace App\Admin\Controllers;

use App\Models\AdvertisePosition;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Tree;

class AdvertisePositionController extends Controller
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

            $content->header('广告位置');
            $content->description('description');

            $content->body($this->treeView());
            //$content->body($this->grid());
        });
    }

    /**
     * @return \Encore\Admin\Tree
     */
    protected function treeView()
    {
        return AdvertisePosition::tree(function (Tree $tree) {

            $tree->disableSave();

            $tree->branch(function ($branch) {

                //$payload = "<i class='fa {$branch['icon']}'></i>&nbsp;<strong>{$branch['cate_name']}</strong>";

                $payload = "&nbsp;<strong>{$branch['position_name']}</strong>";

                return $payload;
            });

            //修改查询
            $tree->query(function ($model) {
                return $model->where('status', 1);
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

            $content->header('广告位置');
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

            $content->header('广告位置');
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
        return Admin::grid(AdvertisePosition::class, function (Grid $grid) {

            $grid->id('ID')->sortable();

            $grid->created_at();
            $grid->updated_at();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(AdvertisePosition::class, function (Form $form) {

            //$form->display('id', 'ID');

            $id=request()->route()->parameters()['advertise_position'] ?? 0;
            if($id){
                $form->text('position_name','广告名称')->rules('required|unique:advertise_position,position_name,'.$id.',id');
            }else{
                $form->text('position_name','广告名称')->rules('required|unique:advertise_position');
            }

            $form->select('parent_id','父类')->options(AdvertisePosition::selectOptions())->rules('required');

            $form->number('width','广告宽度')->rules('required|integer');
            $form->number('height','广告高度')->rules('required|integer');

            if($id){
                $form->switch('status','状态');
            }

            $form->display('created_at', '创建时间');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
