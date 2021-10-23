<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientSourcesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_sources', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id');
            $table->integer('user_id')->nullable();
            $table->integer('restaurant_user')->nullable();
            $table->string('token')->nullable()->unique();
            $table->text('firebase_token')->unique();
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
        Schema::dropIfExists('client_sources');
    }
}
