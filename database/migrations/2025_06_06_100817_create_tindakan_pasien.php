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
        Schema::create('tindakan_pasien', function (Blueprint $table) {
            $table->id();

            // Relasi ke rekam_medis (tindakan terkait rekam medis pasien)
            $table->unsignedBigInteger('rekam_medis_id');

            // Relasi ke master tindakan medis
            $table->unsignedBigInteger('tindakan_id');

            $table->integer('jumlah')->default(1);
            $table->decimal('subtotal', 12, 2);

            $table->timestamps();

            // Foreign keys & indexes
            $table->foreign('rekam_medis_id')->references('id')->on('rekam_medis')->onDelete('cascade');
            $table->foreign('tindakan_id')->references('tindakan_id')->on('tindakan_medis')->onDelete('cascade');

            $table->index('rekam_medis_id');
            $table->index('tindakan_id');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tindakan_pasien');
    }

};
