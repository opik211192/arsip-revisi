<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArsipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arsips', function (Blueprint $table) {
            $table->id();
            //$table->unsignedBigInteger('id_struktural');
            $table->unsignedBigInteger('jenis_arsip_id');
            // $table->string('judul_arsip');
            $table->string('lokasi_arsip');
            $table->unsignedBigInteger('jenis_id');
            $table->string('no_berkas');
            $table->string('no_box');
            $table->string('tahun');
            $table->unsignedBigInteger('id_pencipta_arsip');
            $table->string('uraian_arsip');
            $table->string('file_arsip');
            $table->integer('status')->default(0);
            $table->text('keterangan')->nullable();  
            
            //$table->foreign('id_struktural')->references('id')->on('struktural_details');
            $table->foreign('id_pencipta_arsip')->references('id')->on('struktural_details');


            //$table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->references('id')->on('users')->onDelete('cascade');

            //ini untuk jenis klasifikasi
            $table->foreign('jenis_id')->references('id')->on('jenis');

            //ini untuk jenis arsip
            $table->foreign('jenis_arsip_id')->references('id')->on('jenis_arsips');

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
        Schema::dropIfExists('arsips');
    }
}
