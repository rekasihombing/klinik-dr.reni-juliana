<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddObatLuarToRekamMedisTable extends Migration
{
    public function up()
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->text('obat_luar')->nullable()->after('catatan_dokter');
        });
    }

    public function down()
    {
        Schema::table('rekam_medis', function (Blueprint $table) {
            $table->dropColumn('obat_luar');
        });
    }
}
