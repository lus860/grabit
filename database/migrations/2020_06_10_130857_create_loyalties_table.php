<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLoyaltiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('loyalties', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vendor_id')->nullable();
            $table->integer('spend')->nullable();
            $table->integer('redemption')->nullable();
            $table->text('image')->nullable();
            $table->timestamps();

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
        Schema::dropIfExists('loyalties');
    }
}
