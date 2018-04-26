<?php

namespace App\Admin\Controllers;

use App\Models\Bonus;

use App\Models\Source;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class BonusController extends Controller
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

            $content->header('红包活动');
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

            $content->header('红包');
            $content->description('活动');

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

            $content->header('红包');
            $content->description('活动');

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
        return Admin::grid(Bonus::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->bonus_name('活动名称');
            $grid->bonus_price('红包金额');
            $grid->order_price('最小订单金额');
            $grid->source('适用范围')->display(function ($sources){
                $sources=array_map(function ($source){
                    return Source::find($source)->source_name ?? '';
                },$sources);
                return join('<br>',$sources);
            });
            $grid->status('状态')->switch([
                'on'=>['text'=>'展示'],
                'off'=>['text'=>'下架']
            ]);
            $grid->begin_at('活动开始时间');
            $grid->end_at('活动结束时间');
            $grid->use_begin_at('红包开始使用');
            $grid->use_end_at('红包结束使用');

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->filter(function ($filter){
                $filter->disableIdFilter();
                $filter->like('bonus_name','红包名称')->placeholder('请输入品牌名称');
                $filter->equal('status', '状态')->radio(['' => '全部'] + Bonus::statusMap());
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
        return Admin::form(Bonus::class, function (Form $form) {

            //$form->display('id', 'ID');

            $id=request()->route()->parameters()['bonus'] ?? 0;
            if($id){
                $form->text('bonus_name','活动名称')->rules('required|unique:bonus,bonus_name,'.$id.',id');
            }else{
                $form->text('bonus_name','活动名称')->rules('required|unique:bonus,bonus_name');
            }

            $form->currency('bonus_price','红包金额')->symbol('￥')->rules('required');
            $form->currency('order_price','最小订单金额')->symbol('￥')->rules('required');
            $form->checkbox('source','适用范围')->options(Source::pluck('source_name','id'));

            if($id){
                $form->switch('status');
            }
            $form->datetimeRange('begin_at','end_at','活动时间');
            $form->datetimeRange('use_begin_at','use_end_at','红包使用时间');

            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
