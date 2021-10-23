<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnCourierOrderNew extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('courier_orders', function(Blueprint $table) {
            $table->integer('carrier')->after('weight')->nullable();;
            $table->integer('parcel_type')->after('weight')->nullable();
            $table->text('comments')->after('weight')->nullable();
            $table->integer('payment')->after('weight')->nullable();;
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
