<?php

namespace Humweb\Teams\Tests\Helpers;

use Humweb\Teams\Events\UserJoinedTeam;
use Humweb\Teams\Models\Team;
use Illuminate\Support\Facades\Event;

trait CreatesTeams
{
    /**
     * @param null $user
     *
     * @return null|static
     */
    public function createTeam($user = null)
    {
        $team = Team::create([
            'name'     => 'New Team',
            'slug'     => 'new-team',
            'owner_id' => $user->id ?? 0,
        ]);

        if ( ! is_null($user)) {
            $user->joinTeam($team);
        }

        return $team;
    }
}