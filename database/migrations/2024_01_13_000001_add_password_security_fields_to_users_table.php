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
        Schema::table('users', function (Blueprint $table) {
            // Add password security fields if not exists
            if (!Schema::hasColumn('users', 'password_changed_at')) {
                $table->timestamp('password_changed_at')->nullable()->after('password');
                $table->index('password_changed_at');
            }

            if (!Schema::hasColumn('users', 'password_expires_at')) {
                $table->timestamp('password_expires_at')->nullable()->after('password_changed_at');
                $table->index('password_expires_at');
            }

            if (!Schema::hasColumn('users', 'must_change_password')) {
                $table->boolean('must_change_password')->default(false)->after('password_expires_at');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex(['password_changed_at']);
            $table->dropIndex(['password_expires_at']);
            $table->dropColumn(['password_changed_at', 'password_expires_at', 'must_change_password']);
        });
    }
};
