<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateInquiryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('inquiry_cates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cate_name')->unique();
            $table->smallInteger('status')->default(1)->comment('1为展示2为下架');
            $table->timestamps();
        });

        Schema::create('inquiry_departments', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cate_name')->unique();
            $table->smallInteger('status')->default(1)->comment('1为展示2为下架');
            $table->timestamps();
        });

        Schema::create('inquiry_expert', function (Blueprint $table) {
            $table->increments('id');
            $table->string('username')->unique();
            $table->integer('departments_id');
            $table->integer('age');
            $table->smallInteger('gender');
            $table->string('positional_title');
            $table->text('intro');
            $table->smallInteger('status')->default(1)->comment('12');
            $table->timestamps();
        });

        Schema::create('inquiry_ask', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('departments_id');
            $table->integer('expert_id');
            $table->smallInteger('source_id');
            $table->integer('user_id');
            $table->string('phone',11);
            $table->text('content');
            $table->text('reply')->nullable();
            $table->smallInteger('status')->default(1)->comment('12');
            $table->timestamps();
        });

        Schema::create('inquiry_appointment', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('departments_id');
            $table->integer('expert_id');
            $table->smallInteger('source_id');
            $table->integer('user_id');
            $table->string('phone',11);
            $table->smallInteger('type')->comment('12');
            $table->date('appointment_time');
            $table->string('msg')->nullable();
            $table->smallInteger('status')->default(1)->comment('12');
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
        Schema::dropIfExists('inquiry_cates');
        Schema::dropIfExists('inquiry_departments');
        Schema::dropIfExists('inquiry_expert');
        Schema::dropIfExists('inquiry_ask');
        Schema::dropIfExists('inquiry_appointment');
    }
}
