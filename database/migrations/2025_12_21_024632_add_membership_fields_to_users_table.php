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
        Schema::table('users', function (Blueprint $table) {
            $table->enum('membership_status', ['active', 'expired', 'trial'])->default('trial')->after('password');
            $table->string('membership_package')->default('Free')->after('membership_status');
            $table->date('membership_pay_date')->nullable()->after('membership_package');
            $table->date('membership_expire')->nullable()->after('membership_pay_date');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
