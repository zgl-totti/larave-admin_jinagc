<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('user_id');
            $table->integer('district');
            $table->string('area');
            $table->string('username');
            $table->string('phone',11);
            $table->smallInteger('default')->comment('1为默认地址2为其他地址')->default(2);
            $table->timestamps();
        });

        Schema::create('source', function (Blueprint $table) {
            $table->increments('id');
            $table->string('source_name',30);
            $table->timestamps();
        });

        Schema::create('express', function (Blueprint $table) {
            $table->increments('id');
            $table->string('express_name');
            $table->integer('status')->comment('1为展示2为下架')->default(1);
            $table->timestamps();
        });

        Schema::create('pay', function (Blueprint $table) {
            $table->increments('id');
            $table->string('pay_name');
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
        Schema::dropIfExists('address');
        Schema::dropIfExists('source');
        Schema::dropIfExists('express');
        Schema::dropIfExists('pay');
    }
}
