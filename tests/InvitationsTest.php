<?php

namespace Humweb\Teams\Tests;

use Carbon\Carbon;
use Humweb\Teams\Mail\TeamInvitation;
use Humweb\Teams\Models\Invitation;
use Humweb\Teams\Tests\Helpers\CreatesTeams;
use Humweb\Teams\Tests\Stubs\User;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

/**
 * Class AddTransactionTest
 *
 * @package Humweb\Teams\Tests
 */
class InvitationsTest extends TestCase
{
    use CreatesTeams, DatabaseTransactions;

    protected $user;


    public function setUp()
    {
        parent::setUp();
        $this->user = factory(User::class)->create();
    }


    /**
     * @test
     */
    public function it_can_check_if_user_belongs_to_any_teams()
    {
        Mail::fake();
        $team = $this->createTeam($this->user);
        $team->inviteUserByEmail('foo@gmail.com');

        Mail::assertSent(TeamInvitation::class, function ($mail) use ($team) {
            return $mail->team->id === $team->id;
        });

        Mail::assertSent(TeamInvitation::class, function ($mail) use ($team) {
            return $mail->hasTo('foo@gmail.com');
        });
    }


    /**
     * @test
     */
    public function it_can_check_if_invitation_is_expired()
    {
        Mail::fake();
        $team = $this->createTeam($this->user);

        Carbon::setTestNow(Carbon::now()->subDays(8)->startOfDay());
        $team->inviteUserByEmail('foo@gmail.com');
        Carbon::setTestNow();
        $expiredInvitation  = Invitation::where('email', 'foo@gmail.com')->firstOrFail();
        $nullInvitation = Invitation::where('email', 'foo@gmail.com')->notExpired()->first();
        $this->assertTrue($expiredInvitation->isExpired());
        $this->isNull($nullInvitation);

        Carbon::setTestNow(Carbon::now()->subDays(6)->startOfDay());
        $team->inviteUserByEmail('foo2@gmail.com');
        Carbon::setTestNow();

        $nonExpiredInvitation = Invitation::where('email', 'foo2@gmail.com')->notExpired()->first();
        $this->assertFalse($nonExpiredInvitation->isExpired());

        $this->assertInstanceOf(Invitation::class, $nonExpiredInvitation);
    }

}
