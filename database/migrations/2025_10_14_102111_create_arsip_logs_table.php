<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArsipLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
   public function up(): void
    {
        Schema::create('arsip_logs', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('arsip_id')->nullable(); // relasi ke arsip
            $table->unsignedBigInteger('user_id')->nullable(); // relasi ke user
            $table->string('aksi'); // contoh: buat, ubah, hapus, unduh
            $table->text('keterangan')->nullable();
            $table->string('ip_address')->nullable();
            $table->string('user_agent')->nullable();
            $table->timestamps();

            $table->foreign('arsip_id')->references('id')->on('arsips')->onDelete('set null');
            $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('arsip_logs');
    }
}
