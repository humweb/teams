<?php

namespace Humweb\Teams\Traits;

use Humweb\Teams\Events\UserJoinedTeam;
use Humweb\Teams\Events\UserLeftTeam;
use Humweb\Teams\Models\Invitation;
use Humweb\Teams\Models\Team;

trait TeamUserTrait
{
    /**
     * Teams user is a member of
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function teams()
    {
        return $this->belongsToMany(Team::class, 'team_members', 'user_id', 'team_id')
                    ->orderBy('name', 'asc')
                    ->withTimestamps();
    }


    /**
     * has-one relation with the current selected team model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function currentTeam()
    {
        return $this->hasOne(Team::class, 'id', 'current_team_id');
    }


    /**
     * Get all of the pending invitations for the user.
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class);
    }


    /**
     *  Hook to clean up entries from pivot table when a user is deleted
     */
    public static function bootTeamUserTrait()
    {
        static::deleting(function ($user) {
            if ( ! method_exists(config('teams.user_model'), 'bootSoftDeletes')) {
                $user->teams()->detach();
            }
        });
    }


    public function createTeam($name, $description = '')
    {
        $team = Team::create([
            'name'        => $name,
            'slug'        => str_slug($name),
            'description' => $description,
            'owner_id'    => $this->id,
        ]);

    }
    /**
     * Teams user owns
     *
     * @return mixed
     */
    public function ownedTeams()
    {
        return $this->hasMany(Team::class, 'owner_id');
//        return $this->teams()->where("owner_id", "=", $this->id);
    }


    /**
     * Check if user is the owner of any teams
     *
     * @return bool
     */
    public function ownsAnyTeams()
    {
        return $this->teams()->where("owner_id", "=", $this->id)->count() > 0;
    }


    /**
     * Checks if user is a owner of a specific team
     *
     * @param Team $team
     *
     * @return bool
     */
    public function isOwnerOfTeam($team)
    {
        return $team->owner_id === $this->id;
    }

    public function joinTeamFromToken($token)
    {
        $team = Invitation::whereToken($token)->first()->team;
        return $this->joinTeam($team);
    }

    /**
     * Add user to a team
     *
     * @param       $team
     * @param array $pivotData
     *
     * @param bool  $eagerLoadTeams
     *
     * @return $this
     */
    public function joinTeam($team, $pivotData = [], $eagerLoadTeams = true)
    {
        if (is_null($this->current_team_id)) {
            $this->current_team_id = $team->id;
            $this->save();
            if ($eagerLoadTeams) {
                $this->load('currentTeam');
            }
        }

        // Reload relation
        if ( ! $this->teams->contains($team->id)) {
            $this->teams()->attach($team->id, $pivotData);
            if ($eagerLoadTeams) {
                $this->load('teams');
            }
            event(new UserJoinedTeam($this, $team));
        }

        return $this;
    }


    /**
     * Remove user from a team
     *
     * @param Team $team
     *
     * @return $this
     */
    public function leaveTeam($team)
    {
        $this->teams()->detach($team->id);
        event(new UserLeftTeam($this, $team));

        return $this;
    }


    /**
     * Attach user to multiple teams
     *
     * @param Team $teams
     *
     * @return $this
     */
    public function joinTeams($teams)
    {
        foreach ($teams as $team) {
            $this->joinTeam($team, [], false);
        }
        $this->load('teams');

        return $this;
    }


    /**
     * Remove user from multiple teams
     *
     * @param Team $teams
     *
     * @return $this
     */
    public function leaveTeams($teams)
    {
        foreach ($teams as $team) {
            $this->leaveTeam($team);
        }

        return $this;
    }


    /**
     * Determine if the user is a member of any teams.
     *
     * @return bool
     */
    public function hasTeams()
    {
        return count($this->teams) > 0;
    }


    public function isTeamOwner($team)
    {
        if (is_null($team->owner_id) || is_null($this->id)) {
            return false;
        }

        return $this->id === $team->owner_id;
    }


    /**
     * @param $team
     */
    public function switchTeam($team = null)
    {
        if ($team) {
            $this->current_team_id = $team->id;
            $this->save();
            $this->load('currentTeam');
        }
    }


    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function refreshCurrentTeam()
    {
        $this->current_team_id = null;

        $this->save();

        return $this->currentTeam();
    }
}