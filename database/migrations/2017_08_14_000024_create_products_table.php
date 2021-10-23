<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_table = 'products';

    /**
     * Run the migrations.
     * @table products
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->set_table, function (Blueprint $table) {
            $table->increments('id');
            $table->integer('parent_id');
            $table->string('slug')->nullable();
            $table->string('name')->nullable();
            $table->text('description')->nullable();
            $table->string('a_img')->nullable();
            $table->string('b_img')->nullable();
            $table->string('c_img')->nullable();
            $table->integer('quantity')->nullable()->default(null);
            $table->double('price')->nullable();
            $table->double('product_weight')->nullable();
            $table->double('shipping_weight')->nullable();
            $table->string('image1')->nullable();
            $table->string('image2')->nullable();
            $table->string('image3')->nullable();
            $table->string('image4')->nullable();
            $table->string('image5')->nullable();
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
        Schema::dropIfExists($this->set_table);
    }
}
