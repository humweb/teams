<?php

namespace Teams\Http\Controllers;

use Humweb\Teams\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class InviteController extends Controller
{

    /**
     * Get invitation
     *
     * @param  string $code
     *
     * @return \Illuminate\Http\Response
     */
    public function getInvite($code)
    {
        $invitation = Invitation::with('team.owner')->where('token', $code)->firstOrFail();

        if ($invitation->isExpired()) {
            $invitation->delete();

            abort(404);
        }

        return $invitation;
    }


    /**
     * Send an invitation
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string                   $teamId
     *
     * @return \Illuminate\Http\Response
     */
    public function postSend(Request $request, $teamId)
    {
        $user = $request->user();

        $this->validate($request, [
            'email' => 'required|max:255|email',
        ]);

        $team = $user->teams()->where('owner_id', $user->id)->findOrFail($teamId);

        if ($team->invitations()->where('email', $request->email)->exists()) {
            return response()->json(['email' => ['That user is already invited to the team.']], 422);
        }

        $team->inviteUserByEmail($request->email);

        return response()->json([
            'message' => 'Invite was sent to '.$request->email.'.'
        ]);
    }


    /**
     * Delete the given invitation.
     *
     * @param  string $inviteId
     *
     * @return \Illuminate\Http\Response
     */
    public function postDeleteByOwner(Request $request, $teamId, $inviteId)
    {
        $userId = $request->user()->id;

        $invitation = Invitation::whereHas('team', function ($query) use ($useId) {
            $query->where('owner_id', $useId);
        })->find($inviteId);

        $deleteCount = is_null($invitation) ? 0 : $invitation->delete();

        return response()->json([
            'message' => $deleteCount > 0 ? 'Invitation was deleted.' : 'Invitation was not found.'
        ]);
    }
}
