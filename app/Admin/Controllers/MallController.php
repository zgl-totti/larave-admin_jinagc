<?php

namespace App\Admin\Controllers;

use App\Models\Mall;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class MallController extends Controller
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

            $content->header('商城设置');
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

            $content->header('商城设置');
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

            $content->header('商城');
            $content->description('平台');

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
        return Admin::grid(Mall::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->mall_name('商城名称')->label();
            $grid->keywords('关键字')->label('info');
            $grid->column('describe','描述')->display(function ($describe){
               return str_limit(strip_tags($describe),20);
            });
            $grid->logo('LOGO')->image(asset('storage').'/',50,50);
            $grid->hotline('热线电话');
            $grid->support_phone('售后电话');
            $grid->qq('客服QQ');
            $grid->ICP('备案号');
            $grid->certificate('证书编号');

            $grid->status('状态')->switch([
                'on' => ['text' => '展示'],
                'off' => ['text' => '下架'],
            ]);

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->disableExport();

            $grid->filter(function ($filter){
                $filter->disableIdFilter();

                $filter->like('mall_name', '商城名称')->placeholder('请输入商城名称');

                $filter->equal('status', '状态')->radio(['' => '全部'] + Mall::statusMap());
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
        return Admin::form(Mall::class, function (Form $form) {

            //$form->display('id', '编号');

            $id=request()->route()->parameters()['mall'] ?? 0;

            if($id){
                $form->text('mall_name','商城名称')->rules('required|unique:mall,mall_name,'.$id.',id');
            }else{
                $form->text('mall_name','商城名称')->rules('required|unique:mall');
            }

            $form->text('keywords','关键字')->rules('required');
            $form->text('describe','描述')->rules('required');
            $form->image('logo','LOGO')->uniqueName()->move('mall','public')->rules('required');
            $form->mobile('hotline','热线电话')->rules('required');
            $form->mobile('support_phone','售后电话')->rules('required');
            $form->text('qq','客服QQ')->rules('required');
            $form->text('ICP','备案号')->rules('required');
            $form->text('certificate','证书编号')->rules('required');

            if($id){
                $form->switch('status','状态');
            }

            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
