<?php

namespace App\Admin\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\User;
use Encore\Admin\Controllers\Dashboard;
use Encore\Admin\Facades\Admin;
use Encore\Admin\Layout\Column;
use Encore\Admin\Layout\Content;
use Encore\Admin\Layout\Row;
use Encore\Admin\Widgets\InfoBox;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    public function index()
    {
        return Admin::content(function (Content $content) {

            $content->header('商城详情');
            $content->description('Description...');


            $user_today=User::select(DB::raw('count(*) as user_count'))
                ->whereDay('created_at',date('d'))
                ->first();
            $order_today=Order::select(DB::raw('count(*) as order_count,sum(order_price) as price_count'))
                ->whereDay('created_at',date('d'))
                ->first();
            $sale_today=Order::select(DB::raw('sum(order_price) as price_count'))
                ->whereDay('created_at',date('d'))
                ->whereNotIn('order_status',[1,8,9,10])
                ->first();
            $content->row(function ($row) use ($user_today,$order_today,$sale_today){
                $row->column(3, new InfoBox('今日新增用户', 'users', 'aqua', '/admin/users/today', $user_today->user_count));
                $row->column(3, new InfoBox('今日新增订单', 'shopping-cart', 'green', '/admin/orders/today', $order_today->order_count));
                $row->column(3, new InfoBox('今日销售额', 'rmb', 'yellow', '/admin/sale/today', $order_today->price_count ?? 0));
                $row->column(3, new InfoBox('实际销售额', 'dollar', 'red', '/admin/sale/today_revenue', $sale_today->price_count ?? 0));
            });

            //$content->body(view('admin.echarts'));
            //$content->row(Dashboard::title());

            $content->row(function (Row $row) {

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::environment());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::extensions());
                });

                $row->column(4, function (Column $column) {
                    $column->append(Dashboard::dependencies());
                });
            });
        });
    }
}
