<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('username')->unique();
            $table->string('password');
            $table->unsignedBigInteger('struktural_id');
            $table->unsignedBigInteger('struktural_detail_id');
            $table->rememberToken();
            $table->timestamps();

          
            $table->foreign('struktural_id')->references('id')->on('strukturals');
            $table->foreign('struktural_detail_id')->references('id')->on('struktural_details');
          
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
