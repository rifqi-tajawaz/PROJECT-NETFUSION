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
        Schema::create('hotspot_profiles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('rate_limit')->nullable();
            $table->integer('session_timeout')->nullable();
            $table->timestamps();
        });

        Schema::create('hotspot_users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('password');
            $table->string('email')->nullable();
            $table->foreignId('profile_id')->nullable()->constrained('hotspot_profiles')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('hotspot_vouchers', function (Blueprint $table) {
            $table->id();
            $table->string('code')->unique();
            $table->integer('validity_days');
            $table->integer('max_uses')->default(1);
            $table->foreignId('profile_id')->constrained('hotspot_profiles')->cascadeOnDelete();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotspot_vouchers');
        Schema::dropIfExists('hotspot_users');
        Schema::dropIfExists('hotspot_profiles');
    }
};
