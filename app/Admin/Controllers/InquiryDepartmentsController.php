<?php

namespace App\Admin\Controllers;

use App\Models\InquiryDepartments;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class InquiryDepartmentsController extends Controller
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

            $content->header('科室');
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

            $content->header('科室');
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

            $content->header('科室');
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
        return Admin::grid(InquiryDepartments::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->cate_name('科室名称');
            $grid->status('状态')->switch([
                'on'=>['text'=>'展示'],
                'off'=>['text'=>'下架']
            ]);

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->disableExport();
            $grid->filter(function ($filter){
                $filter->disableIdFilter();
                $filter->like('cate_name','分类名称')->placeholder('请输入分类名称');
                $filter->equal('status','状态')->radio([''=>'全部'] + InquiryDepartments::statusMap());
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
        return Admin::form(InquiryDepartments::class, function (Form $form) {

            //$form->display('id', 'ID');

            $id=request()->route()->parameters()['inquiry_department'] ?? 0;
            if($id){
                $form->text('cate_name','分类名称')->rules('required|unique:inquiry_departments,cate_name,'.$id.',id');
            }else{
                $form->text('cate_name','分类名称')->rules('required|unique:inquiry_departments');
            }

            if($id){
                $form->switch('status','状态');
            }

            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
