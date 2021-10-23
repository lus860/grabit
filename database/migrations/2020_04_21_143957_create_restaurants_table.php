<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRestaurantsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('restaurants', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('area_id')->nullable();
            $table->unsignedInteger('country_id')->nullable();
            $table->unsignedInteger('city_id')->nullable();
            $table->unsignedInteger('vendor_id')->nullable();
            $table->string('name')->nullable();
            $table->string('slug')->nullable();
            $table->string('company_name')->nullable();
            $table->string('contact_name')->nullable();
            $table->string('phone')->nullable();
            $table->string('email')->nullable();
            $table->double('average_rating')->nullable()->default(1);
            $table->string('latitude')->nullable();
            $table->string('longitude')->nullable();
            $table->string('address1')->nullable();
            $table->string('address2')->nullable();
            $table->double('delivery_commission')->nullable();
            $table->double('collection_commission')->nullable();
            $table->double('dine_commission')->nullable();
            $table->double('number_for_customers')->nullable();
            $table->string('beneficiary_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('paytz_number')->nullable();
            $table->text('registration_certificate')->nullable();
            $table->text('tin_certificate')->nullable();
            $table->text('business_license')->nullable();
            $table->text('director_id')->nullable();
            $table->text('agreement')->nullable();
            $table->integer('payment_frequent')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('website')->nullable();
            $table->string('preparation_time')->nullable();
            $table->integer('minimum_order')->nullable();
            $table->text('banner_image')->nullable();
            $table->text('display_image')->nullable();
            $table->double('cost_for_two')->nullable();
            $table->enum('status',['1','2'])->nullable()->default(1);
            $table->timestamps();
            $table->foreign('area_id')->references('id')->on('areas')->onDelete('cascade');
            $table->foreign('country_id')->references('id')->on('countries')->onDelete('cascade');
            $table->foreign('city_id')->references('id')->on('cities')->onDelete('cascade');
            $table->foreign('vendor_id')->references('id')->on('vendor_type')->onDelete('cascade');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('restaurants');
    }
}
