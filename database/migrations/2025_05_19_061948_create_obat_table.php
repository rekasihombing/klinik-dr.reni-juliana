<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateObatTable extends Migration
{
    public function up()
    {
        Schema::create('obat', function (Blueprint $table) {
            $table->id();
            $table->string('nama_obat');
            $table->string('jenis_obat')->nullable();
            $table->text('deskripsi')->nullable();
            $table->decimal('harga', 10, 2)->nullable();
            $table->string('satuan')->default('pcs'); // pcs, botol, strip, dll
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('obat');
    }
}