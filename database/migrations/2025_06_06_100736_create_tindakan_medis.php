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
            $table->id('tindakan_id'); // primary key
            $table->string('nama_tindakan');
            $table->decimal('tarif', 10, 2);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('tindakan_medis');
    }
};
