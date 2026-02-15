<?php

namespace Tests\Feature\Database;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Tests\TestCase;

class UserMigrationTest extends TestCase
{
    use RefreshDatabase;

    /**
     * Test that the users table is created with expected columns.
     */
    public function test_users_table_structure(): void
    {
        $this->assertTrue(Schema::hasTable('users'));

        $expectedColumns = [
            'id',
            'name',
            'email',
            'email_verified_at',
            'password',
            'remember_token',
            'created_at',
            'updated_at',
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('users', $column),
                "Missing column '$column' in 'users' table"
            );
        }
    }

    /**
     * Test that the password_reset_tokens table is created with expected columns.
     */
    public function test_password_reset_tokens_table_structure(): void
    {
        $this->assertTrue(Schema::hasTable('password_reset_tokens'));

        $expectedColumns = [
            'email',
            'token',
            'created_at',
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('password_reset_tokens', $column),
                "Missing column '$column' in 'password_reset_tokens' table"
            );
        }
    }

    /**
     * Test that the sessions table is created with expected columns.
     */
    public function test_sessions_table_structure(): void
    {
        $this->assertTrue(Schema::hasTable('sessions'));

        $expectedColumns = [
            'id',
            'user_id',
            'ip_address',
            'user_agent',
            'payload',
            'last_activity',
        ];

        foreach ($expectedColumns as $column) {
            $this->assertTrue(
                Schema::hasColumn('sessions', $column),
                "Missing column '$column' in 'sessions' table"
            );
        }
    }
}
