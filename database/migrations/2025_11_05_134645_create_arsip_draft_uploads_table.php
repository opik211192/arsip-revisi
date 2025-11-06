<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateArsipDraftUploadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('arsip_draft_uploads', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('arsip_draft_id');
            $table->string('no_item')->nullable();
            $table->string('keterangan')->nullable();
            $table->string('file_path');
            $table->timestamps();

            // foreign key ke tabel utama
            $table->foreign('arsip_draft_id')
                ->references('id')
                ->on('arsip_drafts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('arsip_draft_uploads');
    }
}
