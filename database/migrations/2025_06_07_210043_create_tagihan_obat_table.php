<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('tagihan_obat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tagihan_id');
            $table->unsignedBigInteger('resep_obat_id');
            $table->string('nama_obat'); // snapshot
            $table->string('dosis')->nullable();
            $table->integer('jumlah');
            $table->decimal('harga_satuan', 12, 2);
            $table->decimal('subtotal', 12, 2);
            $table->text('catatan')->nullable();
            $table->timestamps();

            $table->foreign('tagihan_id')->references('id')->on('tagihan')->onDelete('cascade');
            $table->foreign('resep_obat_id')->references('id')->on('resep_obat');

            $table->index('tagihan_id');
            $table->index('resep_obat_id');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('tagihan_obat');
    }
};

