<?php

namespace Tests;

use Database\Seeders\RolesAndPermissionsSeeder;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    /**
     * Seed the RBAC matrix whenever a test refreshes the database.
     *
     * Authorization is permission-based end to end (route middleware plus
     * FormRequest::authorize), so a test that only does
     * Role::firstOrCreate(['name' => 'Admin']) would get a role with no
     * permissions and every admin request would 403. Seeding the real matrix
     * keeps the tests honest about what a given role can actually do.
     *
     * DatabaseSeeder is deliberately not used here — it also loads demo content.
     */
    protected $seed = true;

    protected $seeder = RolesAndPermissionsSeeder::class;
}
