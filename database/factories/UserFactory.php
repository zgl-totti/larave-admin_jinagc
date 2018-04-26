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
        'remember_token' => str_random(10),
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
