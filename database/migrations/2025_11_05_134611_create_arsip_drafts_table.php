<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArsipDraftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arsip_drafts', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('jenis_arsip_id');
            $table->unsignedBigInteger('jenis_id');
            $table->unsignedBigInteger('id_pencipta_arsip');
            
            $table->string('lokasi_arsip');
            $table->string('no_berkas');
            $table->string('no_box');
            $table->string('tahun');
            $table->string('uraian_arsip');

            $table->integer('status')->default(0);
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->unsignedBigInteger('approved_by')->nullable();

            $table->foreign('id_pencipta_arsip')->references('id')->on('struktural_details');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('jenis_id')->references('id')->on('jenis');
            $table->foreign('jenis_arsip_id')->references('id')->on('jenis_arsips');

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null'); 
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
        Schema::dropIfExists('arsip_drafts');
    }
}
