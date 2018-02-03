<?php

namespace Humweb\Teams\Events;

use Humweb\Teams\Models\Team;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\SerializesModels;

class UserLeftTeam
{
    use SerializesModels;

    public $team;
    public $user;


    /**
     * TransactionAdded constructor.
     *
     * @param \Illuminate\Database\Eloquent\Model $user
     * @param \Humweb\Teams\Models\Team          $team
     */
    public function __construct(Model $user, Team $team)
    {
        $this->team = $team;

        $this->user = $user;
    }

}