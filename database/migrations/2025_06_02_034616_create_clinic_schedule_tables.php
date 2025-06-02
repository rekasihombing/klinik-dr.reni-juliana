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
        // clinic_schedules
        Schema::create('clinic_schedules', function (Blueprint $table) {
            $table->id();
            $table->enum('day_of_week', ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu']);
            $table->boolean('is_open')->default(true);
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->timestamps();

            $table->unique('day_of_week');
        });

        // schedule_exceptions
        Schema::create('schedule_exceptions', function (Blueprint $table) {
            $table->id();
            $table->date('exception_date')->unique();
            $table->boolean('is_open')->default(false);
            $table->time('open_time')->nullable();
            $table->time('close_time')->nullable();
            $table->string('reason')->nullable();
            $table->timestamps();
        });

        // schedule_change_logs
        Schema::create('schedule_change_logs', function (Blueprint $table) {
            $table->id();
            $table->enum('schedule_type', ['regular', 'exception']);
            $table->unsignedBigInteger('schedule_id');
            $table->json('old_values')->nullable();
            $table->json('new_values')->nullable();
            $table->unsignedBigInteger('changed_by')->nullable();
            $table->timestamp('changed_at')->useCurrent();

            // Kalau mau, bisa buat foreign key di sini kalau ada tabel user
            // $table->foreign('changed_by')->references('id')->on('users')->onDelete('set null');
        });

        // Buat view di migration agak ribet di Laravel,
        // jadi kamu bisa buat manual via DB::statement atau lewat seed khusus.
        DB::statement("
            CREATE OR REPLACE VIEW v_current_schedule AS
            SELECT 
                day_of_week,
                is_open,
                CASE 
                    WHEN is_open = TRUE THEN CONCAT(TIME_FORMAT(open_time, '%H:%i'), ' - ', TIME_FORMAT(close_time, '%H:%i'))
                    ELSE 'Tutup'
                END as schedule_display,
                open_time,
                close_time
            FROM clinic_schedules
            ORDER BY 
                CASE day_of_week
                    WHEN 'Senin' THEN 1
                    WHEN 'Selasa' THEN 2
                    WHEN 'Rabu' THEN 3
                    WHEN 'Kamis' THEN 4
                    WHEN 'Jumat' THEN 5
                    WHEN 'Sabtu' THEN 6
                    WHEN 'Minggu' THEN 7
                END
        ");
    }

    public function down()
    {
        DB::statement("DROP VIEW IF EXISTS v_current_schedule");

        Schema::dropIfExists('schedule_change_logs');
        Schema::dropIfExists('schedule_exceptions');
        Schema::dropIfExists('clinic_schedules');
    }
};
