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

class ChartMonthController extends Controller
{
    use ModelForm;

    public function index()
    {
        return Admin::content(function (Content $content){

            $content->header('本月详情');
            $content->description('Description...');


            $user_month=User::select(DB::raw('count(*) as user_count'))->whereMonth('created_at',date('m'))->first();
            $order_month=Order::select(DB::raw('count(*) as order_count,sum(order_price) as price_count'))
                ->whereMonth('created_at',date('m'))
                ->first();
            $sale_month=Order::select(DB::raw('sum(order_price) as price_count'))
                ->whereMonth('created_at',date('m'))
                ->whereNotIn('order_status',[1,8,9,10])
                ->first();
            $content->row(function ($row) use ($user_month,$order_month,$sale_month){
                $row->column(3, new InfoBox('新增用户', 'users', 'aqua', 'users/month', $user_month->user_count));
                $row->column(3, new InfoBox('新增订单', 'shopping-cart', 'green', 'orders/month',$order_month->order_count));
                $row->column(3, new InfoBox('新增销售额', 'rmb', 'yellow', 'sale/month', $order_month->price_count));
                $row->column(3, new InfoBox('实际销售额', 'dollar', 'red', 'sale/month_revenue', $sale_month->price_count ?? 0));
            });


            $order=Order::with('status')
                ->whereMonth('created_at',date('m'))
                ->select(DB::raw('count(*) as order_count'),'order_status')
                ->groupBy(['order_status'])
                ->get();
            $order_text='订单来源详情';

            $source=Order::with('source')
                ->whereMonth('created_at',date('m'))
                ->whereNotIn('order_status',[1,8,9,10])
                ->select(DB::raw('count(*) as source_count'),'order_source')
                ->groupBy(['order_source'])
                ->get();
            $source_text='订单来源详情';

            $express=Order::with('express')
                ->whereMonth('created_at',date('m'))
                ->whereNotIn('order_status',[1,8,9,10])
                ->select(DB::raw('count(*) as express_count'),'express_id')
                ->groupBy(['express_id'])
                ->get();
            $express_text='订单快递详情';

            $pay=Order::with('pay')
                ->whereMonth('created_at',date('m'))
                ->whereNotIn('order_status',[1,8,9,10])
                ->select(DB::raw('count(*) as pay_count'),'pay_type')
                ->groupBy(['pay_type'])
                ->get();
            $pay_text='本月订单支付方式详情';

            $content->body(view('admin.echarts', compact('order','source',
                'express','pay','source_text','express_text','pay_text','order_text'
            )));

        });

    }
}
