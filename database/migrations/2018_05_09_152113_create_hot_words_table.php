<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateHotWordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('hot_words', function (Blueprint $table) {
            $table->increments('id');
            $table->string('hot_word')->unique();
            $table->bigInteger('click_total');
            $table->smallInteger('status')->default(1)->comment('1为上架2为下架');
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
        Schema::dropIfExists('hot_words');
    }
}
