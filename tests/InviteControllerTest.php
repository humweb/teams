<?php

namespace Humweb\Teams\Tests;

use Humweb\Teams\Mail\TeamInvitation;
use Humweb\Teams\Tests\Helpers\CreatesTeams;
use Humweb\Teams\Tests\Stubs\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\Mail;

/**
 * Class AddTransactionTest
 *
 * @package Humweb\Teams\Tests
 */
class InviteControllerTest extends TestCase
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
    public function it_can_send_invitation()
    {
        $this->actingAs($this->owner)
             ->post('teams/invite/'.$this->team->id, ['email' => $this->user->email])
             ->assertStatus(200);

        Mail::assertSent(TeamInvitation::class, function ($mail) {
            return $mail->hasTo($this->user->email);
        });
    }


    /**
     * @test
     */
    public function it_throws_404_error_when_invitation_not_is_found()
    {
        $this->actingAs($this->owner)->get('teams/invite/12345')->assertStatus(404);
    }


    /**
     * @test
     */
    public function it_can_get_invitation_by_token()
    {
        $invitation = $this->team->inviteUserByEmail('foo@gmail.com');

        $this->actingAs($this->owner)->get('teams/invite/'.$invitation->token)->assertJson([
            'id' => $invitation->id,
        ]);
    }


    /**
     * @test
     */
    public function it_can_delete_invitation_by_id()
    {
        $invitation = $this->team->inviteUserByEmail('foo@gmail.com');
        $this->assertDatabaseHas('team_invitations', [
            'id' => $invitation->id
        ]);

        $this->actingAs($this->owner)->get('teams/invite/'.$invitation->id.'/delete')->assertJson([
            'message' => 'Invitation was deleted.',
        ]);

        // Make sure it return not found message
        $this->actingAs($this->owner)->get('teams/invite/1234/delete')->assertJson([
            'message' => 'Invitation was not found.',
        ]);
    }
}
