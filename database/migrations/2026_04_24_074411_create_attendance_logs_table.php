<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendance_logs', function (Blueprint $table) {
            $table->id();

            // Relasi ke user/employee yang absen
            $table->foreignId('employee_id')
                  ->constrained('users') // atau 'employees' sesuaikan tabel user Mas
                  ->onDelete('cascade');

            $table->string('location_code'); // Nyimpen kode lokasi (misal: ALEENA-PINTU-01)
            $table->enum('type', ['in', 'out']); // Masuk atau Pulang
            $table->date('date'); // Tanggal absen (YYYY-MM-DD)

            $table->timestamps(); // Ini bakal nyatet jam absen detil di created_at
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendance_logs');
    }
};
