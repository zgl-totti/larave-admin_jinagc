<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSeckillTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('seckill', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->integer('goods_id');
            $table->json('type');
            $table->decimal('price',15,2);
            $table->integer('num');
            $table->integer('limit');
            $table->smallInteger('status')->comment('1为展示2为下架')->default(1);
            $table->timestamp('begin_at');
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
        });

        Schema::create('seckill_order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_sn',30)->unique();
            $table->integer('seckill_id');
            $table->integer('num');
            $table->integer('price');
            $table->integer('type_id');
            $table->integer('order_status');
            $table->integer('user_id');
            $table->string('consignee');
            $table->string('consignee_phone',11);
            $table->integer('district');
            $table->string('area');
            $table->integer('express_id');
            $table->integer('order_source');
            $table->string('order_msg',100);
            $table->smallInteger('show')->comment('1为展示2为删除')->default(1);
            $table->timestamps();
        });

        Schema::create('bonus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->integer('goods_id');
            $table->decimal('price',15,2);
            $table->integer('num');
            $table->integer('limit');
            $table->smallInteger('status')->comment('1为展示2为下架')->default(1);
            $table->timestamp('begin_at');
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
        });

        Schema::create('bonus_users', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('order_id');
            $table->integer('bonus_id');
            $table->decimal('money',15,2);
            $table->smallInteger('status')->comment('1为已使用2为未使用3为过期');
            $table->timestamps();
        });

        Schema::create('promotions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->integer('goods_id');
            $table->json('type');
            $table->decimal('promotions_price',15,2);
            $table->integer('inventory_number');
            $table->integer('sale_number');
            $table->integer('limit');
            $table->smallInteger('status')->comment('1为展示2为下架')->default(1);
            $table->timestamp('begin_at');
            $table->timestamp('end_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('seckill');
        Schema::dropIfExists('seckill_order');
        Schema::dropIfExists('bonus');
        Schema::dropIfExists('bonus_users');
        Schema::dropIfExists('promotions');
    }
}
