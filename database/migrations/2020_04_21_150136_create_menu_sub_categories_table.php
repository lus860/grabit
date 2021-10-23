<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuSubCategoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_sub_categories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('menu_category_id')->nullable()->unsigned();
            $table->string('name')->nullable();
            $table->timestamps();
            $table->foreign('menu_category_id')->references('id')->on('menu_categories')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_sub_categories');
    }
}
