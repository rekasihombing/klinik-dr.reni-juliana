<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagihanTindakanTable extends Migration
{
    public function up()
    {
        Schema::create('tagihan_tindakan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tagihan_id');
            $table->unsignedBigInteger('tindakan_id');
            $table->integer('jumlah')->default(1);
            $table->decimal('subtotal', 12, 2);

            // Foreign Key constraints
            $table->foreign('tagihan_id')->references('tagihan_id')->on('tagihan')->onDelete('cascade');
            $table->foreign('tindakan_id')->references('tindakan_id')->on('tindakan_medis')->onDelete('cascade'); // Tambahkan onDelete jika diperlukan

            // Indexes for faster queries
            $table->index('tagihan_id');
            $table->index('tindakan_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tagihan_tindakan');
    }
}
