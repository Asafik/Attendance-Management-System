<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id();

            // --- FIELD LOGIN UNTUK APLIKASI ---
            $table->string('username')->unique(); // NIK atau Username unik
            $table->string('password');

            // --- RELASI ---
            $table->foreignId('division_id')
                  ->constrained('divisions')
                  ->onDelete('cascade');
            $table->foreignId('position_id')
                  ->nullable()
                  ->constrained('positions')
                  ->nullOnDelete();
            $table->foreignId('bank_id')
                  ->nullable()
                  ->constrained('banks')
                  ->nullOnDelete();

            // --- DATA DIRI ---
            $table->string('name');
            $table->string('phone')->nullable();
            $table->string('account_number')->nullable();
            $table->string('photo')->nullable();

            // --- STATUS & SETTING ---
            $table->enum('status', ['Aktif', 'Nonaktif'])->default('Aktif');
            $table->enum('regular_off_day', [
                'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu', 'Tidak Libur'
            ])->nullable();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('employees');
    }
};
