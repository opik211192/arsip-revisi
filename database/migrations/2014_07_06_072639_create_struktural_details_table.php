<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStrukturalDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('struktural_details', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('struktural_id');
            $table->string('name');
            $table->timestamps();

            $table->foreign('struktural_id')->references('id')->on('strukturals');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('struktural_details');
    }
}
