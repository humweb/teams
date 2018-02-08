<?php

namespace Teams\Http\Controllers;

use Humweb\Teams\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserInviteController extends Controller
{
    /**
     * Get pending invitations for the user.
     *
     * @param  \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function getInvites(Request $request)
    {
        return $request->user()->invitations()->with('team.owner')->notExpired()->get();
    }


    /**
     * Accept invitation
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string                   $inviteId
     *
     * @return \Illuminate\Http\Response
     */
    public function getAccept(Request $request, $inviteId)
    {
        $user = $request->user();

        $invitation = $user->invitations()->findOrFail($inviteId);

        if ($invitation->isExpired()) {
            $invitation->delete();
            abort(404);
        }

        $user->joinTeamById($invitation->team_id);

        $invitation->delete();

        return $user->teams()->with('owner')->get();
    }


    /**
     * Decline invitation
     *
     * @param  \Illuminate\Http\Request $request
     * @param  string                   $inviteId
     *
     * @return \Illuminate\Http\Response
     */
    public function getDecline(Request $request, $inviteId)
    {
        $request->user()->invitations()->findOrFail($inviteId)->delete();

        return response()->json([
            'message' => 'Invitation was deleted.'
        ]);
    }
}
