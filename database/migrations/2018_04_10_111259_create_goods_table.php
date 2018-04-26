<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGoodsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('goods', function (Blueprint $table) {
            $table->increments('id');
            $table->string('goods_name',50)->unique();
            $table->text('goods_unit');
            $table->string('goods_keywords',50);
            $table->string('goods_brief');
            $table->integer('brand_id');
            $table->integer('cate_id');
            $table->decimal('market_price',15,2);
            $table->decimal('mail_price',15,2);
            $table->integer('inventory_number');
            $table->integer('sale_number');
            $table->smallInteger('hot')->comment('1为热门');
            $table->smallInteger('new')->comment('1为新品');
            $table->smallInteger('recommend')->comment('1为推荐');
            $table->string('pic',100);
            $table->json('images');
            $table->smallInteger('status')->comment('1为展示2为下架')->default(1);
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
        Schema::dropIfExists('goods');
    }
}
