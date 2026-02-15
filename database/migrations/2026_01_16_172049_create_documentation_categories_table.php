<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('documentation_categories', function (Blueprint $table) {
            $table->id();
            $table->string('name'); // Display Name (or locale key)
            $table->string('slug')->unique(); // e.g. 'getting_started'
            $table->string('icon')->nullable(); // e.g. 'rocket_launch'
            $table->integer('order')->default(0); // For sorting sidebar
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('documentation_categories');
    }
};
