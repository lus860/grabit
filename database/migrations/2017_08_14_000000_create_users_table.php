<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_table = 'users';

    /**
     * Run the migrations.
     * @table users
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->set_table, function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('restaurant_id')->nullable();
            $table->string('name')->nullable();
            $table->string('avatar')->nullable();
            $table->string('email')->unique()->nullable();
            $table->string('phone')->nullable();
            $table->integer('origin')->nullable()->comment('1=ios, 2=android, 3=web');
            $table->string('source_app')->nullable();
            $table->string('imei')->nullable();
            $table->string('password')->nullable();
            $table->tinyInteger('is_activated')->nullable();
            $table->enum('is_busy',['1','2'])->nullable();
            $table->string('firebase_id')->nullable();
            $table->integer('otp')->nullable();
            $table->enum('user_type',['1','2'])->nullable()->default(1);
            $table->text('token')->nullable();

            $table->rememberToken();
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
