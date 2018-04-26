<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMallTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mall', function (Blueprint $table) {
            $table->increments('id');
            $table->string('mall_name',50)->unique();
            $table->string('describe');
            $table->string('keywords');
            $table->string('hotline',11);
            $table->string('qq',11);
            $table->smallInteger('status')->comment('1为展示2为下架')->default(1);
            $table->string('logo');
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
        Schema::dropIfExists('mall');
    }
}
