<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStokObatTable extends Migration
{
    public function up()
    {
        Schema::create('stok_obat', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('obat_id');
            $table->integer('jumlah')->default(0);
            $table->date('tanggal_kadaluarsa');
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();

            $table->foreign('obat_id')->references('id')->on('obat')->onDelete('cascade');
            $table->index('obat_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('stok_obat');
    }
}
