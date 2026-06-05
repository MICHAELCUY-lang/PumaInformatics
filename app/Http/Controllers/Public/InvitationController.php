<?php

namespace App\Http\Controllers\Public;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\InvitationService;
use App\Http\Requests\Public\RedeemInvitationRequest;
use Illuminate\Support\Facades\Auth;

class InvitationController extends Controller
{
    protected InvitationService $service;

    public function __construct(InvitationService $service)
    {
        $this->service = $service;
    }

    public function show($token)
    {
        $invitation = $this->service->validateToken($token);

        if (!$invitation) {
            return redirect('/login')->withErrors(['email' => 'This invitation link is invalid or has expired.']);
        }

        return view('auth.register', [
            'email' => $invitation->email,
            'token' => $token,
        ]);
    }

    public function store(RedeemInvitationRequest $request, $token)
    {
        $invitation = $this->service->validateToken($token);

        if (!$invitation) {
            return redirect('/login')->withErrors(['email' => 'This invitation link is invalid or has expired.']);
        }

        $user = $this->service->redeemInvitation($invitation, $request->validated());

        Auth::login($user);

        return redirect()->route('dashboard');
    }
}
