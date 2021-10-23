<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMenuItemOptionsValuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('menu_item_option_values', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('option_id')->nullable();
            $table->string('value')->nullable();
            $table->string('price')->nullable();
            $table->enum('status',['0','1'])->nullable()->default(1);
            $table->timestamps();
            $table->foreign('option_id')->references('id')->on('menu_item_options')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('menu_item_options_values');
    }
}
