<?php

namespace Humweb\Teams\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Mail;

class Team extends Model
{

    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['name', 'slug', 'description', 'owner_id'];

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'teams';


    /**
     * Many-to-Many relations with the user model.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function users()
    {
        return $this->belongsToMany(config('teams.user_model'), 'team_members', 'team_id', 'user_id')->withTimestamps();
    }


    /**
     * Get the owner of the team.
     */
    public function owner()
    {
        return $this->belongsTo(config('teams.users_model'), 'owner_id');
    }


    /**
     * Get all of the pending invitations for the team.
     */
    public function invitations()
    {
        return $this->hasMany(Invitation::class)->orderBy('created_at', 'desc');
    }


    /**
     * @param \Illuminate\Database\Eloquent\Model $user
     *
     * @return bool
     */
    public function isMember(Model $user)
    {
        return $this->users()->where('id', "=", $user->id)->exists();
    }


    /**
     * Invite a user to the team by e-mail address.
     *
     * @param  string $email
     *
     * @return \Laravel\Spark\Teams\Invitation
     */
    public function inviteUserByEmail($email)
    {
        $model       = config('teams.user_model');
        $user = (new $model)->where('email', $email)->first();
        $invitation  = $this->invitations()->where('email', $email)->first();

        if (is_null($invitation)) {
            $invitation = $this->invitations()->create([
                'user_id' => $user ? $user->id : null,
                'email'   => $email,
                'token'   => str_random(40),
            ]);
        }

        $view = is_null($user) ? 'teams::emails.team.invitations.new'
            : 'teams::emails.team.invitations.existing';

        Mail::send($email, [
            'team'       => $this,
            'invitation' => $invitation,
            'user'       => $user ?: null
        ], function ($mail) use ($invitation) {
            $mail->to($invitation->email)->subject('New Invitation!');
        });

        return $invitation;
    }
}