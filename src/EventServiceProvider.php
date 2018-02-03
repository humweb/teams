<?php
/**
 * User: ryun
 * Date: 1/23/18
 * Time: 7:13 PM
 */

namespace Humweb\Teams;

use Humweb\Teams\Events\UserJoinedTeam;
use Humweb\Teams\Events\UserLeftTeam;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        UserJoinedTeam::class => [],
        UserLeftTeam::class   => [],
    ];

}