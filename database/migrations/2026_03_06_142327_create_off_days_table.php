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
        Schema::create('weekly_off_days', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id')->constrained()->onDelete('cascade');
            $table->string('day'); // Senin, Selasa, Rabu, Kamis, Jumat, Sabtu, Minggu
            $table->date('week_start'); // Tanggal mulai minggu (misal: 2026-03-01)
            $table->date('week_end');   // Tanggal akhir minggu (misal: 2026-03-07)
            $table->timestamps();

            // Unique: 1 karyawan tidak boleh punya hari yang sama di minggu yang sama
            $table->unique(['employee_id', 'day', 'week_start'], 'unique_weekly_off');

            // Index untuk pencarian cepat
            $table->index(['employee_id', 'week_start', 'week_end']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('weekly_off_days');
    }
};
