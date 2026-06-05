<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Services\InvitationService;
use App\Http\Requests\Admin\StoreUserInvitationRequest;
use App\Models\UserInvitation;

class UserInvitationController extends Controller
{
    protected InvitationService $service;

    public function __construct(InvitationService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $invitations = UserInvitation::orderBy('created_at', 'desc')->paginate(15);
        return view('admin.invitations.index', compact('invitations'));
    }

    public function store(StoreUserInvitationRequest $request)
    {
        $invitation = $this->service->createInvitation(
            $request->input('email'),
            $request->input('role_id'),
            $request->user()->id
        );

        // In a real scenario, we would dispatch an email here.
        // For MVP governance, we flash the raw token to the session so the Admin can copy it.
        $link = route('invitation.show', $invitation->raw_token);
        
        return redirect()->route('admin.invitations.index')
            ->with('success', 'Invitation created successfully.')
            ->with('invitation_link', $link);
    }

    public function destroy(UserInvitation $invitation)
    {
        $invitation->delete();

        return redirect()->route('admin.invitations.index')
            ->with('success', 'Invitation revoked.');
    }
}

