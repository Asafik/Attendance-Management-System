<?php
// database/migrations/2024_03_08_xxxxxx_create_activity_logs_table.php

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
        Schema::create('activity_logs', function (Blueprint $table) {
            $table->id();

            // Relasi ke user
            $table->foreignId('user_id')
                  ->constrained()
                  ->onDelete('cascade');

            // Informasi aktivitas
            $table->string('action'); // login, logout, create, update, delete, view
            $table->string('model')->nullable(); // Employee, Division, dll
            $table->unsignedBigInteger('model_id')->nullable(); // ID data yang diakses
            $table->text('description')->nullable();

            // Status
            $table->string('status')->default('success'); // success, failed, warning

            // Informasi request
            $table->string('method')->nullable(); // GET, POST, PUT, DELETE
            $table->text('url')->nullable();
            $table->text('payload')->nullable(); // Data yang dikirim

            // Informasi IP & Device
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->string('browser')->nullable();
            $table->string('browser_version')->nullable();
            $table->string('os')->nullable();
            $table->string('os_version')->nullable();
            $table->string('device')->nullable(); // desktop, tablet, mobile
            $table->string('device_model')->nullable();

            // Informasi Lokasi (dari IP - geolocation)
            $table->string('city')->nullable();
            $table->string('region')->nullable(); // Provinsi/State
            $table->string('country')->nullable();
            $table->string('country_code', 2)->nullable();

            // ===== TAMBAHAN UNTUK LOKASI DARI BROWSER (GPS) =====
            $table->decimal('latitude', 10, 8)->nullable(); // -6.2088
            $table->decimal('longitude', 11, 8)->nullable(); // 106.8456
            $table->integer('accuracy')->nullable(); // akurasi dalam meter
            $table->string('location_source')->nullable(); // 'gps', 'network', 'ip_fallback'
            $table->string('full_address')->nullable(); // alamat lengkap dari reverse geocoding
            // ===================================================

            // Waktu
            $table->timestamps();

            // Index untuk pencarian cepat
            $table->index(['user_id', 'created_at']);
            $table->index('action');
            $table->index('status');
            $table->index(['model', 'model_id']);
            $table->index('ip_address');
            $table->index('created_at');

            // Index untuk kolom baru
            $table->index('latitude');
            $table->index('longitude');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('activity_logs');
    }
};
