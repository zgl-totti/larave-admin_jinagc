<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateIntegralTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('integral_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('goods_id');
            $table->json('type');
            $table->integer('integral');
            $table->smallInteger('status')->comment('1为展示2为下架')->default(1);
            $table->timestamps();
        });

        Schema::create('integral_order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_sn',30)->unique();
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

        Schema::create('integral_order_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('goods_id');
            $table->integer('type_id');
            $table->integer('buy_number');
            $table->integer('integral');
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
        Schema::dropIfExists('integral_goods');
        Schema::dropIfExists('integral_order');
        Schema::dropIfExists('integral_order_goods');
    }
}
