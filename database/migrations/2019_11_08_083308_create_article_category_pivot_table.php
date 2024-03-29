<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticleCategoryPivotTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('article_category', function (Blueprint $table) {
            $table->unsignedInteger('article_id');
            $table->unsignedInteger('category_id');

            $table->primary(['article_id', 'category_id']);
            $table->foreign('article_id')->references('id')->on('articles');
            $table->foreign('category_id')->references('id')->on('categories');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('article_category');
    }
}
