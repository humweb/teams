<?php

namespace Humweb\Teams\Http\Controllers;

use Humweb\Core\Http\Controllers\Controller;
use Humweb\Teams\Models\Invitation;
use Humweb\Teams\Requests\CreateTeamRequest;
use Illuminate\Http\Request;

class TeamsController extends Controller
{
    public function getIndex(Request $request)
    {
        $this->setTitle('Teams');
        $this->setContent('teams::teams.index', [
            'teams' => $request->user()->ownedTeams
        ]);
    }


    public function getCreate()
    {
        $this->setContent('teams::teams.create');
    }


    public function postCreate(CreateTeamRequest $request)
    {
        $request->user()->createTeam($request->name, $request->get('description', ''));
        return redirect()->route('teams.get.index')->with('success', 'you have created team: '.$request->name);
    }


    public function getEdit(Request $request, $id)
    {
        $this->setContent('teams::teams.edit', [
            'team' => $request->user()->ownedTeams()->findOrFail($id)
        ]);
    }


    public function postEdit(Request $request, $id)
    {
        $team = $request->user()->ownedTeams()->findOrFail($id);

        $team->fill([
            'name'        => $request->name,
            'slug'        => str_slug($request->name),
            'description' => $request->get('description', '')
        ])->save();

        return redirect()->route('teams.get.index');
    }


    public function getInvite(Request $request, $teamId = null)
    {
        $this->setContent('teams::teams.invite', [
            'teamId' => $teamId,
            'teams'  => $request->user()->ownedTeams->pluck('name', 'id')
        ]);
    }


    public function postInvite(Request $request)
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $team = $request->user()->ownedTeams()->findOrFail($teamId);

        $team->inviteUserByEmail($request->email, $request->get('message'));

//        $this->setContent('teams::teams.invite', [
//            'teamId' => $teamId,
//            'teams'  => $request->user()->ownedTeams->pluck('name', 'id')
//        ]);
    }


    public function getPendingInvites(Request $request, $teamId = null)
    {

        if ($teamId) {
            $invites = Invitation::with(['team', 'user'])->whereHas('team', function ($query) use ($request, $teamId) {
                $query->where('owner_id', $request->user()->id)->where('team_id', $teamId);
            })->get();
        } else {
            $invites = Invitation::with(['team', 'user'])->whereHas('team', function ($query) use ($request) {
                $query->where('owner_id', $request->user()->id);
            })->get();
        }

        $this->setContent('teams::teams.invites', [
            'invites' => $invites
        ]);
    }
}
