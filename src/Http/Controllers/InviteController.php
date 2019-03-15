<?php

namespace Humweb\Teams\Http\Controllers;

use Humweb\Teams\Models\Invitation;
use Humweb\Teams\Models\Team;
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
    public function postSend(Request $request)
    {
        $user = $request->user();

        $request->validate([
            'email' => 'required|max:255|email',
            'team_id' => 'required',
        ]);

        $team = Team::where('owner_id', $user->id)->findOrFail($request->team_id);

        if ($team->invitations()->where('email', $request->email)->exists()) {
            return request()->isJson()
                ? response()->json(['email' => ['That user is already invited to the team.']], 422)
                : back()->with('message', 'That user is already invited to the team.');
        }

        $team->inviteUserByEmail($request->email, $request->get('message'));

        return request()->isJson()
            ? response()->json(['message' => 'Invite was sent to '.$request->email.'.'])
            : redirect()->route('teams.get.index')->with('message', 'Invite was sent to '.$request->email.'.');
    }


    /**
     * Delete the given invitation.
     *
     * @param  string $inviteId
     *
     * @return \Illuminate\Http\Response
     */
    public function postDeleteByOwner(Request $request, $inviteId)
    {
        $userId = $request->user()->id;

        $invitation = Invitation::whereHas('team', function ($query) use ($userId) {
            $query->where('owner_id', $userId);
        })->find($inviteId);

        $deleteCount = is_null($invitation) ? 0 : $invitation->delete();

        return response()->json([
            'message' => $deleteCount > 0 ? 'Invitation was deleted.' : 'Invitation was not found.'
        ]);
    }
}
