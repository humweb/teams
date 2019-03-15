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
     * @var null
     */
    protected $message;


    /**
     * Create a new message instance.
     *
     * @param \Humweb\Teams\Models\Invitation $invitation
     * @param \Humweb\Teams\Models\Team       $team
     * @param Model                           $user
     * @param null                            $message
     */
    public function __construct(Invitation $invitation, Team $team, $user = null, $message = null)
    {
        $this->invitation = $invitation;
        $this->team       = $team;
        $this->user       = $user;
        $this->message    = $message;
    }


    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $view = is_null($this->user) ? 'teams::email.new' : 'teams::email.existing';

        return $this->view($view)->subject('New Invitation!')->with([
            'team'       => $this->team,
            'invitation' => $this->invitation,
            'user'       => $this->user,
            'notes'      => $this->message
        ]);
    }
}