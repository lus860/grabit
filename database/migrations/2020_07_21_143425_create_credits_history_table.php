<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCreditsHistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('credits_history', function (Blueprint $table) {
            $table->id();
            $table->text('transaction_id')->nullable();
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('vendor_id')->nullable();
            $table->unsignedInteger('vendor_type_id')->nullable();
            $table->unsignedInteger('branch_id')->nullable();
            $table->bigInteger('amount')->nullable();
            $table->text('txn_type')->nullable();
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
        Schema::dropIfExists('credits_history');
    }
}
