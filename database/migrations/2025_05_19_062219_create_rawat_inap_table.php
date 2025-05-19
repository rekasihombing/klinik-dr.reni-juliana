<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRawatInapTable extends Migration
{
    public function up()
    {
        Schema::create('rawat_inap', function (Blueprint $table) {
            $table->id('inap_id');
            $table->unsignedBigInteger('pasien_id');
            $table->date('tanggal_masuk');
            $table->date('tanggal_keluar')->nullable();
            $table->decimal('tarif_per_hari', 10, 2);

            $table->foreign('pasien_id')->references('id')->on('patients')->onDelete('cascade');
            $table->index('pasien_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('rawat_inap');
    }
}