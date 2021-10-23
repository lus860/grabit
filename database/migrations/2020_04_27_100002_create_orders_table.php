<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrdersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_table = 'orders';

    /**
     * Run the migrations.
     * @table orders
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->set_table, function (Blueprint $table) {           
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable();
            $table->unsignedInteger('restaurant_id')->nullable();
            $table->unsignedInteger('shipping_id')->nullable();
            $table->unsignedInteger('payment_id')->nullable();
            $table->unsignedInteger('address_id')->nullable();
            $table->unsignedInteger('product_id')->nullable();
            $table->timestamp('order_date')->default(DB::raw('CURRENT_TIMESTAMP'));
            $table->dateTime('due_date')->nullable();
            $table->enum('order_type',['1','2'])->nullable();
            $table->string('status', 32)->nullable();
            $table->string('size', 32)->nullable();
            $table->string('img', 64)->nullable();
            $table->string('color', 32)->nullable();
            $table->integer('quantity')->nullable();
            $table->integer('amount')->nullable();
            $table->text('instruction')->nullable();
            $table->text('delivery_note')->nullable();
            $table->integer('food_rating')->nullable();
            $table->integer('delivery_rating')->nullable();
            $table->text('feedback')->nullable();
            $table->string('required_at')->nullable();
            $table->integer('rider')->nullable();
            $table->string('source')->nullable();
            $table->time('prepared_from')->nullable();
            $table->time('prepared_to')->nullable();
            $table->text('firebase_id')->nullable();
            $table->text('update_token')->nullable();
            $table->integer('delivery_task_id')->nullable();
            $table->string('delivery_method')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('restaurant_id')->references('id')->on('restaurants')->onDelete('cascade');
            $table->foreign('shipping_id')->references('id')->on('shippings')->onDelete('cascade');
            $table->foreign('payment_id')->references('id')->on('payments')->onDelete('cascade');
            $table->foreign('address_id')->references('id')->on('address')->onDelete('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade');


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
