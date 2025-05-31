<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRekamMedisTable extends Migration
{
    public function up()
    {
       Schema::create('rekam_medis', function (Blueprint $table) {
    $table->id();

    $table->unsignedBigInteger('appointment_id')->unique();
    $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');

    $table->unsignedBigInteger('patient_id')->unique();
    $table->foreign('patient_id')->references('id')->on('patients')->onDelete('cascade');

    // Riwayat Kunjungan
    $table->string('no_rekam_medis')->nullable();
    $table->date('tanggal_kunjungan')->nullable();
    $table->text('keluhan')->nullable();
    $table->text('rps')->nullable();
    $table->text('rpd')->nullable();
    $table->text('alergi')->nullable();
    $table->text('riwayat_obat')->nullable();

    // Pemeriksaan Fisik
    $table->string('tekanan_darah')->nullable();
    $table->string('suhu_tubuh')->nullable();
    $table->string('nadi')->nullable();
    $table->string('pernapasan')->nullable();
    $table->string('berat_badan')->nullable();
    $table->string('status_gizi')->nullable();

    // Diagnosa & Catatan (tanpa tindakan karena sudah ada relasi di tagihan_tindakan)
    $table->text('diagnosa')->nullable();
    $table->text('catatan_dokter')->nullable();

    $table->timestamps();
});

    }

    public function down()
    {
        Schema::dropIfExists('rekam_medis');
    }
}
