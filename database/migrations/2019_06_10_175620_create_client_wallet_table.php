<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClientWalletTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_wallet', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('client_id')->unsigned();
            $table->decimal('wallet_credits');
            $table->integer('loyalty_credits')->unsigned();
            $table->integer('currency_id')->unsigned();
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
        Schema::dropIfExists('client_wallet');
    }
}
