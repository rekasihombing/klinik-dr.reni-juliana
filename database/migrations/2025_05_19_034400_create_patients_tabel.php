<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('patients', function (Blueprint $table) {
        $table->id();
        $table->unsignedInteger('user_id');
        $table->string('nama_lengkap', 100);
        $table->string('nik', 20);
        $table->date('tanggal_lahir');
        $table->enum('jenis_kelamin', ['L', 'P']);
        $table->string('golongan_darah', 3)->nullable();
        $table->string('email', 100)->nullable();
        $table->string('no_hp', 20)->nullable();
        $table->text('alamat')->nullable();
        $table->timestamp('created_at')->useCurrent();
        $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
    });
    }

    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
