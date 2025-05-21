<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAppointmentsTable extends Migration
{
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('pasien_id');
            $table->unsignedBigInteger('dokter_id');
            $table->date('tanggal');
            $table->time('jam_konsultasi');
            $table->text('keluhan');
            $table->enum('status', ['menunggu', 'dikonfirmasi', 'selesai', 'dibatalkan'])->default('menunggu');
            $table->enum('dibuat_oleh', ['pasien', 'staff']);
$table->timestamps(); 

            $table->foreign('pasien_id')->references('id')->on('patients')->onDelete('cascade');
            $table->foreign('dokter_id')->references('id')->on('doctors')->onDelete('cascade');

            $table->index('pasien_id');
            $table->index('dokter_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
}
