<?php
/**
 * User: ryun
 * Date: 1/23/18
 * Time: 4:28 PM
 */

namespace Humweb\Teams\Jobs;

use Humweb\Teams\Events\UserJoinedTeam;
use Humweb\Teams\Facades\Events;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AddTransaction
{
    use Dispatchable, SerializesModels;

    protected $user;

    /**
     * @var string
     */
    protected $event;

    /**
     * @var int|null
     */
    public $points;


    /**
     * AddTransaction constructor.
     *
     * @param $user
     */
    public function __construct($user, $event, $points = null)
    {
        $this->user   = $user;
        $this->event  = $event;
        $this->points = $points;
    }


    /**
     *
     */
    public function handle()
    {
        $transaction = $this->user->gamifyTransactions()->create([
            'points' => is_null($this->points) ? Events::getPoints($this->event) : $this->points,
            'reason' => $this->event
        ]);

        event(new UserJoinedTeam($transaction, $this->user));
    }
}