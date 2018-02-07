<?php
/**
 * User: ryun
 * Date: 1/30/18
 * Time: 8:26 PM
 */

namespace Humweb\Teams\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Invitation extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'team_invitations';

    /**
     * The attributes that are fillable via mass assignment.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'email', 'token'];

    /**
     * Get the team that owns the invitation.
     */
    public function team()
    {
        return $this->belongsTo(Team::class, 'team_id');
    }

    /**
     * Determine if the invitation is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return Carbon::now()->subDays(7)->startOfDay()->gte($this->created_at->startOfDay());
    }

    /**
     * Determine if the invitation is expired.
     *
     * @return bool
     */
    public function scopeNotExpired($query)
    {
        return $query->whereDate('created_at', '>', Carbon::now()->subDays(7)->startOfDay());
    }
}