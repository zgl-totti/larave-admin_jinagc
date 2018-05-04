<?php

namespace App\Admin\Controllers;

use App\Models\UsersIntegral;

use App\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class UsersIntegralController extends Controller
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

            $content->header('会员积分');
            $content->description('详情');

            $content->body($this->grid());
        });
    }

    /**
     * Edit interface.
     *
     * @param $id
     * @return Content
     */
    /*public function edit($id)
    {
        return Admin::content(function (Content $content) use ($id) {

            $content->header('header');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }*/

    /**
     * Create interface.
     *
     * @return Content
     */
    /*public function create()
    {
        return Admin::content(function (Content $content) {

            $content->header('header');
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
        return Admin::grid(UsersIntegral::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->column('user.name','用户')->label();
            $grid->integral('积分');
            $grid->integral_source('积分来源')->display(function ($source){
                return UsersIntegral::sourceMap()[$source] ?? '未知';
            })->label('info');

            $grid->column('order.order_sn','订单号');

            $grid->created_at('生成时间');
            //$grid->updated_at();

            $grid->disableExport();
            $grid->disableCreateButton();

            $grid->actions(function ($actions){
                $actions->disableDelete();
                $actions->disableEdit();
            });

            $grid->filter(function ($filter){
                $filter->disableIdFilter();

                $filter->where(function ($query){

                    $username=$this->input;
                    $info=User::where('name','like',$username.'%')->select('id')->get();

                    $query->whereIn('user_id',$info);

                },'用户名');

                $filter->equal('integral_source', '积分来源')->radio(['' => '全部'] + UsersIntegral::sourceMap());
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
        return Admin::form(UsersIntegral::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
