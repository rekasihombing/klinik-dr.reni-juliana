<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('jadwal_kontrol', function (Blueprint $table) {
            $table->enum('status', ['terjadwal', 'batal'])->default('terjadwal')->change();
        });
    }

    public function down(): void
    {
        Schema::table('jadwal_kontrol', function (Blueprint $table) {
            $table->string('status')->nullable()->change();
        });
    }
};