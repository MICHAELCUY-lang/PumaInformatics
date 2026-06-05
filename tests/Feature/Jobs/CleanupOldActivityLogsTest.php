<?php

use Spatie\Activitylog\Models\Activity;
use App\Models\User;
use Spatie\Permission\Models\Role;
use App\Jobs\CleanupOldActivityLogs;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Carbon\Carbon;

uses(RefreshDatabase::class);

it('deletes non-critical logs older than 1 year', function () {
    Carbon::setTestNow(now()->subYear()->subDay());
    $oldContentLog = Activity::create([
        'log_name' => 'default',
        'description' => 'updated article',
    ]);
    
    Carbon::setTestNow(); // reset to real now before creating the next date!
    Carbon::setTestNow(now()->subDays(10));
    $recentContentLog = Activity::create([
        'log_name' => 'default',
        'description' => 'created article',
    ]);
    
    Carbon::setTestNow(); // reset to real now

    (new CleanupOldActivityLogs())->handle();

    $this->assertDatabaseMissing('activity_log', ['id' => $oldContentLog->id]);
    $this->assertDatabaseHas('activity_log', ['id' => $recentContentLog->id]);
});

it('preserves critical security and governance logs indefinitely', function () {
    Carbon::setTestNow();
    Carbon::setTestNow(now()->subYears(5));
    $oldSecurityLog = Activity::create([
        'log_name' => 'security',
        'description' => 'failed login',
    ]);

    Carbon::setTestNow();
    Carbon::setTestNow(now()->subYears(3));
    $oldRoleLog = Activity::create([
        'log_name' => 'governance',
        'description' => 'assigned role',
        'subject_type' => Role::class,
    ]);

    Carbon::setTestNow();
    Carbon::setTestNow(now()->subYears(2));
    $oldUserLog = Activity::create([
        'log_name' => 'default',
        'description' => 'suspended user',
        'subject_type' => User::class,
    ]);
    
    Carbon::setTestNow(); // reset

    (new CleanupOldActivityLogs())->handle();

    $this->assertDatabaseHas('activity_log', ['id' => $oldSecurityLog->id]);
    $this->assertDatabaseHas('activity_log', ['id' => $oldRoleLog->id]);
    $this->assertDatabaseHas('activity_log', ['id' => $oldUserLog->id]);
});
