<?php
// database/migrations/2024_03_08_xxxxxx_add_location_permission_to_users_table.php

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
        Schema::table('users', function (Blueprint $table) {
            // Tambah kolom location_permission
            // enum: 'not_set' (default), 'allowed', 'blocked'
            $table->enum('location_permission', ['not_set', 'allowed', 'blocked'])
                  ->default('not_set')
                  ->after('is_active');

            // Opsional: simpan lokasi terakhir user (bukan history, tapi lokasi terkini)
            $table->decimal('last_latitude', 10, 8)->nullable()->after('location_permission');
            $table->decimal('last_longitude', 11, 8)->nullable()->after('last_latitude');
            $table->integer('last_accuracy')->nullable()->after('last_longitude');
            $table->timestamp('last_location_at')->nullable()->after('last_accuracy');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'location_permission',
                'last_latitude',
                'last_longitude',
                'last_accuracy',
                'last_location_at'
            ]);
        });
    }
};
