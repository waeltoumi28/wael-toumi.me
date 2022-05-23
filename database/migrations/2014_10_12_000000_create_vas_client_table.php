<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVasClientTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vas_client', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('username');
            $table->string('vas_centre_id')->nullable();
            $table->string('first_name');
            $table->string('last_name');
            $table->bigInteger('cell');
            $table->string('email')->unique();
            $table->integer('country_id');
            $table->string('national_id');
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->boolean('isVerified')->default(false);
            $table->integer('status')->nullable();
            $table->boolean('delete')->default(false);
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
        Schema::dropIfExists('vas_client');
    }
}
