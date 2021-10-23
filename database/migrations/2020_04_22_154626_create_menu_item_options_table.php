<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemOptionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_item_options', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('item_id')->nullable()->unsigned();
            $table->string('name')->nullable();
            $table->string('type')->nullable();
            $table->integer('item_maximum')->nullable();

            $table->timestamps();
            $table->foreign('item_id')->references('id')->on('menu_items')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_item_options');
    }
}
