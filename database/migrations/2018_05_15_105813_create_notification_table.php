<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notification', function (Blueprint $table) {
            $table->increments('id');
            $table->string('title')->unique();
            $table->integer('type_id');
            $table->string('intro');
            $table->integer('resource');
            $table->text('content');
            $table->timestamps();
        });

        Schema::create('notification_type', function (Blueprint $table) {
            $table->increments('id');
            $table->string('type_name')->unique();
            $table->smallInteger('status')->default(1)->comment('1为展示2为下架');
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
        Schema::dropIfExists('notification');
        Schema::dropIfExists('notification_type');
    }
}
