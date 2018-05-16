<?php

namespace App\Admin\Controllers;

use App\Models\NotificationType;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class NotificationTypeController extends Controller
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

            $content->header('消息');
            $content->description('类型');

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

            $content->header('消息');
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

            $content->header('消息');
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
        return Admin::grid(NotificationType::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->type_name('类型名称');

            $grid->status('状态')->switch([
                'on'=>['text'=>'展示'],
                'off'=>['text'=>'下架']
            ]);

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->disableExport();
            $grid->filter(function ($filter){
                $filter->disableIdFilter();
                $filter->like('type_name','类型名称')->placeholder('请输入类型名称');
                $filter->equal('status','状态')->radio([''=>'全部'] + NotificationType::statusMap());
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
        return Admin::form(NotificationType::class, function (Form $form) {

            //$form->display('id', 'ID');

            $id=request()->route()->parameters['notification_type'] ?? 0;

            if($id){
                $form->text('type_name','类型名称')->rules('required|unique:notification_type,type_name,'.$id.',id');
            }else{
                $form->text('type_name','类型名称')->rules('required|unique:notification_type');
            }

            if($id){
                $form->switch('status','状态')->rules('required');
            }

            $form->display('created_at', '创建时间');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
