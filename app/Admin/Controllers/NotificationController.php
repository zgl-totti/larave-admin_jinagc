<?php

namespace App\Admin\Controllers;

use App\Models\Goods;
use App\Models\IntegralGoods;
use App\Models\Notification;

use App\Models\NotificationType;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NotificationController extends Controller
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

            $content->header('消息');
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

            $content->header('消息');
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

            $content->header('消息');
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
        return Admin::grid(Notification::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->title('标题');
            $grid->column('type.type_name','类型');

            $grid->column('intro','简介')->display(function ($intro){
                return str_limit($intro,20);
            });

            $grid->column('resource','资源名称')->display(function ($resource) {
                if($this->type_id==1){
                    return Goods::find($resource)->goods_name;
                }elseif ($this->type_id==2){
                    $info=IntegralGoods::with('goods')->where('id',$resource)->first();
                    return $info->goods->goods_name ?? '';
                }else{
                    return $resource;
                }
            });

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->disableExport();
            $grid->filter(function ($filter){
                $filter->disableIdFilter();
                $filter->like('title','标题')->placeholder('请输入标题');
                $filter->equal('type_id','类型')->select(NotificationType::pluck('type_name','id'));
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
        return Admin::form(Notification::class, function (Form $form) {

            //$form->display('id', 'ID');

            $id=request()->route()->parameters['notification'] ?? 0;

            if($id){
                $form->text('title','消息标题')->rules('required|unique:notification,title,'.$id.',id');
            }else{
                $form->text('title','消息标题')->rules('required|unique:notification');
            }

            $type=NotificationType::where('status',1)->pluck('type_name','id');
            $form->select('type_id','消息类型')->options($type)->load('resource',url('admin/resource'));
            $form->select('resource','资源名称');

            $form->text('intro','简介')->rules('required');
            $form->editor('content','内容')->rules('required');

            $form->display('created_at', '创建时间');
            //$form->display('updated_at', 'Updated At');
        });
    }


    public function resource(Request $request)
    {
        $type_id = $request->get('q');
        if($type_id==1){
            return Goods::get([DB::raw('goods_name as text'),'id']);
        }elseif($type_id==2){
            return IntegralGoods::join('goods as g','g.id','integral_goods.goods_id')
                ->get([DB::raw('g.goods_name as text'),'integral_goods.id']);
        }else{
            return [];
        }
    }
}
