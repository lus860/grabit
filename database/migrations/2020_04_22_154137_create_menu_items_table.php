<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_items', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('menu_id')->nullable();
            $table->string('name')->nullable();
            $table->tinyInteger('type')->nullable();
            $table->text('description')->nullable();
            $table->integer('max_quantity')->nullable()->default(1);
            $table->double('price')->nullable()->default(0);
            $table->double('container_price')->nullable()->default(0);
            $table->double('popular_item')->nullable()->default(0);
            $table->double('special_offer')->nullable()->default(0);
            $table->double('offer_price')->nullable()->default(0);
            $table->enum('status',['0','1'])->nullable()->default(1);
            $table->timestamps();

            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_items');
    }
}
