<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_cates', function (Blueprint $table) {
            $table->increments('id');
            $table->string('cate_name')->unique();
            $table->integer('parent_id');
            $table->timestamps();
        });

        Schema::create('article_contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('cate_id');
            $table->string('title');
            $table->text('content');
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
        Schema::dropIfExists('article_cates');
        Schema::dropIfExists('article_content');
    }
}
