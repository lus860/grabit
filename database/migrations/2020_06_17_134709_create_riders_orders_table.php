<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRidersOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('riders_orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedInteger('rider_id')->nullable();
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('courier_id')->nullable();
            $table->timestamps();

            $table->foreign('rider_id')->references('id')->on('riders')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('riders_orders');
    }
}
