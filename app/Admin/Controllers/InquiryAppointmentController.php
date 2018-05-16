<?php

namespace App\Admin\Controllers;

use App\Models\InquiryAppointment;

use App\Models\InquiryDepartments;
use App\Models\InquiryExpert;
use App\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InquiryAppointmentController extends Controller
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

            $content->header('预约');
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

            $content->header('预约');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Create interface.
     *
     * @return Content
     */
    /*public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('预约');
            $content->description('description');

            $content->body($this->form());
        });
    }*/

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid()
    {
        return Admin::grid(InquiryAppointment::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->column('user.name','预约者');
            $grid->phone('预约电话');
            $grid->column('departments.cate_name','科室');
            $grid->column('expert.username','专家');
            $grid->column('source.source_name','来源');

            $grid->type('预约方式')->display(function ($type){
                return InquiryAppointment::typeMap()[$type] ?? '未知';
            });

            $grid->appointment_time('预约时间');

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->disableCreateButton();
            $grid->filter(function ($filter){
                $filter->disableIdFilter();

                $filter->where(function ($query){

                    $username=$this->input;
                    $info=User::where('name','like',$username.'%')->select('id')->get();

                    $query->whereIn('user_id',$info);

                },'预约者');

                $filter->equal('departments_id','科室')
                    ->select(InquiryDepartments::pluck('cate_name','id'))
                    ->load('expert_id',url('admin/expert'));

                $filter->equal('expert_id','专家')->select();
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
        return Admin::form(InquiryAppointment::class, function (Form $form) {

            //$form->display('id', 'ID');

            $form->display('user.name','预约者');
            $form->mobile('phone','预约电话')->rules('required|regex:/^1[34578]\d{9}$/');
            $form->select('departments_id','科室')->options(InquiryDepartments::pluck('cate_name','id'))->load('expert_id',url('admin/expert'));
            $form->select('expert_id','专家')->options(function ($id){
                return InquiryExpert::where('departments_id',InquiryExpert::find($id)->departments_id)->pluck('username','id');
            })->rules('required|integer');

            $form->radio('type','预约方式')->options(InquiryAppointment::typeMap())->rules('required');
            $form->date('appointment_time','预约时间')->rules('required');

            $form->display('created_at', '创建时间');
            //$form->display('updated_at', 'Updated At');
        });
    }


    public function expert(Request $request)
    {
        $departments_id=$request->get('q');
        $list=InquiryExpert::where('departments_id',$departments_id)->get([DB::raw('username as text'),'id']);
        return $list->prepend(['text'=>'全部','id'=>' ']);
    }
}
