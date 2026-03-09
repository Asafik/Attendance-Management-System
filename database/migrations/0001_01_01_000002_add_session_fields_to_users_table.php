<?php
// database/migrations/2024_03_07_000002_add_session_fields_to_users_table.php

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
            // Cek apakah kolom sudah ada sebelum menambah
            if (!Schema::hasColumn('users', 'session_id')) {
                $table->string('session_id')->nullable();
            }

            if (!Schema::hasColumn('users', 'last_login_at')) {
                $table->timestamp('last_login_at')->nullable();
            }

            if (!Schema::hasColumn('users', 'last_ip')) {
                $table->string('last_ip')->nullable();
            }

            if (!Schema::hasColumn('users', 'last_user_agent')) {
                $table->text('last_user_agent')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Hapus kolom jika ada
            $columns = ['session_id', 'last_login_at', 'last_ip', 'last_user_agent'];
            $table->dropColumn($columns);
        });
    }
};
