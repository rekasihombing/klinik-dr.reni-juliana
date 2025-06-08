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
        Schema::table('appointments', function (Blueprint $table) {
            // Hanya tambah kolom antrian
            if (!Schema::hasColumn('appointments', 'antrian')) {
                $table->string('antrian')->nullable()->after('status')
                      ->comment('Nomor antrian pasien (A01, A02, dst) - reset setiap hari');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('antrian');
        });
    }
};