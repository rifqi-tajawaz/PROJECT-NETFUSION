<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserMaintenanceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $adminEmail = 'tajawaz.solutions@gmail.com';
        $testEmail = 'test@gmail.com';

        // 1. Promote Tajawaz Solutions to Admin
        $admin = User::where('email', $adminEmail)->first();
        if ($admin) {
            $admin->role = 'admin';
            $admin->save();
            $this->command->info("User {$adminEmail} promoted to ADMIN.");
        } else {
            // Optional: Create if missing, but user implied it exists. 
            // Let's create it to be safe and ensure access.
            User::create([
                'name' => 'Tajawaz Solutions',
                'email' => $adminEmail,
                'password' => Hash::make('password'), // Temporary default if it didn't exist
                'role' => 'admin',
                'email_verified_at' => now(),
            ]);
            $this->command->warn("User {$adminEmail} was not found, so it was created with default password 'password'.");
        }

        // 2. Create/Update Test User
        User::updateOrCreate(
            ['email' => $testEmail],
            [
                'name' => 'Test User',
                'password' => Hash::make('11111111'),
                'role' => 'user',
                'email_verified_at' => now(),
                'is_active' => true,
            ]
        );
        $this->command->info("User {$testEmail} created/updated with password '11111111'.");

        // 3. Delete all other users
        $deleted = User::whereNotIn('email', [$adminEmail, $testEmail])->delete();
        $this->command->info("Deleted {$deleted} other users.");
    }
}
