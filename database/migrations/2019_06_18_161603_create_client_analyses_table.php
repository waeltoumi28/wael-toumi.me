<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientAnalysesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_analyses', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('client_id')->unsigned();
            $table->integer('total_cost')->unsigned();
            $table->integer('spent')->unsigned();
            $table->integer('saved')->unsigned();
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
        Schema::dropIfExists('client_analyses');
    }
}
