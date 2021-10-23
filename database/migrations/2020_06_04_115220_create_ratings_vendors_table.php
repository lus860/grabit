<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRatingsVendorsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ratings_vendors', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vendor_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('transaction_id')->nullable();
            $table->integer('delivery_rating')->nullable();
            $table->text('delivery_rating_message')->nullable();
            $table->integer('vendor_rating')->nullable();
            $table->text('vendor_rating_message')->nullable();
            $table->integer('seen')->nullable();
            $table->timestamps();

            $table->foreign('vendor_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ratings_vendors');
    }
}
