<?php

namespace Humweb\Teams\Mail;

use Humweb\Teams\Models\Invitation;
use Humweb\Teams\Models\Team;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class TeamInvitation extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * The invitation instance.
     *
     * @var \Humweb\Teams\Models\Invitation
     */
    public $invitation;

    /**
     * @var \Humweb\Teams\Models\Team
     */
    public $team;

    public $user;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Invitation $invitation, Team $team, $user = null)
    {
        $this->invitation = $invitation;
        $this->team       = $team;
        $this->user       = $user;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = is_null($this->user) ? 'teams::emails.new' : 'teams::emails.existing';

        return $this->view($view)->subject('New Invitation!')->with([
            'team'       => $this->team,
            'invitation' => $this->invitation,
            'user'       => $this->user
        ]);
    }
}