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
        Schema::create('tindakan_medis', function (Blueprint $table) {
            $table->id('tindakan_id'); // Kolom primary key untuk tindakan medis
            $table->string('nama_tindakan'); // Nama tindakan medis
            $table->decimal('tarif', 10, 2); // Tarif untuk tindakan medis
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tindakan_medis'); // Menghapus tabel tindakan_medis jika rollback
    }
};
