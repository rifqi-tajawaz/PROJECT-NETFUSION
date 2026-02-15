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
        Schema::create('user_devices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('fingerprint', 64)->unique();
            $table->string('device_name', 255);
            $table->string('device_type', 20); // mobile, tablet, desktop, bot
            $table->string('platform', 255);
            $table->string('browser', 255);
            $table->string('ip_address', 45);
            $table->text('user_agent');
            $table->timestamp('first_seen_at')->nullable();
            $table->timestamp('last_seen_at')->nullable();
            $table->boolean('is_trusted')->default(false);
            $table->json('device_data')->nullable();
            $table->timestamps();

            // Indexes for performance
            $table->index(['user_id', 'fingerprint']);
            $table->index(['user_id', 'ip_address']);
            $table->index(['user_id', 'last_seen_at']);
            $table->index(['user_id', 'is_trusted']);
            $table->index('fingerprint');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_devices');
    }
};