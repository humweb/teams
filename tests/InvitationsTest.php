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
        $invitation = Invitation::where('email', 'foo@gmail.com')->firstOrFail();
        $invitation1 = Invitation::where('email', 'foo@gmail.com')->notExpired()->first();
        $this->assertTrue($invitation->isExpired());
        $this->isNull($invitation1);

        Carbon::setTestNow(Carbon::now()->subDays(7)->startOfDay());
        $team->inviteUserByEmail('foo2@gmail.com');
//        dd(Carbon::now());
        Carbon::setTestNow();
        $invitation2 = Invitation::where('email', 'foo2@gmail.com')->first();

//        DB::listen(function($q){
//            dd($q->sql,$q->bindings);
//        });
        $invitation22 = Invitation::where('email', 'foo2@gmail.com')->notExpired()->first();
//dd(Invitation::all()->toArray());
        $this->assertFalse($invitation2->isExpired());
        $this->assertInstanceOf(Invitation::class, $invitation22);
        Carbon::setTestNow();

    }

}
