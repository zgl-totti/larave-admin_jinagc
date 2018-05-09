<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCommentTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->text('content');
            $table->integer('order_id');
            $table->integer('goods_id');
            $table->smallInteger('source')->comment('1为普通2为积分');
            $table->integer('parent_id');
            $table->integer('comment_status');
            $table->smallInteger('status')->default(1)->comment('1为展示2为下架');
            $table->json('images')->nullable();
            $table->timestamps();
        });

        Schema::create('comment_reply', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('comment_id');
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('comment_status', function (Blueprint $table) {
            $table->increments('id');
            $table->string('status_name');
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
        Schema::dropIfExists('comment');
        Schema::dropIfExists('comment_reply');
        Schema::dropIfExists('comment_status');
    }
}
