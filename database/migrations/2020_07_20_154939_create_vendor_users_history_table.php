<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorUsersHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_users_history', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('vendor_id')->nullable();
            $table->text('source')->nullable();
            $table->text('token_login')->nullable();
            $table->text('firebase_token')->nullable();
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
        Schema::dropIfExists('vendor_users_history');
    }
}
