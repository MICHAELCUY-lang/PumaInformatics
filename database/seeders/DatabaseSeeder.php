<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     *
     * RolesAndPermissionsSeeder must run first: every FormRequest::authorize()
     * and Controller::authorize() call in the admin panel resolves against the
     * permissions it creates.
     */
    public function run(): void
    {
        $this->call([
            RolesAndPermissionsSeeder::class,
            AdminUserSeeder::class,
        ]);

        // Demo content is for local/staging only — never for production.
        if (! app()->isProduction()) {
            $this->call(SampleDataSeeder::class);
        }
    }
}
