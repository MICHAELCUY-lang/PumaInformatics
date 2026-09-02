<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use RuntimeException;

/**
 * Creates the bootstrap Super Admin account.
 *
 * The credentials come from the environment so that production never ships with
 * a password that is sitting in a public Git repository.
 */
class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        $email = env('ADMIN_EMAIL', 'admin@puma.it');
        $name = env('ADMIN_NAME', 'Super Admin');
        $password = env('ADMIN_PASSWORD');

        if (blank($password)) {
            if (app()->isProduction()) {
                throw new RuntimeException(
                    'ADMIN_PASSWORD must be set in .env before seeding in production.'
                );
            }

            $password = 'password';
            $this->command?->warn('ADMIN_PASSWORD not set — using the local-only default "password".');
        }

        $admin = User::withTrashed()->firstOrNew(['email' => $email]);

        $admin->fill([
            'name' => $name,
            'password' => Hash::make($password),
            'status' => 'active',
        ]);
        $admin->email_verified_at ??= now();
        $admin->deleted_at = null;
        $admin->save();

        $admin->syncRoles(['Super Admin']);

        $this->command?->info("Super Admin ready: {$email}");
    }
}
