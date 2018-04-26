<?php

namespace App\Admin\Controllers;

use App\Models\Brand;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class BrandController extends Controller
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

            $content->header('品牌');
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

            $content->header('品牌');
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

            $content->header('品牌');
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
        return Admin::grid(Brand::class, function (Grid $grid) {

            $grid->id('编号')->sortable();
            $grid->brand_name('名称')->label();

            $grid->logo('图标')->image(asset('storage').'/',50,50);

            /*$grid->column('logo','图标')->display(function (){
                //$src=storage_path('app/public/').$this->logo;
                $src=asset('storage').'/'.$this->logo;
                $img = "<img src='$src' style='max-width:30px;max-height:30px' class='img'/>";
                return $img;
            });*/

            $grid->status('状态')->switch([
                'on' => ['text' => '展示'],
                'off' => ['text' => '下架'],
            ]);

            /*$grid->column('status','状态')->display(function () use ($statusMap){
                return $statusMap[$this->status] ?? '未知';
            })->label();*/

            $grid->created_at('添加时间');
            $grid->updated_at('修改时间');
            $grid->disableExport();

            $grid->filter(function ($filter) {
                // 去掉默认的id过滤器
                $filter->disableIdFilter();
                // 在这里添加字段过滤器
                $filter->like('brand_name', '名称')->placeholder('请输入品牌名称');
                $filter->equal('status', '状态')->radio(['' => '全部'] + Brand::statusMap());
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
        return Admin::form(Brand::class, function (Form $form) {

            //$form->display('id', 'ID');

            //获取id
            $id=request()->route()->parameters()['brand'] ?? 0;

            if(empty($id)){
                $form->text('brand_name','名称')->rules('required|max:100|unique:brand');
            }else{
                $form->text('brand_name','名称')->rules('required|max:100|unique:brand,brand_name,'.$id.',id');
            }
            $form->image('logo','图标')->uniqueName()->move('brand','public')->rules('required');

            if ($id){
                $form->switch('status','状态');
                //$form->select('status','状态')->options(Brand::statusMap());
            }

            $form->display('created_at', '创建时间');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
