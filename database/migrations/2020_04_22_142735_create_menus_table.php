<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menus', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('cuisine_id')->nullable();
            $table->unsignedInteger('restaurant_id')->nullable();
            $table->unsignedInteger('menu_sub_category_id')->nullable();
            $table->string('name')->nullable();
            $table->time('start_time')->nullable();
            $table->time('end_time')->nullable();
            $table->integer('same_as_restaurant')->nullable();
            $table->enum('availability',['all_days','specific_days'])->nullable();
            $table->integer('sort_id')->nullable();
            $table->time('early_schedule_time')->nullable();
            $table->time('latest_schedule_time')->nullable();
            $table->text('image')->nullable();
            $table->timestamps();

            $table->foreign('cuisine_id')->references('id')->on('cuisines')->onDelete('cascade');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreign('menu_sub_category_id')->references('id')->on('menu_sub_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menus');
    }
}
