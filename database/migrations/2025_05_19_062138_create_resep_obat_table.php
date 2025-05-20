<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateResepObatTable extends Migration
{
    public function up()
    {
        Schema::create('resep_obat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('rekam_medis_id');
            $table->unsignedBigInteger('obat_id')->nullable();
            $table->string('nama_obat', 100);
            $table->string('dosis', 100)->nullable();
            $table->text('catatan')->nullable();
            $table->date('tanggal_mulai');
            $table->date('tanggal_terakhir');
            $table->boolean('dari_klinik')->default(true);

            $table->foreign('rekam_medis_id')->references('id')->on('rekam_medis')->onDelete('cascade');
            $table->foreign('obat_id')->references('id')->on('obat')->nullOnDelete();

            $table->index('rekam_medis_id');
            $table->index('obat_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('resep_obat');
    }
}
