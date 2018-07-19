<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\Goods;
use App\Models\OrderGoods;
use Illuminate\Support\Facades\Log;


//sh -c "cd /www/wwwroot/laravel-admin_jinagc;php artisan command:order;"
//0 */1 * * * /home/crontab/laravel-admin_jinagc.sh


class TestCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'command:order';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        //执行的逻辑

        try {
            $where = [
                ['order_status', 1],
                ['created_at', '<', date('Y-m-d H:i:s', time() - 72 * 3600)]
            ];

            $order_ids = Order::where($where)->select('id')->get();

            //$order_ids = Order::where('order_status',1)->whereDate('created_at','<',time() - 72 * 3600)->select('id')->get();

            $list = OrderGoods::when($order_ids, function ($query) use ($order_ids) {
                $query->whereIn('order_id', $order_ids);
            })
                ->select('goods_id', 'buy_number')
                ->get();

            if ($list) {
                foreach ($list as $v) {
                    Goods::where('id', $v['goods_id'])->increment('inventory_number', $v['buy_number']);

                    $goods = Goods::find($v['goods_id']);
                    if ($goods['sale_number'] >= $v['buy_number']) {
                        Goods::where('id', $v['goods_id'])->decrement('sale_number', $v['buy_number']);
                    } else {
                        Goods::where('id', $v['goods_id'])->update(['sale_number' => 0]);
                    }
                }
            }

            //$row=Order::where('order_status',1)->whereDate('created_at','<',time() - 72 * 3600)->update(['order_status' => 10]);

            $row = Order::where($where)->update(['order_status' => 10]);

            if ($row) {
                Log::info('command:success_' . time() . '_条数：' . $row);
            } else {
                Log::info('command:error_' . time());
            }
        }catch (\Exception $e){
            Log::info('command:warning_' . time().'_'.$e->getMessage());
        }
    }
}
