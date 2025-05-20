<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTagihanObatTable extends Migration
{
    public function up()
    {
        Schema::create('tagihan_obat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('tagihan_id');
            $table->unsignedBigInteger('obat_id');
            $table->integer('jumlah')->default(1);
            $table->decimal('subtotal', 12, 2);

            $table->foreign('tagihan_id')->references('tagihan_id')->on('tagihan')->onDelete('cascade');
            $table->foreign('obat_id')->references('id')->on('obat');

            $table->index('tagihan_id');
            $table->index('obat_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tagihan_obat');
    }
}
