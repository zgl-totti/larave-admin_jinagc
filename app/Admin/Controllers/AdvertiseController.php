<?php

namespace App\Admin\Controllers;

use App\Models\Advertise;

use App\Models\AdvertisePosition;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class AdvertiseController extends Controller
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

            $content->header('广告');
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

            $content->header('广告');
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

            $content->header('广告');
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
        return Admin::grid(Advertise::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->advertise_name('广告名字')->label();
            //$grid->column('position.position_name','广告位置');

            $grid->image('图片')->image(asset('storage').'/',50,50);

            $grid->column('position_id','广告位置')->display(function ($position){
                $info=AdvertisePosition::find($position);
                if($info->parent_id != 0){
                    $parent=AdvertisePosition::find($info->parent_id);
                    $str=$parent->position_name;
                }else{
                    $str='';
                }
                return $str.$info->position_name;
            })->label('info');

            $grid->column('position.width','广告宽度');
            $grid->column('position.height','广告高度');

            $grid->status('状态')->switch([
                'on'=>['text'=>'展示'],
                'off'=>['text'=>'下架'],
            ]);

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->disableExport();

            $grid->filter(function ($filter){
                $filter->disableIdFilter();

                $filter->like('advertise_name', '广告名称')->placeholder('请输入广告名称');
                $filter->in('position_id','广告位置')->select(AdvertisePosition::selectOptions());
                $filter->equal('status', '状态')->radio(['' => '全部'] + Advertise::statusMap());
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
        return Admin::form(Advertise::class, function (Form $form) {

            //$form->display('id', 'ID');

            $id=request()->route()->parameters()['advertise'] ?? 0;
            if($id){
                $form->text('advertise_name','广告名字')->rules('required|unique:advertise,advertise_name,'.$id.',id');
            }else{
                $form->text('advertise_name','广告名字')->rules('required|unique:advertise');
            }

            $form->select('position_id','广告位置')->options(AdvertisePosition::selectOptions());
            $form->image('image','图片')->uniqueName()->move('advertise','public')->rules('required');

            if($id){
                $form->switch('status','状态');
            }

            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
