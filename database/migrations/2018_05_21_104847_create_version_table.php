<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVersionTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('version', function (Blueprint $table) {
            $table->increments('id');
            $table->smallInteger('app_type')->comment('1为android2为ios');
            $table->string('version');
            $table->string('version_mini');
            $table->smallInteger('is_force');
            $table->string('url');
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
        Schema::dropIfExists('version');
    }
}
