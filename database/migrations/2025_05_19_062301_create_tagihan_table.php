<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagihanTable extends Migration
{
    public function up()
    {
        Schema::create('tagihan', function (Blueprint $table) {
            $table->id('tagihan_id');
            $table->unsignedBigInteger('pasien_id');
            $table->date('tanggal_tagih');
            $table->decimal('total_biaya', 12, 2)->default(0);
            $table->enum('status', ['belum bayar', 'lunas'])->default('belum bayar');

            $table->foreign('pasien_id')->references('id')->on('patients')->onDelete('cascade');
            $table->index('pasien_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tagihan');
    }
}
