<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdminCenterNotificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('admin_center_notifications', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('vendor_offline')->nullable();
            $table->unsignedInteger('product_not_available')->nullable();
            $table->unsignedInteger('overdue_order')->nullable();
            $table->integer('status')->nullable();
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
        Schema::dropIfExists('admin_center_notifications');
    }
}
