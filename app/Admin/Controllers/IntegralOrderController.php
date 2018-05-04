<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\ExcelExporter;
use App\Admin\Extensions\Shipments;
use App\Models\ChinaArea;
use App\Models\Express;
use App\Models\IntegralOrder;

use App\Models\Order;
use App\Models\Source;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;

class IntegralOrderController extends Controller
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

            $content->header('积分订单');
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

            $content->header('积分订单');
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
    protected function grid()
    {
        return Admin::grid(IntegralOrder::class, function (Grid $grid) {

            $grid->id('编号')->sortable();

            $grid->column('user.name','订单用户');
            $grid->order_sn('订单号');

            $grid->orderGoods('订单商品')->display(function ($orderGoods){
                $orderGoods=array_map(function ($order_goods){
                    return str_limit($order_goods['goods_name'],30).'：'.$order_goods['buy_number'].$order_goods['goods_brief'].'<br>';
                },$orderGoods);
                return join('<br>',$orderGoods);
            });

            $grid->column('consignee','收货人');
            $grid->column('consignee_phone','收货电话');

            $grid->column('district','收货地址')->display(function () {
                $info=ChinaArea::find($this->district);
                if($info->type==3){
                    $city=ChinaArea::find($info->parent_id);
                    $province=ChinaArea::find($city->parent_id);
                    $address=$province->name.$city->name.$info->name;
                }elseif ($info->type==2){
                    $province=ChinaArea::find($info->parent_id);
                    $address=$province->name.$info->name;
                }else{
                    $address=$info->name;
                }
                return $address.$this->area;
            });

            $grid->column('source.source_name','订单来源');
            $grid->column('express.express_name','快递');
            $grid->order_msg('备注');

            $grid->column('status.status_name','订单状态')->display(function ($status){
                $status= $status ?? '未知';
                $order_status=$this->order_status;
                $color=['','red','green','deepskyblue','darkmagenta','indianred','orange','plum','darkturquoise','indianred','teal'];

                return "<button class='btn btn-sm btn-twitter' style='background: $color[$order_status]'>$status</button>";
            });

            $grid->created_at('创建时间');
            //$grid->updated_at();

            $grid->disableCreateButton();

            $grid->actions(function ($actions) {
                //禁用删除按钮
                $actions->disableDelete();

                $row = $actions->row;

                if ($row->order_status == 2) {
                    $actions->append(new Shipments(2,$actions->getKey()));
                }

                $actions->prepend('<a href="integral-order-detail/'.$actions->getKey().'"><i class="fa fa-eye"></i></a>');
            });

            //导出
            $filename='积分订单_'.date('Y/m/d');
            $column=['id','order_sn','consignee','consignee_phone','area','created_at','user','source','express','status'];
            $line=['A','B','C','D','E','F','G','H','I','J'];
            $header=['编号','订单号','收货人','收货电话','收货地址','生成时间','订单用户','订单来源','快递','订单状态'];
            $size=['B'=>25,'C'=>20,'D'=>25,'E'=>40,'F'=>30,'G'=>25];
            $relevance=['user'=>'name','source'=>'source_name','express'=>'express_name','status'=>'status_name'];

            $grid->exporter(new ExcelExporter($filename,$column,$line,$header,$size,$relevance));

            $grid->filter(function ($filter) {
                // 去掉默认的id过滤器
                $filter->disableIdFilter();
                // 在这里添加字段过滤器

                $filter->where(function ($query){

                    $username=$this->input;
                    $info=User::where('name','like',$username.'%')->select('id')->get();

                    $query->whereIn('user_id',$info);

                },'订单用户');

                $filter->like('order_sn', '订单编号')->placeholder('请输入订单编号');

                $filter->like('consignee_phone','收货电话')->placeholder('请输入收货电话');
                $filter->equal('order_status', '订单状态')->select(IntegralOrder::statusMap());
                $filter->equal('order_source', '订单来源')->select(Source::pluck('source_name','id')->toArray());
                $filter->equal('express_id', '快递')->select(Express::pluck('express_name','id')->toArray());

                $filter->between('created_at','生成时间')->datetime();

                /*$filter->equal('order_status', '订单状态')->radio(['' => '全部'] + Order::statusMap());*/
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
        return Admin::form(IntegralOrder::class, function (Form $form) {

            //$form->display('id', 'ID');

            $form->display('order_sn','订单编号');

            $id=request()->route()->parameters()['integral_order'] ?? 0;
            $info=IntegralOrder::find($id);
            $town=ChinaArea::find($info->district);
            $city=ChinaArea::find($town->parent_id);
            $province=ChinaArea::find($city->parent_id);

            if($info->order_status==2){
                $form->text('consignee','收货人')->rules('required');
                $form->mobile('consignee_phone','收货电话')->rules('required|regex:/^1[34578]\d{9}$/');

                $express=Express::where('status',1)->pluck('express_name','id');
                $form->radio('express_id','快递')->options($express);

                $form->select('province','省')->options(function () use ($province){
                    $address=ChinaArea::province()->pluck('name','id');
                    return $address->prepend($province->name,0);
                })->load('city', url('admin/city'));

                $form->select('city','市')->options(function () use ($city){
                    $address=ChinaArea::options($city->id);
                    return $address->prepend($city->name,0);
                })->load('district',url('admin/town'));

                $form->select('district','县')->options(function ($id){
                    return ChinaArea::options($id);
                })->rules('required');

                $form->text('area','详细地址')->rules('required');
            }

            $form->ignore(['province','city']);

            //$form->display('created_at', 'Created At');
            //$form->display('updated_at', 'Updated At');
        });
    }


    /**
     * 订单详情
     * @param int $id
     * @return Content
     * @author totti_zgl
     * @date 2018/5/2 9:43
     */
    public function orderDetail(int $id)
    {
        $info=IntegralOrder::with(['user','status','source','express','orderGoods'])
            ->where('id',$id)
            ->first();
        $town=ChinaArea::find($info->district);
        if($town->type==3){
            $city=ChinaArea::find($town->parent_id);
            $province=ChinaArea::find($city->parent_id);
            $address=$province->name.$city->name.$town->name;
        }elseif ($town->type==2){
            $province=ChinaArea::find($info->parent_id);
            $address=$province->name.$town->name;
        }else{
            $address=$town->name;
        }
        $info['address']=$address;

        return Admin::content(function (Content $content) use ($info){

            $content->header('积分订单详情');

            $content->body(view('admin.integral-order-detail',compact('info')));

        });
    }
}
