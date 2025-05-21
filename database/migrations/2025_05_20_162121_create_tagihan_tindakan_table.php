<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
   public function up()
    {
        Schema::create('tagihan_tindakan', function (Blueprint $table) {
            $table->id('id'); // Kolom primary key untuk tagihan_tindakan
            $table->unsignedBigInteger('tagihan_id'); // Kolom untuk foreign key tagihan_id
            $table->unsignedBigInteger('tindakan_id'); // Kolom untuk foreign key tindakan_id
            $table->integer('jumlah')->default(1); // Jumlah tindakan yang dilakukan
            $table->decimal('subtotal', 12, 2); // Subtotal biaya tindakan
            $table->timestamps();

            // Menambahkan foreign key ke tagihan_id
            $table->foreign('tagihan_id')->references('tagihan_id')->on('tagihan')->onDelete('cascade');
            
            // Menambahkan foreign key ke tindakan_id
            $table->foreign('tindakan_id')->references('tindakan_id')->on('tindakan_medis')->onDelete('cascade');
            
            // Menambahkan index pada tagihan_id dan tindakan_id
            $table->index('tagihan_id');
            $table->index('tindakan_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tagihan_tindakan'); // Menghapus tabel tagihan_tindakan jika rollback
    }
};
