<?php

namespace App\Admin\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Goods;
use App\Models\IntegralGoods;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class IntegralGoodsController extends Controller
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

            $content->header('积分商品');
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

            $content->header('积分商品');
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

            $content->header('积分商品');
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
        return Admin::grid(IntegralGoods::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->column('品牌')->display(function (){
                $goods=Goods::where('id',$this->goods_id)->select('brand_id')->first();
                $info=Brand::find($goods->brand_id);
                return $info->brand_name ?? '';
            });
            $grid->column('分类')->display(function (){
                $goods=Goods::where('id',$this->goods_id)->select('cate_id')->first();
                $info=Category::find($goods->cate_id);
                return $info->cate_name ?? '';
            });

            $grid->column('goods.goods_name','商品名称')->label();
            $grid->integral('积分');

            $grid->status('状态')->switch([
                'on' => ['text' => '展示'],
                'off' => ['text' => '下架'],
            ]);

            $grid->created_at('添加时间');
            //$grid->updated_at();

            $grid->filter(function ($filter) {
                // 去掉默认的id过滤器
                $filter->disableIdFilter();

                $filter->between('integral','积分');

                $filter->equal('status', '状态')->radio(['' => '全部'] + Goods::statusMap());
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
        return Admin::form(IntegralGoods::class, function (Form $form) {

            //$form->display('id', 'ID');

            $id=request()->route()->parameters()['integral_good'] ?? 0;
            if(empty($id)){
                $form->select('goods_id','商品名称')->options(function (){
                    $brands=Goods::where('status',1)->pluck('goods_name','id');
                    return $brands->prepend('请选择商品',0);
                })->rules('required|unique:integral_goods');
            }else {
                $form->select('goods_id', '商品名称')->options(function () {
                    $brands = Goods::where('status', 1)->pluck('goods_name', 'id');
                    return $brands->prepend('请选择商品', 0);
                })->rules('required|unique:integral_goods,goods_id,' . $id . ',id');
            }

            $form->number('integral','积分');

            $form->switch('status','状态');

            $form->display('created_at', '创建时间');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
