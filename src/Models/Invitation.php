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
        return Carbon::now()->subWeek()->gte($this->created_at);
    }

    /**
     * Determine if the invitation is expired.
     *
     * @return bool
     */
    public function scopeNotExpired($query)
    {
        return $query->whereDate('created_at', '<=', Carbon::now()->subWeek());
    }
}