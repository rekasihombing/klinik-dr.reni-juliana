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
            $table->unsignedBigInteger('appointment_id');
            $table->text('keluhan')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('tindakan')->nullable();
            $table->timestamp('tanggal_input')->useCurrent();

            $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('cascade');
            $table->index('appointment_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rekam_medis');
    }
}
