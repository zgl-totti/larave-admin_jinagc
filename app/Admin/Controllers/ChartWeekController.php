<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\User;
use Encore\Admin\Controllers\ModelForm;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Content;
use Encore\Admin\Widgets\InfoBox;
use Illuminate\Support\Facades\DB;

class ChartWeekController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content){

            $content->header('本周详情');
            $content->description('Description...');


            $week=date('w');
            $time=strtotime(date('Y-m-d'))-($week-1)*86400;
            $user_week=User::select(DB::raw('count(*) as user_count'))
                ->whereDate('created_at','>',date('Y-m-d',$time))
                ->first();
            $order_week=Order::select(DB::raw('count(*) as order_count,sum(order_price) as price_count'))
                ->whereDate('created_at','>',date('Y-m-d',$time))
                ->first();
            $sale_week=Order::select(DB::raw('sum(order_price) as price_count'))
                ->whereDate('created_at','>',date('Y-m-d',$time))
                ->whereNotIn('order_status',[1,8,9,10])
                ->first();
            $content->row(function ($row) use ($user_week,$order_week,$sale_week){
                $row->column(3, new InfoBox('新增用户', 'users', 'aqua', 'users/week', $user_week->user_count));
                $row->column(3, new InfoBox('新增订单', 'shopping-cart', 'green', 'orders/week', $order_week->order_count));
                $row->column(3, new InfoBox('新增销售额', 'rmb', 'yellow', 'sale/week', $order_week->price_count ?? 0));
                $row->column(3, new InfoBox('实际销售额', 'dollar', 'red', 'sale/week_revenue', $sale_week->price_count ?? 0));
            });

            $order=Order::with('status')
                ->whereDate('created_at','>',date('Y-m-d',$time))
                ->select(DB::raw('count(*) as order_count'),'order_status')
                ->groupBy(['order_status'])
                ->get();
            $order_text='订单来源详情';

            $source=Order::with('source')
                ->whereDate('created_at','>',date('Y-m-d',$time))
                ->whereNotIn('order_status',[1,8,9,10])
                ->select(DB::raw('count(*) as source_count'),'order_source')
                ->groupBy(['order_source'])
                ->get();
            $source_text='订单来源详情';

            $express=Order::with('express')
                ->whereDate('created_at','>',date('Y-m-d',$time))
                ->whereNotIn('order_status',[1,8,9,10])
                ->select(DB::raw('count(*) as express_count'),'express_id')
                ->groupBy(['express_id'])
                ->get();
            $express_text='订单快递详情';

            $pay=Order::with('pay')
                ->whereDate('updated_at','>',date('Y-m-d',$time))
                ->whereNotIn('order_status',[1,8,9,10])
                ->select(DB::raw('count(*) as pay_count'),'pay_type')
                ->groupBy(['pay_type'])
                ->get();
            $pay_text='订单支付方式详情';

            $content->body(view('admin.echarts',compact(
                'order','source','express','pay','source_text','express_text','pay_text','order_text'
            )));

        });

    }
}
