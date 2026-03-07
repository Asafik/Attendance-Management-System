<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('attendances', function (Blueprint $table) {
            $table->id();

            $table->foreignId('employee_id')
                  ->constrained('employees')
                  ->onDelete('cascade');

            $table->date('date');

            $table->enum('status', [
                'WFH',
                'Izin',
                'Alpha',
                'Libur'
            ]);

            $table->text('note')->nullable();

            $table->timestamps();

            // Supaya 1 karyawan tidak bisa punya 2 status di tanggal sama
            $table->unique(['employee_id', 'date']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('attendances');
    }
};
