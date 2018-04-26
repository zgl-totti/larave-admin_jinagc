<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertiseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('advertise', function (Blueprint $table) {
            $table->increments('id');
            $table->string('advertise_name');
            $table->integer('position_id');
            $table->string('image');
            $table->smallInteger('status')->comment('1为展示2为下架')->default(1);
            $table->timestamps();
        });

        Schema::create('advertise_position', function (Blueprint $table) {
            $table->increments('id');
            $table->string('position_name');
            $table->smallInteger('width');
            $table->smallInteger('height');
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
        Schema::dropIfExists('advertise');
        Schema::dropIfExists('advertise_position');
    }
}
