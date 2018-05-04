<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('email')->unique();
            $table->string('password');
            $table->integer('integral');
            $table->smallInteger('level');
            $table->rememberToken();
            $table->timestamps();
        });

        Schema::create('users_level', function (Blueprint $table) {
            $table->increments('id');
            $table->string('level_name')->unique();
            $table->integer('level_integral');
            $table->smallInteger('status')->default(1);
            $table->timestamps();
        });

        Schema::create('users_integral', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('integral');
            $table->smallInteger('integral_source')->comment('1为注册,2为订单,3为评论');
            $table->integer('order_id');
            $table->smallInteger('status')->default(1);
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
        Schema::dropIfExists('users');
        Schema::dropIfExists('users_level');
        Schema::dropIfExists('users_integral');
    }
}
