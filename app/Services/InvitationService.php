<?php

namespace App\Services;

use App\Models\UserInvitation;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class InvitationService
{
    public function createInvitation(string $email, ?int $roleId, int $invitedBy): UserInvitation
    {
        // Cancel any pending invitations for this email
        UserInvitation::where('email', $email)->delete();

        // 64-char crypto secure string
        $rawToken = Str::random(64);
        
        // We hash the token in the DB to prevent database leaks from compromising tokens
        $hashedToken = hash('sha256', $rawToken);

        $invitation = UserInvitation::create([
            'email' => $email,
            'token' => $hashedToken,
            'role_id' => $roleId,
            'invited_by' => $invitedBy,
            'expires_at' => now()->addHours(48),
        ]);

        // We temporarily attach the raw token for mail delivery / manual link copying.
        // It is NOT stored anywhere but memory for this request cycle.
        $invitation->raw_token = $rawToken;

        return $invitation;
    }

    public function validateToken(string $rawToken): ?UserInvitation
    {
        $hashedToken = hash('sha256', $rawToken);
        
        return UserInvitation::where('token', $hashedToken)
            ->where('expires_at', '>', now())
            ->whereNull('accepted_at')
            ->first();
    }

    public function redeemInvitation(UserInvitation $invitation, array $userData): User
    {
        return DB::transaction(function () use ($invitation, $userData) {
            $user = User::create([
                'name' => $userData['name'],
                'email' => $invitation->email, // Lock to invited email
                'password' => Hash::make($userData['password']),
                'status' => 'active',
            ]);

            // Redeeming the emailed token already proves control of the address,
            // so don't make invited staff go through a second verification round.
            $user->forceFill(['email_verified_at' => now()])->save();

            if ($invitation->role_id) {
                $user->assignRole($invitation->role_id);
            }

            $invitation->update(['accepted_at' => now()]);

            return $user;
        });
    }
}
