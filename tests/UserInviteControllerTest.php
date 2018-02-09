<?php

namespace Humweb\Teams\Tests;

use Humweb\Teams\Events\UserJoinedTeam;
use Humweb\Teams\Tests\Helpers\CreatesTeams;
use Humweb\Teams\Tests\Stubs\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Mail;

/**
 * Class AddTransactionTest
 *
 * @package Humweb\Teams\Tests
 */
class UserInviteControllerTest extends TestCase
{
    use CreatesTeams, DatabaseTransactions;

    protected $owner;
    protected $user;
    protected $team;


    public function setUp()
    {
        parent::setUp();
        $this->owner = factory(User::class)->create();
        $this->user  = factory(User::class)->create();
        $this->team  = $this->createTeam($this->owner);
        Mail::fake();
    }


    /**
     * @test
     */
    public function it_can_get_users_invites()
    {
        $this->team->inviteUserByEmail($this->user->email);

        $this->actingAs($this->user)->get('teams/user/invites')->assertJsonStructure([
            'invites' => [
                '*' => [
                    'id',
                    'user_id',
                    'email',
                    'token',
                    'team' => ['owner'],
                ]
            ]
        ]);
    }


    /**
     * @test
     */
    public function it_can_accept_user_invites_by_id()
    {
        Event::fake();
        $invite = $this->team->inviteUserByEmail($this->user->email);

        $this->actingAs($this->user)->get('teams/user/invites/accept/'.$invite->id)->assertSuccessful();

        $this->assertDatabaseMissing('team_invitations', [
            'id' => $invite->id
        ]);
        Event::assertDispatched(UserJoinedTeam::class);
    }


    /**
     * @test
     */
    public function it_can_decline_user_invites()
    {
        $invite = $this->team->inviteUserByEmail($this->user->email);

        $this->actingAs($this->user)
             ->get('teams/user/invites/decline/'.$invite->id)
             ->assertSuccessful()
             ->assertJson(['message' => 'Invitation was deleted.']);

        $this->assertDatabaseMissing('team_invitations', [
            'id' => $invite->id
        ]);
    }

}
