<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCourierOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('courier_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('transaction_id')->unique()->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('weight')->nullable();
            $table->text('pick_up_address')->nullable();
            $table->string('pick_up_area')->nullable();
            $table->string('pick_up_city')->nullable();
            $table->string('pick_up_latitude')->nullable();
            $table->string('pick_up_longitude')->nullable();
            $table->longText('pick_up_information')->nullable();
            $table->text('delivery_address')->nullable();
            $table->string('delivery_area')->nullable();
            $table->string('delivery_city')->nullable();
            $table->longText('delivery_information')->nullable();
            $table->string('delivery_latitude')->nullable();
            $table->string('delivery_longitude')->nullable();
            $table->string('distance')->nullable();
            $table->integer('price')->nullable();
            $table->string('rider_name')->nullable();
            $table->string('status')->nullable();
            $table->string('status_text')->nullable();
            $table->integer('seen')->nullable();
            $table->string('envelope')->nullable();
            $table->text('action')->nullable();
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
        Schema::dropIfExists('courier_orders');
    }
}
