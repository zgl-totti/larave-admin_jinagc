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
            $table->string('keywords');
            $table->string('describe');
            $table->string('hotline',11);
            $table->string('support_phone',11);
            $table->string('qq',11);
            $table->string('ICP');
            $table->string('certificate');
            $table->string('logo');
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
        Schema::dropIfExists('mall');
    }
}
