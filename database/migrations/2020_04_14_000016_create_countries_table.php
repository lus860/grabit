<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCountriesTable extends Migration
{
    /**
     * Schema table name to migrate
     * @var string
     */
    public $set_table = 'countries';

    /**
     * Run the migrations.
     * @table countries
     *
     * @return void
     */
    public function up()
    {
        Schema::create($this->set_table, function (Blueprint $table) {            
            $table->increments('id');
            $table->char('code', 2)->nullable()->default('');
            $table->string('name', 45)->nullable()->default('');
            $table->double('vat');
            $table->string('images', 45);
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
