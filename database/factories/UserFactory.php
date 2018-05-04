<?php

use Faker\Generator as Faker;

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| This directory should contain each of the model factory definitions for
| your application. Factories provide a convenient way to generate new
| model instances for testing / seeding your application's database.
|
*/

$factory->define(App\User::class, function (Faker $faker) {
    return [
        'name' => $faker->name,
        'email' => $faker->unique()->safeEmail,
        'password' => '$2y$10$TKh8H1.PfQx37YgCzwiKb.KjNyWgaHb9cbcoQgdIVFlYg7B77UdFm', // secret
        'integral'=>rand(1,50000),
        'level'=>rand(1,5),
        'remember_token' => str_random(10),
    ];
});

$factory->define(App\Models\UsersIntegral::class, function (Faker $faker) {
    return [
        'user_id' => rand(1,10000),
        'integral' => rand(1,5000),
        'integral_source'=>rand(1,3),
        'order_id'=>rand(1,10000),
    ];
});

$factory->define(App\Models\Order::class, function (Faker $faker) {
    return [
        'order_sn' => time(),
        'order_price' => rand(100,10000),
        'order_status' => rand(1,8),
        'user_id' => rand(1,10000),
        'consignee' => str_random(10),
        'consignee_phone' => time(),
        'district'=>rand(50,3000),
        'area'=>str_random(5),
        'order_source' =>rand(1,6),
        'express_id'=>rand(1,9),
        'pay_type'=>rand(1,4),
        'order_msg'=>str_random(10),
    ];
});


$factory->define(App\Models\Address::class, function (Faker $faker) {
    return [
        'user_id' => rand(1,10000),
        'district'=>rand(100,3000),
        'area'=>str_random(4),
        'username'=>str_random(5),
        'phone'=>$faker->time()
    ];
});

$factory->define(App\Models\OrderGoods::class, function (Faker $faker) {
    return [
        'order_id' => rand(1,10000),
        'goods_id'=>rand(1,11),
        'buy_number'=>rand(1,100),
        'buy_price' => rand(100,10000),
    ];
});

$factory->define(App\Models\Seckill::class, function (Faker $faker) {
    return [
        'goods_id'=>rand(1,11),
        'title'=>$faker->unique()->name,
        'price'=>rand(100,10000),
        'num'=>rand(50,100),
        'limit'=>rand(1,5),
        'begin_at'=>date('Y-m-d H:i:s',time()),
        'end_at'=>date('Y-m-d H:i:s',time()+18800)
    ];
});

$factory->define(App\Models\SeckillOrder::class, function (Faker $faker) {
    return [
        'order_sn' => time().rand(1,9),
        'seckill_id'=>rand(1,16),
        'num'=>rand(1,10),
        'price' => rand(1000,10000),
        'order_status' => rand(1,8),
        'user_id' => rand(1,10000),
        'consignee' => str_random(10),
        'consignee_phone' => time(),
        'district'=>rand(50,3000),
        'area'=>str_random(5),
        'order_source' =>rand(1,6),
        'express_id'=>rand(1,9),
        'pay_type'=>rand(1,4),
        'order_msg'=>str_random(10),
    ];
});

$factory->define(App\Models\Promotions::class, function (Faker $faker) {
    return [
        'title'=>$faker->unique()->name,
        'goods_id'=>rand(1,11),
        'promotions_price'=>rand(100,10000),
        'inventory_number'=>rand(50,100),
        'sale_number'=>rand(1,100),
        'limit'=>rand(1,5),
        'begin_at'=>date('Y-m-d H:i:s',time()),
        'end_at'=>date('Y-m-d H:i:s',time()+18800)
    ];
});
