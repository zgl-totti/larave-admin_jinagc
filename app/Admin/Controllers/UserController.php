<?php

namespace App\Admin\Controllers;

use App\User;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;

class UserController extends Controller
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

            $content->header('会员');
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

            $content->header('header');
            $content->description('description');

            $content->body($this->form());
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid($type='')
    {
        return Admin::grid(User::class, function (Grid $grid) use ($type){

            $grid->id('编号')->sortable();
            $grid->name('用户名')->label();
            $grid->email('邮箱');

            $grid->status('状态')->switch([
                'on' => ['text' => '显示'],
                'off' => ['text' => '停用'],
            ]);

            /*$grid->status('状态')->display(function ($status){
                return User::statusMap()[$status] ?? '未知';
            })->badge();*/

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->disableCreateButton();

            $grid->filter(function ($filter){
                $filter->disableIdFilter();

                $filter->like('name', '用户名')->placeholder('请输入用户名');

                $filter->equal('status', '状态')->radio(['' => '全部'] + User::statusMap());
            });

            if($type=='today'){
                $grid->model()->whereDay('created_at',date('d'));
            }elseif ($type=='week'){
                $week=date('w');
                $time=strtotime(date('Y-m-d'))-($week-1)*86400;
                $grid->model()->whereDate('created_at','>',date('Y-m-d',$time));
            }elseif ($type=='month'){
                $grid->model()->whereMonth('created_at',date('m'));
            }

        });
    }

    /**
     * Make a form builder.
     *
     * @return Form
     */
    protected function form()
    {
        return Admin::form(User::class, function (Form $form) {

            //$form->display('id', 'ID');

            $id=request()->route()->parameters()['user'];
            $form->text('name','用户名')->rules('required|max:20|min:5|unique:users,name,'.$id.',id');
            $form->text('email','邮箱')->rules('required|email|unique:users,email,'.$id.',id');

            $form->switch('status','状态');
            //$form->select('status','状态')->options(User::statusMap())->rules('required');

            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }


    /**
     * 新增会员列表
     * @param string $type
     * @return Content
     * @author totti_zgl
     * @date 2018/4/19 17:15
     */
    public function newly(string $type)
    {
        if($type=='today'){
            $header='今日';
        }elseif($type=='week'){
            $header='本周';
        }elseif($type=='month'){
            $header='本月';
        }else{
            return redirect()->back();
        }

        return Admin::content(function (Content $content) use ($header,$type){

            $content->header($header.'新增会员');
            $content->description('列表');

            $content->body($this->grid($type));
        });

    }
}
