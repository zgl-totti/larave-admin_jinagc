<?php

namespace App\Admin\Controllers;

use App\Models\UsersLevel;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class UsersLevelController extends Controller
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

            $content->header('会员等级');
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

            $content->header('会员');
            $content->description('等级');

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

            $content->header('会员');
            $content->description('等级');

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
        return Admin::grid(UsersLevel::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->level_name('会员等级')->label();
            $grid->level_integral('等级积分');
            $grid->status('状态')->switch([
                'on'=>['text'=>'展示'],
                'off'=>['text'=>'下架']
            ]);

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->disableExport();
            $grid->disableFilter();
        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(UsersLevel::class, function (Form $form) {

            //$form->display('id', 'ID');
            $id=request()->route()->parameters()['users_level'] ?? 0;
            if($id){
                $form->text('level_name','会员等级')->rules('required|unique:users_level,level_name,'.$id.',id');
            }else{
                $form->text('level_name','会员等级')->rules('required|unique:users_level');
            }

            $form->number('level_integral','等级积分');

            if($id){
                $form->switch('status','状态');
            }

            $form->display('created_at', '创建时间');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
