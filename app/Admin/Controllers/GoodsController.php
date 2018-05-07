<?php

namespace App\Admin\Controllers;

use App\Models\Brand;
use App\Models\Category;
use App\Models\Goods;

use App\Models\GoodsPic;
use App\Models\GoodsType;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Widgets\Tab;
use Illuminate\Support\Facades\DB;

class GoodsController extends Controller
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

            $content->header('商品');
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

            $content->header('商品');
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

            $content->header('商品');
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
        return Admin::grid(Goods::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->column('brand_id','品牌')->display(function ($brand_id){
                $info=Brand::find($brand_id);
                return $info->brand_name ?? '';
            });
            $grid->column('cate_id','分类')->display(function ($cate_id){
                $info=Category::find($cate_id);
                return $info->cate_name ?? '';
            });

            $grid->column('goods_name','商品名称')->display(function ($goods_name){
                return str_limit($goods_name,30);
            })->label();

            $grid->pic('主图')->image(asset('storage').'/',50,50);

            // 链式方法调用来显示多图
            $grid->images('附图')->map(function ($path) {
                return asset('storage').'/'.$path;
            })->image(30,30);


            /*$grid->column('pic','主图')->display(function (){
                $src=asset('storage').'/'.$this->pic;
                $img = "<img src='$src' style='max-width:30px;max-height:30px' class='img'/>";
                return $img;
            });

            //$grid->images('附图')->image();

            $grid->column('images','附图')->display(function ($images){
                if(is_array($images)) {
                    $img='';
                    foreach ($images as $image) {
                        $src = asset('storage') . '/' . $image;
                        $img.= "<img src='$src' style='max-width:30px;max-height:30px' class='img'/>".'&nbsp;&nbsp;';
                    }
                }
                return $img ?? '';
            });*/

            $grid->type('类型')->display(function ($type){
                if(empty($type)){
                    return '';
                }
                $type=array_map(function ($id){
                    $info=GoodsType::find($id);
                    return "<button class='btn btn-info' style='background-color: $info->tag;width: 35px;height: 20px;'></button>".'&nbsp;&nbsp;'.$info->name;
                },$type);

                return join('<br>',$type);
            });

            $grid->market_price('市场价格');
            $grid->mail_price('商城价格');
            $grid->inventory_number('库存量');
            $grid->sale_number('销量');

            $grid->column('标签')->switchGroup([
                'hot'       => '热门',
                'new'       => '最新',
                'recommend' => '推荐',
            ], [
                'on' => ['text' => 'YES'],
                'off' => ['text' => 'NO'],
            ]);

            $grid->status('状态')->switch([
                'on' => ['text' => '展示'],
                'off' => ['text' => '下架'],
            ]);

            /*$grid->status('状态')->display(function ($status){
                return Goods::statusMap()[$status] ?? '未知';
            })->label();*/

            $grid->created_at('添加时间');
            //$grid->updated_at('更改时间');

            $grid->filter(function ($filter) {
                // 去掉默认的id过滤器
                $filter->disableIdFilter();
                // 在这里添加字段过滤器
                $filter->like('goods_name', '商品名称')->placeholder('请输入商品名称');

                $filter->equal('brand_id','品牌')->select(Brand::pluck('brand_name','id'));
                $filter->in('cate_id','分类')->select(Category::selectOptions());

                $filter->between('mail_price','商品价格');

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
        return Admin::form(Goods::class, function (Form $form) {

            //$form->display('id', '编号');

            $id=request()->route()->parameters()['good'] ?? 0;
            if(empty($id)){
                $form->text('goods_name','商品名称')->rules('required|max:100|unique:goods');
            }else{
                $form->text('goods_name','商品名称')->rules('required|max:100|unique:goods,goods_name,'.$id.',id');
            }

            $form->select('brand_id','品牌')->options(function (){
                $brands=Brand::pluck('brand_name','id');
                return $brands->prepend('请选择品牌',0);
            })->rules('required');

            $form->select('cate_id','分类')->options(Category::selectOptions())->rules('required');

            $form->text('goods_keywords','关键字')->rules('required');

            $form->checkbox('type','商品类型')->options(GoodsType::pluck('name','id'))->rules('required');

            $form->text('goods_brief','商品单位')->rules('required');

            $form->currency('market_price','市场价格')->symbol('￥')->rules('required|numeric');
            $form->currency('mail_price','商城价格')->symbol('￥')->rules('required|numeric');

            $form->number('inventory_number','商品库存')->rules('required|integer');

            if ($id){
                $form->switch('status','商品状态');
                //$form->select('status','商品状态')->options(Goods::statusMap());
            }

            $form->switch('hot','热门标签')->rules('required');
            $form->switch('new','最新标签')->rules('required');
            $form->switch('recommend','推荐标签')->rules('required');

            $form->editor('goods_unit','商品简介')->rules('required');

            $form->image('pic','商品主图')->uniqueName()->move('goods','public')->removable()->rules('required');

            $form->multipleImage('images', '商品附图')->uniqueName()->removable();

            $form->display('created_at', '添加时间');
            //$form->display('updated_at', 'Updated At');

        });
    }
}
