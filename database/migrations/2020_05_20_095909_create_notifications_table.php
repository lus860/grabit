<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('order_id')->nullable();
            $table->unsignedInteger('courier_order_id')->nullable();
            $table->integer('admin_notification')->nullable();
            $table->integer('restaurant_notification')->nullable();
            $table->integer('dash_api')->nullable();
            $table->integer('user_firebase')->nullable();
            $table->integer('restaurant_firebase')->nullable();
            $table->integer('admin_center')->nullable();
            $table->integer('admin_firebase')->nullable();

            $table->timestamps();

            $table->foreign('order_id')->references('id')->on('pending_orders')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('notifications');
    }
}
