<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('order', function (Blueprint $table) {
            $table->increments('id');
            $table->string('order_sn',30)->unique();
            $table->decimal('order_price',15,2);
            $table->integer('order_status');
            $table->integer('user_id');
            $table->string('consignee');
            $table->string('consignee_phone',11);
            $table->integer('district');
            $table->string('area');
            $table->integer('express_id');
            $table->integer('order_source');
            $table->integer('pay_type');
            $table->string('order_msg',100);
            $table->smallInteger('show')->comment('1为展示2为删除')->default(1);
            $table->timestamps();
        });

        Schema::create('order_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status_name',30);
            $table->string('member_status',30);
            $table->string('admin_status',30);
            $table->timestamps();
        });

        Schema::create('order_goods', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('goods_id');
            $table->integer('type_id');
            $table->integer('buy_number');
            $table->decimal('buy_price',15,2);
            $table->timestamps();
        });

        Schema::create('after-sales', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('order_id');
            $table->integer('goods_id');
            $table->text('content');
            $table->smallInteger('opinion')->comment('1为未处理,2为不同意,3为同意')->default(1);
            $table->integer('status')->comment('1为展示,2为删除')->default(1);
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
        Schema::dropIfExists('order');
        Schema::dropIfExists('order_status');
        Schema::dropIfExists('order_goods');
        Schema::dropIfExists('after-sales');
    }
}
