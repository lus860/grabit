<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePendingOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pending_orders', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('vendor_id')->nullable();
            $table->unsignedInteger('address_id')->nullable();
            $table->enum('order_type',['1','2','3'])->nullable();
            $table->enum('order_from',['1','2','3'])->nullable();
            $table->enum('schedule',['1','2'])->nullable();
            $table->timestamp('schedule_time')->nullable();
            $table->enum('payment',['1','2'])->nullable();
            $table->string('order_total')->nullable();
            $table->string('delivery_fee')->nullable();
            $table->text('cooking_directions')->nullable();
            $table->text('order_notes')->nullable();
            $table->string('rider_name')->nullable();
            $table->timestamp('due_in')->nullable();
            $table->string('view')->nullable();
            $table->integer('transaction_id')->unique()->nullable();
            $table->string('status')->nullable();
            $table->integer('accept')->nullable();
            $table->text('accept_message')->nullable();
            $table->integer('seen')->nullable();
            $table->integer('admin_notification')->nullable();
            $table->integer('restaurant_notification')->nullable();
            $table->double('discount')->nullable();
            $table->double('discounted_price')->nullable();
            $table->double('collection_amount')->nullable();
            $table->text('action')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('restaurants')->onDelete('cascade');


        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pending_orders');
    }
}
