<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan_tindakan', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tagihan_id');
            $table->unsignedBigInteger('tindakan_pasien_id');
            $table->string('nama_tindakan'); // snapshot dari master
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('tagihan_id')->references('id')->on('tagihan')->onDelete('cascade');
            $table->foreign('tindakan_pasien_id')->references('id')->on('tindakan_pasien');

            $table->index('tagihan_id');
            $table->index('tindakan_pasien_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan_tindakan');
    }
};
