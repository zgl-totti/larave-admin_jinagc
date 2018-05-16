<?php

namespace App\Admin\Controllers;

use App\Models\InquiryDepartments;
use App\Models\InquiryExpert;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class InquiryExpertController extends Controller
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

            $content->header('专家');
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

            $content->header('专家');
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

            $content->header('专家');
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
        return Admin::grid(InquiryExpert::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->username('专家名字');
            $grid->column('departments.cate_name','科室名称');
            $grid->age('年龄');
            $grid->gender('性别')->display(function ($gender){
                return InquiryExpert::genderMap()[$gender] ?? '未知';
            });
            $grid->positional_title('职称');
            $grid->status('状态')->switch([
                'on'=>['text'=>'展示'],
                'off'=>['text'=>'下架']
            ]);

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->disableExport();
            $grid->filter(function ($filter){
                $filter->disableIdFilter();
                $filter->like('username','专家名字')->placeholder('请输入专家名字');

                $filter->equal('departments_id','科室名称')->select(InquiryDepartments::where('status',1)->pluck('cate_name','id'));

                $filter->equal('status','状态')->radio([''=>'全部'] + InquiryExpert::statusMap());
                $filter->equal('gender','性别')->radio([''=>'全部'] + InquiryExpert::genderMap());
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
        return Admin::form(InquiryExpert::class, function (Form $form) {

            //$form->display('id', 'ID');

            $id=request()->route()->parameters()['inquiry_expert'] ?? 0;
            if($id){
                $form->text('username','名字')->rules('required|unique:inquiry_expert,username,'.$id.',id');
            }else{
                $form->text('username','名字')->rules('required|unique:inquiry_expert');
            }

            $departments=InquiryDepartments::where('status',1)->pluck('cate_name','id');
            $form->select('departments_id','科室')->options($departments->prepend('请选择科室',0))->rules('required');
            $form->number('age','年龄')->rules('required|integer');
            $form->radio('gender','性别')->options(InquiryExpert::genderMap())->rules('required');

            $form->text('positional_title','职称')->rules('required');

            if($id){
                $form->switch('status','状态');
            }

            $form->editor('intro','介绍')->rules('required');

            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
