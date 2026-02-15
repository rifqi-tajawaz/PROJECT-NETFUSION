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
            // Skip 2FA fields as they already exist from 2025_12_20_020244 migration

            // OAuth provider fields
            $table->string('provider')->nullable()->after('two_factor_confirmed_at');
            $table->string('provider_id')->nullable()->after('provider');
            $table->text('provider_token')->nullable()->after('provider_id');
            $table->text('provider_refresh_token')->nullable()->after('provider_token');

            // Security fields
            $table->boolean('is_active')->default(true)->after('provider_refresh_token');
            $table->boolean('is_locked')->default(false)->after('is_active');
            $table->text('lock_reason')->nullable()->after('is_locked');
            $table->timestamp('locked_at')->nullable()->after('lock_reason');
            $table->timestamp('password_changed_at')->nullable()->after('locked_at');
            $table->timestamp('last_login_at')->nullable()->after('password_changed_at');
            $table->string('last_login_ip', 45)->nullable()->after('last_login_at');
            $table->integer('login_attempts')->default(0)->after('last_login_ip');

            // Skip avatar field as it already exists from 2025_12_20_052000 migration

            // Indexes
            $table->index(['provider', 'provider_id']);
            $table->index('is_active');
            $table->index('is_locked');
            $table->index('email_verified_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'provider',
                'provider_id',
                'provider_token',
                'provider_refresh_token',
                'is_active',
                'is_locked',
                'lock_reason',
                'locked_at',
                'password_changed_at',
                'last_login_at',
                'last_login_ip',
                'login_attempts',
            ]);

            $table->dropIndex(['provider', 'provider_id']);
            $table->dropIndex(['is_active']);
            $table->dropIndex(['is_locked']);
            $table->dropIndex(['email_verified_at']);
        });
    }
};