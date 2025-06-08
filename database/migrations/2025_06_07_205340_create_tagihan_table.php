<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
    Schema::create('tagihan', function (Blueprint $table) {
    $table->id();
    $table->string('nomor_tagihan', 50)->unique();
    $table->unsignedBigInteger('rekam_medis_id');
    $table->unsignedBigInteger('pasien_id');
    $table->date('tanggal_tagihan');
    $table->decimal('subtotal', 12, 2);
    $table->enum('status', ['sudah_dibayar', 'menunggu_pembayaran'])->default('menunggu_pembayaran');
    $table->text('catatan')->nullable();
    $table->timestamps();

    $table->foreign('rekam_medis_id')->references('id')->on('rekam_medis');
    $table->foreign('pasien_id')->references('id')->on('patients');

    $table->index('rekam_medis_id');
    $table->index('pasien_id');
    $table->index('nomor_tagihan');
    $table->index(['status', 'tanggal_tagihan']);
});

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tagihan');
    }
};
