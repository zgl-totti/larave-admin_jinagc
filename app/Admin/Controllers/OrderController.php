<?php

namespace App\Admin\Controllers;

use App\Admin\Extensions\ExcelExporter;
use App\Admin\Extensions\Shipments;
use App\Models\AfterSales;
use App\Models\ChinaArea;
use App\Models\Express;
use App\Models\IntegralOrder;
use App\Models\Order;

use App\Models\SeckillOrder;
use Encore\Admin\Form;
use Encore\Admin\Grid;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use App\Http\Controllers\Controller;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Layout\Row;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use yii\console\Exception;

class OrderController extends Controller
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

            $content->header('订单');
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

            $content->header('订单');
            $content->description('description');

            $content->body($this->form()->edit($id));
        });
    }

    /**
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function grid($type='')
    {
        return Admin::grid(Order::class, function (Grid $grid) use ($type){

            $grid->id('编号')->sortable();
            $grid->column('user.name','订单用户');
            $grid->order_sn('订单号');

            /*$grid->orderGoods('订单商品')->display(function ($orderGoods){
                $orderGoods=array_map(function ($order_goods){
                    return str_limit($order_goods['goods_name'],20).'：¥ '.$order_goods['buy_price'].' * '.$order_goods['buy_number'].$order_goods['goods_brief'].'<br>';
                },$orderGoods);
                return join('<br>',$orderGoods);
            });*/

            $grid->order_price('订单总价');
            $grid->column('consignee','收货人');
            $grid->column('consignee_phone','收货电话');

            $grid->column('district','收货地址')->display(function ($district) {
                $info=ChinaArea::find($district);
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
            $grid->column('pay.pay_name','支付方式');
            $grid->order_msg('备注');

            $grid->column('status.status_name','订单状态')->display(function ($status){
                $status= $status ?? '未知';
                $order_status=$this->order_status;
                $color=['','red','green','deepskyblue','darkmagenta','indianred','orange','plum','darkturquoise','indianred','teal'];

                return "<button class='btn btn-sm btn-twitter' style='background: $color[$order_status]'>$status</button>";
            });

            $grid->created_at('生成时间');
            //$grid->updated_at();

            //禁用创建按钮
            $grid->disableCreateButton();

            $grid->actions(function ($actions) {
                //禁用删除按钮
                $actions->disableDelete();

                $row=$actions->row;

                if($row->order_status==2){
                    $actions->append(new Shipments(1,$actions->getKey()));
                }
                if ($row->order_status==8){
                    $actions->append('<a href="after-sales/'.$row->id.'"
                            class="btn btn-sm btn-facebook"
                            style="background: darkorchid"
                            data-id="{$this->id}"
                            data-container="body">
                            售后
                        </a>'
                    );
                }

                $actions->prepend('<a href="order-detail/'.$actions->getKey().'"><i class="fa fa-eye"></i></a>');

                // 当前行的数据数组
                //$actions->row;
                // 获取当前行主键值
                //$actions->getKey();
                // 添加操作
                //$actions->prepend(new Shipments($actions->getKey()));
                //$actions->append(new Shipments($actions->getKey()));
            });


            //导出
            $filename='订单列表_'.date('Y/m/d');
            $column=['id','order_sn','order_price','consignee','consignee_phone','area','created_at','user','source','express','pay','status'];
            $line=['A','B','C','D','E','F','G','H','I','J','K','L'];
            $header=['编号','订单号','订单总价','收货人','收货电话','收货地址','生成时间','订单用户','订单来源','快递','支付方式','订单状态'];
            $size=['B'=>25,'D'=>20,'E'=>25,'F'=>40,'G'=>30,'H'=>25];
            $relevance=['user'=>'name','source'=>'source_name','express'=>'express_name','pay'=>'pay_name','status'=>'status_name'];

            $grid->exporter(new ExcelExporter($filename,$column,$line,$header,$size,$relevance));

            $grid->filter(function ($filter) {
                // 去掉默认的id过滤器
                $filter->disableIdFilter();
                // 在这里添加字段过滤器
                $filter->like('order_sn', '订单编号')->placeholder('请输入订单编号');
                $filter->between('created_at','生成时间')->datetime();
                $filter->equal('order_status', '订单状态')->radio(['' => '全部'] + Order::statusMap());
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


            /*//底部
            use App\Admin\Contracts\Facades\Admin;
            use App\Admin\Contracts\Grid;

            $model=\App\Admin\Contracts\Grid::getQueryModel();
            $grid->footer(function ($footer) use ($model){
                $footer->td($model->sum('id'));
            });*/

        });
    }

    /**
     * 销售额列表
     * Make a grid builder.
     *
     * @return Grid
     */
    protected function sales($type='')
    {
        return Admin::grid(Order::class, function (Grid $grid) use ($type){

            $grid->id('编号')->sortable();
            $grid->column('user.name','订单用户');
            $grid->order_sn('订单号');

            $grid->orderGoods('订单商品')->display(function ($orderGoods){
                $orderGoods=array_map(function ($order_goods){
                    return str_limit($order_goods['goods_name'],20).'：¥ '.$order_goods['buy_price'].' * '.$order_goods['buy_number'].$order_goods['goods_brief'].'<br>';
                },$orderGoods);
                return join('<br>',$orderGoods);
            });

            $grid->order_price('订单总价');
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
            $grid->column('pay.pay_name','支付方式');
            $grid->order_msg('备注');

            $grid->column('status.status_name','订单状态')->display(function ($status){
                $status= $status ?? '未知';
                $order_status=$this->order_status;
                $color=['','red','green','deepskyblue','darkmagenta','indianred','orange','plum','darkturquoise','indianred','teal'];

                return "<button class='btn btn-sm btn-twitter' style='background: $color[$order_status]'>$status</button>";
            });

            $grid->created_at('生成时间');
            //$grid->updated_at();

            //禁用创建按钮
            $grid->disableCreateButton();

            $grid->actions(function ($actions) {
                $actions->disableDelete();
                $actions->disableEdit();
            });

            //导出
            $filename='订单列表_'.date('Y/m/d');
            $column=['id','order_sn','order_price','consignee','consignee_phone','area','created_at','user','source','express','pay','status'];
            $line=['A','B','C','D','E','F','G','H','I','J','K','L','M'];
            $header=['编号','订单号','订单总价','收货人','收货电话','收货地址','生成时间','订单用户','订单来源','快递','支付方式','订单状态'];
            $size=['B'=>25,'D'=>20,'E'=>25,'F'=>40,'G'=>30,'H'=>25];
            $relevance=['user'=>'name','source'=>'source_name','express'=>'express_name','pay'=>'pay_name','status'=>'status_name'];

            $grid->exporter(new ExcelExporter($filename,$column,$line,$header,$size,$relevance));

            $grid->filter(function ($filter) {
                // 去掉默认的id过滤器
                $filter->disableIdFilter();
                // 在这里添加字段过滤器
                $filter->like('order_sn', '订单编号')->placeholder('请输入订单编号');
                $filter->between('created_at','生成时间')->datetime();
                //$filter->equal('order_status', '订单状态')->radio(['' => '全部'] + Order::statusMap());
            });

            if($type=='today'){
                $grid->model()->whereDay('created_at',date('d'));
            }elseif ($type=='week'){
                $week=date('w');
                $time=strtotime(date('Y-m-d'))-($week-1)*86400;
                $grid->model()->whereDate('created_at','>',date('Y-m-d',$time));
            }elseif ($type=='month'){
                $grid->model()->whereMonth('created_at',date('m'));
            }elseif($type=='today_revenue'){
                $grid->model()->whereDay('created_at',date('d'))->whereNotIn('order_status',[1,8,9,10]);
            }elseif ($type=='week_revenue'){
                $week=date('w');
                $time=strtotime(date('Y-m-d'))-($week-1)*86400;
                $grid->model()->whereDate('created_at','>',date('Y-m-d',$time))->whereNotIn('order_status',[1,8,9,10]);
            }elseif ($type=='month_revenue'){
                $grid->model()->whereMonth('created_at',date('m'))->whereNotIn('order_status',[1,8,9,10]);
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
        return Admin::form(Order::class, function (Form $form) {

            //$form->display('id', 'ID');

            $form->display('order_sn','订单编号');

            $id=request()->route()->parameters()['order'] ?? 0;
            $info=Order::find($id);
            $town=ChinaArea::find($info->district);
            $city=ChinaArea::find($town->parent_id);
            $province=ChinaArea::find($city->parent_id);

            if($info->order_status==1){
                $form->currency('order_price','订单总价')->symbol('￥')->rules('required|numeric');
            }

            if($info->order_status==2){
                $form->text('consignee','收货人')->rules('required');

                $form->mobile('consignee_phone','收货电话')
                    //->options(['mask' => '999 9999 9999'])
                    ->rules('required|regex:/^1[34578]\d{9}$/');

                //$form->text('consignee_phone','收货电话')->rules('required');

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

    public function city(Request $request)
    {
        $provinceId = $request->get('q');

        return ChinaArea::city()->where('parent_id', $provinceId)->get(['id', DB::raw('name as text')]);
    }

    public function town(Request $request)
    {
        $cityId = $request->get('q');

        return ChinaArea::where('parent_id', $cityId)->get(['id', DB::raw('name as text')]);
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
        $info=Order::with(['user','status','source','express','orderGoods'])
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

            $content->header('订单详情');

            $content->body(view('admin.order-detail',compact('info')));

        });
    }


    /**
     * 发货
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author totti_zgl
     * @date 2018/4/12 11:09
     */
    public function shipments(Request $request)
    {
        $id=$request->input('id');
        $type=$request->input('type');
        if (empty($id)) {
            $result['status']=0;
            $result['message']='订单不能为空';
            return response()->json($result);
        }
        if (empty($type)) {
            $result['status']=0;
            $result['message']='非法操作';
            return response()->json($result);
        }
        if($type==1){
            $order= new Order();
            $res=$order->shipments($id);
            if(empty($res)){
                $result['status']=0;
                $result['message']='发货失败';
                return response()->json($result);
            }
            $result['status']=1;
            $result['message']='发货成功';
            return response()->json($result);
        }elseif ($type==2){
            $order= new IntegralOrder();
            $res=$order->shipments($id);
            if(empty($res)){
                $result['status']=0;
                $result['message']='发货失败';
                return response()->json($result);
            }
            $result['status']=1;
            $result['message']='发货成功';
            return response()->json($result);
        }elseif ($type==3){
            $order= new SeckillOrder();
            $res=$order->shipments($id);
            if(empty($res)){
                $result['status']=0;
                $result['message']='发货失败';
                return response()->json($result);
            }
            $result['status']=1;
            $result['message']='发货成功';
            return response()->json($result);
        }
    }


    /**
     * 新增订单列表
     * @param string $type
     * @return Content
     * @author totti_zgl
     * @date 2018/4/19 17:22
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

            $content->header($header.'新增订单');
            $content->description('列表');

            $content->body($this->grid($type));
        });

    }


    /**
     * 销售列表
     * @param string $type
     * @return Content|\Illuminate\Http\RedirectResponse
     * @author totti_zgl
     * @date 2018/4/24 16:59
     */
    public function sale(string $type)
    {
        if($type=='today'){
            $header='今日';
        }elseif($type=='week'){
            $header='本周';
        }elseif($type=='month'){
            $header='本月';
        }elseif($type=='today_revenue'){
            $header='今日实际';
        }elseif($type=='week_revenue'){
            $header='本周实际';
        }elseif($type=='month_revenue'){
            $header='本月实际';
        }else{
            return redirect()->back();
        }

        return Admin::content(function (Content $content) use ($header,$type){

            $content->header($header.'销售额');
            //$content->description('列表');

            $content->body($this->sales($type));
        });

    }

    /**
     * 售后详情
     * @param int $id
     * @return Content
     * @author totti_zgl
     * @date 2018/4/27 16:21
     */
    public function afterSales(int $id)
    {
        $info=AfterSales::with('goods')
            ->with(['order'=>function($query){
                $query->join('users as u','u.id','=','order.user_id')->select('u.id','u.name','order.*');
            }])
            ->where('order_id',$id)
            ->first();

        return Admin::content(function (Content $content) use ($info){

            $content->header('售后处理');

            $content->body(view('admin.after-sales',compact('info')));

        });
    }


    /**
     * 售后处理
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @author totti_zgl
     * @date 2018/4/27 16:21
     */
    public function afterSalesService(Request $request)
    {
        $id=intval($request->input('id'));
        $type=intval($request->input('type'));
        $info=AfterSales::find($id);
        $order=Order::find($info->order_id);
        if($type==1){
            DB::beginTransaction();
            try{
                $info->opinion=3;
                $order->order_status=9;
                $row1=$info->save();
                $row2=$order->save();
                if(empty($row1) || empty($row2)){
                    throw new Exception('更新失败！');
                }
                DB::commit();
                $result['status']=1;
                $result['message']='处理完毕';
                return response()->json($result);
            }catch (Exception $exception){
                DB::rollBack();
                $result['status']=2;
                $result['message']='处理失败';
                return response()->json($result);
            }
        }elseif($type==2){
            $info->opinion=3;
            if($info->save()){
                $result['status']=1;
                $result['message']='处理成功';
                return response()->json($result);
            }else{
                $result['status']=2;
                $result['message']='处理失败';
                return response()->json($result);
            }
        }else{
            $result['status']=2;
            $result['message']='非法操作';
            return response()->json($result);
        }
    }
}
