<?php

namespace App\Admin\Controllers;

use App\Models\Bonus;
use App\Models\BonusUsers;

use App\Models\Order;
use App\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class BonusUsersController extends Controller
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

            $content->header('用户');
            $content->description('红包列表');

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
        return Admin::grid(BonusUsers::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->column('user.name','用户名字');
            $grid->column('order.order_sn','订单编号');
            $grid->column('bonus.bonus_name','活动名称');
            $grid->money('红包金额');

            $grid->column('status','红包状态')->display(function ($status){
                $info=BonusUsers::find($this->id);
                if($status==1){
                    $str='已使用';
                }else{
                    if($info['bonus']['use_end_at']<time()){
                        $info->status=3;
                        $info->save();
                        $str='过期';
                    }else{
                        $str='未使用';
                    }
                }
                return $str;
            })->label('info');

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->disableCreateButton();
            $grid->disableExport();
            $grid->actions(function ($actions){
                $actions->disableDelete();
                $actions->disableEdit();
            });
            $grid->filter(function ($filter){
                $filter->disableIdFilter();
                $filter->where(function ($query){
                    $username=trim($this->input);
                    $info=User::where('name','like',$username.'%')->select('id')->get();
                    $query->whereIn('user_id',$info);
                },'用户名');

                $filter->where(function ($query){
                    $order_sn=trim($this->input);
                    $info=Order::where('order_sn','like',$order_sn.'%')->select('id')->get();
                    $query->whereIn('order_id',$info);
                },'订单号');

                $filter->equal('bonus_id','红包活动')->select(Bonus::pluck('bonus_name','id'));
                $filter->equal('status','状态')->radio([''=>'全部',1=>'已使用',2=>'未使用',3=>'过期']);
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
        return Admin::form(BonusUsers::class, function (Form $form) {

            $form->display('id', 'ID');

            $form->display('created_at', 'Created At');
            $form->display('updated_at', 'Updated At');
        });
    }
}
