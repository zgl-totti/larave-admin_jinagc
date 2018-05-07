<?php

namespace App\Admin\Controllers;

use App\Models\Goods;
use App\Models\GoodsType;
use App\Models\Seckill;

use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class SeckillController extends Controller
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

            $content->header('秒杀活动');
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

            $content->header('秒杀');
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

            $content->header('秒杀');
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
        return Admin::grid(Seckill::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->title('活动标题')->label();
            $grid->column('goods.goods_name','商品名称')->label('info');

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

            $grid->price('秒杀价格');
            $grid->num('商品数量');
            $grid->limit('限购数量');

            $grid->status('状态')->switch([
                'on' => ['text' => '展示'],
                'off' => ['text' => '下架'],
            ]);

            $grid->begin_at('开始时间');
            $grid->end_at('结束时间');

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->filter(function ($filter) {
                $filter->disableIdFilter();

                $filter->like('title', '活动标题')->placeholder('请输入活动标题');

                $filter->between('begin_at','活动开始时间')->datetime();

                $filter->equal('status', '状态')->radio(['' => '全部'] + Seckill::statusMap());
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
        return Admin::form(Seckill::class, function (Form $form) {

            //$form->display('id', 'ID');

            $id=request()->route()->parameters()['seckill'] ?? 0;
            if(empty($id)){
                $form->text('title','活动标题')->rules('required|unique:seckill');
            }else {
                $form->text('title','活动标题')->rules('required|unique:seckill,title,'.$id.',id');
            }

            $form->select('goods_id','商品名称')->options(function (){
                $goods=Goods::where('status',1)->pluck('goods_name','id');
                return $goods->prepend('请选择商品',0);
            })->load('type',url('admin/type'))->rules('required');

            if($id){
                $info=Seckill::with('goods')->where('id',$id)->first();
                $type=GoodsType::whereIn('id',$info->goods->type)->pluck('name','id');
            }else{
                $type=[];
            }
            $form->multipleSelect('type','商品类型')->options($type);

            $form->currency('price','秒杀价格')->symbol('￥')->rules('required|numeric');
            $form->number('num','商品数量')->rules('required|integer');
            $form->number('limit','限购数量')->rules('required|integer');

            if($id){
                $form->switch('status','状态');
            }

            $form->datetimeRange('begin_at','end_at','活动时间');

            /*$form->datetime('begin_at','开始时间');
            $form->datetime('end_at','结束时间');*/

            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }
}
