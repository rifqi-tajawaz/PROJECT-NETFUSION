<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('system_settings', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique();
            $table->text('value')->nullable();
            $table->string('type')->default('text'); // text, boolean, number, json
            $table->string('description')->nullable();
            $table->string('group')->default('general'); // general, security, registration, etc.
            $table->timestamps();

            $table->index('key');
            $table->index('group');
        });

        // Insert default settings
        DB::table('system_settings')->insert([
            [
                'key' => 'allow_registration',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Allow new user registration',
                'group' => 'registration',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'require_email_verification',
                'value' => 'true',
                'type' => 'boolean',
                'description' => 'Require email verification for new accounts',
                'group' => 'security',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'key' => 'maintenance_mode',
                'value' => 'false',
                'type' => 'boolean',
                'description' => 'Put the site in maintenance mode',
                'group' => 'general',
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('system_settings');
    }
};
