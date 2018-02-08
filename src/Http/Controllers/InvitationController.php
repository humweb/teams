<?php

namespace Teams\Http\Controllers;

use Humweb\Teams\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class InvitationController extends Controller
{
    /**
     * Get all of the pending invitations for the user.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function getInvitationsForCurrentUser(Request $request)
    {
        return $request->user()->invitations()->with('team.owner')->notExpired()->get();
    }


    /**
     * Get invitation
     *
     * @param  string $code
     *
     * @return \Illuminate\Http\Response
     */
    public function getInvitation($code)
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
    public function postSendInvitation(Request $request, $teamId)
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
            'message' => 'Invitation was sent to '.$request->email.'.'
        ]);
    }


    /**
     * Accept an invitation.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string                   $inviteId
     *
     * @return \Illuminate\Http\Response
     */
    public function getAcceptInvitation(Request $request, $inviteId)
    {
        $user = $request->user();

        $invitation = $user->invitations()->findOrFail($inviteId);

        $user->joinTeamById($invitation->team_id);

        $invitation->delete();

        return $user->teams()->with('owner')->get();
    }


    /**
     * Delete the given invitation.
     *
     * @param  string $inviteId
     *
     * @return \Illuminate\Http\Response
     */
    public function getDeleteInvitationByOwner(Request $request, $teamId, $inviteId)
    {
        $user = $request->user();
        
        $team = $user->teams()
                     ->where('owner_id', $user->id)
                     ->findOrFail($teamId);

        $count = $team->invitations()->where('id', $inviteId)->delete();

        return response()->json([
            'message' => $count > 0 ? 'Invitation was deleted.' : 'Invitation was not deleted.'
        ]);
    }


    /**
     * Destroy the given team invitation.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string                   $inviteId
     *
     * @return \Illuminate\Http\Response
     */
    public function getDeleteInvitationForUser(Request $request, $inviteId)
    {
        $request->user()->invitations()->findOrFail($inviteId)->delete();

        return response()->json([
            'message' => 'Invitation was deleted.'
        ]);
    }
}
